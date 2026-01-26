<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nominas_historico', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('mes'); // 1-12
            $table->unsignedSmallInteger('año');
            $table->decimal('total_bruto', 14, 2)->default(0);
            $table->decimal('total_neto', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nominas_historico');
    }
};
