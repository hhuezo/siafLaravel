<?php

namespace App\Models\catalogo;

use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Traccion extends Model
{
    protected $table = 'traccion';

    protected $fillable = ['descripcion', 'user_id', 'oid'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'traccion_id');
    }
}
