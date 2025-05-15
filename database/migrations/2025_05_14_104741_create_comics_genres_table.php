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
        Schema::create('comics_genres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comics_id');
            $table->unsignedBigInteger('genre_id');

            $table->foreign('comics_id')->references('id')->on('author_comics')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comics_genres');
    }
};
