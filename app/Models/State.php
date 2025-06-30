<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    // Si la tabla no se llama "states", especifica el nombre
    // protected $table = 'states';

    // Si no usas timestamps en esa tabla, desactívalos
    public $timestamps = false;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'name',
        'abbreviation', // o 'code_2_digits' según como la hayas nombrado
        'code_3_digits', // opcional, si la usas
        'country_code',  // opcional si quieres filtrar por país
    ];
}
