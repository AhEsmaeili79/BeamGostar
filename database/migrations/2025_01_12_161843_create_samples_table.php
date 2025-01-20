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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_analysis_id')->constrained('customer_analysis')->onDelete('cascade')->comment('کد آنالیز مشتریان');
            $table->foreignId('analyze_id')->constrained('analyze')->onDelete('cascade')->comment('آنالیز');
            $table->unsignedTinyInteger('sample_code')->zerofill()->comment('کد پیگیری نمونه');
            $table->integer('order')->nullable()->comment('اولویت');
            $table->tinyInteger('status')->comment('وضعیت نمونه');
            $table->string('deleted_at', 16)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
