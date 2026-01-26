<?php

namespace App\Http\Controllers;

use App\Models\NominaHistorico;
use App\Models\DetallePago;
use App\Models\Empleado;
use App\Models\DocumentoRequerido;
use App\Models\ExpedienteDigital;
use App\Models\AuditoriaFinanciera;
use Illuminate\Http\Request;

class NominaController extends Controller
{
    public function carga()
    {
        return view('nomina.carga');
    }

    public function importar(Request $request)
    {
        $data = $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer|min:2000|max:2100',
            'archivo' => 'required|file',
            'bloquear_pago' => 'nullable|boolean',
        ]);

        $nomina = NominaHistorico::create([
            'mes' => $data['mes'],
            'año' => $data['año'],
            'total_bruto' => 0,
            'total_neto' => 0,
        ]);

        $file = $request->file('archivo')->getRealPath();
        $handle = fopen($file, 'r');
        if (!$handle) {
            return back()->with('error', 'No se pudo leer el archivo');
        }

        $contratoDoc = DocumentoRequerido::where('nombre_doc', 'like', '%contrato%')->first();
        $sum = 0;
        $count = 0;
        $header = null;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if ($count === 0) {
                $header = array_map('mb_strtolower', $row);
                // Detect header
                if (in_array('empleado_id', $header) || in_array('monto_pagado', $header)) {
                    $count++;
                    continue;
                }
            }

            $empleadoId = isset($row[0]) ? (int)$row[0] : null;
            $montoPagado = isset($row[1]) ? (float)$row[1] : null;
            $metodoPago = isset($row[2]) ? $row[2] : null;

            if (!$empleadoId || !$montoPagado) {
                $count++;
                continue;
            }

            $empleado = Empleado::find($empleadoId);
            if (!$empleado) {
                $count++;
                continue;
            }

            $documentoFaltante = false;
            if ($contratoDoc) {
                $documentoFaltante = !ExpedienteDigital::where('empleado_id', $empleado->id)->where('doc_id', $contratoDoc->id)->exists();
            }

            $bloqueo = $request->boolean('bloquear_pago') || ($empleado->bloquear_pago ?? false);
            if ($bloqueo && $documentoFaltante) {
                AuditoriaFinanciera::create([
                    'empleado_id' => $empleado->id,
                    'tipo_error' => 'Contrato faltante - Pago bloqueado',
                    'monto_diferencia' => null,
                    'detalles' => json_encode(['motivo' => 'faltante contrato']),
                    'nomina_id' => $nomina->id,
                ]);
                $count++;
                continue;
            }

            DetallePago::create([
                'nomina_id' => $nomina->id,
                'empleado_id' => $empleado->id,
                'monto_pagado' => $montoPagado,
                'metodo_pago' => $metodoPago,
            ]);

            $sum += $montoPagado;
            $count++;
        }
        fclose($handle);

        $nomina->total_bruto = $sum;
        $nomina->total_neto = $sum;
        $nomina->save();

        return redirect()->route('finanzas.panel')->with('status', 'Nómina importada');
    }
}
