<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUserTalkGroupRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_user_talk_group_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('shop_id')->unsigned();
            $table->string('voip_group_id', 30)->nullable();
            $table->bigInteger('row_num')->nullable();
            $table->string('request_type')->nullable();
            $table->string('name', 255);
            $table->string('priority', 3)->nullable();
            $table->string('member_view', 1)->nullable();
            $table->string('group_responsible_person', 255)->nullable();
            $table->string('status', 3)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('request_id')->references('id')
                ->on('m_user_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')
                ->on('m_users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('shop_id')->references('id')
                ->on('m_shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        DB::statement("ALTER TABLE m_user_talk_group_requests AUTO_INCREMENT = 1000000001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_user_talk_group_requests');
    }
}
