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
        Schema::create('author_comics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->integer('views')->default(0);
            $table->string('cover');
            $table->string('comics_file');
            $table->integer('age_restriction')->default(0);
            $table->integer('average_assessment')->nullable();
            $table->enum('is_moderated', ['successful', 'unsuccessful', 'under review'])->default('under review');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_comics');
    }
};
