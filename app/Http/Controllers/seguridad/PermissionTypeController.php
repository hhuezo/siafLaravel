<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\seguridad\PermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionTypeController extends Controller
{
    public function index()
    {
        $permissionTypes = PermissionType::orderBy('name')->get();
        return view('seguridad.permission_type.index', compact('permissionTypes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permission_type,name',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.unique'   => 'El tipo de permiso ya existe.',
        ]);

        try {
            PermissionType::create([
                'name'   => $request->name,
                'active' => $request->boolean('active', true),
            ]);
            return back()->with('success', 'El registro ha sido creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar tipo de permiso: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);
            return back()->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
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
            'name' => 'required|string|max:255|unique:permission_type,name,' . $id,
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.unique'   => 'El tipo de permiso ya existe.',
        ]);

        try {
            $permissionType = PermissionType::findOrFail($id);
            $permissionType->name = $request->name;
            $permissionType->active = $request->boolean('active');
            $permissionType->save();
            return back()->with('success', 'El registro ha sido modificado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar tipo de permiso: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);
            return back()->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function destroy(string $id)
    {
        try {
            $permissionType = PermissionType::findOrFail($id);
            $permissionType->delete();
            return back()->with('success', 'El registro ha sido eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar tipo de permiso: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Ocurrió un error al eliminar el registro. Por favor intente nuevamente.');
        }
    }
}
