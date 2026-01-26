<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 128);
            $table->unsignedInteger('puesto_id')->nullable()->index();
            $table->date('fecha_ingreso')->nullable();
            $table->string('estado', 32)->default('activo');
            $table->timestamps();

            $table->foreign('puesto_id')->references('id')->on('puestos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
