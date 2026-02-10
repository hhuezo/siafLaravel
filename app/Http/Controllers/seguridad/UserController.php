<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Region;
use App\Models\seguridad\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::get();
        $roles = Role::get();
        return view('seguridad.usuario.index', compact('usuarios', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        // Validaciones
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:50|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:6',
                'role_id' => 'required|exists:roles,id',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser una cadena de texto.',
                'name.max' => 'El nombre no debe superar los 255 caracteres.',

                'username.required' => 'El nombre de usuario es obligatorio.',
                'username.string' => 'El nombre de usuario debe ser una cadena de texto.',
                'username.max' => 'El nombre de usuario no debe superar los 50 caracteres.',
                'username.unique' => 'Este nombre de usuario ya está en uso.',

                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico no es válido.',
                'email.max' => 'El correo no debe superar los 255 caracteres.',
                'email.unique' => 'Este correo electrónico ya está en uso.',

                'password.required' => 'La contraseña es obligatoria.',
                'password.string' => 'La contraseña debe ser una cadena de texto.',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres.',

                'role_id.required' => 'El rol es obligatorio.',
                'role_id.exists' => 'El rol seleccionado no existe.',
            ]
        );

        try {
            DB::beginTransaction();

            // Crear usuario
            $user = new User();
            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->active = 1;
            $user->save();

            // Asignar rol
            $role = Role::findOrFail($validated['role_id']);
            $user->assignRole($role->name);

            DB::commit();

            return redirect()->back()->with('success', 'Usuario creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear usuario: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Hubo un error al crear el usuario.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::get();
        return view('seguridad.usuario.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // === Validaciones según tu formulario ===
        $validated = $request->validate(
            [
                'username' => 'required|string|max:50|unique:users,username,' . $id,
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'cargo' => 'required|string|max:100',
            ],
            [
                'username.required' => 'El nombre de usuario es obligatorio.',
                'username.string' => 'El nombre de usuario debe ser texto.',
                'username.max' => 'El nombre de usuario no debe superar los 50 caracteres.',
                'username.unique' => 'Este nombre de usuario ya está en uso.',

                'name.required' => 'El nombre completo es obligatorio.',
                'name.string' => 'El nombre debe ser texto.',
                'name.max' => 'El nombre no debe superar los 255 caracteres.',

                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico no es válido.',
                'email.max' => 'El correo no debe superar los 255 caracteres.',
                'email.unique' => 'Este correo electrónico ya está en uso.',

                'cargo.required' => 'El cargo es obligatorio.',
                'cargo.string' => 'El cargo debe ser texto.',
                'cargo.max' => 'El cargo no debe superar los 100 caracteres.',
            ]
        );

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->username = $validated['username'];
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->cargo = $validated['cargo'];
            $user->save();

            DB::commit();
            return redirect()->back()->with('success', 'Usuario modificado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al modificar usuario: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Hubo un error al modificar el usuario.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $usuario = User::findOrFail($id);

            $usuario->active = $usuario->active == 1 ? 0 : 1;
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => $usuario->active == 1 ? 'Usuario activado' : 'Usuario desactivado',
                'nuevo_estado' => $usuario->active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        // Validaciones de la contraseña
        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ], [
            'id.required' => 'El usuario no es válido.',
            'id.exists' => 'El usuario no existe en la base de datos.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        try {
            // Buscar al usuario
            $user = User::findOrFail($id);

            // Actualizar la contraseña
            $user->password = Hash::make($request->password);
            $user->save();

            // Retornar con mensaje de éxito
            return back()->with('success', 'La contraseña se ha actualizado correctamente.');
        } catch (\Exception $e) {
            // Si ocurre un error, retornamos con mensaje de error
            return back()->with('error', 'Hubo un error al actualizar la contraseña. Intenta nuevamente.');
        }
    }

    public function sync_rol(string $user_id, string $rol_id)
    {
        $user = User::findOrFail($user_id);
        $role = Role::findOrFail($rol_id);

        if ($user->hasRole($role->name)) {
            $user->removeRole($role->name);

            return response()->json([
                'success' => true,
                'message' => "Rol '{$role->name}' removido del usuario correctamente.",
                'data' => [
                    'user_id' => $user->id,
                    'rol_id' => $role->id,
                    'accion' => 'removido',
                ],
            ]);
        } else {
            $user->assignRole($role->name);

            return response()->json([
                'success' => true,
                'message' => "Rol '{$role->name}' asignado al usuario correctamente.",
                'data' => [
                    'user_id' => $user->id,
                    'rol_id' => $role->id,
                    'accion' => 'asignado',
                ],
            ]);
        }
    }

    public function sync_region(string $user_id, string $region_id)
    {
        $user = User::findOrFail($user_id);
        $region = Region::findOrFail($region_id);

        // Verificar si el usuario ya está asociado a la región
        $exists = $user->regiones()->where('region_id', $region_id)->exists();

        if ($exists) {
            // Si ya existe, se elimina la relación
            $user->regiones()->detach($region_id);

            return response()->json([
                'success' => true,
                'message' => "Región '{$region->nombre}' removida del usuario correctamente.",
                'data' => [
                    'user_id' => $user->id,
                    'region_id' => $region->id,
                    'accion' => 'removido',
                ],
            ]);
        } else {
            // Si no existe, se agrega la relación
            $user->regiones()->attach($region_id);

            return response()->json([
                'success' => true,
                'message' => "Región '{$region->nombre}' asignada al usuario correctamente.",
                'data' => [
                    'user_id' => $user->id,
                    'region_id' => $region->id,
                    'accion' => 'asignado',
                ],
            ]);
        }
    }
}
