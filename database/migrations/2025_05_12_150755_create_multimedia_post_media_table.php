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
        Schema::create('multimedia_post_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('multimedia_post_id');
            $table->unsignedBigInteger('media_id');

            $table->foreign('multimedia_post_id')->references('id')->on('multimedia_posts')->onDelete('cascade');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multimedia_post_media');
    }
};
