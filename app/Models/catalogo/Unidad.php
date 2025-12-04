<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unidad extends Model
{
    protected $table = 'unidad';

    protected $fillable = ['oid', 'gc_record', 'user_id', 'descripcion'];

    public function ambientes(): HasMany
    {
        return $this->hasMany(Ambiente::class, 'unidad_id');
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'unidad_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'unidad_id');
    }
}
