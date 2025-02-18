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
        Schema::create('bank_account', function (Blueprint $table) {
            $table->id('id')->comment(comment: 'کد');
            $table->tinyInteger('account_type')->comment('نوع حساب');
            $table->string('account_number', 16)->comment('شماره حساب');
            $table->string('card_number', 16)->comment('شماره کارت');
            $table->string('shaba_number', 24)->comment('شماره شبا');
            $table->string('account_holder_name', 150)->comment('نام دارنده حساب');
            $table->timestamp('deleted_at')->nullable()->comment('تاریخ حذف');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account');
    }
};