<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'cantidad', 'ultimo_movimiento'];

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * remove created_at and updated_at from toArray
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        unset($array['created_at'], $array['updated_at']);
        return $array;
    }
}
