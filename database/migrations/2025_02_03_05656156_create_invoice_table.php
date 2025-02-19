<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    public function up()
    {
        Schema::create('invoice_set', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->tinyInteger('max_day')->comment('حداکثر تعداد روز مجاز درخواست فاکتور');
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_set');
    }
}
