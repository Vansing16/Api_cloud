<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('web_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100);
            $table->char('message', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('web_reviews');
    }
}
