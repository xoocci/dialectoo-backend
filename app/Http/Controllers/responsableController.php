<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\responsable;
use App\Models\usuario;
class responsableController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return responsable::all();
    }

    public function getByEmpleadoId($idEmpleado)
{
          // Convertir $idEmpleado a entero
    $idEmpleado = $idEmpleado;
    // Buscar un docente por el idempleado
    $responsable = responsable::where('rfc', $idEmpleado)->first();

    $idresp=$responsable->rfc;
    $usuario=usuario::where('rfc', $idresp)->first();
    // Verificar si se encontró un docente
    $responsable->usuario=$usuario;
    if ($responsable) {
        return response()->json($responsable);
    } 
        // Si no se encuentra, devolver una respuesta adecuada (puede ser un código HTTP 404)
        return response()->json(['message' => 'Responsable no encontrado'], 404);
    
}
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return responsable::create($request->all());
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
        $emp= responsable::findOrFail($id);
        $emp->idempleado=$request->idempleado;
        $emp->idusuario=$request->idusuario;
        $emp->update();
        return $emp;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $emp= responsable::findOrFail($id);
        $emp->delete();
    }
}
