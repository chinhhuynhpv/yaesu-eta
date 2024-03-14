<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUserLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_user_lines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('request_line_id')->unsigned();
            $table->string('voip_line_id', 30)->nullable();
            $table->string('line_num', 30);
            $table->string('voip_id_name', 255);
            $table->bigInteger('models_id')->unsigned()->nullable();
            $table->string('sim_num', 30)->nullable();
            $table->string('voip_line_password', 255)->nullable();
            $table->string('call_type', 1)->nullable();
            $table->string('priority', 2);
            $table->string('individual', 1)->nullable();
            $table->string('recording', 1)->nullable();
            $table->string('gps', 1)->nullable();
            $table->string('commander', 1)->nullable();
            $table->string('individual_priority', 1)->nullable();
            $table->string('cue_reception', 1)->nullable();
            $table->string('video', 1)->nullable();
            $table->date('start_date')->nullable();
            $table->string('status', 3)->nullable();
            $table->date('change_application_date')->nullable();
            $table->date('contract_renewal_date')->nullable();
            $table->text('memo', 255)->nullable();
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
            $table->foreign('models_id')->references('id')
                ->on('m_models')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('request_id')->references('id')
                ->on('m_user_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('request_line_id')->references('id')
                ->on('m_user_line_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unique(['shop_id', 'user_id', 'request_id','request_line_id'], 'm_user_lines_keys_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_user_lines');
    }
}
