<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expediente_digital', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empleado_id')->index();
            $table->unsignedInteger('doc_id')->index();
            $table->string('ruta_archivo', 255);
            $table->date('fecha_subida')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->boolean('valido')->default(true);
            $table->unsignedInteger('usuario_id')->nullable();
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('doc_id')->references('id')->on('documentos_requeridos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expediente_digital');
    }
};
