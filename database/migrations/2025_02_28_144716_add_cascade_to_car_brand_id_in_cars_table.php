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
        Schema::table('cars', function (Blueprint $table) {
            // Drop the existing foreign key if it exists
            $table->dropForeign(['car_brand_id']);

            // Add the foreign key with ON DELETE CASCADE
            $table->foreign('car_brand_id')
                ->references('id')
                ->on('car_brands')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Drop the foreign key with cascade
            $table->dropForeign(['car_brand_id']);

            // Optionally, you can re-add the foreign key without cascade or leave it out
            $table->foreign('car_brand_id')
                ->references('id')
                ->on('car_brands');
        });
    }
};
