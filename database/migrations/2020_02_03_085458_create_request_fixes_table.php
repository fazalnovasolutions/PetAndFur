<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestFixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_fixes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('msg')->nullable();
            $table->text('photo')->nullable();
            $table->text('response')->nullable();
            $table->timestamp('response_created_at')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('order_product_id')->nullable();
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
        Schema::dropIfExists('request_fixes');
    }
}
