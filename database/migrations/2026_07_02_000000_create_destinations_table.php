<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->longText('full_description');
            $table->string('image', 2048)->nullable();
            $table->string('category');
            $table->string('price');
            $table->text('location');
            $table->string('location_map', 512)->nullable();
            $table->string('open_hours');
            $table->json('facilities');
            $table->json('activities');
            $table->json('tips');
            $table->timestamps();
        });

        Schema::create('destination_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('image', 2048);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_galleries');
        Schema::dropIfExists('destinations');
    }
};
