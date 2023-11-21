<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion'];

    public function estadoSolicitud()
    {
        return $this->hasOne(EstadoSolicitud::class, 'id', 'estado_solicitud_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class); // Una categoria puede tener muchos productos asociados.
    }
}
