<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Empleado;
use App\Models\DocumentoRequerido;
use App\Models\ExpedienteDigital;
use App\Models\Hallazgo;
use Illuminate\Http\Request;

class AuditoriasController extends Controller
{
    public function index()
    {
        $auditorias = Auditoria::with('empleado')->orderBy('id', 'desc')->paginate(20);
        return view('auditorias.index', compact('auditorias'));
    }

    public function auditarEmpleado(Request $request, Empleado $empleado)
    {
        $requeridos = DocumentoRequerido::orderBy('id')->get();
        $archivos = ExpedienteDigital::where('empleado_id', $empleado->id)->get()->keyBy('doc_id');

        $total = $requeridos->count();
        $cumplidos = 0;

        $auditoria = Auditoria::create([
            'empleado_id' => $empleado->id,
            'auditor_id' => $request->user()->id ?? null,
            'fecha_auditoria' => now(),
            'resultado_porcentaje' => 0,
            'plantilla_id' => null,
        ]);

        foreach ($requeridos as $doc) {
            $archivo = $archivos->get($doc->id);
            if (!$archivo) {
                Hallazgo::create([
                    'auditoria_id' => $auditoria->id,
                    'doc_id' => $doc->id,
                    'tipo' => 'faltante',
                    'descripcion' => 'Faltante',
                    'estado' => 'abierto',
                ]);
                continue;
            }
            if ($archivo->valido !== true) {
                Hallazgo::create([
                    'auditoria_id' => $auditoria->id,
                    'doc_id' => $doc->id,
                    'tipo' => 'no_valido',
                    'descripcion' => 'No válido',
                    'estado' => 'abierto',
                ]);
                continue;
            }
            if ($doc->requiere_vencimiento && $archivo->fecha_vencimiento) {
                if (now()->greaterThan($archivo->fecha_vencimiento)) {
                    Hallazgo::create([
                        'auditoria_id' => $auditoria->id,
                        'doc_id' => $doc->id,
                        'tipo' => 'vencido',
                        'descripcion' => 'Vencido',
                        'estado' => 'abierto',
                    ]);
                    continue;
                }
            }
            $cumplidos++;
        }

        $porcentaje = $total ? intval(($cumplidos / $total) * 100) : 0;
        $auditoria->resultado_porcentaje = $porcentaje;
        $auditoria->save();

        return redirect()->route('auditorias')->with('status', 'Auditoría completada');
    }
}
