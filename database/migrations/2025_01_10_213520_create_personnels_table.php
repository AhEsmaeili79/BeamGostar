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
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->nullable()->comment('نام');
            $table->string('family', 40)->nullable()->comment('نام خانوادگي');
            $table->string('national_code', 15)->nullable()->unique()->comment('کد ملي');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('کاربر مرتبط');
            $table->foreignId('role_id')->nullable()->constrained('roles'); // Add the role_id column as a foreign key
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
