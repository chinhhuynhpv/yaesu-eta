<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUserLineTalkGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_user_line_talk_group', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('line_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('number')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('shop_id')->references('id')
                ->on('m_shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')
                ->on('m_users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('request_id')->references('id')
                ->on('m_user_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('line_id')->references('id')
                ->on('m_user_lines')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('group_id')->references('id')
                ->on('m_user_talk_groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unique(['shop_id', 'user_id', 'request_id', 'group_id', 'line_id'], 'm_user_line_talk_group_keys_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_user_line_talk_group');
    }
}
