<?php

declare(strict_types=1);

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
        Schema::create('photo_spots', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('full_description', 896);
            $table->string('image');
            $table->string('category');
            $table->string('bestHour');
            $table->string('location');
            $table->string('location_map', 512)->nullable();
            $table->json('tips');
            $table->json('nearestAttraction');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_spots');
    }
};
