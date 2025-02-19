<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceRulesTable extends Migration
{
    public function up()
    {
        Schema::create('invoice_rules', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->string('title', 200)->comment('عنوان قانون');
            $table->text('text')->comment('متن قانون');
            $table->boolean('state')->default(1)->comment('وضعیت');
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_rules');
    }
}
