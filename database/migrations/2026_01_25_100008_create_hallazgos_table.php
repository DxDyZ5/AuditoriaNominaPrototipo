<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hallazgos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('auditoria_id')->index();
            $table->unsignedInteger('doc_id')->nullable()->index();
            $table->string('tipo', 32);
            $table->text('descripcion')->nullable();
            $table->string('estado', 32)->default('abierto');
            $table->timestamps();

            $table->foreign('auditoria_id')->references('id')->on('auditorias')->onDelete('cascade');
            $table->foreign('doc_id')->references('id')->on('documentos_requeridos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hallazgos');
    }
};
