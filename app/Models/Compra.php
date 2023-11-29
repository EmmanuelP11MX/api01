<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $fillable = ['subtotal', 'total'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot('prercio','cantidad','subtotal')->withTimestamps();
    }
}
