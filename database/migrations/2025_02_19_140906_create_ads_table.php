<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Ad title
            $table->text('description'); // Ad description
            $table->string('image')->nullable(); // Optional image for the ad
            $table->string('url')->nullable(); // Optional link to an external site
            $table->integer('user_id'); // Assuming ads are linked to a user (if required)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
