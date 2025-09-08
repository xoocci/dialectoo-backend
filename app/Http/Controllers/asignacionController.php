<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\articulos_asignados;
use App\Models\almacen_articulo;
use App\Models\empleado;
use App\Models\docente;
use App\Models\responsable;
use App\Models\articulo;
use Illuminate\Support\Facades\DB;

class asignacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return asignacion::all();
        $asignacionConDetalles = asignacion::with(['articulo', 'responsable.empleado', 'docente.empleado'])
        ->where('cantidad', '>', 0)
        ->get();
    
    return response()->json($asignacionConDetalles, 200);
    }

    public function getEmpleadoDocenteById($idDocente)
    {
        // Convertir $idDocente a entero
        $idDocente = (int)$idDocente;
    
        // Buscar un docente por el idempleado y unir con la tabla empleado
        $docente = docente::leftJoin('empleado', 'docente.idempleado', '=', 'empleado.idempleado')
            ->where('docente.iddocente', $idDocente)
            ->select(
                'docente.*', // Seleccionar todos los campos de la tabla docente
                'empleado.nombre as nombreEmpleado',
                'empleado.apellido_p as apellidoPEmpleado',
                'empleado.apellido_m as apellidoMEmpleado',
                // Agregar otros campos de la tabla empleado que necesites
            )
            ->first();
    
        // Verificar si se encontró un docente
        if ($docente) {
            return response()->json($docente);
        } else {
            // Si no se encuentra, devolver una respuesta adecuada (puede ser un código HTTP 404)
            return response()->json(['message' => 'Empleado docente no encontrado'], 404);
        }
    }
    
    public function getEmpleadoResponsableById($idResponsable)
    {
        // Convertir $responsable a entero
        $idResponsable = (int)$idResponsable;
    
        // Buscar un responsable por el idempleado y unir con la tabla empleado
        $responsable = responsable::leftJoin('empleado', 'responsable.idempleado', '=', 'empleado.idempleado')
            ->where('responsable.iddocente', $idResponsable)
            ->select(
                'responsable.*', // Seleccionar todos los campos de la tabla responsable
                'empleado.nombre as nombreEmpleado',
                'empleado.apellido_p as apellidoPEmpleado',
                'empleado.apellido_m as apellidoMEmpleado',
                // Agregar otros campos de la tabla empleado que necesites
            )
            ->first();
    
        // Verificar si se encontró un responsable
        if ($responsable) {
            return response()->json($responsable);
        } else {
            // Si no se encuentra, devolver una respuesta adecuada (puede ser un código HTTP 404)
            return response()->json(['message' => 'Empleado responsable no encontrado'], 404);
        }
    }

    public function getArticuloById($idArticulo)
{
    // Convertir $idArticulo a entero
    $idArticulo = (int)$idArticulo;

    // Buscar un artículo por el idarticulo
    $articulo = articulo::where('idarticulo', $idArticulo)->first();

    // Verificar si se encontró un artículo
    if ($articulo) {
        return response()->json($articulo);
    } else {
        // Si no se encuentra, devolver una respuesta adecuada (puede ser un código HTTP 404)
        return response()->json(['message' => 'Artículo no encontrado'], 404);
    }
}

   

  

    public function store(Request $request)
    {
        $data = $request->all();
        $data['rfc_docente'] = $request->iddocente;
        $data['rfc_responsable'] = $request->idresponsable;
        $data['idarticulo'] = $request->idarticulo;
        $data['identificador_articulo'] = $request->identificador;
        $data['fecha'] = $request->fecha_asignacion;
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
                    $asignacion = articulos_asignados::create($data);
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
        $asig= asignacion::findOrFail($id);
        $asig->idarticulo=$request->idarticulo;
        $asig->idresponsable=$request->idresponsable;
        $asig->iddocente=$request->iddocente;
        $asig->fecha_asignacion=$request->fecha_asignacion;
        $asig->hora_asignacion=$request->hora_asignacion;
        $asig->tipo=$request->tipoAsignacion;
        $asig->cantidad=$request->cantidad;
        $asig->update();
        return $asig;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $emp= asignacion::findOrFail($id);
        $emp->delete();
    }
}
