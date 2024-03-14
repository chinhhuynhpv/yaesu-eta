<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWUserLinePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_user_line_plans', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('line_id')->unsigned();
            $table->bigInteger('plan_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->bigInteger('option_id1')->nullable();
            $table->bigInteger('option_id2')->nullable();
            $table->bigInteger('option_id3')->nullable();
            $table->bigInteger('option_id4')->nullable();
            $table->bigInteger('option_id5')->nullable();
            $table->bigInteger('option_id6')->nullable();
            $table->bigInteger('option_id7')->nullable();
            $table->bigInteger('option_id8')->nullable();
            $table->bigInteger('option_id9')->nullable();
            $table->bigInteger('option_id10')->nullable();
            $table->string('automatic_update', 1)->nullable();
            $table->date('plan_set_start_date');
            $table->date('plan_set_end_date')->nullable();
            $table->string('line_status', 3)->nullable();
            $table->unsignedInteger('month_left_num')->nullable();
            $table->date('limited_time_start_date')->nullable();
            $table->unsignedInteger('limited_time_month_left_num')->nullable();
            $table->string('billing_calc_type', 1)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('w_user_line_plans');
    }
}
