<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index('payments_user_id_index');
            $table->unsignedInteger('plan_id')->index('payments_plan_id_index');
            $table->string('payment_id', 128)->index('payments_payment_id_index');
            $table->string('invoice_id', 128)->nullable()->index('payments_invoice_id_index');
            $table->string('processor', 32)->index('payments_processor_index');
            $table->string('amount', 32);
            $table->string('currency', 12);
            $table->string('interval', 16)->index('payments_interval_index');
            $table->string('status', 16)->index('payments_status_index');
            $table->text('product')->nullable();
            $table->text('coupon')->nullable();
            $table->text('tax_rates')->nullable();
            $table->text('seller')->nullable();
            $table->text('customer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
