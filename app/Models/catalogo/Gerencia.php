<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gerencia extends Model
{
    protected $table = 'gerencia';

    protected $fillable = ['descripcion', 'codigo', 'user_id', 'oid'];

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'gerencia_id');
    }
}
