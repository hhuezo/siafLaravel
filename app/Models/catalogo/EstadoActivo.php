<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoActivo extends Model
{
    protected $table = 'estado_activo';

    protected $fillable = ['descripcion', 'codigo'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'estado_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'estado_id');
    }
}
