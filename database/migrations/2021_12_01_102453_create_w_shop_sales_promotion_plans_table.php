<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWShopSalesPromotionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_shop_sales_promotion_plans', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->date('incentive_date');
            $table->bigInteger('sales_promotion_id')->unsigned();
            $table->decimal('usage_unit_price', 13, 0)->nullable();
            $table->decimal('incentive_unit_price', 13, 0)->nullable();
            $table->bigInteger('user_request_id')->unsigned();
            $table->bigInteger('line_id')->unsigned();
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
        Schema::dropIfExists('w_shop_sales_promotion_plans');
    }
}
