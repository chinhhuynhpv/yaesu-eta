<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TLinePauses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_line_pauses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('line_id')->unsigned();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('line_plans_id')->unsigned();
            $table->date('pause_start_date');
            $table->date('pause_end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('line_id')->references('id')
                ->on('m_user_lines')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('line_plans_id')->references('id')
                ->on('t_user_line_plans')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('shop_id')->references('id')
                ->on('m_shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')
                ->on('m_users')
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
        Schema::dropIfExists('t_line_pauses');
    }
}
