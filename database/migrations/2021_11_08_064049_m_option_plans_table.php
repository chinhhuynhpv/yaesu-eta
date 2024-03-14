<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MOptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_option_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_num', 10)->unique();
            $table->string('option_type', '1')->default('1');
            $table->string('calculation_unit', '1')->default('1');
            $table->string('plan_name', 255)->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('shop_web', '1')->default('1');
            $table->string('authority', '1')->default('1');
            $table->string('calculation_type', '1')->default('1');
            $table->boolean('usage_details_description')->default(1);
            $table->boolean('incentive_description')->default(1);
            $table->string('cancellation_limit_period', 4)->nullable();
            $table->decimal('usage_unit_price', 13, 0)->nullable();
            $table->string('period', 4)->nullable();
            $table->decimal('incentive_unit_price', 13, 0)->nullable();
            $table->decimal('incentive_unit_price2', 13,0)->nullable();
            $table->decimal('incentive_unit_price3', 13,0)->nullable();
            $table->string('discount_target1', 10)->nullable();
            $table->string('discount_target2', 10)->nullable();
            $table->string('discount_target3', 10)->nullable();
            $table->string('discount_target4', 10)->nullable();
            $table->string('discount_target5', 10)->nullable();
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
        //
        Schema::dropIfExists('m_option_plans');
    }
}
