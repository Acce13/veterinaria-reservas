<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;
    protected $table = 'citas';
    protected $fillable = [
        'cliente_id',
        'hora_id',
        'fecha',
        'reservado',
    ];
}
