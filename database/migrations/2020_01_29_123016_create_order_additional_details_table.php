<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAdditionalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_additional_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('status')->nullable();
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->bigInteger('shop_id')->unsigned()->nullable();
            $table->bigInteger('designer_id')->unsigned()->nullable();
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
        Schema::dropIfExists('order_additional_details');
    }
}
