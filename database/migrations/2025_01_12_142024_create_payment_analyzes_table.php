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
        Schema::create('payment_analyze', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_analysis_id')->constrained('customer_analysis')->onDelete('cascade')->comment('کد آنالیز مشتریان');
            $table->text('upload_fish')->nullable()->comment('آپلود فیش پرداختی');
            $table->text('transaction_id')->nullable()->comment('شماره تراکنش');
            $table->text('uniq_id')->nullable()->comment('uniq id');
            $table->string('datepay', 16)->comment('تاریخ و زمان پرداخت');
            $table->string('deleted_at', 16)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_analyze');
    }
};