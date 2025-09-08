<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\docente;
class docenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return docente::all();
    }
    public function getByEmpleadoId($idEmpleado)
{
          // Convertir $idEmpleado a entero
    $idEmpleado = $idEmpleado;
    // Buscar un docente por el idempleado
    $docente = docente::where('rfc', $idEmpleado)->first();
    
    // Verificar si se encontró un docente
    if ($docente) {
        return response()->json($docente);
    } 
        // Si no se encuentra, devolver una respuesta adecuada (puede ser un código HTTP 404)
        return response()->json(['message' => 'Docente no encontrado'], 404);
    
}
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return docente::create($request->all());
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
        $emp= docente::findOrFail($id);
        $emp->idempleado=$request->idempleado;
        $emp->rfc=$request->rfc;
        $emp->perfil=$request->perfil;
        $emp->update();
        return $emp;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $emp= docente::findOrFail($id);
        $emp->delete();
    }
}
