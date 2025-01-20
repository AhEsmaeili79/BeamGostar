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
        Schema::create('subsystem', function (Blueprint $table) {
            $table->id()->comment('کد');
            $table->string('title', 50);
            $table->string('title_en', 50)->nullable();
            $table->string('icon_class', 30)->nullable();
            $table->tinyInteger('state');
            $table->tinyInteger('ordering')->unsigned();
            $table->string('header_title', 100)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsystem');
    }
};
