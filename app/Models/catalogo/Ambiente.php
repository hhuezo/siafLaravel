<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ambiente extends Model
{
    protected $table = 'ambiente';

    protected $fillable = ['oid', 'gc_record', 'user_id', 'descripcion', 'unidad_id', 'unidad_oid'];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'ambiente_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'ambiente_id');
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class,'unidad_id');
    }
}
