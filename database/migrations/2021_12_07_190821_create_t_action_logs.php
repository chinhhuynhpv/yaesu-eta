<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTActionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_action_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type');
            $table->integer('shop_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->boolean('is_admin')->nullable();
            $table->string('controller');
            $table->string('method');
            $table->string('action');
            $table->text('parameter');
            $table->string('ip');
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
        Schema::dropIfExists('t_action_logs');
    }
}
