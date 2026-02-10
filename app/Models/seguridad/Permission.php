<?php

namespace App\Models\seguridad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'permission_type_id',
    ];

    protected $guarded = [];

    public function permissionType(): BelongsTo
    {
        return $this->belongsTo(PermissionType::class, 'permission_type_id');
    }
}
