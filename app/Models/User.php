<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Esta línea ya está bien

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at // Corregí el tipo para Carbon
 * @property string $password
 * @property string $role // Nota: si 'role' no está en tu migración, necesitará una.
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; // <--- ¡AQUÍ ESTÁ EL CAMBIO CLAVE! Agregamos HasApiTokens

    protected $table = 'users';

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        // 'role', // Si agregas la columna 'role' a la migración, descomenta esta línea también.
    ];

    // Si planeas usar el campo 'role', podrías añadir un accessor/mutator o simplemente
    // asegurarte de que la columna exista en la tabla 'users' y esté en $fillable si la asignas masivamente.
}