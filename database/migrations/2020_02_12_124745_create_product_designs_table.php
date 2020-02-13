<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_designs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('order_product_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->text('design')->nullable();
            $table->timestamps();
        });
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('design_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_designs');
    }
}
