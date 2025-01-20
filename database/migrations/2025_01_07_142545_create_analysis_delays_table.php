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
        Schema::create('analysis_delay', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->foreignId('analyze_id')->constrained('analyze')->onDelete('cascade')->comment('آنالیز');
            $table->integer('delay')->comment('تاخیر برحسب روز');
            $table->text('text')->comment('متن پیامک');
            $table->timestamp('deleted_at')->nullable()->comment('تاریخ حذف');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_delay');
    }
};
