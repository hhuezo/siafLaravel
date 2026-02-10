<?php

namespace Database\Seeders;

use App\Models\seguridad\PermissionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTypeAndPermissionsSeeder extends Seeder
{
    protected string $guard = 'web';

    /**
     * Tipos de permiso (menús) y sus subopciones con permisos read, create, edit, delete.
     * SIAF Reportes no tiene permisos por el momento.
     */
    protected function getStructure(): array
    {
        return [
            'Seguridad' => [
                'usuario',
                'role',
                'permiso',
                'tipo permiso',
            ],
            'SIAF Movimientos' => [
                // Sin submenús por el momento
            ],
            'SIAF Activos' => [
                'equipo',
                'vehiculo',
                'reporte inventario equipo',
            ],
            'SIAF Catálogos' => [
                'color',
                'clase',
                'ambiente',
                'estado fisico',
                'fuente',
                'marca',
                'material',
                'procedencia',
                'subclase',
                'traccion',
                'unidad',
                'cuenta contable',
                'departamento',
                'gerencia',
                'grupo',
            ],
            'SIAF Reportes' => [
                // Sin permisos por el momento (aún no se ha trabajado)
            ],
        ];
    }

    public function run(): void
    {
        $structure = $this->getStructure();
        $now = now();

        foreach ($structure as $typeName => $submenus) {
            $type = PermissionType::firstOrCreate(
                ['name' => $typeName],
                ['active' => true]
            );

            foreach ($submenus as $recurso) {
                foreach (['read', 'create', 'edit', 'delete'] as $action) {
                    $name = "{$recurso} {$action}";
                    $exists = DB::table('permissions')
                        ->where('name', $name)
                        ->where('guard_name', $this->guard)
                        ->exists();

                    if (!$exists) {
                        DB::table('permissions')->insert([
                            'name' => $name,
                            'guard_name' => $this->guard,
                            'permission_type_id' => $type->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        }
    }
}
