<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\actfijo;
class actfijoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actfijo = actfijo::select(
            'actfijo.*',
            'actfijo.noinventario as noinventarioa'
        )->get();
        return response()->json($actfijo);

    }
    public function getByArticuloId($idarticulo)
{
          // Convertir $idEmpleado a entero
    $idarticulo = (int)$idarticulo;
    // Buscar un docente por el idempleado
    $actfijo = actfijo::where('idarticulo', $idarticulo)->first();
    
    // Verificar si se encontró un docente
    if ($actfijo) {
        return response()->json($actfijo);
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
        return actfijo::create($request->all());
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
        $actfijo= actfijo::findOrFail($id);
        $actfijo->idarticulo=$request->idarticulo;
        $actfijo->modelo=$request->modeloa;
        $actfijo->update();
        return $actfijo;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $actfijo= actfijo::findOrFail($id);
        $actfijo->delete();
    }
}
