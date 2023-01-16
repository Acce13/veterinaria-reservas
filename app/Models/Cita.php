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

    //RelationShip
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function hora()
    {
        return $this->belongsTo(Hora::class, 'hora_id');
    }
}
