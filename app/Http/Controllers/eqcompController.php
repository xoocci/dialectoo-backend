<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eqcomp;
class eqcompController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eqcomp = eqcomp::select(
            'eqcomp.*',
            'eqcomp.noinventario as noinventarioe'
        )->get();
    
        return response()->json($eqcomp);
    }
    public function getByArticuloId($idarticulo)
{
          // Convertir $idEmpleado a entero
    $idarticulo = (int)$idarticulo;
    // Buscar un docente por el idempleado
    $eqcomp = eqcomp::where('idarticulo', $idarticulo)->first();
    
    // Verificar si se encontró un docente
    if ($eqcomp) {
        return response()->json($eqcomp);
    } else {
        // Si no se encuentra, devolver una respuesta adecuada (puede ser un código HTTP 404)
        return response()->json(null, 204);
    }
}
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return eqcomp::create($request->all());
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
        $eqcomp= eqcomp::findOrFail($id);
        $eqcomp->idarticulo=$request->idarticulo;
        $eqcomp->modelo=$request->modeloe;
        $eqcomp->serie=$request->seriee;
        $eqcomp->update();
        return $eqcomp;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $eqcomp= eqcomp::findOrFail($id);
        $eqcomp->delete();
    }
}
