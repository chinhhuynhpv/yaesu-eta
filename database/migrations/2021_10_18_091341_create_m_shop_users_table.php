<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMShopUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_shop_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('shop_code', 30)->unique();
            $table->string('password', 255);
            $table->bigInteger('shop_id')->unsigned();
            $table->string('shop_email', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('shop_id')->references('id')
                ->on('m_shops')
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
        Schema::dropIfExists('m_shop_users');
    }
}
