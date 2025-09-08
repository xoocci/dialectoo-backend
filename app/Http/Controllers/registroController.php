<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\registro;

class registroController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return registro::all();
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return registro::create($request->all());
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
        $reg= registro::findOrFail($id);
        $reg->idarticulo=$request->idarticulo;
        $reg->idalmacen=$request->idalmacen;
        $reg->idresponsable=$request->idresponsable;
        $reg->fecha_registro=$request->fecha_registro;
        $reg->update();
        return $reg;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reg= registro::findOrFail($id);
        $reg->delete();
    }
}
