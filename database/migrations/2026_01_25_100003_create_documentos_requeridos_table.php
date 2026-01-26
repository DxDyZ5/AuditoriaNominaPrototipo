<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentos_requeridos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_doc', 128);
            $table->boolean('es_obligatorio')->default(true);
            $table->boolean('requiere_vencimiento')->default(false);
            $table->unsignedInteger('dias_aviso')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_requeridos');
    }
};
