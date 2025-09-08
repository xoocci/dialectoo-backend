<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\baja;

class bajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return baja::all();
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return baja::create($request->all());
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
        $baj= baja::findOrFail($id);
        $baj->idarticulo=$request->idarticulo;
        $baj->idalmacen=$request->idalmacen;
        $baj->idresponsable=$request->idresponsable;
        $baj->fecha_baja=$request->fecha_baja;
        $baj->update();
        return $baj;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $baj= baja::findOrFail($id);
        $baj->delete();
    }
}
