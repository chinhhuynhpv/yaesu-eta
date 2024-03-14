<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTShopSalesPromotionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_shop_sales_promotion_plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->date('incentive_date');
            $table->bigInteger('sales_promotion_id')->unsigned();
            $table->decimal('usage_unit_price', 13, 0)->nullable();
            $table->decimal('incentive_unit_price', 13, 0)->nullable();
            $table->bigInteger('user_request_id')->unsigned();
            $table->bigInteger('line_id')->unsigned();
            $table->timestamps();
            $table->foreign('shop_id')->references('id')
                ->on('m_shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')
                ->on('m_users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('sales_promotion_id')->references('id')
                ->on('m_sales_promotion_plans')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_request_id')->references('id')
                ->on('m_user_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('line_id')->references('id')
                ->on('m_user_lines')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_shop_sales_promotion_plans');
    }
}
