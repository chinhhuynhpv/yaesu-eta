<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUserLineTalkGroupRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_user_line_talk_group_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('row_num')->nullable();
            $table->bigInteger('line_id')->unsigned();
            $table->string('line_num', 255);
            $table->string('request_type', 1)->nullable();
            $table->string('name', 255);
            $table->bigInteger('group_id')->unsigned();
            $table->string('group_name', 255);
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
            $table->foreign('group_id')->references('id')
                ->on('m_user_talk_group_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('line_id')->references('id')
                ->on('m_user_line_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        DB::statement("ALTER TABLE m_user_line_talk_group_requests AUTO_INCREMENT = 1000000001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_user_line_talk_group_requests');
    }
}
