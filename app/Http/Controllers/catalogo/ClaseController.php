<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Clase;
use App\Models\general\Grupo;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    public function index()
    {
        $clases = Clase::get();
        $grupos = Grupo::get();

        return view('catalogo.clase.index', compact('clases', 'grupos'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|unique:clase,descripcion',
            'grupo_id'    => 'required|exists:grupo,id',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una clase con esta descripción.',
            'grupo_id.required'    => 'Debe seleccionar un grupo.',
            'grupo_id.exists'      => 'El grupo seleccionado no es válido.',
        ]);

        $clase = new Clase();
        $clase->descripcion = $request->descripcion;
        $clase->grupo_id = $request->grupo_id;
        $clase->save();

        return back()->with('success', 'Clase creada correctamente');
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
            'descripcion' => 'required|unique:clase,descripcion,' . $id,
            'grupo_id'    => 'required|exists:grupo,id',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una clase con esta descripción.',
            'grupo_id.required'    => 'Debe seleccionar un grupo.',
            'grupo_id.exists'      => 'El grupo seleccionado no es válido.',
        ]);

        $clase = Clase::findOrFail($id);
        $clase->descripcion = $request->descripcion;
        $clase->grupo_id = $request->grupo_id;
        $clase->save();

        return back()->with('success', 'Clase actualizada correctamente');
    }


    public function destroy(string $id)
    {
        $clase = Clase::findOrFail($id);
        $clase->delete();

        return back()->with('success', 'Clase eliminada correctamente');
    }
}
