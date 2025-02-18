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
        Schema::create('analysis_discount', function (Blueprint $table) {
            $table->increments('id')->comment('کد');
            $table->foreignId('analyze_id')->constrained('analyze')->onDelete('cascade')->comment('آنالیز');
            $table->tinyInteger('discount_type')->comment('نوع تخفیف');
            $table->tinyInteger('cent')->nullable()->comment('درصد');
            $table->string('amount', 10)->nullable()->comment('مبلغ');
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
        Schema::dropIfExists('analysis_discount');
    }
};