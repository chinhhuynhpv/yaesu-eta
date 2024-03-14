<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUserTalkGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_user_talk_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('request_group_id')->unsigned();
            $table->string('voip_group_id', 30)->nullable();
            $table->string('name', 255);
            $table->string('priority', 3)->nullable();
            $table->string('member_view', 1)->nullable();
            $table->string('group_responsible_person', 255)->nullable();
            $table->string('status', 3)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')
                ->on('m_users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('shop_id')->references('id')
                ->on('m_shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('request_id')->references('id')
                ->on('m_user_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('request_group_id')->references('id')
                ->on('m_user_talk_group_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unique(['shop_id', 'user_id', 'request_id', 'request_group_id'], 'm_user_talk_groups_keys_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_user_talk_groups');
    }
}
