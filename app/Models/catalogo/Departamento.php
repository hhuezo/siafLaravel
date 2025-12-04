<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $table = 'departamento';

    protected $fillable = ['descripcion', 'region_id', 'user_id', 'oid'];

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'departamento_id');
    }

}
