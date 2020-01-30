<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('variant_id')->nullable();
            $table->longText('title')->nullable();
            $table->text('quantity')->nullable();
            $table->text('sku')->nullable();
            $table->text('variant_title')->nullable();
            $table->longText('name')->nullable();
            $table->text('price')->nullable();
            $table->longText('properties')->nullable();

            $table->text('shopify_id')->nullable();
            $table->text('product_id')->nullable();
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->bigInteger('shop_id')->unsigned()->nullable();

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
        Schema::dropIfExists('order_products');
    }
}
