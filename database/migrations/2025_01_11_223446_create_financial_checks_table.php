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
        Schema::create('financial_check', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->foreignId('customer_analysis_id')->constrained('customer_analysis')->onDelete('cascade')->comment('کد آنالیز مشتریان'); 
            $table->text('scan_form')->nullable()->comment('اسکن فرم');
            $table->tinyInteger('state')->comment('وضعیت');
            $table->string('date_success', 16)->nullable()->comment('تاریخ تایید');
            $table->string('deleted_at', 16)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_check');
    }
};