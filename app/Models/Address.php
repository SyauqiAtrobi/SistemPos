<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id','label','address','city','postal_code','phone','is_primary','lat','lng'];

    protected $casts = [
        'is_primary' => 'boolean',
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
