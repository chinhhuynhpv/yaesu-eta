<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_models', function (Blueprint $table) {
            $table->id();
            $table->string('class', 1);
            $table->string('management_number',255);
            $table->string('product_name',255);
            $table->string('soft_hard', 1);
            $table->integer('status')->nullable();
            $table->date('start_date', 1)->nullable();
            $table->date('end_date', 1)->nullable();
            $table->date('sport_end_date', 1)->nullable();
            $table->string('voice', 1)->nullable();
            $table->string('GPS', 1)->nullable();
            $table->string('camera', 1)->nullable();
            $table->string('base', 1)->nullable();
            $table->string('wifi', 1)->nullable();
            $table->string('sim_slot', 3)->nullable();
            $table->string('career', 255)->nullable();
            $table->bigInteger('standard_price');
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
        Schema::dropIfExists('m_models');
    }
}
