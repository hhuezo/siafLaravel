<?php

namespace App\Http\Controllers\activo;

use App\Http\Controllers\Controller;
use App\Models\activo\Equipo;
use App\Models\catalogo\Ambiente;
use App\Models\catalogo\Clase;
use App\Models\catalogo\Color;
use App\Models\catalogo\CuentaContable;
use App\Models\catalogo\Empleado;
use App\Models\catalogo\Fuente;
use App\Models\catalogo\Material;
use App\Models\catalogo\SubClase;
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
                $editUrl = route('equipo.show', $item->equipo_id);

                return '<a href="' . $editUrl . '" class="btn btn-sm btn-info btn-wave">
                        <i class="bi bi-eye-fill"></i>
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


    public function loadEmpleados(string $id)
    {
        $empleados = Empleado::where('ambiente_id', $id)->get();
        return response()->json($empleados);
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

        // 🔹 Filtrar los equipos cuya fecha de adquisición sea menor o igual a la fecha final
        if ($request->filled('fechaFinal')) {
            $query->whereDate('fecha_adquisicion', '<=', $request->fechaFinal);
        }

        $equipos = $query->orderBy('codigo_activo')->get();

        $fechaFinal = Carbon::parse($request->fechaFinal);
        $anio = $fechaFinal->year;

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

        // return view('reportes.inventario_equipo', compact('equipos'));


        // 🔹 Generar PDF usando la vista existente
        $pdf = Pdf::loadView('reportes.inventario_equipo', compact('equipos'))
            ->setPaper('a4', 'landscape'); // o 'portrait' si prefieres vertical

        // 🔹 Descargar el PDF
        $nombreArchivo = 'Inventario_Equipo_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->stream($nombreArchivo);
        return $pdf->download($nombreArchivo);
    }


    public function create()
    {
        $colores = Color::get();
        $materiales = Material::get();
        return view('activo.equipo.create', compact('colores', 'materiales'));
    }


    public function store(Request $request)
    {
        //
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
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
