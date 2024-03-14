<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_users', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->string('contractor_id',50)->unique();
            $table->string('email',50);
            $table->string('password',255)->nullable();
            $table->string('contract_name',255);
            $table->string('contract_name_kana',255);
            $table->string('representative_position',255);
            $table->string('representative_name',255);
            $table->string('representative_name_kana',255);
            $table->string('billing_department',255)->nullable();
            $table->string('billing_manager_position',255)->nullable();
            $table->string('billing_manager_name',255)->nullable();
            $table->string('billing_post_number',255)->nullable();
            $table->string('billing_prefectures',255)->nullable();
            $table->string('billing_municipalities',255)->nullable();
            $table->string('billing_address',255)->nullable();
            $table->string('billing_building',255)->nullable();
            $table->string('billing_tel',255)->nullable();
            $table->string('billing_fax',255)->nullable();
            $table->string('billing_email',255)->nullable();
            $table->boolean('billing_shipping')->default(0);
            $table->string('shipping_destination',255)->nullable();
            $table->string('shipping_post_number',10)->default(1);
            $table->string('shipping_prefectures',10)->nullable();
            $table->string('shipping_municipalities',100)->nullable();
            $table->string('shipping_address',100)->nullable();
            $table->string('shipping_building',30)->nullable();
            $table->string('shipping_tel',30)->nullable();
            $table->string('shipping_fax',15)->nullable();
            $table->string('payment_type',1)->default(1);
            $table->string('bank_num',255)->nullable();
            $table->string('bank_name',255)->nullable();
            $table->string('branchi_num',255)->nullable();
            $table->string('branchi_name',255)->nullable();
            $table->string('deposit_type',1)->default(1);
            $table->string('account_num',255)->nullable();
            $table->string('account_name',50)->nullable();
            $table->string('bank_entruster_num',8)->nullable();
            $table->string('bank_customer_num',255)->nullable();
            $table->bigInteger('shop_id')->unsigned();
            $table->string('status',1)->default('1');
            $table->boolean('exists_document')->default(0);
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
        Schema::dropIfExists('m_users');
    }
}
