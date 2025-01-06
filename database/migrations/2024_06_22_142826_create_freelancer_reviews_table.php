<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancerReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('freelancer_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('review_by_admin_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->char('comment', 100);
            $table->tinyInteger('rating');
            $table->timestamps();

            $table->foreignId('review_by_admin_id')->nullable()->constrained('admins');
            $table->foreign('freelancer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('freelancer_reviews');
    }
}
