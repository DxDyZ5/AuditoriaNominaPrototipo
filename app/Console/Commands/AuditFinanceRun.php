<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NominaHistorico;
use App\Models\DetallePago;
use App\Models\DocumentoRequerido;
use App\Models\ExpedienteDigital;
use App\Models\Empleado;
use App\Models\AutorizacionExtra;
use App\Models\AuditoriaFinanciera;

class AuditFinanceRun extends Command
{
    protected $signature = 'audit:finance-run {nomina_id?}';
    protected $description = 'Ejecuta la auditoría financiera sobre la nómina indicada o la última';

    public function handle(): int
    {
        $nominaId = $this->argument('nomina_id');
        $nomina = $nominaId ? NominaHistorico::find($nominaId) : NominaHistorico::orderBy('id', 'desc')->first();
        if (!$nomina) {
            $this->error('No hay nóminas cargadas.');
            return Command::FAILURE;
        }

        $pagos = DetallePago::with('empleado')->where('nomina_id', $nomina->id)->get();
        $contratoDoc = DocumentoRequerido::where('nombre_doc', 'like', '%contrato%')->first();

        foreach ($pagos as $pago) {
            $empleado = $pago->empleado;
            if (!$empleado) {
                continue;
            }
            $proyectado = $this->salarioProyectado($empleado, $nomina);
            $bonos = $this->bonosAutorizados($empleado, $nomina);
            $diferencia = round($pago->monto_pagado - ($proyectado + $bonos), 2);
            if ($diferencia > 0) {
                AuditoriaFinanciera::create([
                    'empleado_id' => $empleado->id,
                    'tipo_error' => 'Sueldo base vs Pagado',
                    'monto_diferencia' => $diferencia,
                    'detalles' => json_encode(['proyectado' => $proyectado, 'bonos' => $bonos, 'pagado' => $pago->monto_pagado]),
                    'nomina_id' => $nomina->id,
                ]);
            }
        }

        foreach ($pagos as $pago) {
            $empleado = $pago->empleado;
            if (!$empleado) continue;
            if (mb_strtolower($empleado->estado) !== 'activo') {
                AuditoriaFinanciera::create([
                    'empleado_id' => $empleado->id,
                    'tipo_error' => 'Pago a ex-empleado',
                    'monto_diferencia' => null,
                    'detalles' => json_encode(['estado' => $empleado->estado]),
                    'nomina_id' => $nomina->id,
                ]);
            }
        }

        if ($contratoDoc) {
            foreach ($pagos as $pago) {
                $empleado = $pago->empleado;
                if (!$empleado) continue;
                $tieneContrato = ExpedienteDigital::where('empleado_id', $empleado->id)->where('doc_id', $contratoDoc->id)->exists();
                if (!$tieneContrato) {
                    AuditoriaFinanciera::create([
                        'empleado_id' => $empleado->id,
                        'tipo_error' => 'Contrato faltante',
                        'monto_diferencia' => null,
                        'detalles' => json_encode(['doc' => 'contrato']),
                        'nomina_id' => $nomina->id,
                    ]);
                }
            }
        }

        $porCuenta = [];
        foreach ($pagos as $pago) {
            $empleado = $pago->empleado;
            if (!$empleado || !$empleado->cuenta_bancaria_hash) continue;
            $porCuenta[$empleado->cuenta_bancaria_hash] = $porCuenta[$empleado->cuenta_bancaria_hash] ?? [];
            $porCuenta[$empleado->cuenta_bancaria_hash][] = $empleado->id;
        }
        foreach ($porCuenta as $hash => $ids) {
            if (count($ids) > 1) {
                foreach ($ids as $id) {
                    AuditoriaFinanciera::create([
                        'empleado_id' => $id,
                        'tipo_error' => 'Cuenta Duplicada',
                        'monto_diferencia' => null,
                        'detalles' => json_encode(['cuenta_hash' => $hash, 'coincidentes' => $ids]),
                        'nomina_id' => $nomina->id,
                    ]);
                }
            }
        }

        $sfs = (float) (config('compliance.sfs') ?? 0.0);
        $afp = (float) (config('compliance.afp') ?? 0.0);
        foreach ($pagos as $pago) {
            $empleado = $pago->empleado;
            if (!$empleado) continue;
            $proyectado = $this->salarioProyectado($empleado, $nomina);
            $bonos = $this->bonosAutorizados($empleado, $nomina);
            $bruto = $proyectado + $bonos;
            if ($bruto <= 0) continue;
            $deduccionesSS = round(($bruto * $sfs) + ($bruto * $afp), 2);
            $baseMensual = round($bruto - $deduccionesSS, 2);
            $isrMensual = round($this->calcularIsrMensual($baseMensual), 2);
            $netoEstimado = round($baseMensual - $isrMensual, 2);
            $diff = round($pago->monto_pagado - $netoEstimado, 2);
            if (abs($diff) > 1) {
                AuditoriaFinanciera::create([
                    'empleado_id' => $empleado->id,
                    'tipo_error' => 'Recálculo de Ley',
                    'monto_diferencia' => $diff,
                    'detalles' => json_encode(['bruto' => $bruto, 'base_mensual' => $baseMensual, 'isr' => $isrMensual, 'neto_estimado' => $netoEstimado, 'pagado' => $pago->monto_pagado]),
                    'nomina_id' => $nomina->id,
                ]);
            }
        }

        $this->info('Auditoría financiera ejecutada para nómina ID: '.$nomina->id);
        return Command::SUCCESS;
    }

    private function salarioProyectado(Empleado $empleado, NominaHistorico $nomina): float
    {
        if (!$empleado->salario_diario) {
            return 0.0;
        }
        $dias = 30;
        if ($empleado->tipo_pago === 'quincenal') {
            $dias = 30;
        }
        return round($empleado->salario_diario * $dias, 2);
    }

    private function bonosAutorizados(Empleado $empleado, NominaHistorico $nomina): float
    {
        return (float) AutorizacionExtra::where('empleado_id', $empleado->id)
            ->whereMonth('created_at', $nomina->mes)
            ->whereYear('created_at', $nomina->getAttribute('año'))
            ->sum('monto');
    }

    private function calcularIsrMensual(float $baseMensual): float
    {
        if ($baseMensual <= 0) {
            return 0.0;
        }
        $tramos = (array) (config('compliance.isr.anual') ?? []);
        $anual = $baseMensual * 12;
        foreach ($tramos as $t) {
            $hasta = $t['hasta'];
            $tasa = (float) $t['tasa'];
            $exceso = (float) $t['exceso'];
            $fijo = (float) $t['fijo'];
            if ($hasta === null || $anual <= $hasta) {
                $isrAnual = $tasa > 0 ? $fijo + (($anual - $exceso) * $tasa) : 0.0;
                return $isrAnual / 12.0;
            }
        }
        return 0.0;
    }
}
