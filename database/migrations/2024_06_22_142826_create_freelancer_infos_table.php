<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancerInfosTable extends Migration
{
    public function up()
    {
        Schema::create('freelancer_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('freelancer_id')->primary();
            $table->string('professional_role', 100);
            $table->string('skill_detail', 500);
            $table->string('bio_detail', 500);
            $table->timestamps();

            $table->foreign('freelancer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('freelancer_infos');
    }
}
