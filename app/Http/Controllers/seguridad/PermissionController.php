<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\seguridad\Permission;
use App\Models\seguridad\PermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission as ModelPermission;

class PermissionController extends Controller
{
    public function index()
    {
        $permisos = Permission::with('permissionType')->orderBy('name')->get();
        $permissionTypes = PermissionType::where('active', true)->orderBy('name')->get();

        return view('seguridad.permission.index', compact('permisos', 'permissionTypes'));
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'permission_type_id' => 'nullable|exists:permission_type,id',
        ], [
            'name.required' => 'El campo permiso es obligatorio.',
            'name.unique' => 'El permiso ya existe en el sistema.',
            'permission_type_id.exists' => 'El tipo de permiso seleccionado no es válido.',
        ]);
        try {
            $permission = ModelPermission::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);
            Permission::where('id', $permission->id)->update([
                'permission_type_id' => $request->permission_type_id ?: null,
            ]);

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar permiso: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
            'permission_type_id' => 'nullable|exists:permission_type,id',
        ], [
            'name.required' => 'El campo permiso es obligatorio.',
            'name.unique' => 'El permiso ya existe en el sistema.',
            'permission_type_id.exists' => 'El tipo de permiso seleccionado no es válido.',
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->name = $request->get('name');
            $permission->permission_type_id = $request->permission_type_id ?: null;
            $permission->save();

            return back()->with('success', 'El registro ha sido modificado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar permiso: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function destroy(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return back()->with('success', 'El registro ha sido eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar permiso: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al eliminar el registro. Por favor intente nuevamente.');
        }
    }
}
