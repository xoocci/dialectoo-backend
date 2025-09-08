<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\salida;

class salidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los ingresos con sus detalles, incluyendo información de almacen y articulo
        $ingresosConDetalles = salida::with(['detalles', 'almacen', 'detalles.articulo'])->get();

        return response()->json($ingresosConDetalles, 200);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return salida::create($request->all());
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
        $sal= salida::findOrFail($id);
        $sal->idalmacen=$request->idalmacen;
        $sal->fecha_salida=$request->fecha_salida;
        $sal->update();
        return $sal;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sal= salida::findOrFail($id);
        $sal->delete();
    }
}
