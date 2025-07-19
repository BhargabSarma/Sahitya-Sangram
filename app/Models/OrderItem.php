<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'book_id',
        'quantity',
        'price',
        'type',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    // OrderItem.php
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
