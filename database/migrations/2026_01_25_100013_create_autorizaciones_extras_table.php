<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('autorizaciones_extras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empleado_id')->index();
            $table->string('concepto', 64);
            $table->decimal('monto', 14, 2);
            $table->unsignedInteger('usuario_autorizo_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizaciones_extras');
    }
};
