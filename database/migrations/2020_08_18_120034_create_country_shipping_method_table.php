<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryShippingMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Country_Shipping_Method', function (Blueprint $table) {
           $table->integer('country_id')->unsigned()->index();
           $table->integer('shipping_method_id')->unsigned()->index();
           $table->foreign('country_id')->references('id')->on('countries');
           $table->foreign('shipping_method_id')->references('id')->on('shipping_methods');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Country_Shipping_Method');
    }
}
