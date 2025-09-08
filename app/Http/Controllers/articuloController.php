<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\articulo;
use App\Models\eqcomp;
use App\Models\actfijo;
use App\Models\consumible;
use App\Models\almacen_articulo;
use App\Models\identificadores_articulo;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class articuloController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Obtener todos los registros de la tabla 'articulo' con información adicional
    $articulos = articulo::with(['eqcomp', 'actfijo', 'consumible', 'almacen_articulo','almacen_articulo.almacen', 'identificadoresArticulo'])
        ->get();

    // Iterar sobre cada artículo para asignar el tipo de artículo y obtener información del almacén
    foreach ($articulos as $articulo) {
        $articulo->tipo = $this->obtenerTipoDeArticulo($articulo->idarticulo);
    }

    // Devolver la respuesta JSON con los datos de los artículos
    return response()->json($articulos);
}

public function searchEqcomp($query)
{
    $eqcompResults = eqcomp::join('articulo', 'eqcomp.idarticulo', '=', 'articulo.idarticulo')
        ->where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('articulo.nombre', 'ilike', '%' . $query . '%')
                ->orWhere('eqcomp.noinventario', 'ilike', '%' . $query . '%');
        })
        ->select('eqcomp.*', 'eqcomp.noinventario as noinventarioe' ,'articulo.nombre','articulo.stock','articulo.idarticulo')
        ->get();

    return response()->json($eqcompResults);
}
public function searchActfijo($query)
{
    $actfijoResults = actfijo::join('articulo', 'actfijo.idarticulo', '=', 'articulo.idarticulo')
        ->where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('articulo.nombre', 'ilike', '%' . $query . '%')
                ->orWhere('actfijo.noinventario', 'ilike', '%' . $query . '%');
        })
        ->select('actfijo.*','actfijo.noinventario as noinventarioa' ,'articulo.nombre','articulo.stock','articulo.idarticulo')
        ->get();

    return response()->json($actfijoResults);
}
public function search($query)
{
    $eqcompResults = $this->searchEqcomp($query)->original->toArray();
    $actfijoResults = $this->searchActfijo($query)->original->toArray();

    $combinedResults = array_merge($eqcompResults, $actfijoResults);

    return response()->json($combinedResults);
}
public function searchinf($query)
{
    $articulos = articulo::select(
        'articulo.idarticulo',
        'articulo.nombre',
        'articulo.marca',
        'articulo.stock'
    )
    ->where('articulo.nombre', 'ilike', '%' . $query . '%')
    ->get();
    
    return response()->json($articulos);
}
public function search11($query)
{
    $articulos = Articulo::select(
        'articulo.idarticulo',
        'articulo.nombre',
        'articulo.marca',
        'articulo.stock',
        'articulo.color'
    )
    ->with('identificadoresArticulo') 
    ->with('almacen_articulo')
    ->whereIn('articulo.idarticulo', function($query) {
        $query->select('idarticulo')->from('eqcomp')
              ->unionAll(
                  \DB::table('actfijo')->select('idarticulo')
              );
    })
    ->where('articulo.nombre', 'ilike', '%' . $query . '%')
    ->get();
    
    return response()->json($articulos);
}
public function search111($query)
{
    $articulos = Articulo::select(
        'articulo.idarticulo',
        'articulo.nombre',
        'articulo.marca',
        'articulo.stock',
        'articulo.color'
    )
    ->with('identificadoresArticulo') 
    ->with('almacen_articulo')
    ->whereIn('articulo.idarticulo', function($query) {
        $query->select('idarticulo')->from('eqcomp')
              ->unionAll(
                  \DB::table('actfijo')->select('idarticulo')
              )
              ->unionAll(
                  \DB::table('consumible')->select('idarticulo')
              );
    })
    ->where('articulo.nombre', 'ilike', '%' . $query . '%')
    ->get();
    
    return response()->json($articulos);
}

public function search111C($query)
{
    $articulos = Articulo::select(
        'articulo.idarticulo',
        'articulo.nombre',
        'articulo.marca',
        'articulo.descripcion',
        'articulo.stock',
        'articulo.stockmin',
        'articulo.stockmax',
        'articulo.color',
        'eqcomp.modelo',
        'actfijo.tipo',
        'consumible.presentacion',
         \DB::raw("CASE 
                        WHEN eqcomp.idarticulo IS NOT NULL THEN 'Equipo de cómputo'
                        WHEN actfijo.idarticulo IS NOT NULL THEN 'Activo fijo'
                        WHEN consumible.idarticulo IS NOT NULL THEN 'Consumible'
                        ELSE NULL
                    END AS tipo_articulo")
    )
    ->with('identificadoresArticuloComp') 
    ->with('almacen_articulo')
    ->whereIn('articulo.idarticulo', function($query) {
        $query->select('idarticulo')->from('eqcomp')
              ->unionAll(
                  \DB::table('actfijo')->select('idarticulo')
              )
              ->unionAll(
                  \DB::table('consumible')->select('idarticulo')
              );
    })
    ->leftJoin('eqcomp', 'articulo.idarticulo', '=', 'eqcomp.idarticulo')
    ->leftJoin('actfijo', 'articulo.idarticulo', '=', 'actfijo.idarticulo')
    ->leftJoin('consumible', 'articulo.idarticulo', '=', 'consumible.idarticulo')
    ->where('articulo.nombre', 'ilike', '%' . $query . '%')
    ->get();
    
    return response()->json($articulos);
}

public function search111CC($query)
{
    $articulos = Articulo::select(
        'articulo.idarticulo',
        'articulo.nombre',
        'articulo.marca',
        'articulo.descripcion',
        'articulo.stock',
        'articulo.stockmin',
        'articulo.stockmax',
        'articulo.color',
        'eqcomp.modelo',
        'actfijo.tipo',
        'consumible.presentacion',
         \DB::raw("CASE 
                        WHEN eqcomp.idarticulo IS NOT NULL THEN 'Equipo de cómputo'
                        WHEN actfijo.idarticulo IS NOT NULL THEN 'Activo fijo'
                        WHEN consumible.idarticulo IS NOT NULL THEN 'Consumible'
                        ELSE NULL
                    END AS tipo_articulo")
    )
    ->with('identificadoresArticuloComp') 
    ->with('almacen_articulo')
    ->whereIn('articulo.idarticulo', function($query) {
        $query->select('idarticulo')->from('eqcomp')
              ->unionAll(
                  \DB::table('actfijo')->select('idarticulo')
              );
    })
    ->leftJoin('eqcomp', 'articulo.idarticulo', '=', 'eqcomp.idarticulo')
    ->leftJoin('actfijo', 'articulo.idarticulo', '=', 'actfijo.idarticulo')
    ->leftJoin('consumible', 'articulo.idarticulo', '=', 'consumible.idarticulo')
    ->where('articulo.nombre', 'ilike', '%' . $query . '%')
    ->get();
    
    return response()->json($articulos);
}
public function search1($query)
{
    $articulos = articulo::select(
        'articulo.*',
        'articulo.idarticulo as idarticulol',
        'almacen.idalmacen',
        'almacen.nombre as nombrealmacen',
        'eqcomp.noinventario as noinventarioe',
        'eqcomp.modelo as modeloe',
        'eqcomp.serie as seriee',
        'actfijo.noinventario as noinventarioa',
        'actfijo.modelo as modeloa',
        'consumible.idconsumible',
        'consumible.presentacion'
    )
    ->leftJoin('almacen', 'articulo.idalmacen', '=', 'almacen.idalmacen')
    ->leftJoin('eqcomp', 'articulo.idarticulo', '=', 'eqcomp.idarticulo')
    ->leftJoin('actfijo', 'articulo.idarticulo', '=', 'actfijo.idarticulo')
    ->leftJoin('consumible', 'articulo.idarticulo', '=', 'consumible.idarticulo')
    ->where(function ($queryBuilder) use ($query) {
        $queryBuilder->where('articulo.nombre', 'ilike', '%' . $query . '%')
            ->orWhere('eqcomp.noinventario', 'ilike', '%' . $query . '%');
            // Puedes agregar más condiciones aquí según tus necesidades
    })
    ->where('articulo.stock', '>', 0) // Agregar la condición para el stock mayor a 0
    ->get();
    
    // Iterar sobre cada artículo y calcular el valor del campo 'tipo'
    foreach ($articulos as $articulo) {
        $articulo->tipo = $this->obtenerTipoDeArticulo($articulo->idarticulol);
    }
    return response()->json($articulos);
}


   
    public function obtenerTipoDeArticulo($idarticulo)
    {
        $eqcomp = eqcomp::where('idarticulo', $idarticulo)->first();
        $actfijo = actfijo::where('idarticulo', $idarticulo)->first();
        $consumible = consumible::where('idarticulo', $idarticulo)->first();
    
        if ($eqcomp) {
            return 'Equipo de cómputo';
        } elseif ($actfijo) {
            return 'Activo fijo';
        } elseif ($consumible) {
            return 'Consumible';
        } else {
            return 'Desconocido';
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*$rules = [
            'noinventarioe' => 'unique:eqcomp,noinventario', // La regla unique se encarga de verificar que el correo no esté repetido en la tabla 'empleados'
            'noinventarioa' => 'unique:actfijo,noinventario', // La regla unique se encarga de verificar que el RFC no esté repetido en la tabla 'docente'
        ];
    
        // Define los mensajes de error personalizados
        $messages = [
            'noinventarioe.unique' => 'El numero de inventario ya esta en uso.',
            'noinventarioa.unique' => 'El numero de inventario ya esta en uso.'
        ];
    
        // Valida los datos del formulario
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // Comprueba si hubo errores de validación
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }*/
        DB::beginTransaction();
    
        try {
    
            // Verificar si ya existe un artículo con la misma información
            $existingArticle = articulo::where('nombre', $request->input('nombre'))
            ->where('marca', $request->input('marca'))
            ->where('color', $request->input('color'))
            ->first();
    
            if ($existingArticle) {
            DB::rollback();
            return response()->json(['message' => 'Ya existe un artículo con esta información. Puede agregar un nuevo número de inventario al artículo existente.'], 422);
            }
    
    
            if ($request->filled('modeloe')) {
                $tipoArticulo = 'SCEQ';
            } elseif ($request->filled('tipoa')) {
                $tipoArticulo = 'SCAF';
            } elseif ($request->filled('presentacion')) {
                $tipoArticulo = 'SCCO';
            } else {
                // Tipo de artículo no válido
                // Aquí puedes manejar el error o lanzar una excepción según tu lógica de aplicación
            }
            
    
        $ultimoArticulo = articulo::select('articulo.*','idarticulo as idarticulol')
        ->where('idarticulo', 'ilike', '%' . $tipoArticulo . '%')
        ->orderBy('idarticulo', 'desc')
        ->first();
            
            //return response()->json($ultimoArticulo);
    
            if ($ultimoArticulo) {
                // Obtener el número de idarticulo más alto y sumarle 1
                $ultimoNumero = intval(substr($ultimoArticulo->idarticulol, 4));
                $nuevoNumero = $ultimoNumero + 1;
                $nuevoIdArticulo = $tipoArticulo . str_pad($nuevoNumero, 5, '0', STR_PAD_LEFT);
                //return response()->json($nuevoIdArticulo);
            } else {
                // No se encontraron artículos de este tipo, comenzar desde el número 1
                $nuevoIdArticulo = $tipoArticulo . '00001';
            }
                    // Insertar en la tabla 'empleado'
                    $articuloData = [
                        'idarticulo' => $nuevoIdArticulo,
                        'nombre' => $request->input('nombre'),
                        'descripcion' => $request->input('descripcion'),  
                        'marca' => $request->input('marca'),  
                        'color' => $request->input('color'),  
                        'stock' => $request->input('stock'),  
                        'stockmin' => $request->input('stockmin'),  
                        'stockmax' => $request->input('stockmax')
                    ];
                    //return response()->json($articuloData);
            //$articuloData['idarticulo'] = $nuevoIdArticulo;
            
            // Insertar el artículo en la tabla 'articulo'
            $articulo = articulo::create($articuloData);
            //$idarticulo = $articulo->idarticulo;
    
            if ($tipoArticulo === 'SCEQ') {
                // Lógica para insertar en la tabla 'usuario'
                $eqcompData = [
                    'idarticulo' => $nuevoIdArticulo,
                    'modelo' => $request->input('modeloe'), 
                ];
                eqcomp::create($eqcompData);
    
                $numerosInventarioe = $request->input('numerosInventarioe');
                $numerosSerie = $request->input('numerosSerie');
                $identificadores_data = [];
                // Iterar sobre los arreglos de números de inventario y números de serie
                for ($i = 0; $i < count($numerosInventarioe); $i++) {
                    // Crear un nuevo registro para cada par de números de inventario y serie
                    $identificadores_data = [
                        'idarticulo' => $nuevoIdArticulo,
                        'identificador_articulo' => $numerosInventarioe[$i],
                        'numero_serie' => $numerosSerie[$i],
                        'estado' => 'Disponible',
                    ];
                    identificadores_articulo::create($identificadores_data);
                }
                
    
            }
            if ($tipoArticulo === 'SCAF') {
                // Lógica para insertar en la tabla 'usuario'
                $actfijoData = [
                    'idarticulo' => $nuevoIdArticulo,
                    'tipo' => $request->input('tipoa'), 
                    // Otros campos necesarios
                ];
                actfijo::create($actfijoData);
                $numerosInventarioa = $request->input('numerosInventarioa');
                $identificadores_data = [];
                // Iterar sobre los arreglos de números de inventario y números de serie
                for ($i = 0; $i < count($numerosInventarioa); $i++) {
                    // Crear un nuevo registro para cada par de números de inventario y serie
                    $identificadores_data = [
                        'idarticulo' => $nuevoIdArticulo,
                        'identificador_articulo' => $numerosInventarioa[$i],
                        'numero_serie' => NULL,
                        'estado' => 'Disponible',
                    ];
                    identificadores_articulo::create($identificadores_data);
                }
            }
            if ($tipoArticulo === 'SCCO') {
                // Lógica para insertar en la tabla 'docente'
                $consumubleData = [
                    'idarticulo' => $nuevoIdArticulo,
                    'presentacion' => $request->input('presentacion'), 
                    // Otros campos necesarios
                ];
                consumible::create($consumubleData);
            }
            DB::commit();
    
            return response()->json($articulo, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error en la transacción'.$e], 500);
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
   /**
 * Update the specified resource in storage.
 */
public function update(Request $request, string $id)
{

    if ($request->filled('modeloe')) {
        $tipoArticulo = 'SCEQ';
    } elseif ($request->filled('tipoa')) {
        $tipoArticulo = 'SCAF';
    } elseif ($request->filled('presentacion')) {
        $tipoArticulo = 'SCCO';
    } else {
        // Tipo de artículo no válido
        // Aquí puedes manejar el error o lanzar una excepción según tu lógica de aplicación
    }
    DB::beginTransaction();

    try {
        // Buscar el empleado
        $articulo = articulo::findOrFail($id);

        $valStockAct = $articulo->stock ;
        $valStockNuv = $request->stock; ;
        // Actualizar los datos del articulo
        $articulo->nombre = $request->nombre;
        $articulo->descripcion = $request->descripcion;
        $articulo->marca = $request->marca;
        $articulo->color = $request->color;
        $articulo->stock = $request->stock;
        $articulo->stockmin = $request->stockmin;
        $articulo->stockmax = $request->stockmax;
        $articulo->update();

                    // Ejecutar la actualización mediante una consulta SQL
        if ($tipoArticulo === 'SCEQ') {

            DB::table('eqcomp')
            ->where('idarticulo', $articulo->idarticulo)
            ->update([
                'modelo' => $request->input('modeloe'),
            ]);
                    
            if( $valStockNuv > $valStockAct){
                $numerosInventarioe = $request->input('numerosInventarioe');
                $numerosSerie = $request->input('numerosSerie');
                $identificadores_data = [];
                // Iterar sobre los arreglos de números de inventario y números de serie
                for ($i = 0; $i < count($numerosInventarioe); $i++) {
                // Crear un nuevo registro para cada par de números de inventario y serie
                $identificadores_data = [
                'idarticulo' => $articulo->idarticulo,
                'identificador_articulo' => $numerosInventarioe[$i],
                'numero_serie' => $numerosSerie[$i],
                'estado' => 'Disponible',
                ];
                identificadores_articulo::create($identificadores_data);
                }
            }
            if ($valStockNuv < $valStockAct) {
                $numerosInventarioeE = $request->input('numerosInventarioeD');
                $numerosSerieE = $request->input('numerosSerieD');
                
                // Iterar sobre los arreglos de números de inventario y números de serie
                for ($i = 0; $i < count($numerosInventarioeE); $i++) {
                    $identificadores_data = [
                        'idarticulo' => $articulo->idarticulo,
                        'identificador_articulo' => $numerosInventarioeE[$i],
                        'numero_serie' => $numerosSerieE[$i],
                        'estado' => 'Disponible',
                    ];
            
                    // Buscar el registro correspondiente en la tabla identificadores_articulo
                    identificadores_articulo::where('idarticulo', $articulo->idarticulo)
                                              ->where('identificador_articulo', $numerosInventarioeE[$i])
                                              ->where('numero_serie', $numerosSerieE[$i])
                                              ->where('estado', 'Disponible')
                                              ->delete();
                }
            }
            
                

        }
       

        if ($tipoArticulo === 'SCAF') {

            DB::table('actfijo')
            ->where('idarticulo', $articulo->idarticulo)
            ->update([
                'tipo' => $request->input('tipoa'),
            ]);    

            if( $valStockNuv > $valStockAct){
                
                $numerosInventarioa = $request->input('numerosInventarioa');
                $identificadores_data = [];
                // Iterar sobre los arreglos de números de inventario y números de serie
                for ($i = 0; $i < count($numerosInventarioa); $i++) {
                    // Crear un nuevo registro para cada par de números de inventario y serie
                    $identificadores_data = [
                        'idarticulo' => $articulo->idarticulo,
                        'identificador_articulo' => $numerosInventarioa[$i],
                        'numero_serie' => NULL,
                        'estado' => 'Disponible',
                    ];
                    identificadores_articulo::create($identificadores_data);
            }
            
            }
            if ($valStockNuv < $valStockAct) {
                $numerosInventarioaE = $request->input('numerosInventarioaD');
                
                // Iterar sobre los arreglos de números de inventario
                for ($i = 0; $i < count($numerosInventarioaE); $i++) {
                    $identificadores_data = [
                        'idarticulo' => $articulo->idarticulo,
                        'identificador_articulo' => $numerosInventarioaE[$i],
                        'numero_serie' => NULL,
                        'estado' => 'Disponible',
                    ];
            
                    // Buscar el registro correspondiente en la tabla identificadores_articulo
                    identificadores_articulo::where('idarticulo', $articulo->idarticulo)
                                              ->where('identificador_articulo', $numerosInventarioaE[$i])
                                              ->whereNull('numero_serie')
                                              ->where('estado', 'Disponible')
                                              ->delete();
                }
            }
            

        }


        if ($tipoArticulo === 'SCCO') {
            DB::table('consumible')
            ->where('idarticulo', $articulo->idarticulo)
            ->update([
                'presentacion' => $request->input('presentacion'),
            ]);
        }
        
      

        DB::commit();

        return response()->json($articulo, 200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['message' => 'Error en la transacción: ' . $e], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    $articulo = articulo::findOrFail($id);

    $eqcomp = eqcomp::where('idarticulo', $articulo->idarticulo)->first();
    $actfijo = actfijo::where('idarticulo', $articulo->idarticulo)->first();
    $consumible = consumible::where('idarticulo', $articulo->idarticulo)->first();

    $articulo->delete();
    // Puedes retornar una respuesta adecuada (por ejemplo, un código HTTP 204 - No Content)
    return response()->noContent();
    }
}
