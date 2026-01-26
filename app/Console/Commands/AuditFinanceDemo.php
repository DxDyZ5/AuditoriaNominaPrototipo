<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentoRequerido;
use App\Models\Empleado;
use App\Models\ExpedienteDigital;
use App\Models\NominaHistorico;
use App\Models\DetallePago;
use App\Models\AutorizacionExtra;

class AuditFinanceDemo extends Command
{
    protected $signature = 'audit:finance-demo';
    protected $description = 'Semilla de datos de nómina y empleados para probar auditoría financiera';

    public function handle(): int
    {
        $this->info('');

        $contrato = DocumentoRequerido::firstOrCreate([
            'nombre_doc' => 'Contrato',
        ], [
            'es_obligatorio' => true,
            'requiere_vencimiento' => false,
            'dias_aviso' => null,
        ]);

        $ana = Empleado::firstOrCreate([
            'nombre' => 'Ana Activa',
        ], [
            'estado' => 'activo',
            'salario_diario' => 500.00,
            'tipo_pago' => 'mensual',
            'cuenta_bancaria_hash' => hash('sha256', '1111'),
        ]);

        $carlos = Empleado::firstOrCreate([
            'nombre' => 'Carlos Inactivo',
        ], [
            'estado' => 'inactivo',
            'salario_diario' => 300.00,
            'tipo_pago' => 'mensual',
            'cuenta_bancaria_hash' => hash('sha256', '1111'),
        ]);

        ExpedienteDigital::firstOrCreate([
            'empleado_id' => $ana->id,
            'doc_id' => $contrato->id,
        ], [
            'ruta_archivo' => 'expedientes/contrato_ana.pdf',
            'fecha_subida' => now()->toDateString(),
            'valido' => true,
        ]);

        AutorizacionExtra::create([
            'empleado_id' => $ana->id,
            'concepto' => 'Bono de productividad',
            'monto' => 200.00,
        ]);

        $nomina = NominaHistorico::create([
            'mes' => now()->month,
            'año' => now()->year,
            'total_bruto' => 0,
            'total_neto' => 0,
        ]);

        DetallePago::create([
            'nomina_id' => $nomina->id,
            'empleado_id' => $ana->id,
            'monto_pagado' => 17000.00,
            'metodo_pago' => 'transferencia',
        ]);

        DetallePago::create([
            'nomina_id' => $nomina->id,
            'empleado_id' => $carlos->id,
            'monto_pagado' => 8000.00,
            'metodo_pago' => 'transferencia',
        ]);

        $nomina->total_bruto = 17000.00 + 8000.00;
        $nomina->total_neto = $nomina->total_bruto;
        $nomina->save();

        $this->info('');
        return Command::SUCCESS;
    }
}
