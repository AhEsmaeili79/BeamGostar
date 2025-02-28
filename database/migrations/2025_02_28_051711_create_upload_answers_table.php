<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('upload_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_analysis_id')->constrained('customer_analysis')->onDelete('cascade')->comment('کد آنالیز مشتریان'); 
            $table->string('result')->comment('جواب آنالیز');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_answers');
    }
};
