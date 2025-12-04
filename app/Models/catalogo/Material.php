<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
     protected $table = 'material';

    protected $fillable = ['descripcion', 'user_id', 'oid'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'material_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'material_id');
    }
}
