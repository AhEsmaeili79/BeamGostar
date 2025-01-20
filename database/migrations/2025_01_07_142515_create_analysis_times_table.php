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
        Schema::create('analysis_time', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->foreignId('analyze_id')->constrained('analyze')->onDelete('cascade')->comment('آنالیز');
            $table->tinyInteger('accordingto')->comment('برحسب');
            $table->string('number_done', 5)->comment('تعداد قابل انجام');
            $table->string('number_minutes', 5)->nullable()->comment('دقیقه قابل انجام');
            $table->integer('default_number_day')->comment('تعداد روز پیش فرض');
            $table->string('date', 10)->nullable()->comment('تاریخ ثبت');
            $table->string('time', 10)->nullable()->comment('زمان ثبت');
            $table->timestamp('deleted_at')->nullable()->comment('تاریخ حذف');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_time');
    }
};
