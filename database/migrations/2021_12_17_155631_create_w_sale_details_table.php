<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_sale_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('sale_id')->unsigned();
            $table->date('contract_date');
            $table->char('plan_type', 1);
            $table->bigInteger('plan_id')->unsigned();
            $table->string('plan_num', 10);
            $table->string('plan_name', 255);
            $table->decimal('unit_price', 13, 0)->nullable();
            $table->decimal('incentive_unit_price', 13, 0)->nullable();
            $table->integer('amount')->nullable();
            $table->decimal('total_price', 13, 0)->nullable();
            $table->decimal('incentive_total_price', 13, 0)->nullable();
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
        Schema::dropIfExists('w_sale_details');
    }
}
