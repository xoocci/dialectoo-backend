<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ingreso;
use App\Models\detalle_ingreso;

class ingresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los ingresos con sus detalles, incluyendo información de almacen y articulo
        $ingresosConDetalles = ingreso::with(['detalles', 'almacen', 'detalles.articulo'])->get();

        return response()->json($ingresosConDetalles, 200);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return ingreso::create($request->all());
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
        $ing= ingreso::findOrFail($id);
        $ing->idalmacen=$request->idalmacen;
        $ing->fecha_ingreso=$request->fecha_ingreso;
        $ing->update();
        return $ing;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ing= ingreso::findOrFail($id);
        $ing->delete();
    }
}
