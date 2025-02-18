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
        Schema::create('technical_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_analysis_id')->constrained('customer_analysis')->onDelete('cascade')->comment('کد آنالیز مشتریان');
            $table->foreignId('analyze_id')->constrained('analyze')->onDelete('cascade')->comment('آنالیز');
            $table->tinyInteger('state')->comment('وضعیت');
            $table->text('text')->nullable()->comment('توضیحات درصورت عدم تایید');
            $table->timestamp('deleted_at')->nullable(); // You can use 'timestamp' instead of varchar for deleted_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_reviews');
    }
};