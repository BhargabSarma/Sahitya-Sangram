<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorInquiriesTable extends Migration
{
    public function up()
    {
        Schema::create('author_inquiries', function (Blueprint $table) {
            $table->id();
            // Author Details
            $table->string('author_name');
            $table->string('author_email');
            $table->string('author_phone');
            $table->text('author_bio')->nullable();
            // Book Details
            $table->string('book_title');
            $table->string('book_subtitle')->nullable();
            $table->text('book_description');
            $table->string('book_category');
            $table->string('book_pdf_path');
            // Status
            $table->enum('status', ['pending', 'approved', 'denied'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('author_inquiries');
    }
}
