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
        Schema::create('customer_analysis', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->foreignId('customers_id')->constrained('customers')->onDelete('cascade')->comment('مشتریان');
            $table->date('acceptance_date')->default(now())->comment('تاریخ پذیرش');
            $table->foreignId('get_answers_id')->constrained('get_answers')->onDelete('cascade')->comment('نحوه دریافت جواب آنالیز');
            $table->foreignId('analyze_id')->constrained('analyze')->onDelete('cascade')->comment('آنالیز');
            $table->tinyInteger('samples_number')->comment('تعداد نمونه');
            $table->string('analyze_time', 5)->comment('کل زمان آنالیز');
            $table->tinyInteger('value_added')->comment('ارزش افزوده');
            $table->tinyInteger('grant')->nullable()->comment('گرنت دارد');
            $table->string('additional_cost', 15)->nullable()->comment('هزینه اضافه');
            $table->string('additional_cost_text', 150)->nullable()->comment('توضیح هزینه اضافه');
            $table->string('total_cost', 20)->comment('هزینه کل');
            $table->string('applicant_share', 20)->comment('سهم متقاضی');
            $table->string('network_share', 20)->comment('سهم شبکه');
            $table->string('network_id', 20)->comment('ID شبکه');
            $table->foreignId('payment_method_id')->constrained('payment_method')->onDelete('cascade')->comment('نحوه پرداخت');
            $table->tinyInteger('discount')->nullable()->comment('تخفیف');
            $table->string('discount_num', 10)->nullable()->comment('مبلغ /درصد تخفیف');
            $table->text('scan_form')->nullable()->comment('اسکن فرم');
            $table->text('description')->nullable()->comment('توضیحات پذیرش');
            $table->tinyInteger('priority')->nullable()->comment('اولویت');
            $table->tinyInteger('status')->comment('وضعیت پذیرش');
            $table->tinyInteger('state')->nullable()->comment('وضعیت');
            $table->string('tracking_code', 20)->nullable()->comment('کد پیگیری');
            $table->string('date_answer', 10)->nullable()->comment('تاریخ جوابدهی');
            $table->string('upload_answer', 20)->nullable()->comment('تاریخ جوابده');
            $table->string('deleted_at', 16)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_analysis');
    }
};
