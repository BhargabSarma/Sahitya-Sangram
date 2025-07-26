<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorInquiry extends Model
{
    protected $fillable = [
        'author_name',
        'author_email',
        'author_phone',
        'author_bio',
        'book_title',
        'book_subtitle',
        'book_description',
        'book_category',
        'book_pdf_path',
        'status'
    ];
}
