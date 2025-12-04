<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubClase extends Model
{
     protected $table = 'subclase';

    protected $fillable = ['descripcion', 'codigo', 'clase_id', 'vida_util', 'oid', 'gc_record', 'user_id'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'subclase_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'subclase_id');
    }
}
