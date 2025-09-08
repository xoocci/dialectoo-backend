<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\almacen_articulo;
class almacen_articuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $almacen_articulos = almacen_articulo::all();
        return response()->json($almacen_articulos);
    }

    public function store(Request $request)
{
    // Obtener los datos del request
    $data = $request->all();
    $newTotal=0;
    $totalStored=0;
    // Verificar si ya existe un registro para este artículo en el almacén especificado
    

    $existingRecord2 = almacen_articulo::where('idarticulo', $data['idarticulo'])
                                      ->where('idalmacen', $data['idalmacen'])
                                      ->first();                                 
    if ($existingRecord2) {
        // Si ya existe un registro para este artículo y almacén, obtener la suma de cantidades
        $totalStored = almacen_articulo::where('idarticulo', $data['idarticulo'])->sum('cantidad');
        
        // Sumar la cantidad que se quiere guardar
        $newTotal = $totalStored + $data['cantidad'];
        
        // Verificar si la suma supera el stock máximo
        if ($newTotal > $data['stock']) {
            if($totalStored <  $data['stock'] ){
                return response()->json(['error' => 'La suma de la cantidad que se quiere almacenar y lo que ya esta almacenado supera el stock del articulo.Revise los detalles del articulo'], 400);
            }
            return response()->json(['error' => 'Todos los elementos del stock ya han sido asignados a un almacén'], 400);
        }
        
        $totalStored2 = almacen_articulo::where('idarticulo', $data['idarticulo'])->where('idalmacen', $data['idalmacen'])->sum('cantidad');
        
        // Sumar la cantidad que se quiere guardar
        $newTotal2 = $totalStored2 + $data['cantidad'];
        // Actualizar el registro existente con la nueva cantidad
        //$existingRecord2->update(['cantidad' => $newTotal]);
        almacen_articulo::where('idarticulo', $data['idarticulo'])
                     ->where('idalmacen', $data['idalmacen'])
                     ->update(['cantidad' => $newTotal2]);
        $existingRecord = almacen_articulo::where('idarticulo', $data['idarticulo'])
                                            ->where('idalmacen', $data['idalmacen'])
                                            ->first();
        return response()->json($existingRecord, 200);
    } else {
        $existingRecord = almacen_articulo::where('idarticulo', $data['idarticulo'])
                                      ->first();
        if ($existingRecord) {                    
            $totalStored = almacen_articulo::where('idarticulo', $data['idarticulo'])->sum('cantidad');
        }  
            // Sumar la cantidad que se quiere guardar
            $newTotal = $totalStored + $data['cantidad'];
            // Si no existe un registro para este artículo en el almacén especificado, crear uno nuevo
            // Verificar si la cantidad a almacenar supera el stock máximo
            if ($newTotal > $data['stock']) {
                if($totalStored <  $data['stock'] ){
                    return response()->json(['error' => 'La suma de la cantidad que se quiere almacenar y lo que ya esta almacenado supera el stock del articulo.Revise los detalles del articulo'], 400);
                }
                return response()->json(['error' => 'Todos los elementos del stock ya han sido asignados a un almacén'], 400);
            }
            
            $almacen_articulo = almacen_articulo::create($data);
            return response()->json($almacen_articulo, 201);
        
    }
}



    public function show($id)
    {

    }

    public function update(Request $request, $id)
{
    // Obtener los datos del request
    $data = $request->all();

    // Buscar el registro en la base de datos utilizando las claves idalmacen e idarticulo
    $almacen_articulo = almacen_articulo::where('idalmacen', $data['idalmacen'])
                                       ->where('idarticulo', $data['idarticulo'])
                                       ->first();

    if (!$almacen_articulo) {
        return response()->json(['error' => 'No se encontró el registro'], 404);
    }

    // Actualizar la cantidad del registro encontrado
    $almacen_articulo->cantidad = $data['cantidad'];
    $almacen_articulo->save();

    return response()->json($almacen_articulo, 200);
}


    public function destroy($id)
    {
        $almacen_articulo = almacen_articulo::findOrFail($id);
        $almacen_articulo->delete();
        return response()->json(null, 204);
    }
}
