<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'stock',
        'location',
    ];

    // Relationships
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
