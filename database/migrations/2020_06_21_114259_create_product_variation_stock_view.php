<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationStockView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement("
       CREATE or replace VIEW product_variation_stock_view AS
            SELECT
            product_variations.product_id AS product_id ,
            product_variations.id AS product_variation_id,
            COALESCE(SUM(stocks.quantity)-COALESCE(SUM(product_variation_order.quantity),0) , 0 ) AS stock ,
            case when COALESCE(SUM(stocks.quantity)-COALESCE(SUM(product_variation_order.quantity),0) , 0 ) > 0
            then TRUE ELSE FALSE END AS in_stock
            FROM product_variations
            LEFT JOIN (
            SELECT stocks.product_variation_id AS id ,
            SUM(stocks.quantity) AS quantity
            FROM stocks
            GROUP BY stocks.product_variation_id
            ) AS stocks using(id)
            LEFT JOIN (
            SELECT product_variation_order.product_variation_id AS id ,
            SUM(product_variation_order.quantity) AS quantity
            FROM product_variation_order
            GROUP BY product_Variation_order.product_variation_id
            ) AS product_variation_order using(id)
            group by product_variations.id ,product_variations.product_id


       ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::DropIfExists('product_variation_stock_view');

        DB::statement('drop view if exists product_variation_stock_view');
    }
}
