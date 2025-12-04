<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fuente extends Model
{
    protected $table = 'fuente';

    protected $fillable = ['descripcion', 'user_id'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'fuente_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'fuente_id');
    }
}
