<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSalesPromotionPlanSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sales_promotion_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_num', 10);
            $table->string('calculation_unit', 1)->nullable();
            $table->string('plan_name', 255)->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('shop_web', 1)->nullable();
            $table->string('authority', 1)->nullable();
            $table->string('calculation_type', 1)->nullable();
            $table->boolean('usage_details_description')->nullable();
            $table->boolean('incentive_description')->nullable();
            $table->string('cancellation_limit_period', 4)->nullable();
            $table->decimal('usage_unit_price', 13, 0)->nullable();
            $table->string('period', 4)->nullable();
            $table->decimal('incentive_unit_price', 13,0)->nullable();
            $table->decimal('incentive_unit_price2', 13, 0)->nullable();
            $table->decimal('incentive_unit_price3', 13, 0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_sales_promotion_plans');
    }
}
