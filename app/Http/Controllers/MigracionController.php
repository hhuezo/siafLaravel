<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MigracionController extends Controller
{

    public function index()
    {
        /*$users = DB::connection('sqlsrv2')->table('SecuritySystemUser')->where('GCRecord',null)->get();
        foreach ($users as $usuario) {
            DB::connection('mysql')->table('users')->updateOrInsert(
                [
                    'username'       => $usuario->UserName,
                    'oid'       => $usuario->Oid,
                    'name'       => $usuario->NombreCompleto,
                    'email'      => $usuario->Email,
                    'password'   => Hash::make("12345678"),
                    'active'     => $usuario->IsActive ? true : false,
                    'gc_record'       => $usuario->GCRecord,
                    'created_at' => $usuario->FechaDeIngreso,
                    'updated_at' => now(),
                ]
            );
        }


        $roles = DB::connection('sqlsrv2')->table('SecuritySystemRole')->where('GCRecord', null)->get();
        foreach ($roles as $rol) {
            Role::create(['name' => $rol->Name, 'oid' => $rol->Oid]);
        }

        $users_roles = DB::connection('sqlsrv2')->table('SecuritySystemUserUsers_SecuritySystemRoleRoles')->get();

        foreach($users_roles as $user_rol)
        {
            $user = User::where('oid',$user_rol->Users)->first();
            $role = Role::where('oid',$user_rol->Roles)->first();

            $user->assignRole($role->name);
        }*/


        /*$unidades = DB::connection('sqlsrv2')->table('Unidad')->get();

        foreach ($unidades as $unidad) {
           $user = User::where('oid',$unidad->UsuarioCreador)->first();
            DB::connection('mysql')->table('unidad')->insert([
                'oid' => $unidad->Oid,
                'user_id' => $user->id ?? null,
                'descripcion' => $unidad->Descripcion,
                'gcrecord' => $unidad->GCRecord,
                'created_at' => $unidad->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $ambientes = DB::connection('sqlsrv2')->table('Ambiente')->get();

        foreach ($ambientes as $ambiente) {
            // Buscar el usuario en MySQL por el campo oid
            $user = User::where('oid', $ambiente->UsuarioCreador)->first();

            // Buscar la unidad en MySQL por el campo oid
            $unidad = DB::connection('mysql')->table('unidad')
                ->where('oid', $ambiente->Unidad)
                ->first();

            DB::connection('mysql')->table('ambiente')->insert([
                'oid' => $ambiente->Oid,
                'gc_record' => $ambiente->GCRecord,
                'user_id' => $user->id ?? null,         // si no existe, null
                'descripcion' => $ambiente->Descripcion,
                'unidad_id' => $unidad->id ?? null,     // si no existe, null
                'unidad_oid' => $ambiente->CodAnterior2, // campo nullable
                'created_at' => $ambiente->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }


        $grupos = DB::connection('sqlsrv2')->table('Grupo')->get();

        foreach ($grupos as $grupo) {
            // Buscar el usuario en MySQL por oid
            $user = User::where('oid', $grupo->UsuarioCreador)->first();

            DB::connection('mysql')->table('grupo')->insert([
                'descripcion' => $grupo->Descripcion,
                'codigo' => $grupo->Codigo,
                'oid' => $grupo->Oid,
                'gc_record' => $grupo->GCRecord,
                'user_id' => $user->id ?? null,  // si no existe, null
                'created_at' => $grupo->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $clases = DB::connection('sqlsrv2')->table('Clase')->get();

        foreach ($clases as $clase) {
            // Buscar el usuario en MySQL por oid
            $user = User::where('oid', $clase->UsuarioCreador)->first();

            // Buscar el grupo en MySQL por oid
            $grupo = DB::connection('mysql')->table('grupo')
                ->where('oid', $clase->Grupo)
                ->first();

            DB::connection('mysql')->table('clase')->insert([
                'descripcion' => $clase->Descripcion,
                'codigo' => $clase->Codigo,
                'grupo_id' => $grupo->id ?? null,      // si no existe, null
                'vidautil' => $clase->VidaUtil,
                'oid' => $clase->Oid,
                'gc_record' => $clase->GCRecord,
                'user_id' => $user->id ?? null,        // si no existe, null
                'created_at' => $clase->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $subclases = DB::connection('sqlsrv2')->table('SubClase')->get();

        foreach ($subclases as $subclase) {
            // Buscar el usuario en MySQL por oid
            $user = User::where('oid', $subclase->UsuarioCreador)->first();

            // Buscar la clase en MySQL por oid
            $clase = DB::connection('mysql')->table('clase')
                ->where('oid', $subclase->Clase)
                ->first();

            DB::connection('mysql')->table('subclase')->insert([
                'descripcion' => $subclase->Descripcion,
                'codigo' => $subclase->Codigo,
                'clase_id' => $clase->id ?? null,       // si no existe, null
                'oid' => $subclase->Oid,
                'gc_record' => $subclase->GCRecord,
                'user_id' => $user->id ?? null,         // si no existe, null
                'created_at' => $subclase->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $cuentas = DB::connection('sqlsrv2')->table('CuentaContable')->get();

        foreach ($cuentas as $cuenta) {
            // Buscar el usuario en MySQL por oid
            $user = User::where('oid', $cuenta->UsuarioCreador)->first();

            DB::connection('mysql')->table('cuenta_contable')->insert([
                'descripcion' => $cuenta->Descripcion,
                'codigo' => $cuenta->Codigo,
                'oid' => $cuenta->Oid,
                'user_id' => $user->id ?? null,   // si no existe, null
                'created_at' => $cuenta->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $estados = DB::connection('sqlsrv2')->table('EstadoFisico')->get();

        foreach ($estados as $estado) {
            // Buscar el usuario en MySQL por oid
            $user = User::where('oid', $estado->UsuarioCreador)->first();

            DB::connection('mysql')->table('estado_fisico')->insert([
                'descripcion' => $estado->Descripcion,
                'oid' => $estado->Oid,
                'user_id' => $user->id ?? null,   // si no existe, null
                'created_at' => $estado->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $procedencias = DB::connection('sqlsrv2')
            ->table('Procedencia')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($procedencias as $procedencia) {
            // Buscar el usuario en MySQL por oid
            $user = User::where('oid', $procedencia->UsuarioCreador)->first();

            DB::connection('mysql')->table('procedencia')->insert([
                'descripcion' => $procedencia->Descripcion,
                'user_id' => $user->id ?? null,   // si no existe, null
                'oid' => $procedencia->Oid,
                'created_at' => $procedencia->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $colores = DB::connection('sqlsrv2')
            ->table('Color')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($colores as $color) {
            // Buscar el usuario en MySQL por oid
            $user = User::where('oid', $color->UsuarioCreador)->first();

            DB::connection('mysql')->table('color')->insert([
                'descripcion' => $color->Descripcion,
                'user_id' => $user->id ?? null,   // si no existe, null
                'oid' => $color->Oid,
                'created_at' => $color->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $materiales = DB::connection('sqlsrv2')
            ->table('Material')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($materiales as $material) {
            // Buscar el usuario creador por Oid
            $user = User::where('oid', $material->UsuarioCreador)->first();

            DB::connection('mysql')->table('material')->insert([
                'descripcion' => $material->Descripcion,
                'user_id' => $user->id ?? null,
                'oid' => $material->Oid,
                'created_at' => $material->FechaDeIngreso,
                'updated_at' => now(),
            ]);
        }

        $marcas = DB::connection('sqlsrv2')
            ->table('Marca')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($marcas as $marca) {
            $user = User::where('oid', $marca->UsuarioCreador)->first();

            DB::connection('mysql')->table('marca')->insert([
                'descripcion' => $marca->Descripcion,
                'user_id'     => $user->id ?? null,
                'oid'         => $marca->Oid,
                'created_at'  => $marca->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }

        $combustibles = DB::connection('sqlsrv2')
            ->table('Combustible')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($combustibles as $combustible) {
            $user = User::where('oid', $combustible->UsuarioCreador)->first();

            DB::connection('mysql')->table('combustible')->insert([
                'descripcion' => $combustible->Descripcion,
                'user_id'     => $user->id ?? null,
                'oid'         => $combustible->Oid,
                'created_at'  => $combustible->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }

        $tracciones = DB::connection('sqlsrv2')
            ->table('Traccion')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($tracciones as $traccion) {
            $user = User::where('oid', $traccion->UsuarioCreador)->first();

            DB::connection('mysql')->table('traccion')->insert([
                'descripcion' => $traccion->Descripcion,
                'user_id'     => $user->id ?? null,
                'oid'         => $traccion->Oid,
                'created_at'  => $traccion->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }



        $autores = DB::connection('sqlsrv2')
            ->table('AutorSoftware')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($autores as $autor) {
            $user = User::where('oid', $autor->UsuarioCreador)->first();

            DB::connection('mysql')->table('autor_software')->insert([
                'descripcion' => $autor->Descripcion,
                'user_id'     => $user->id ?? null,
                'oid'         => $autor->Oid,
                'created_at'  => $autor->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }


        $lineas = DB::connection('sqlsrv2')
            ->table('LineaSoftware')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($lineas as $linea) {
            $user = User::where('oid', $linea->UsuarioCreador)->first();

            DB::connection('mysql')->table('linea_software')->insert([
                'descripcion' => $linea->Descripcion,
                'user_id'     => $user->id ?? null,
                'oid'         => $linea->Oid,
                'created_at'  => $linea->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }

            $gerencias = DB::connection('sqlsrv2')
            ->table('Gerencia')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($gerencias as $gerencia) {
            $user = User::where('oid', $gerencia->UsuarioCreador)->first();

            DB::connection('mysql')->table('gerencia')->insert([
                'descripcion' => $gerencia->Descripcion,
                'codigo'      => $gerencia->Codigo,
                'user_id'     => $user->id ?? null,
                'oid'         => $gerencia->Oid,
                'created_at'  => $gerencia->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }


        $regiones = DB::connection('sqlsrv2')
            ->table('Region')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($regiones as $region) {
            $user = User::where('oid', $region->UsuarioCreador)->first();

            DB::connection('mysql')->table('region')->insert([
                'descripcion' => $region->Descripcion,
                'user_id'     => $user->id ?? null,
                'oid'         => $region->Oid,
                'created_at'  => $region->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }

        $departamentos = DB::connection('sqlsrv2')
            ->table('Depto')
            ->orderByRaw('CASE WHEN CodAnterior IS NULL THEN 1 ELSE 0 END')
            ->orderBy('CodAnterior')
            ->get();

        foreach ($departamentos as $departamento) {
            $user = User::where('oid', $departamento->UsuarioCreador)->first();
           // $region = DB::connection('mysql')->table('region')->where('oid', $departamento->Region)->first();

            DB::connection('mysql')->table('departamento')->insert([
                'descripcion' => $departamento->Descripcion,
                'codigo'   => $departamento->Codigo,
                'user_id'     => $user->id ?? null,
                'oid'         => $departamento->Oid,
                'created_at'  => $departamento->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }


        $empleados = DB::connection('sqlsrv2')
            ->table('Empleado')
            ->get();

        foreach ($empleados as $empleado) {
            $user = User::where('oid', $empleado->UsuarioCreador)->first();
            $ambiente = DB::connection('mysql')->table('ambiente')->where('oid', $empleado->Ambiente)->first();
            $gerencia = DB::connection('mysql')->table('gerencia')->where('oid', $empleado->Gerencia)->first();
            $departamento = DB::connection('mysql')->table('departamento')->where('oid', $empleado->Departamento)->first();

            DB::connection('mysql')->table('empleado')->insert([
                'codigo'        => $empleado->Codigo,
                'nombre'        => $empleado->Nombre,
                'ambiente_id'   => $ambiente->id ?? null,
                'gerencia_id'   => $gerencia->id ?? null,
                'departamento_id' => $departamento->id ?? null,
                'activo'        => $empleado->Activo ? true : false,
                'user_id'       => $user->id ?? null,
                'gc_record'     => $empleado->GCRecord,
                'oid'           => $empleado->Oid,
                'created_at'    => $empleado->FechaDeIngreso,
                'updated_at'    => now(),
            ]);
        }



        $equipos = DB::connection('sqlsrv2')
            ->table('Activo as a')
            ->join('Equipo as e', 'a.Oid', '=', 'e.Oid')
            ->select('a.*', 'e.Marca', 'e.Modelo', 'e.Serie', 'e.Color', 'e.Material')
            ->get();

        foreach ($equipos as $equipo) {
            DB::connection('mysql')->table('equipo')->insert([
                'oid' => $equipo->Oid,
                'cod_anterior' => $equipo->CodAnterior,
                'establecimiento_id' => DB::table('establecimiento')->where('oid', $equipo->Establecimiento)->value('id'),
                'grupo_id' => DB::table('grupo')->where('oid', $equipo->Grupo)->value('id'),
                'clase_id' => DB::table('clase')->where('oid', $equipo->Clase)->value('id'),
                'subclase_id' => DB::table('subclase')->where('oid', $equipo->SubClase)->value('id'),
                'correlativo' => $equipo->Correlativo,
                'codigo_activo' => $equipo->CodigoDeActivo,
                'cuenta_contable_id' => DB::table('cuenta_contable')->where('oid', $equipo->CuentaContable)->value('id'),
                'estado_fisico_id' => DB::table('estado_fisico')->where('oid', $equipo->EstadoFisico)->value('id'),
                'fecha_adquisicion' => $equipo->FechaDeAdquisicion ?? null,
                'procedencia_id' => DB::table('procedencia')->where('oid', $equipo->Procedencia)->value('id'),
                'fuente' => $equipo->Fuente,
                'numero_factura' => $equipo->NumeroDeFactura,
                'otra_caracteristica' => $equipo->OtraCaracteristica,
                'observacion' => $equipo->Observacion,
                'valor_inicial' => $equipo->ValorInicial,
                'valor_actual' => $equipo->ValorActual,
                'vida_util' => $equipo->VidaUtil,
                'depreciacion' => $equipo->Depreciacion,
                'depreciacion_acumulada' => $equipo->DepreciacionAcumulada,
                'depreciacion_diaria' => $equipo->DepreciacionDiaria,
                'unidad_id' => DB::table('unidad')->where('oid', $equipo->Unidad)->value('id'),
                'ambiente_id' => DB::table('ambiente')->where('oid', $equipo->Ambiente)->value('id'),
                'empleado_id' => DB::table('empleado')->where('oid', $equipo->Empleado)->value('id'),
                'estado_id' => DB::table('estado_activo')->where('codigo', $equipo->Estado)->value('id'),
                'estado_activo_guardado' => $equipo->EstadoActivoGuardado,
                'depreciado_totalmente' => $equipo->DepreciadoTotalmente,
                'gc_record' => $equipo->GCRecord,
                'valor_residual' => $equipo->ValorResidual,
                'valor_a_depreciar' => $equipo->ValorADepreciar,
                'dias_a_depreciar' => $equipo->DiasADepreciar,
                'depreciacion_anual' => $equipo->DepreciacionAnual,
                'depreciacion_mensual' => $equipo->DepreciacionMensual,
                'correlativo_int' => $equipo->CorrelativoInt,
                'detalle' => $equipo->Detalle,
                'no_depreciable' => $equipo->NoDepreciable ?? 0,
                'depresiable' => $equipo->Depresiable ?? 0,
                'valor_fijo' => $equipo->ValorFijo ?? 0,
                // Campos nuevos de la tabla Equipo
                'marca' => $equipo->Marca,
                'modelo' => $equipo->Modelo,
                'serie' => $equipo->Serie,
                'color_id' => DB::table('color')->where('oid', $equipo->Color)->value('id'),
                'material_id' => DB::table('material')->where('oid', $equipo->Material)->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
 */

        /*


          $fuentes = DB::connection('sqlsrv2')
            ->table('Fuente')
            ->get();

        foreach ($fuentes as $fuente) {
            $user = User::where('oid', $fuente->UsuarioCreador)->first();

            DB::connection('mysql')->table('fuente')->insert([
                'descripcion' => $fuente->Descripcion,
                'user_id'     => $user->id ?? null,
                'oid'         => $fuente->Oid,
                'created_at'  => $fuente->FechaDeIngreso,
                'updated_at'  => now(),
            ]);
        }





        $vehiculos = DB::connection('sqlsrv2')
            ->table('Activo')
            ->join('Vehiculo', 'Activo.Oid', '=', 'Vehiculo.Oid')
            ->select('Activo.*', 'Vehiculo.Marca', 'Vehiculo.Modelo', 'Vehiculo.Placa', 'Vehiculo.Color', 'Vehiculo.Motor', 'Vehiculo.NumeroChasis', 'Vehiculo.AnioFabricacion', 'Vehiculo.Combustible', 'Vehiculo.Traccion', 'Vehiculo.Equipo')
            ->get();

        foreach ($vehiculos as $vehiculo) {
            DB::connection('mysql')->table('vehiculo')->insert([
                'oid'                  => $vehiculo->Oid,
                'cod_anterior'         => $vehiculo->CodAnterior,
                'establecimiento_id'   => DB::table('establecimiento')->where('oid', $vehiculo->Establecimiento)->value('id') ?? null,
                'grupo_id'             => DB::table('grupo')->where('oid', $vehiculo->Grupo)->value('id') ?? null,
                'clase_id'             => DB::table('clase')->where('oid', $vehiculo->Clase)->value('id') ?? null,
                'subclase_id'          => DB::table('subclase')->where('oid', $vehiculo->SubClase)->value('id') ?? null,
                'correlativo'          => $vehiculo->Correlativo,
                'codigo_de_activo'     => $vehiculo->CodigoDeActivo,
                'cuenta_contable_id'   => DB::table('cuenta_contable')->where('oid', $vehiculo->CuentaContable)->value('id') ?? null,
                'estado_fisico_id'     => DB::table('estado_fisico')->where('oid', $vehiculo->EstadoFisico)->value('id') ?? null,
                'fecha_de_adquisicion' => $vehiculo->FechaDeAdquisicion,
                'procedencia_id'       => DB::table('procedencia')->where('oid', $vehiculo->Procedencia)->value('id') ?? null,
                'fuente_id'               => DB::table('fuente')->where('oid', $vehiculo->Fuente)->value('id') ?? null,
                'numero_factura'    => $vehiculo->NumeroDeFactura,
                'otra_caracteristica'  => substr($vehiculo->OtraCaracteristica, 0, 191), // evitar truncamiento
                'observacion'          => substr($vehiculo->Observacion, 0, 191),
                'valor_inicial'        => $vehiculo->ValorInicial,
                'valor_actual'         => $vehiculo->ValorActual,
                'vida_util'            => $vehiculo->VidaUtil,
                'depreciacion'         => $vehiculo->Depreciacion,
                'depreciacion_acumulada' => $vehiculo->DepreciacionAcumulada,
                'depreciacion_diaria'  => $vehiculo->DepreciacionDiaria,
                'unidad_id'            => DB::table('unidad')->where('oid', $vehiculo->Unidad)->value('id') ?? null,
                'ambiente_id'          => DB::table('ambiente')->where('oid', $vehiculo->Ambiente)->value('id') ?? null,
                'empleado_id'          => DB::table('empleado')->where('oid', $vehiculo->Empleado)->value('id') ?? null,
                'estado_id'            => DB::table('estado_activo')->where('codigo', $vehiculo->Estado)->value('id') ?? null,
                'estado_activo_guardado' => $vehiculo->EstadoActivoGuardado,
                'depreciado_totalmente' => $vehiculo->DepreciadoTotalmente,
                'gc_record'            => $vehiculo->GCRecord,
                'marca_id'             => DB::table('marca')->where('oid', $vehiculo->Marca)->value('id') ?? null,
                'modelo'               => $vehiculo->Modelo,
                'placa'                => $vehiculo->Placa,
                'color_id'             => DB::table('color')->where('oid', $vehiculo->Color)->value('id') ?? null,
                'motor'                => $vehiculo->Motor,
                'numero_chasis'        => $vehiculo->NumeroChasis,
                'anio_fabricacion'     => $vehiculo->AnioFabricacion,
                'combustible_id'       => DB::table('combustible')->where('oid', $vehiculo->Combustible)->value('id') ?? null,
                'traccion_id'          => DB::table('traccion')->where('oid', $vehiculo->Traccion)->value('id') ?? null,
                'equipo'               => $vehiculo->Equipo,
                'valor_residual'       => $vehiculo->ValorResidual,
                'valor_a_depreciar'    => $vehiculo->ValorADepreciar,
                'dias_a_depreciar'     => $vehiculo->DiasADepreciar,
                'depreciacion_anual'   => $vehiculo->DepreciacionAnual,
                'depreciacion_mensual' => $vehiculo->DepreciacionMensual,
                'correlativo_int'      => $vehiculo->CorrelativoInt,
                'detalle'              => $vehiculo->Detalle,
                'no_depreciable'       => $vehiculo->NoDepreciable ?? 0,
                'depresiable'          => $vehiculo->Depresiable ?? 0,
                'valor_fijo'           => $vehiculo->ValorFijo ?? 0,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }



        $equipos = DB::connection('sqlsrv2')
            ->table('Activo as a')
            ->join('Equipo as e', 'a.Oid', '=', 'e.Oid')
            ->select('a.Oid', 'a.Fuente')
            ->get();

        foreach ($equipos as $equipo) {
            // Buscar el ID correspondiente en la tabla fuente (por Oid o por nombre, según tu estructura)
            $fuenteId = DB::table('fuente')
                ->where('oid', $equipo->Fuente)
                ->value('id');

            // Si existe la fuente, actualiza el registro en la tabla equipo
            if ($fuenteId) {
                DB::table('equipo')
                    ->where('oid', $equipo->Oid)
                    ->update([
                        'fuente_id' => $fuenteId,
                        'updated_at' => now(),
                    ]);
            }
        }
*/



        dd("");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
