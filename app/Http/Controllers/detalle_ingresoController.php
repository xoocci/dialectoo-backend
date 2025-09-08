<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\detalle_ingreso;

class detalle_ingresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return detalle_ingreso::all();
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return detalle_ingreso::create($request->all());
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
        $ing= detalle_ingreso::findOrFail($id);
        $ing->idingreso=$request->idingreso;
        $ing->idarticulo=$request->idarticulo;
        $ing->cantidad=$request->cantidad;
        $ing->update();
        return $ing;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ing= detalle_ingreso::findOrFail($id);
        $ing->delete();
    }
}
