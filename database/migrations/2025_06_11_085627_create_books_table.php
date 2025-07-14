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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->foreignId('author_id')->constrained('authors');
            $table->text('description')->nullable();
            $table->string('category', 100);
            $table->string('cover_image_front')->nullable();
            $table->string('cover_image_back')->nullable();
            $table->string('book_file')->nullable();
            $table->integer('number_of_pages')->nullable();
            $table->string('language', 50)->nullable();
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced'])->default('Beginner');
            $table->boolean('is_bestseller')->default(false);
            $table->decimal('hard_copy_price', 10, 2)->default(0.00);
            $table->decimal('digital_price', 10, 2)->default(0.00);
            $table->boolean('is_ready')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
