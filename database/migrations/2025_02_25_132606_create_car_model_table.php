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
        Schema::create('car_model', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars');
            $table->json('engines');
            $table->json('colors');
            $table->date('manufacture_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_model');
    }
};
