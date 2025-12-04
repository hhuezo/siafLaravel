<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clase extends Model
{
    protected $table = 'clase';

    protected $fillable = ['descripcion', 'codigo', 'grupo_id', 'vida_util', 'oid', 'gc_record', 'user_id'];

    public function subclases(): HasMany
    {
        return $this->hasMany(SubClase::class, 'clase_id');
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'clase_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'clase_id');
    }
}
