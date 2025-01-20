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
        Schema::create('sendsms', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->foreignId('customers_id')->nullable()->constrained('customers')->onDelete('cascade');
            $table->string('number', 11)->nullable()->comment('شماره همراه');
            $table->text('text')->comment('متن پیام');
            $table->tinyInteger('state')->comment('وضعیت');
            $table->string('send_time', 16)->comment('زمان ارسال');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sendsms');
    }
};
