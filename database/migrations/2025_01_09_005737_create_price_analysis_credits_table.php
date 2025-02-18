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
        Schema::create('price_analysis_credit', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->foreignId('analyze_id')->constrained('analyze')->onDelete('cascade')->comment('آنالیز');
            $table->foreignId('customers_id')->constrained('customers')->onDelete('cascade')->comment('مشتریان');
            $table->string('price', 10)->comment('قیمت(ریال)');
            $table->string('date', 10)->nullable()->comment('تاریخ ثبت');
            $table->string('time', 10)->nullable()->comment('زمان ثبت');
            $table->string('deleted_at', 16)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_analysis_credit');
    }
};