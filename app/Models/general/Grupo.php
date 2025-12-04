<?php

namespace App\Models\general;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use App\Models\catalogo\Clase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $table = 'grupo';

    protected $fillable = ['descripcion', 'codigo', 'oid', 'gc_record', 'user_id'];

    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class, 'grupo_id');
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'grupo_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'grupo_id');
    }
}
