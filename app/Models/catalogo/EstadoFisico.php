<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoFisico extends Model
{
    protected $table = 'estado_fisico';

    protected $fillable = ['descripcion', 'oid', 'user_id'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'estado_fisico_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'estado_fisico_id');
    }
}
