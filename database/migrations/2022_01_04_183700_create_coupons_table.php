<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index('coupons_name_index');
            $table->string('code')->index('coupons_code_index');
            $table->tinyInteger('type')->index('coupons_type_index');
            $table->decimal('percentage', 5)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('days')->nullable();
            $table->integer('redeems')->nullable()->default(0);
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
        Schema::dropIfExists('coupons');
    }
}
