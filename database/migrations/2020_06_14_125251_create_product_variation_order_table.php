<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variation_order', function (Blueprint $table) {
            $table->integer('order_id')->unsigned()->index();
            $table->integer('product_variation_id')->unsigned()->index();
            $table->integer('quantity');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_variation_id')->references('id')->on('product_variations');



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
        Schema::dropIfExists('product_variation_order');
    }
}
