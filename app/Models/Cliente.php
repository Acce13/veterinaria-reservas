<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    protected $fillable = [
        'numero_documento',
        'nombre',
        'apellido',
        'nombre_mascota',
    ];
    protected $hidden = [
        'numero_documento',
    ];
}
