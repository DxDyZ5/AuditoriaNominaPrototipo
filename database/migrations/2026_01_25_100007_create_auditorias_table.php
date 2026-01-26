<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empleado_id')->index();
            $table->unsignedInteger('auditor_id')->nullable()->index();
            $table->dateTime('fecha_auditoria')->nullable();
            $table->unsignedInteger('resultado_porcentaje')->default(0);
            $table->unsignedInteger('plantilla_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('plantilla_id')->references('id')->on('plantillas_auditoria')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
