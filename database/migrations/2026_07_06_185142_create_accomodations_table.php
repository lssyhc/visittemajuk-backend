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
        Schema::create('accomodations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->longText('full_description');
            $table->string('image_url', 2048);
            $table->string('category');
            $table->integer('min_price')->unsigned();
            $table->integer('max_price')->unsigned();
            $table->text('location');
            $table->string('contacs');
            $table->string('site_url')->nullable();
            $table->json('facilities');
            $table->json('gallery');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accomodations');
    }
};
