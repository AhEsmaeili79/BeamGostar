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
        Schema::create('customers', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->tinyInteger('customer_type')->comment('نوع مشتری');
            $table->tinyInteger('clearing_type')->comment('نوع تسویه');
            $table->tinyInteger('nationality')->comment('تابعیت');
            $table->string('national_code', 10)->nullable()->unique()->comment('کد ملی');
            $table->string('national_id', 15)->nullable()->unique()->comment('شناسه ملی');
            $table->string('passport', 10)->unique()->nullable()->comment('شماره گذرنامه');
            $table->string('economy_code', 15)->nullable()->unique()->comment('کد اقتصادی');
            $table->string('name_fa', 40)->comment('نام (فارسی)');
            $table->string('family_fa', 40)->comment('نام خانوادگی(فارسی)');
            $table->string('name_en', 40)->nullable()->comment('نام (انگلیسی)');
            $table->string('family_en', 40)->nullable()->comment('نام خانوادگی (انگلیسی)');
            $table->string('birth_date', 10)->nullable()->comment('تاریخ تولد');
            $table->string('company_fa', 255)->nullable()->comment('نام شرکت (فارسی)');
            $table->string('company_en', 255)->nullable()->comment('نام شرکت (انگلیسی)');
            $table->string('mobile', 11)->comment('شماره همراه');
            $table->string('phone', 11)->nullable()->comment('شماره تماس شرکت');
            $table->string('password', 255)->nullable()->comment('رمز عبور');
            $table->string('re_password', 255)->nullable()->comment('تکرار رمز عبور');
            $table->string('email', 170)->nullable()->comment('پست الکترونیک');
            $table->string('postal_code', 10)->nullable()->comment('کد پستی');
            $table->string('address', 255)->nullable()->comment('آدرس');
            $table->timestamp('deleted_at')->nullable()->comment('تاریخ حذف');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
