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
    ];

    /**
     * Boot method to register model events
     */
    protected static function boot()
    {
        parent::boot();

        // When a book is being deleted, also delete its reviews
        static::deleting(function ($book) {
            $book->reviews()->delete();
        });
    }

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
