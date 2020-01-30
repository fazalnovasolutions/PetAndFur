<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('ship_company')->nullable();
            $table->string('ship_first_name')->nullable();
            $table->string('ship_last_name')->nullable();
            $table->longText('ship_address_1')->nullable();
            $table->longText('ship_address_2')->nullable();
            $table->string('ship_country')->nullable();
            $table->string('ship_city')->nullable();
            $table->string('ship_state')->nullable();
            $table->string('ship_zipcode')->nullable();

            $table->longText('note')->nullable();
            $table->text('name')->nullable();
            $table->text('number')->nullable();
            $table->text('taxes_included')->nullable();
            $table->text('currency')->nullable();
            $table->text('financial_status')->nullable();
            $table->text('confirmed')->nullable();


            $table->string('bill_company')->nullable();
            $table->string('bill_first_name')->nullable();
            $table->string('bill_last_name')->nullable();
            $table->longText('bill_address_1')->nullable();
            $table->longText('bill_address_2')->nullable();
            $table->string('bill_country')->nullable();
            $table->string('bill_city')->nullable();
            $table->string('bill_state')->nullable();
            $table->string('bill_zipcode')->nullable();

            $table->string('total_tax')->nullable();
            $table->string('subtotal_price')->nullable();
            $table->string('total_discount')->nullable();

            $table->string('total_shipping_price')->nullable();
            $table->string('total_price')->nullable();

            $table->longText('tax_details')->nullable();
            $table->longText('shipping_details')->nullable();

            $table->longText('line_items')->nullable();

            $table->string('checkout_token')->nullable();
            $table->text('status_url')->nullable();

            $table->text('shopify_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
