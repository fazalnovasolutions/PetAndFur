<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductAdditionalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_additional_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('design')->nullable();
            $table->text('status')->nullable();

            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->bigInteger('order_product_id')->unsigned()->nullable();
            $table->bigInteger('shop_id')->unsigned()->nullable();
            $table->bigInteger('status_id')->unsigned()->nullable();
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
        Schema::dropIfExists('order_product_additional_details');
    }
}
