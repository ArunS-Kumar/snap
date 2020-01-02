<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerCompanyUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_company_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('partner_company_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
 
            $table->unique(['partner_company_id','user_id']);
            $table->foreign('partner_company_id')->references('id')->on('partner_companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_company_user');
    }
}
