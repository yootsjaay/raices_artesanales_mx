<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'artesania_id',
        'content',
        'rating',
        'status',
    ];

    /**
     * Un comentario pertenece a una Artesanía.
     */
    public function artesania()
    {
        return $this->belongsTo(Artesania::class);
    }

    /**
     * Un comentario pertenece a un Usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
