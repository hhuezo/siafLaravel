<?php

namespace App\Models\catalogo;

use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Combustible extends Model
{
    protected $table = 'combustible';

    protected $fillable = ['descripcion', 'user_id', 'oid'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'combustible_id');
    }
}
