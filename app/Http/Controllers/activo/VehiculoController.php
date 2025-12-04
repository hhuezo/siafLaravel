<?php

namespace App\Http\Controllers\activo;

use App\Http\Controllers\Controller;
use App\Models\activo\Vehiculo;
use App\Models\catalogo\Ambiente;
use App\Models\catalogo\Clase;
use App\Models\catalogo\CuentaContable;
use App\Models\catalogo\Fuente;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::with([
            'clase',
            'subclase',
            'empleado',
            'marca',
            'color'
        ])->get();

        $clases = Clase::where('id',6)->get();
        $fuentes = Fuente::get();
        $ambientes = Ambiente::get();
        $cuentas = CuentaContable::get();

        return view('activo.vehiculo.index', compact('vehiculos', 'clases', 'fuentes', 'ambientes', 'cuentas'));
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
