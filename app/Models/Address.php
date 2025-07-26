<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'full_name',
        'type',
        'street_address',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
