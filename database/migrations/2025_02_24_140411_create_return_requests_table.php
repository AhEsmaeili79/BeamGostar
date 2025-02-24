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
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->foreignId('customer_analysis_id')->constrained('customer_analysis')->onDelete('cascade');
            $table->enum('status', ['requested', 'canceled', 'ready_for_return', 'returned'])->default('requested');
            $table->text('rejection_reason')->nullable(); // Store reason if rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};



