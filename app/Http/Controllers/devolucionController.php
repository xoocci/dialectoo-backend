<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\articulos_devueltos;
use App\Models\asignacion;
use App\Models\empleado;
use App\Models\docente;
use App\Models\responsable;
use App\Models\articulo;
use App\Models\ingreso;
use App\Models\almacen_articulo;
use Illuminate\Support\Facades\DB;
class devolucionController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los ingresos con sus detalles, incluyendo información de almacen y articulo
        $ingresosConDetalles = devolucion::with(['detallesAsignacion','detallesAsignacion.articulo','detallesAsignacion.responsableAsi.empleado','detallesAsignacion.docente.empleado', 'detalleResponsableDev.empleado'])->get();

        return response()->json($ingresosConDetalles, 200);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['rfc_docente'] = $request->rfc_docente;
        $data['rfc_responsable'] = $request->rfc_responsable;
        $data['idarticulo'] = $request->idarticulo;
        $data['identificador_articulo'] = $request->identificador_articulo;
        $data['fecha'] = $request->fecha;

        $idArticulo = $data['idarticulo'];
        $idalmacen = $data['idalmacen'];
        $cantidadAsignada = 1;
        $articulo = articulo::find($idArticulo);
        $almacen_articulo = almacen_articulo::where('idarticulo', $idArticulo)
                                    ->where('idalmacen', $idalmacen)
                                    ->first();
        if ($articulo) {
            if ($almacen_articulo) {
                DB::beginTransaction();
                try {
                    $devolucion = articulos_devueltos::create($data);
                    $newTotal2= $almacen_articulo->cantidad+$cantidadAsignada;
                    almacen_articulo::where('idarticulo', $data['idarticulo'])
                     ->where('idalmacen', $data['idalmacen'])
                     ->update(['cantidad' => $newTotal2]);
                    DB::commit();
                } catch (\Exception $e) {
                    // Revertir la transacción en caso de error
                    DB::rollBack();
                    return response()->json(['message' => 'Error al procesar la devolucion'.$e], 500);
                }
        }
        } 
        if(isset($devolucion)){
        return response()->json($devolucion, 201);
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
        $dev= devolucion::findOrFail($id);
        $dev->idasignacion=$request->idasignacion;
        $dev->idresponsable=$request->idresponsable;
        $dev->fecha_devolucion=$request->fecha_devolucion;
        $dev->hora_devolucion=$request->hora_devolucion;
        $dev->cantidad=$request->cantidad;
        $dev->update();
        return $dev;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dev= devolucion::findOrFail($id);
        $dev->delete();
    }
}
