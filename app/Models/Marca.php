<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre','descripcion','estado_solicitud_id'
    ];

    public function estadoSolicitud()
    {
        return $this->hasOne(EstadoSolicitud::class, 'id', 'estado_solicitud_id');
    }
    
    public function productos() // Una Marca puede tener muchos productos asociados.
    {
        return $this->hasMany(Producto::class);
    }
}
