<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'hora', 'cantidad', 'tipo', 'producto_id'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}