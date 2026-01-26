<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\DocumentoRequerido;
use App\Models\ExpedienteDigital;
use App\Models\Hallazgo;
use App\Models\NominaHistorico;
use App\Models\DetallePago;
use App\Models\AutorizacionExtra;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the Dashboard page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $totalEmpleados = Empleado::count();
        $requeridos = DocumentoRequerido::get()->keyBy('id');
        $totalRequeridos = $requeridos->count();

        $cumplimientos = 0;
        if ($totalEmpleados > 0 && $totalRequeridos > 0) {
            Empleado::chunk(100, function ($chunk) use (&$cumplimientos, $requeridos, $totalRequeridos) {
                foreach ($chunk as $empleado) {
                    $archivos = ExpedienteDigital::where('empleado_id', $empleado->id)->get()->keyBy('doc_id');
                    $validos = 0;
                    foreach ($requeridos as $doc) {
                        $archivo = $archivos->get($doc->id);
                        if (!$archivo) {
                            continue;
                        }
                        if ($archivo->valido !== true) {
                            continue;
                        }
                        if ($doc->requiere_vencimiento && $archivo->fecha_vencimiento) {
                            if (Carbon::now()->gt(Carbon::parse($archivo->fecha_vencimiento))) {
                                continue;
                            }
                        }
                        $validos++;
                    }
                    $cumplimientos += ($validos / $totalRequeridos);
                }
            });
        }
        $cumplimientoPromedio = ($totalEmpleados > 0 && $totalRequeridos > 0) ? intval(($cumplimientos / $totalEmpleados) * 100) : 0;

        $vencimientos = ExpedienteDigital::whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '>=', Carbon::now()->toDateString())
            ->where('fecha_vencimiento', '<=', Carbon::now()->addDays(30)->toDateString())
            ->count();

        $hallazgosAbiertos = Hallazgo::where('estado', 'abierto')->count();

        $nomina = NominaHistorico::orderBy('id', 'desc')->first();
        $kpiDesvio = 0.0;
        $fantasmas = [];
        if ($nomina) {
            $pagado = (float) DetallePago::where('nomina_id', $nomina->id)->sum('monto_pagado');
            $empleadosActivos = Empleado::where('estado', 'activo')->get();
            $proyectado = 0.0;
            foreach ($empleadosActivos as $empleado) {
                $dias = 30;
                if ($empleado->salario_diario) {
                    $proyectado += ($empleado->salario_diario * $dias);
                }
                $proyectado += (float) AutorizacionExtra::where('empleado_id', $empleado->id)
                    ->whereMonth('created_at', $nomina->mes)
                    ->whereYear('created_at', $nomina->getAttribute('año'))
                    ->sum('monto');
            }
            $kpiDesvio = round($pagado - $proyectado, 2);

            $fantasmas = DetallePago::with('empleado')->where('nomina_id', $nomina->id)
                ->whereHas('empleado', function ($q) {
                    $q->where('estado', '<>', 'activo');
                })
                ->limit(10)
                ->get();
        }

        return view('dashboard.hr', [
            'totalEmpleados' => $totalEmpleados,
            'cumplimientoPromedio' => $cumplimientoPromedio,
            'vencimientos' => $vencimientos,
            'hallazgosAbiertos' => $hallazgosAbiertos,
            'kpiDesvio' => $kpiDesvio,
            'fantasmas' => $fantasmas,
        ]);
    }
}
