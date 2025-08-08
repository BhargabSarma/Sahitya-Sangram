<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'category',
        'number_of_pages',
        'cover_image_front',
        'cover_image_back',
        'book_file',
        'language',
        'level',
        'is_bestseller',
        'hard_copy_price',
        'digital_price',
        'hard_copy_discount',
        'digital_discount',
        'author_id',
    ];
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
