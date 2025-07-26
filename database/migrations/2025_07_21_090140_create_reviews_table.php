<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('book_id')->constrained('books');
            $table->foreignId('order_id')->constrained('orders');
            $table->integer('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'book_id']);

            // Enforce referential integrity and cascading delete
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
