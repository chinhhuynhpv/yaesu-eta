<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUserRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_user_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 24)->nullable();
            $table->bigInteger('shop_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('status', 1)->default('1');
            $table->boolean('add_flg')->default(0);
            $table->boolean('modify_flg')->default(0);
            $table->boolean('pause_restart_flg')->default(0);
            $table->boolean('discontinued_flg')->default(0);
            $table->date('request_date')->nullable();
            $table->text('remark')->nullable();
            $table->text('precautionary_statement')->nullable();
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
        });
        DB::statement("ALTER TABLE m_user_requests AUTO_INCREMENT = 1000000001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_user_requests');
    }
}
