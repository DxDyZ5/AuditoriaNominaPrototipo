<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detalle_pago', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nomina_id')->index();
            $table->unsignedInteger('empleado_id')->index();
            $table->decimal('monto_pagado', 14, 2);
            $table->string('metodo_pago', 32)->nullable();
            $table->timestamps();

            $table->foreign('nomina_id')->references('id')->on('nominas_historico')->onDelete('cascade');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pago');
    }
};
