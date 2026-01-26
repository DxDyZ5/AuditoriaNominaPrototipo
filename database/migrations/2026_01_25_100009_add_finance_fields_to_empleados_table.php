<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->decimal('salario_diario', 10, 2)->nullable()->after('estado');
            $table->string('tipo_pago', 16)->nullable()->after('salario_diario'); // quincenal|mensual
            $table->string('cuenta_bancaria_hash', 64)->nullable()->after('tipo_pago');
            $table->date('fecha_ultimo_incremento')->nullable()->after('cuenta_bancaria_hash');
        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn(['salario_diario', 'tipo_pago', 'cuenta_bancaria_hash', 'fecha_ultimo_incremento']);
        });
    }
};
