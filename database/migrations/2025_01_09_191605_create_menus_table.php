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

        // Creating menu table
        Schema::create('menu', function (Blueprint $table) {
            $table->id()->comment('id');
            $table->foreignId('subsystem_id')->constrained('subsystem')->onDelete('cascade')->comment('زیرمنو');
            $table->smallInteger('menu_id')->nullable();
            $table->string('title', 50);
            $table->string('title_en', 50)->nullable();
            $table->string('icon_class', 30)->nullable();
            $table->string('route', 100)->nullable();
            $table->boolean('state')->default(true)->comment('Status: 1 – Active, 0 - Inactive');
            $table->smallInteger('ordering');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->smallInteger('clone_of')->nullable();
            $table->boolean('open_in_blank')->default(false);
            $table->boolean('open_in_iframe')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Dropping menu table
        Schema::dropIfExists('menu');
        
    }
};
