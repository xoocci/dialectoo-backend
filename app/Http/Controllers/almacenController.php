<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\almacen;

class almacenController extends Controller
{
    public function index()
    {
        return almacen::all();
     
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return almacen::create($request->all());
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
        $alm= almacen::findOrFail($id);
        $alm->idalmacen=$request->idalmacen;
        $alm->nombre=$request->nombre;
        $alm->update();
        return $alm;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alm= almacen::findOrFail($id);
        $alm->delete();
    }
}
