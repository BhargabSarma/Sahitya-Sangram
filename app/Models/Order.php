<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'total',
        'status',
        'shipping_address',
        'shiprocket_awb',
        'shiprocket_shipment_id',
        'shiprocket_response'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'shiprocket_response' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
