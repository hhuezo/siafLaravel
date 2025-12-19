<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Ambiente;
use App\Models\catalogo\Departamento;
use App\Models\catalogo\Empleado;
use App\Models\catalogo\Gerencia;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = Empleado::get();
        $ambientes = Ambiente::get();
        $gerencias = Gerencia::get();
        $departamentos = Departamento::get();



        return view('catalogo.empleado.index', compact('empleados', 'ambientes', 'gerencias', 'departamentos'));
        //
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
        $request->validate([
            'nombre' => 'required|unique:empleado,nombre',
            'codigo' => 'required|unique:empleado,codigo',
            'ambiente_id' => 'required|exists:ambiente,id',
            'gerencia_id' => 'required|exists:gerencia,id',
            'departamento_id' => 'required|exists:departamento,id',
            'activo' => 'required',


        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe un empleado con este nombre.',

            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Ya existe un empleado con este código.',

            'ambiente_id.required' => 'Debe seleccionar un ambiente.',
            'ambiente_id.exists' => 'El ambiente seleccionado no es válido.',

            'gerencia_id.required' => 'Debe seleccionar una gerencia.',
            'gerencia_id.exists' => 'La gerencia seleccionada no es válida.',

            'departamento_id.required' => 'Debe seleccionar un departamento.',
            'departamento_id.exists' => 'El departamento seleccionado no es válido.',

            'activo.required' => 'Debe seleccionar el estado del empleado.',
        ]);

        $empleado = new Empleado();
        $empleado->nombre = $request->nombre;
        $empleado->codigo      = $request->codigo;
        $empleado->ambiente_id    = $request->ambiente_id;
        $empleado->gerencia_id    = $request->gerencia_id;
        $empleado->departamento_id    = $request->departamento_id;
        $empleado->activo      = $request->activo;

        $empleado->save();

        return back()->with('success', 'Empleado creado correctamente');
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
        $request->validate([
            'nombre' => 'required|unique:empleado,nombre,' . $id,
            'codigo' => 'required|unique:empleado,codigo,' . $id,
            'ambiente_id' => 'required|exists:ambiente,id',
            'gerencia_id' => 'required|exists:gerencia,id',
            'departamento_id' => 'required|exists:departamento,id',
            'activo' => 'required',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe un empleado con este nombre.',

            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Ya existe un empleado con este código.',

            'ambiente_id.required' => 'Debe seleccionar un ambiente.',
            'ambiente_id.exists' => 'El ambiente seleccionado no es válido.',

            'gerencia_id.required' => 'Debe seleccionar una gerencia.',
            'gerencia_id.exists' => 'La gerencia seleccionada no es válida.',

            'departamento_id.required' => 'Debe seleccionar un departamento.',
            'departamento_id.exists' => 'El departamento seleccionado no es válido.',

            'activo.required' => 'Debe seleccionar el estado del empleado.',
        ]);


        $empleado = Empleado::findOrFail($id);
        $empleado->nombre = $request->nombre;
        $empleado->codigo      = $request->codigo;
        $empleado->ambiente_id    = $request->ambiente_id;
        $empleado->gerencia_id    = $request->gerencia_id;
        $empleado->departamento_id    = $request->departamento_id;
        $empleado->activo      = $request->activo;

        $empleado->save();

        return back()->with('success', 'Registro actualizado correctamente');


        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return back()->with('success', 'Empleado eliminado correctamente');
        //
    }
}
