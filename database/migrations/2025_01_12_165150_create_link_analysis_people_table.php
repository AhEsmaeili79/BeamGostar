<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_analysis_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customers_id')->constrained('customers')->onDelete('cascade')->comment('کد مشتریان');
            $table->foreignId('analyze_id')->constrained('analyze')->onDelete('cascade')->comment('آنالیز');
            $table->string('date', 10)->nullable()->comment('تاریخ ثبت');
            $table->string('time', 10)->nullable()->comment('زمان ثبت');
            $table->string('deleted_at', 16)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_analysis_persons');
    }
};
