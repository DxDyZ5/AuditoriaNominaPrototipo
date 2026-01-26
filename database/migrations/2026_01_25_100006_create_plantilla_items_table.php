<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plantilla_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plantilla_id')->index();
            $table->unsignedInteger('documento_requerido_id')->index();
            $table->timestamps();

            $table->foreign('plantilla_id')->references('id')->on('plantillas_auditoria')->onDelete('cascade');
            $table->foreign('documento_requerido_id')->references('id')->on('documentos_requeridos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantilla_items');
    }
};
