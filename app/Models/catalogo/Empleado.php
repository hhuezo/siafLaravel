<?php

namespace App\Models\catalogo;

use App\Models\activo\Equipo;
use App\Models\activo\Vehiculo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    protected $table = 'empleado';

    protected $fillable = [
        'codigo', 'nombre', 'ambiente_id', 'gerencia_id',
        'departamento_id', 'activo', 'user_id', 'gc_record'
    ];

    public function ambiente(): BelongsTo
    {
        return $this->belongsTo(Ambiente::class);
    }

    public function gerencia(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'empleado_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'empleado_id');
    }
}
