<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\articulos_prestados;
use App\Models\almacen_articulo;
use App\Models\empleado;
use App\Models\docente;
use App\Models\responsable;
use App\Models\articulo;
use Illuminate\Support\Facades\DB;

class articulos_prestadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['rfc_docente'] = $request->iddocente;
        $data['rfc_responsable'] = $request->idresponsable;
        $data['idarticulo'] = $request->idarticulo;
        $data['identificador_articulo'] = $request->identificador;
        $data['fecha'] = $request->fecha_asignacion;
        $data['hora'] = $request->hora_asignacion;
        $data['materia'] = $request->materia;
        $data['aula'] = $request->aula;
        $data['observaciones'] = $request->observaciones;
        $data['estado'] = 'Asignado';
        $idArticulo = $data['idarticulo'];
        $idalmacen = $data['idalmacen'];
        $cantidadAsignada = 1;
        $articulo = articulo::find($idArticulo);
        $almacen_articulo = almacen_articulo::where('idarticulo', $idArticulo)
                                    ->where('idalmacen', $idalmacen)
                                    ->first();
        if ($articulo && $articulo->stock >= $cantidadAsignada) {
            if ($almacen_articulo && $almacen_articulo->cantidad >= $cantidadAsignada) {
                DB::beginTransaction();
                try {
                    $asignacion = articulos_prestados::create($data);
                    $newTotal2= $almacen_articulo->cantidad-$cantidadAsignada;
                    almacen_articulo::where('idarticulo', $data['idarticulo'])
                     ->where('idalmacen', $data['idalmacen'])
                     ->update(['cantidad' => $newTotal2]);
                    DB::commit();
                } catch (\Exception $e) {
                    // Revertir la transacción en caso de error
                    DB::rollBack();
                    return response()->json(['message' => 'Error al procesar la asignación'.$e], 500);
                }
        }else {
            // Si el artículo no existe o no tiene suficiente stock, puedes manejarlo según tus necesidades
            // Por ejemplo, lanzar una excepción, devolver un mensaje de error, etc.
            return response()->json(['message' => 'Error: No hay suficiente stock disponible en el almacen seleccionado'], 400);
        }
        } else {
            // Si el artículo no existe o no tiene suficiente stock, puedes manejarlo según tus necesidades
            // Por ejemplo, lanzar una excepción, devolver un mensaje de error, etc.
            return response()->json(['message' => 'Error: No hay suficiente stock en el articulo'], 400);
        }
        if(isset($asignacion)){
        return response()->json($asignacion, 201);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
