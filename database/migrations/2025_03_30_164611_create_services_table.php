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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_car_id')->constrained('user_cars')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('default_service_id')->constrained('default_services')->onDelete('cascade')->nullable();
            $table->integer('price')->nullable();
            $table->json('custom_service');
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->date('last_serviced')->nullable();
            $table->date('next_service')->nullable();
            $table->integer('next_service_km')->nullable();
            $table->string('product_used')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
