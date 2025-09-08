<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\consumible;
class consumibleController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return consumible::all();
    }
    public function getByArticuloId($idarticulo)
{
          // Convertir $idEmpleado a entero
    $idarticulo = (int)$idarticulo;
    // Buscar un docente por el idempleado
    $consumible = consumible::where('idarticulo', $idarticulo)->first();
    
    // Verificar si se encontró un docente
    if ($consumible) {
        return response()->json($consumible);
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
        return consumible::create($request->all());
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
        $consumible= consumible::findOrFail($id);
        $consumible->idarticulo=$request->idarticulo;
        $consumible->presentacion=$request->presentacion;
        $consumible->update();
        return $consumible;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $consumible= consumible::findOrFail($id);
        $consumible->delete();
    }
}
