<?php

namespace App\Models\general;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Establecimiento extends Model
{
     protected $table = 'establecimiento';

    protected $fillable = [
        'oid',
        'user_id',
        'descripcion',
        'codigo',
    ];


    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'establecimiento_id');
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'establecimiento_id');
    }
}
