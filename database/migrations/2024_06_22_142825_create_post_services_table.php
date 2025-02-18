<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostServicesTable extends Migration
{
    public function up()
    {
        Schema::create('post_services', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('thumbmail_url', 100);
            $table->decimal('rate_value', 10, 2);
            $table->string('rate_unit', 100)->default('hour');
            $table->decimal('cost', 10, 2);
            $table->string('desciption', 1000);
            $table->unsignedBigInteger('freelancer_id');
            $table->enum('review_status', ['pending', 'rejected', 'approved'])->default('pending');
            $table->timestamps();

            $table->foreign('freelancer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('review_by_admin_id')->nullable()->constrained('admins');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_services');
    }
}
