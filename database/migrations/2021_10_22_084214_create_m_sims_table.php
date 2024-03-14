<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sims', function (Blueprint $table) {
            $table->id();
            $table->string('sim_num', 30);
            $table->string('career', 30);
            $table->string('sim_contractor', 30)->nullable();
            $table->string('status', 3)->default('1');
            $table->text('remark')->nullable();
            $table->date('sim_opening_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['sim_num']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_sims');
    }
}
