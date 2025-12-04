<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colores = Color::get();

        return view('catalogo.color.index', compact('colores'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|unique:color,descripcion'
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un color con esta descripción.',
        ]);

        $color = new Color();
        $color->descripcion = $request->descripcion;
        $color->save();

        return back()->with('success', 'Registro creado correctamente');
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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'descripcion' => 'required|unique:color,descripcion,' . $id
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un color con esta descripción.',
        ]);

        $color = Color::findOrFail($id);
        $color->descripcion = $request->descripcion;
        $color->save();

        return back()->with('success', 'Registro actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $color = Color::findOrFail($id);
        $color->delete();

        return back()->with('success', 'Registro eliminado correctamente');
    }
}
