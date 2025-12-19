<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Clase;
use App\Models\catalogo\SubClase;
use Illuminate\Http\Request;

class SubClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { {
            $subclases = SubClase::get();
            $clases = Clase::get();

            return view('catalogo.subclase.index', compact('subclases', 'clases'));
        }

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

            'descripcion' => 'required|unique:subclase,descripcion',
            'codigo'      => 'required|unique:subclase,codigo',
            'clase_id'    => 'required|exists:clase,id',
        ], [

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una subclase con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.unique'        => 'Ya existe una subclase con este código.',
            'clase_id.required'    => 'Debe seleccionar una clase.',
            'clase_id.exists'      => 'La clase seleccionada no es válida.',
        ]);

        $subclase = new SubClase();
        $subclase->descripcion = $request->descripcion;
        $subclase->codigo      = $request->codigo;
        $subclase->clase_id    = $request->clase_id;
        $subclase->save();

        return back()->with('success', 'Subclase creada correctamente');


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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([

            'descripcion' => 'required|unique:subclase,descripcion',
            'codigo'      => 'required|unique:subclase,codigo,' . $id,
            'clase_id'    => 'required|exists:clase,id',
        ], [

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una subclase con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.unique'        => 'Ya existe una subclase con este código.',
            'clase_id.required'    => 'Debe seleccionar una clase.',
            'clase_id.exists'      => 'La clase seleccionada no es válida.',
        ]);


        $subclase = SubClase::findOrFail($id);
        $subclase->descripcion = $request->descripcion;
        $subclase->codigo      = $request->codigo;
        $subclase->clase_id    = $request->clase_id;
        $subclase->save();

        return back()->with('success', 'Registro actualizado correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subclase = SubClase::findOrFail($id);
        $subclase->delete();

        return back()->with('success', 'Registro eliminado correctamente');
        //
    }
}
