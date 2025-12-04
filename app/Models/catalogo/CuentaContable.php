<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuentaContable extends Model
{
    protected $table = 'cuenta_contable';

    protected $fillable = ['descripcion', 'codigo', 'user_id'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'cuenta_contable_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'cuenta_contable_id');
    }
}
