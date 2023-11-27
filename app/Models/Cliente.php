<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellidos'];

    public function estadoSolicitud()
    {
        return $this->hasOne(EstadoSolicitud::class, 'id', 'estado_solicitud_id');
    }

}
