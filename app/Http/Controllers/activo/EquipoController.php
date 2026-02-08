<?php

namespace App\Http\Controllers\activo;

use App\Http\Controllers\Controller;
use App\Models\activo\Equipo;
use App\Models\catalogo\Ambiente;
use App\Models\catalogo\Clase;
use App\Models\catalogo\Color;
use App\Models\catalogo\CuentaContable;
use App\Models\catalogo\Empleado;
use App\Models\catalogo\EstadoActivo;
use App\Models\catalogo\EstadoFisico;
use App\Models\catalogo\Fuente;
use App\Models\catalogo\Material;
use App\Models\catalogo\Procedencia;
use App\Models\catalogo\SubClase;
use App\Models\catalogo\Unidad;
use App\Models\general\Establecimiento;
use App\Models\general\Grupo;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EquipoController extends Controller
{
    public function index()
    {
        $clases = Clase::where('id','<>',6)->get();
        $fuentes = Fuente::get();
        $ambientes = Ambiente::get();
        $cuentas = CuentaContable::get();
        return view('activo.equipo.index', compact('clases', 'fuentes', 'ambientes', 'cuentas'));
    }

    /**
     * Muestra la vista con el formulario de filtros para el reporte de inventario de equipo.
     */
    public function vistaReporteInventario()
    {
        $clases = Clase::where('id', '<>', 6)->get();
        $fuentes = Fuente::get();
        $ambientes = Ambiente::get();
        $cuentas = CuentaContable::get();
        return view('reportes.form_inventario_equipo', compact('clases', 'fuentes', 'ambientes', 'cuentas'));
    }

    public function data()
    {
        $equipos = DB::table('equipo as e')
            ->leftJoin('color as col', 'e.color_id', '=', 'col.id')
            ->leftJoin('subclase as sub', 'e.subclase_id', '=', 'sub.id')
            ->leftJoin('clase as c', 'sub.clase_id', '=', 'c.id')
            ->leftJoin('empleado as emp', 'e.empleado_id', '=', 'emp.id')
            ->leftJoin('material as mat', 'e.material_id', '=', 'mat.id')
            ->leftJoin('unidad as u', 'e.unidad_id', '=', 'u.id')
            ->select(
                'e.id as equipo_id',
                'e.codigo_activo',
                'e.marca',
                'e.modelo',
                'e.serie',
                'c.descripcion as clase',
                'sub.descripcion as subclase',
                'emp.nombre as empleado_nombre',
                'col.descripcion as color',
                'mat.descripcion as material',
                'emp.nombre as empleado'
            );

        return DataTables::of($equipos)
            ->addColumn('acciones', function ($item) {
                $showUrl = route('equipo.show', $item->equipo_id);
                $editUrl = route('equipo.edit', $item->equipo_id);

                return '<a href="' . $showUrl . '" class="btn btn-sm btn-info btn-wave me-1" title="Ver detalles">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary btn-wave" title="Editar equipo">
                        <i class="bi bi-pencil-fill"></i>
                    </a>';
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }

    public function loadSubclases(string $id)
    {
        $subclases = SubClase::where('clase_id', $id)->get();
        return response()->json($subclases);
    }


    public function loadAmbientes(string $id)
    {
        $ambientes = Ambiente::where('unidad_id', $id)->get();
        return response()->json($ambientes);
    }

    public function loadEmpleados(string $id)
    {
        $empleados = Empleado::where('ambiente_id', $id)->get();
        return response()->json($empleados);
    }

    public function generarCodigoActivo(string $subclaseId)
    {
        try {
            // Obtener la subclase
            $subclase = SubClase::with(['clase.grupo'])->findOrFail($subclaseId);

            // Obtener la clase relacionada
            $clase = $subclase->clase;
            if (!$clase) {
                return response()->json(['error' => 'Clase no encontrada'], 404);
            }

            // Obtener el grupo relacionado
            $grupo = $clase->grupo;
            if (!$grupo) {
                return response()->json(['error' => 'Grupo no encontrado'], 404);
            }

            // Obtener el establecimiento (obtener el primero disponible o el relacionado)
            // Si hay un establecimiento relacionado con algún equipo de esta subclase, usarlo
            $establecimiento = Establecimiento::first();
            if (!$establecimiento) {
                return response()->json(['error' => 'No se encontró ningún establecimiento'], 404);
            }

            // Obtener el máximo correlativo_int de equipos con esta subclase
            $maxCorrelativo = Equipo::where('subclase_id', $subclaseId)
                ->max('correlativo_int');

            // Si no hay equipos con esta subclase, empezar desde 1
            $nuevoCorrelativo = ($maxCorrelativo ?? 0) + 1;

            // Generar el código: establecimiento.codigo-grupo.codigo-clase.codigo-subclase.codigo-correlativo_int
            $codigoActivo = sprintf(
                '%s-%s-%s-%s-%d',
                $establecimiento->codigo ?? '',
                $grupo->codigo ?? '',
                $clase->codigo ?? '',
                $subclase->codigo ?? '',
                $nuevoCorrelativo
            );


            return response()->json([
                'codigo_activo' => $codigoActivo,
                'correlativo_int' => $nuevoCorrelativo,
                'vida_util' => $clase->vidautil,
                'establecimiento_id' => $establecimiento->id,
                'grupo_id' => $grupo->id,
                'clase_id' => $clase->id,
                'subclase_id' => $subclase->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar el código: ' . $e->getMessage()], 500);
        }
    }


    public function reporteInventario(Request $request)
    {
        // estado 1 y 2 Disponible / Asignado
        $query = Equipo::query()->whereIn('estado_id', [1, 2]);

        if ($request->filled('clase_id')) {
            $query->where('clase_id', $request->clase_id);
        }

        if ($request->filled('subclase_id')) {
            $query->where('subclase_id', $request->subclase_id);
        }

        if ($request->filled('ambiente_id')) {
            $query->where('ambiente_id', $request->ambiente_id);
        }

        if ($request->filled('fuente_id')) {
            $query->where('fuente_id', $request->fuente_id);
        }

        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }

        if ($request->filled('cuenta_id')) {
            $query->where('cuenta_contable_id', $request->cuenta_id);
        }

        $fechaFinal = $request->filled('fechaFinal')
            ? Carbon::parse($request->fechaFinal)
            : Carbon::now();

        // Filtrar los equipos cuya fecha de adquisición sea menor o igual a la fecha final
        $query->whereDate('fecha_adquisicion', '<=', $fechaFinal);

        $equipos = $query->with([
            'subclase', 'cuentaContable', 'ambiente.unidad', 'color', 'estadoFisico', 'fuente'
        ])->orderBy('codigo_activo')->get();

        foreach ($equipos as $equipo) {
            $valorInicial = $equipo->valor_inicial;

            // Si el valor inicial es menor a 600, no se deprecia
            if ($valorInicial < 600) {
                $equipo->valorActual = round($valorInicial, 2);
                $equipo->depresiacionAcumulada = 0;
                continue; // pasa al siguiente equipo
            }

            // 90% del valor se deprecia
            $valorADepreciar = $valorInicial * 0.9;
            $valorAnio = $valorADepreciar / $equipo->vida_util; // Depreciación anual
            $dias = $fechaFinal->isLeapYear() ? 366 : 365; // Ver si es año bisiesto
            $valorDiario = $valorAnio / $dias;

            $fechaAdquisicion = Carbon::parse($equipo->fecha_adquisicion);
            $diasTotales = $fechaAdquisicion->diffInDays($fechaFinal);

            // Depreciación acumulada
            $depreciacionAcumulada = $valorDiario * $diasTotales;

            // Valor actual (no menor al 10% residual)
            $valorActual = max($valorInicial - $depreciacionAcumulada, $valorInicial * 0.1);

            // Asignar valores redondeados
            $equipo->valorActual = round($valorActual, 2);
            $equipo->depresiacionAcumulada = round($depreciacionAcumulada, 2);
        }

        $nombreArchivo = 'Inventario_Equipo_' . now()->format('Ymd_His') . '.pdf';
        $pdf = Pdf::loadView('reportes.inventario_equipo', compact('equipos'))
            ->setPaper('a4', 'landscape');

        if ($request->boolean('descargar')) {
            return $pdf->download($nombreArchivo);
        }
        return $pdf->stream($nombreArchivo);
    }


    public function create()
    {
        $colores = Color::get();
        $materiales = Material::get();
        $clases = Clase::where('id', '<>', 6)->get();
        $fuentes = Fuente::get();
        $ambientes = Ambiente::get();
        $cuentas = CuentaContable::get();
        $unidades = Unidad::get();
        $estadosFisicos = EstadoFisico::get();
        $procedencias = Procedencia::get();
        $estadosActivos = EstadoActivo::get();

        return view(
            'activo.equipo.create',
            compact('colores', 'materiales', 'clases', 'fuentes', 'ambientes', 'cuentas', 'unidades', 'estadosFisicos', 'procedencias', 'estadosActivos')
        );
    }


    public function store(Request $request)
    {
        $rules = [
            'codigo_activo'       => ['required', 'string', 'max:255', 'unique:equipo,codigo_activo'],
            'clase_id'            => ['required', 'exists:clase,id'],
            'subclase_id'         => ['required', 'exists:subclase,id'],
            'fuente_id'           => ['required', 'exists:fuente,id'],
            'cuenta_contable_id'  => ['required', 'exists:cuenta_contable,id'],
            'ambiente_id'         => ['required', 'exists:ambiente,id'],
            'empleado_id'         => ['required', 'exists:empleado,id'],
            'unidad_id'           => ['required', 'exists:unidad,id'],
            'fecha_adquisicion'   => ['required', 'date'],
            'valor_inicial'       => ['required', 'numeric', 'min:0'],
            'vida_util'           => ['required', 'integer', 'min:1'],
            'marca'               => ['required', 'string', 'max:255'],
            'modelo'              => ['required', 'string', 'max:255'],
            'serie'               => ['required', 'string', 'max:255'],
            'color_id'            => ['required', 'exists:color,id'],
            'material_id'         => ['required', 'exists:material,id'],
            'estado_fisico_id'    => ['nullable', 'exists:estado_fisico,id'],
            'procedencia_id'      => ['nullable', 'exists:procedencia,id'],
            'estado_id'           => ['nullable', 'exists:estado_activo,id'],
            'numero_de_factura'   => ['nullable', 'string', 'max:255'],
            'otra_caracteristica' => ['nullable', 'string'],
            'observacion'         => ['nullable', 'string'],
            'depresiable'         => ['nullable', 'boolean'],
            'no_depreciable'      => ['nullable', 'boolean'],
            'detalle'             => ['nullable', 'string'],
        ];

        $messages = [
            'required'            => 'El campo :attribute es obligatorio.',
            'string'              => 'El campo :attribute debe ser un texto.',
            'max'                 => 'El campo :attribute no debe exceder de :max caracteres.',
            'unique'              => 'El valor del campo :attribute ya está en uso.',
            'exists'              => 'El valor seleccionado en :attribute no es válido.',
            'date'                => 'El campo :attribute debe ser una fecha válida.',
            'numeric'             => 'El campo :attribute debe ser numérico.',
            'integer'             => 'El campo :attribute debe ser un número entero.',
            'min.numeric'         => 'El campo :attribute debe ser al menos :min.',
            'min.integer'         => 'El campo :attribute debe ser al menos :min.',
        ];

        $attributes = [
            'codigo_activo'      => 'código de activo',
            'clase_id'           => 'clase',
            'subclase_id'        => 'subclase',
            'fuente_id'          => 'fuente',
            'cuenta_contable_id' => 'cuenta contable',
            'ambiente_id'        => 'ambiente',
            'empleado_id'        => 'empleado',
            'unidad_id'          => 'unidad',
            'fecha_adquisicion'  => 'fecha de adquisición',
            'valor_inicial'      => 'valor inicial',
            'vida_util'          => 'vida útil',
            'marca'              => 'marca',
            'modelo'             => 'modelo',
            'serie'              => 'serie',
            'color_id'           => 'color',
            'material_id'        => 'material',
            'estado_fisico_id'   => 'estado físico',
            'procedencia_id'     => 'procedencia',
            'estado_id'          => 'estado',
            'numero_de_factura'  => 'número de factura',
            'otra_caracteristica' => 'otra característica',
            'observacion'         => 'observación',
            'depresiable'        => 'depreciable',
            'no_depreciable'     => 'no depreciable',
            'detalle'            => 'detalle / descripción',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        try {
            // Obtener la subclase con sus relaciones para obtener grupo y establecimiento
            $subclase = SubClase::with(['clase.grupo'])->findOrFail($validated['subclase_id']);
            $clase = $subclase->clase;
            $grupo = $clase->grupo;

            // Obtener el establecimiento (obtener el primero disponible)
            $establecimiento = Establecimiento::first();
            if (!$establecimiento) {
                return back()
                    ->withInput()
                    ->with('error', 'No se encontró ningún establecimiento. Por favor, configure uno primero.');
            }

            // Obtener el máximo correlativo_int de equipos con esta subclase
            $maxCorrelativo = Equipo::where('subclase_id', $validated['subclase_id'])
                ->max('correlativo_int');
            $nuevoCorrelativo = ($maxCorrelativo ?? 0) + 1;

            $equipo = new Equipo();

            $equipo->establecimiento_id = $establecimiento->id;
            $equipo->grupo_id = $grupo->id;
            $equipo->clase_id = $validated['clase_id'];
            $equipo->subclase_id = $validated['subclase_id'];
            $equipo->correlativo_int = $nuevoCorrelativo;
            $equipo->fuente_id = $validated['fuente_id'];
            $equipo->cuenta_contable_id = $validated['cuenta_contable_id'];
            $equipo->ambiente_id = $validated['ambiente_id'];
            $equipo->empleado_id = $validated['empleado_id'];
            $equipo->unidad_id = $validated['unidad_id'];
            $equipo->codigo_activo = $validated['codigo_activo'];
            $equipo->fecha_adquisicion = $validated['fecha_adquisicion'];
            $equipo->valor_inicial = $validated['valor_inicial'];
            $equipo->vida_util = $validated['vida_util'];
            $equipo->marca = $validated['marca'];
            $equipo->modelo = $validated['modelo'];
            $equipo->serie = $validated['serie'];
            $equipo->color_id = $validated['color_id'];
            $equipo->material_id = $validated['material_id'];
            $equipo->estado_fisico_id = $validated['estado_fisico_id'] ?? null;
            $equipo->procedencia_id = $validated['procedencia_id'] ?? null;
            $equipo->estado_id = $validated['estado_id'] ?? null;
            $equipo->numero_de_factura = $validated['numero_de_factura'] ?? null;
            $equipo->otra_caracteristica = $validated['otra_caracteristica'] ?? null;
            $equipo->observacion = $validated['observacion'] ?? null;
            $equipo->depresiable = isset($validated['depresiable']) ? (bool)$validated['depresiable'] : false;
            $equipo->no_depreciable = isset($validated['no_depreciable']) ? (bool)$validated['no_depreciable'] : false;
            $equipo->detalle = $validated['detalle'] ?? null;

            // Inicializar algunos campos relacionados a depreciación
            if (!is_null($equipo->valor_inicial)) {
                $equipo->valor_actual = $equipo->valor_inicial;
            }

            $equipo->save();

            return redirect()->route('equipo.index')->with('success', 'Equipo creado correctamente.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar el equipo. Por favor, intente de nuevo.');
        }
    }


    public function show(string $id)
    {
        $equipo = Equipo::with([
            'establecimiento',
            'grupo',
            'clase',
            'subclase',
            'fuente',
            'cuentaContable',
            'unidad',
            'ambiente',
            'empleado',
            'color',
            'material',
            'estadoFisico',
            'procedencia',
            'estado'
        ])->findOrFail($id);

        return view('activo.equipo.show', compact('equipo'));
    }

    public function edit(string $id)
    {
        $equipo = Equipo::findOrFail($id);
        $colores = Color::get();
        $materiales = Material::get();
        $clases = Clase::where('id', '<>', 6)->get();
        $fuentes = Fuente::get();
        $ambientes = Ambiente::get();
        $cuentas = CuentaContable::get();
        $unidades = Unidad::get();
        $estadosFisicos = EstadoFisico::get();
        $procedencias = Procedencia::get();
        $estadosActivos = EstadoActivo::get();

        return view(
            'activo.equipo.edit',
            compact('equipo', 'colores', 'materiales', 'clases', 'fuentes', 'ambientes', 'cuentas', 'unidades', 'estadosFisicos', 'procedencias', 'estadosActivos')
        );
    }

    public function update(Request $request, string $id)
    {
        $equipo = Equipo::findOrFail($id);

        $rules = [
            'codigo_activo'       => ['required', 'string', 'max:255', 'unique:equipo,codigo_activo,' . $id],
            'clase_id'            => ['required', 'exists:clase,id'],
            'subclase_id'         => ['required', 'exists:subclase,id'],
            'fuente_id'           => ['required', 'exists:fuente,id'],
            'cuenta_contable_id'  => ['required', 'exists:cuenta_contable,id'],
            'ambiente_id'         => ['required', 'exists:ambiente,id'],
            'empleado_id'         => ['required', 'exists:empleado,id'],
            'unidad_id'           => ['required', 'exists:unidad,id'],
            'fecha_adquisicion'   => ['required', 'date'],
            'valor_inicial'       => ['required', 'numeric', 'min:0'],
            'vida_util'           => ['required', 'integer', 'min:1'],
            'marca'               => ['required', 'string', 'max:255'],
            'modelo'              => ['required', 'string', 'max:255'],
            'serie'               => ['required', 'string', 'max:255'],
            'color_id'            => ['required', 'exists:color,id'],
            'material_id'         => ['required', 'exists:material,id'],
            'estado_fisico_id'    => ['nullable', 'exists:estado_fisico,id'],
            'procedencia_id'      => ['nullable', 'exists:procedencia,id'],
            'estado_id'           => ['nullable', 'exists:estado_activo,id'],
            'numero_de_factura'   => ['nullable', 'string', 'max:255'],
            'otra_caracteristica' => ['nullable', 'string'],
            'observacion'         => ['nullable', 'string'],
            'depresiable'         => ['nullable', 'boolean'],
            'no_depreciable'      => ['nullable', 'boolean'],
            'detalle'             => ['nullable', 'string'],
        ];

        $messages = [
            'required'            => 'El campo :attribute es obligatorio.',
            'string'              => 'El campo :attribute debe ser un texto.',
            'max'                 => 'El campo :attribute no debe exceder de :max caracteres.',
            'unique'              => 'El valor del campo :attribute ya está en uso.',
            'exists'              => 'El valor seleccionado en :attribute no es válido.',
            'date'                => 'El campo :attribute debe ser una fecha válida.',
            'numeric'             => 'El campo :attribute debe ser numérico.',
            'integer'             => 'El campo :attribute debe ser un número entero.',
            'min.numeric'         => 'El campo :attribute debe ser al menos :min.',
            'min.integer'         => 'El campo :attribute debe ser al menos :min.',
        ];

        $attributes = [
            'codigo_activo'      => 'código de activo',
            'clase_id'           => 'clase',
            'subclase_id'        => 'subclase',
            'fuente_id'          => 'fuente',
            'cuenta_contable_id' => 'cuenta contable',
            'ambiente_id'        => 'ambiente',
            'empleado_id'        => 'empleado',
            'unidad_id'          => 'unidad',
            'fecha_adquisicion'  => 'fecha de adquisición',
            'valor_inicial'      => 'valor inicial',
            'vida_util'          => 'vida útil',
            'marca'              => 'marca',
            'modelo'             => 'modelo',
            'serie'              => 'serie',
            'color_id'           => 'color',
            'material_id'        => 'material',
            'estado_fisico_id'   => 'estado físico',
            'procedencia_id'     => 'procedencia',
            'estado_id'          => 'estado',
            'numero_de_factura'  => 'número de factura',
            'otra_caracteristica' => 'otra característica',
            'observacion'         => 'observación',
            'depresiable'        => 'depreciable',
            'no_depreciable'     => 'no depreciable',
            'detalle'            => 'detalle / descripción',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        try {
            $equipo->clase_id = $validated['clase_id'];
            $equipo->subclase_id = $validated['subclase_id'];
            $equipo->fuente_id = $validated['fuente_id'];
            $equipo->cuenta_contable_id = $validated['cuenta_contable_id'];
            $equipo->ambiente_id = $validated['ambiente_id'];
            $equipo->empleado_id = $validated['empleado_id'];
            $equipo->unidad_id = $validated['unidad_id'];
            $equipo->codigo_activo = $validated['codigo_activo'];
            $equipo->fecha_adquisicion = $validated['fecha_adquisicion'];
            $equipo->valor_inicial = $validated['valor_inicial'];
            $equipo->vida_util = $validated['vida_util'];
            $equipo->marca = $validated['marca'];
            $equipo->modelo = $validated['modelo'];
            $equipo->serie = $validated['serie'];
            $equipo->color_id = $validated['color_id'];
            $equipo->material_id = $validated['material_id'];
            $equipo->estado_fisico_id = $validated['estado_fisico_id'] ?? null;
            $equipo->procedencia_id = $validated['procedencia_id'] ?? null;
            $equipo->estado_id = $validated['estado_id'] ?? null;
            $equipo->numero_de_factura = $validated['numero_de_factura'] ?? null;
            $equipo->otra_caracteristica = $validated['otra_caracteristica'] ?? null;
            $equipo->observacion = $validated['observacion'] ?? null;
            $equipo->depresiable = isset($validated['depresiable']) ? (bool)$validated['depresiable'] : false;
            $equipo->no_depreciable = isset($validated['no_depreciable']) ? (bool)$validated['no_depreciable'] : false;
            $equipo->detalle = $validated['detalle'] ?? null;

            $equipo->save();

            return redirect()->route('equipo.index')->with('success', 'Equipo actualizado correctamente.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el equipo. Por favor, intente de nuevo.');
        }
    }


    public function destroy(string $id)
    {
        //
    }
}
