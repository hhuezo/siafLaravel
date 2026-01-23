<?php

namespace App\Http\Controllers\activo;

use App\Http\Controllers\Controller;
use App\Models\activo\Vehiculo;
use App\Models\catalogo\Ambiente;
use App\Models\catalogo\Clase;
use App\Models\catalogo\Color;
use App\Models\catalogo\Combustible;
use App\Models\catalogo\CuentaContable;
use App\Models\catalogo\Empleado;
use App\Models\catalogo\EstadoActivo;
use App\Models\catalogo\EstadoFisico;
use App\Models\catalogo\Fuente;
use App\Models\catalogo\Marca;
use App\Models\catalogo\Procedencia;
use App\Models\catalogo\SubClase;
use App\Models\catalogo\Traccion;
use App\Models\catalogo\Unidad;
use App\Models\general\Establecimiento;
use App\Models\general\Grupo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class VehiculoController extends Controller
{
    public function index()
    {
        $clases = Clase::where('id', 6)->get();
        $fuentes = Fuente::get();
        $ambientes = Ambiente::get();
        $cuentas = CuentaContable::get();
        return view('activo.vehiculo.index', compact('clases', 'fuentes', 'ambientes', 'cuentas'));
    }

    public function data()
    {
        $vehiculos = DB::table('vehiculo as v')
            ->leftJoin('color as col', 'v.color_id', '=', 'col.id')
            ->leftJoin('subclase as sub', 'v.subclase_id', '=', 'sub.id')
            ->leftJoin('clase as c', 'sub.clase_id', '=', 'c.id')
            ->leftJoin('empleado as emp', 'v.empleado_id', '=', 'emp.id')
            ->leftJoin('marca as m', 'v.marca_id', '=', 'm.id')
            ->select(
                'v.id as vehiculo_id',
                'v.codigo_de_activo',
                'v.modelo',
                'v.placa',
                'c.descripcion as clase',
                'sub.descripcion as subclase',
                'emp.nombre as empleado_nombre',
                'col.descripcion as color',
                'm.descripcion as marca',
                'v.equipo'
            );

        return DataTables::of($vehiculos)
            ->addColumn('acciones', function ($item) {
                $showUrl = route('vehiculo.show', $item->vehiculo_id);
                $editUrl = route('vehiculo.edit', $item->vehiculo_id);

                return '<a href="' . $showUrl . '" class="btn btn-sm btn-info btn-wave me-1" title="Ver detalles">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary btn-wave" title="Editar vehículo">
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

            // Obtener el establecimiento (obtener el primero disponible)
            $establecimiento = Establecimiento::first();
            if (!$establecimiento) {
                return response()->json(['error' => 'No se encontró ningún establecimiento'], 404);
            }

            // Obtener el máximo correlativo_int de vehículos con esta subclase
            $maxCorrelativo = Vehiculo::where('subclase_id', $subclaseId)
                ->max('correlativo_int');

            // Si no hay vehículos con esta subclase, empezar desde 1
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
                'codigo_de_activo' => $codigoActivo,
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
        // 🔹 Filtrar vehículos en estado Disponible o Asignado
        $query = Vehiculo::query()->whereIn('estado_id', [1, 2]);

        // 🔹 Filtros opcionales
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

        // 🔹 Filtrar por fecha de adquisición
        if ($request->filled('fechaFinal')) {
            $query->whereDate('fecha_de_adquisicion', '<=', $request->fechaFinal);
        }

        // 🔹 Obtener los resultados
        $vehiculos = $query->orderBy('codigo_de_activo')->get();

        // 🔹 Calcular depreciaciones
        $fechaFinal = Carbon::parse($request->fechaFinal);
        $anio = $fechaFinal->year;

        foreach ($vehiculos as $vehiculo) {
            $valorInicial = $vehiculo->valor_inicial;

            // No se deprecia si vale menos de $600
            if ($valorInicial < 600) {
                $vehiculo->valorActual = round($valorInicial, 2);
                $vehiculo->depresiacionAcumulada = 0;
                continue;
            }

            // Calcular valores
            $valorADepreciar = $valorInicial * 0.9; // 90%
            $valorAnio = $valorADepreciar / $vehiculo->vida_util;
            $dias = $fechaFinal->isLeapYear() ? 366 : 365;
            $valorDiario = $valorAnio / $dias;

            $fechaAdquisicion = Carbon::parse($vehiculo->fecha_de_adquisicion);
            $diasTotales = $fechaAdquisicion->diffInDays($fechaFinal);

            $depreciacionAcumulada = $valorDiario * $diasTotales;
            $valorActual = max($valorInicial - $depreciacionAcumulada, $valorInicial * 0.1);

            $vehiculo->valorActual = round($valorActual, 2);
            $vehiculo->depresiacionAcumulada = round($depreciacionAcumulada, 2);
        }

        // 🔹 Generar PDF
        $pdf = Pdf::loadView('reportes.inventario_vehiculo', compact('vehiculos'))
            ->setPaper('a4', 'landscape');

        // 🔹 Mostrar en el navegador sin descargar
        $nombreArchivo = 'Inventario_Vehiculo_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->stream($nombreArchivo);
    }

    public function create()
    {
        $colores = Color::get();
        $marcas = Marca::get();
        $clases = Clase::where('id', 6)->get();
        $fuentes = Fuente::get();
        $ambientes = Ambiente::get();
        $cuentas = CuentaContable::get();
        $unidades = Unidad::get();
        $estadosFisicos = EstadoFisico::get();
        $procedencias = Procedencia::get();
        $estadosActivos = EstadoActivo::get();
        $combustibles = Combustible::get();
        $tracciones = Traccion::get();

        return view(
            'activo.vehiculo.create',
            compact('colores', 'marcas', 'clases', 'fuentes', 'ambientes', 'cuentas', 'unidades', 'estadosFisicos', 'procedencias', 'estadosActivos', 'combustibles', 'tracciones')
        );
    }

    public function store(Request $request)
    {
        $rules = [
            'codigo_de_activo'       => ['required', 'string', 'max:255', 'unique:vehiculo,codigo_de_activo'],
            'clase_id'               => ['required', 'exists:clase,id'],
            'subclase_id'            => ['required', 'exists:subclase,id'],
            'fuente_id'              => ['required', 'exists:fuente,id'],
            'cuenta_contable_id'     => ['required', 'exists:cuenta_contable,id'],
            'ambiente_id'            => ['required', 'exists:ambiente,id'],
            'empleado_id'            => ['required', 'exists:empleado,id'],
            'unidad_id'              => ['required', 'exists:unidad,id'],
            'fecha_de_adquisicion'   => ['required', 'date'],
            'valor_inicial'          => ['required', 'numeric', 'min:0'],
            'vida_util'               => ['required', 'integer', 'min:1'],
            'marca_id'                => ['required', 'exists:marca,id'],
            'modelo'                  => ['required', 'string', 'max:255'],
            'placa'                   => ['required', 'string', 'max:255'],
            'color_id'                => ['required', 'exists:color,id'],
            'estado_fisico_id'        => ['nullable', 'exists:estado_fisico,id'],
            'procedencia_id'          => ['nullable', 'exists:procedencia,id'],
            'estado_id'               => ['nullable', 'exists:estado_activo,id'],
            'numero_de_factura'      => ['nullable', 'string', 'max:255'],
            'otra_caracteristica'     => ['nullable', 'string'],
            'observacion'             => ['nullable', 'string'],
            'depresiable'             => ['nullable', 'boolean'],
            'no_depreciable'          => ['nullable', 'boolean'],
            'motor'                   => ['nullable', 'string', 'max:255'],
            'numero_chasis'           => ['nullable', 'string', 'max:255'],
            'anio_fabricacion'        => ['nullable', 'integer'],
            'combustible_id'          => ['nullable', 'exists:combustible,id'],
            'traccion_id'             => ['nullable', 'exists:traccion,id'],
            'equipo'                  => ['nullable', 'string', 'max:255'],
            'detalle'                 => ['nullable', 'string'],
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
            'codigo_de_activo'      => 'código de activo',
            'clase_id'              => 'clase',
            'subclase_id'           => 'subclase',
            'fuente_id'             => 'fuente',
            'cuenta_contable_id'    => 'cuenta contable',
            'ambiente_id'           => 'ambiente',
            'empleado_id'           => 'empleado',
            'unidad_id'             => 'unidad',
            'fecha_de_adquisicion'  => 'fecha de adquisición',
            'valor_inicial'         => 'valor inicial',
            'vida_util'             => 'vida útil',
            'marca_id'              => 'marca',
            'modelo'                => 'modelo',
            'placa'                 => 'placa',
            'color_id'              => 'color',
            'estado_fisico_id'      => 'estado físico',
            'procedencia_id'        => 'procedencia',
            'estado_id'             => 'estado',
            'numero_de_factura'     => 'número de factura',
            'otra_caracteristica'   => 'otra característica',
            'observacion'           => 'observación',
            'depresiable'           => 'depreciable',
            'no_depreciable'        => 'no depreciable',
            'motor'                 => 'motor',
            'numero_chasis'         => 'número de chasis',
            'anio_fabricacion'      => 'año de fabricación',
            'combustible_id'        => 'combustible',
            'traccion_id'           => 'tracción',
            'equipo'                => 'equipo',
            'detalle'               => 'detalle / descripción',
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

            // Obtener el máximo correlativo_int de vehículos con esta subclase
            $maxCorrelativo = Vehiculo::where('subclase_id', $validated['subclase_id'])
                ->max('correlativo_int');
            $nuevoCorrelativo = ($maxCorrelativo ?? 0) + 1;

            $vehiculo = new Vehiculo();

            $vehiculo->establecimiento_id = $establecimiento->id;
            $vehiculo->grupo_id = $grupo->id;
            $vehiculo->clase_id = $validated['clase_id'];
            $vehiculo->subclase_id = $validated['subclase_id'];
            $vehiculo->correlativo_int = $nuevoCorrelativo;
            $vehiculo->fuente_id = $validated['fuente_id'];
            $vehiculo->cuenta_contable_id = $validated['cuenta_contable_id'];
            $vehiculo->ambiente_id = $validated['ambiente_id'];
            $vehiculo->empleado_id = $validated['empleado_id'];
            $vehiculo->unidad_id = $validated['unidad_id'];
            $vehiculo->codigo_de_activo = $validated['codigo_de_activo'];
            $vehiculo->fecha_de_adquisicion = $validated['fecha_de_adquisicion'];
            $vehiculo->valor_inicial = $validated['valor_inicial'];
            $vehiculo->vida_util = $validated['vida_util'];
            $vehiculo->marca_id = $validated['marca_id'];
            $vehiculo->modelo = $validated['modelo'];
            $vehiculo->placa = $validated['placa'];
            $vehiculo->color_id = $validated['color_id'];
            $vehiculo->estado_fisico_id = $validated['estado_fisico_id'] ?? null;
            $vehiculo->procedencia_id = $validated['procedencia_id'] ?? null;
            $vehiculo->estado_id = $validated['estado_id'] ?? null;
            $vehiculo->numero_de_factura = $validated['numero_de_factura'] ?? null;
            $vehiculo->otra_caracteristica = $validated['otra_caracteristica'] ?? null;
            $vehiculo->observacion = $validated['observacion'] ?? null;
            $vehiculo->depresiable = isset($validated['depresiable']) ? (bool)$validated['depresiable'] : false;
            $vehiculo->no_depreciable = isset($validated['no_depreciable']) ? (bool)$validated['no_depreciable'] : false;
            $vehiculo->motor = $validated['motor'] ?? null;
            $vehiculo->numero_chasis = $validated['numero_chasis'] ?? null;
            $vehiculo->anio_fabricacion = $validated['anio_fabricacion'] ?? null;
            $vehiculo->combustible_id = $validated['combustible_id'] ?? null;
            $vehiculo->traccion_id = $validated['traccion_id'] ?? null;
            $vehiculo->equipo = $validated['equipo'] ?? null;
            $vehiculo->detalle = $validated['detalle'] ?? null;

            // Inicializar algunos campos relacionados a depreciación
            if (!is_null($vehiculo->valor_inicial)) {
                $vehiculo->valor_actual = $vehiculo->valor_inicial;
            }

            $vehiculo->save();

            return redirect()->route('vehiculo.index')->with('success', 'Vehículo creado correctamente.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar el vehículo. Por favor, intente de nuevo.');
        }
    }

    public function show(string $id)
    {
        $vehiculo = Vehiculo::with([
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
            'marca',
            'estadoFisico',
            'procedencia',
            'estado',
            'combustible',
            'traccion'
        ])->findOrFail($id);

        return view('activo.vehiculo.show', compact('vehiculo'));
    }

    public function edit(string $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $colores = Color::get();
        $marcas = Marca::get();
        $clases = Clase::where('id', 6)->get();
        $fuentes = Fuente::get();
        $ambientes = Ambiente::get();
        $cuentas = CuentaContable::get();
        $unidades = Unidad::get();
        $estadosFisicos = EstadoFisico::get();
        $procedencias = Procedencia::get();
        $estadosActivos = EstadoActivo::get();
        $combustibles = Combustible::get();
        $tracciones = Traccion::get();

        return view(
            'activo.vehiculo.edit',
            compact('vehiculo', 'colores', 'marcas', 'clases', 'fuentes', 'ambientes', 'cuentas', 'unidades', 'estadosFisicos', 'procedencias', 'estadosActivos', 'combustibles', 'tracciones')
        );
    }

    public function update(Request $request, string $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        $rules = [
            'codigo_de_activo'       => ['required', 'string', 'max:255', 'unique:vehiculo,codigo_de_activo,' . $id],
            'clase_id'               => ['required', 'exists:clase,id'],
            'subclase_id'            => ['required', 'exists:subclase,id'],
            'fuente_id'              => ['required', 'exists:fuente,id'],
            'cuenta_contable_id'     => ['required', 'exists:cuenta_contable,id'],
            'ambiente_id'            => ['required', 'exists:ambiente,id'],
            'empleado_id'            => ['required', 'exists:empleado,id'],
            'unidad_id'              => ['required', 'exists:unidad,id'],
            'fecha_de_adquisicion'   => ['required', 'date'],
            'valor_inicial'          => ['required', 'numeric', 'min:0'],
            'vida_util'               => ['required', 'integer', 'min:1'],
            'marca_id'                => ['required', 'exists:marca,id'],
            'modelo'                  => ['required', 'string', 'max:255'],
            'placa'                   => ['required', 'string', 'max:255'],
            'color_id'                => ['required', 'exists:color,id'],
            'estado_fisico_id'        => ['nullable', 'exists:estado_fisico,id'],
            'procedencia_id'          => ['nullable', 'exists:procedencia,id'],
            'estado_id'               => ['nullable', 'exists:estado_activo,id'],
            'numero_de_factura'      => ['nullable', 'string', 'max:255'],
            'otra_caracteristica'     => ['nullable', 'string'],
            'observacion'             => ['nullable', 'string'],
            'depresiable'             => ['nullable', 'boolean'],
            'no_depreciable'          => ['nullable', 'boolean'],
            'motor'                   => ['nullable', 'string', 'max:255'],
            'numero_chasis'           => ['nullable', 'string', 'max:255'],
            'anio_fabricacion'        => ['nullable', 'integer'],
            'combustible_id'          => ['nullable', 'exists:combustible,id'],
            'traccion_id'             => ['nullable', 'exists:traccion,id'],
            'equipo'                  => ['nullable', 'string', 'max:255'],
            'detalle'                 => ['nullable', 'string'],
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
            'codigo_de_activo'      => 'código de activo',
            'clase_id'              => 'clase',
            'subclase_id'           => 'subclase',
            'fuente_id'             => 'fuente',
            'cuenta_contable_id'    => 'cuenta contable',
            'ambiente_id'           => 'ambiente',
            'empleado_id'           => 'empleado',
            'unidad_id'             => 'unidad',
            'fecha_de_adquisicion'  => 'fecha de adquisición',
            'valor_inicial'         => 'valor inicial',
            'vida_util'             => 'vida útil',
            'marca_id'              => 'marca',
            'modelo'                => 'modelo',
            'placa'                 => 'placa',
            'color_id'              => 'color',
            'estado_fisico_id'      => 'estado físico',
            'procedencia_id'        => 'procedencia',
            'estado_id'             => 'estado',
            'numero_de_factura'     => 'número de factura',
            'otra_caracteristica'   => 'otra característica',
            'observacion'           => 'observación',
            'depresiable'           => 'depreciable',
            'no_depreciable'        => 'no depreciable',
            'motor'                 => 'motor',
            'numero_chasis'         => 'número de chasis',
            'anio_fabricacion'      => 'año de fabricación',
            'combustible_id'        => 'combustible',
            'traccion_id'           => 'tracción',
            'equipo'                => 'equipo',
            'detalle'               => 'detalle / descripción',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        try {
            $vehiculo->clase_id = $validated['clase_id'];
            $vehiculo->subclase_id = $validated['subclase_id'];
            $vehiculo->fuente_id = $validated['fuente_id'];
            $vehiculo->cuenta_contable_id = $validated['cuenta_contable_id'];
            $vehiculo->ambiente_id = $validated['ambiente_id'];
            $vehiculo->empleado_id = $validated['empleado_id'];
            $vehiculo->unidad_id = $validated['unidad_id'];
            $vehiculo->codigo_de_activo = $validated['codigo_de_activo'];
            $vehiculo->fecha_de_adquisicion = $validated['fecha_de_adquisicion'];
            $vehiculo->valor_inicial = $validated['valor_inicial'];
            $vehiculo->vida_util = $validated['vida_util'];
            $vehiculo->marca_id = $validated['marca_id'];
            $vehiculo->modelo = $validated['modelo'];
            $vehiculo->placa = $validated['placa'];
            $vehiculo->color_id = $validated['color_id'];
            $vehiculo->estado_fisico_id = $validated['estado_fisico_id'] ?? null;
            $vehiculo->procedencia_id = $validated['procedencia_id'] ?? null;
            $vehiculo->estado_id = $validated['estado_id'] ?? null;
            $vehiculo->numero_de_factura = $validated['numero_de_factura'] ?? null;
            $vehiculo->otra_caracteristica = $validated['otra_caracteristica'] ?? null;
            $vehiculo->observacion = $validated['observacion'] ?? null;
            $vehiculo->depresiable = isset($validated['depresiable']) ? (bool)$validated['depresiable'] : false;
            $vehiculo->no_depreciable = isset($validated['no_depreciable']) ? (bool)$validated['no_depreciable'] : false;
            $vehiculo->motor = $validated['motor'] ?? null;
            $vehiculo->numero_chasis = $validated['numero_chasis'] ?? null;
            $vehiculo->anio_fabricacion = $validated['anio_fabricacion'] ?? null;
            $vehiculo->combustible_id = $validated['combustible_id'] ?? null;
            $vehiculo->traccion_id = $validated['traccion_id'] ?? null;
            $vehiculo->equipo = $validated['equipo'] ?? null;
            $vehiculo->detalle = $validated['detalle'] ?? null;

            $vehiculo->save();

            return redirect()->route('vehiculo.index')->with('success', 'Vehículo actualizado correctamente.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el vehículo. Por favor, intente de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
