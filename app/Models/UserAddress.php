<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address1',
        'address2',
        'country_code',
        'postal_code',
        'state',
        'city',
        'colony',
        'reference',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
