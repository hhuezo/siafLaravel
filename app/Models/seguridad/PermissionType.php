<?php

namespace App\Models\seguridad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionType extends Model
{
    protected $table = 'permission_type';

    protected $fillable = [
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'permission_type_id');
    }
}
