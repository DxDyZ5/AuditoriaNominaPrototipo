<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auditoria_financiera', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empleado_id')->index();
            $table->string('tipo_error', 64);
            $table->decimal('monto_diferencia', 14, 2)->nullable();
            $table->json('detalles')->nullable();
            $table->unsignedInteger('nomina_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('nomina_id')->references('id')->on('nominas_historico')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_financiera');
    }
};
