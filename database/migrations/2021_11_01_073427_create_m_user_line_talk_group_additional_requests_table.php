<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUserLineTalkGroupAdditionalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_user_line_talk_group_add_req', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('line_group_req_id')->unsigned();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('group_id');
            $table->string('group_name', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('line_group_req_id')->references('id')
                ->on('m_user_line_talk_group_requests')
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
        DB::statement("ALTER TABLE m_user_line_talk_group_add_req AUTO_INCREMENT = 1000000001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_user_line_talk_group_add_req');
    }
}
