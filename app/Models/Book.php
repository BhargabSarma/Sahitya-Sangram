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
        'author_id',
        'image_processing_status',
        'image_processing_error',

    ];
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
