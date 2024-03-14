<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_shops', function (Blueprint $table) {
            $table->id();
            $table->integer('serial_number');
            $table->string('name', 30);
            $table->string('code', 7)->nullable();
            $table->string('postal_cd', 10)->nullable();
            $table->string('prefecture', 10)->nullable();
            $table->string('city', 10)->nullable();
            $table->string('address', 10)->nullable();
            $table->string('building_name', 10)->nullable();
            $table->string('tel_number', 15)->nullable();
            $table->string('fax_number', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('pic', 30)->nullable();
            $table->boolean('incentive_flg')->default(0);
            $table->string('incentive_type', 1)->default(1);
            $table->string('sap_supplier_num', 30)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_shops');
    }
}
