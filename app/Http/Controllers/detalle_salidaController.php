<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\detalle_salida;

class detalle_salidaController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return detalle_salida::all();
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return detalle_salida::create($request->all());
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
        $dsal= detalle_salida::findOrFail($id);
        $dsal->idsalida=$request->idsalida;
        $dsal->idarticulo=$request->idarticulo;
        $dsal->cantidad=$request->cantidad;
        $dsal->update();
        return $dsal;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sal= detalle_salida::findOrFail($id);
        $sal->delete();
    }
}
