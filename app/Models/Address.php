<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company',
        'name',
        'email',
        'phone',
        'street',
        'number',
        'internal_number',
        'district',
        'city',
        'state',
        'postal_code',
        'country',
        'phone_code',
        'category',
        'identification_number',
        'reference',
        'type_address',
        'is_default',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Si planeas tener un método para obtener solo la dirección predeterminada de un usuario:
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}