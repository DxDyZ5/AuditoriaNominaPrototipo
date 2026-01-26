<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index('reports_user_id_index');
            $table->string('url', 2048);
            $table->string('domain', 255)->nullable()->index('reports_domain_index');
            $table->tinyInteger('privacy')->nullable()->default(0);
            $table->text('password')->nullable();
            $table->mediumText('results')->nullable();
            $table->tinyInteger('result')->nullable()->index('reports_result_index');
            $table->timestamp('generated_at')->nullable()->index('reports_generated_at_index');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
