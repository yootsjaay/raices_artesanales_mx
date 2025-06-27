<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ¡¡¡AÑADE ESTA LÍNEA!!!

/**
 * Class Address
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $company
 * @property string $name
 * @property string|null $email
 * @property string $phone
 * @property string $street
 * @property string $number
 * @property string|null $internal_number
 * @property string $district
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property string $country
 * @property string|null $phone_code
 * @property int|null $category
 * @property string|null $identification_number
 * @property string|null $reference
 * @property string $type_address
 * @property bool $is_default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 *
 * @package App\Models
 */
class Address extends Model
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles; // Esta línea ya está bien, siempre que HasFactory esté importado arriba
    protected $table = 'addresses';

    protected $casts = [
        'user_id' => 'int',
        'category' => 'int',
        'is_default' => 'bool'
    ];

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
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}