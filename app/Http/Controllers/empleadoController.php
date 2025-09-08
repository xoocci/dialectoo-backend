<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\empleado;
use App\Models\responsable;
use App\Models\docente;
use App\Models\usuarios;
use App\Models\articulos_asignados;
use App\Models\articulo;
use App\Models\eqcomp;
use App\Models\actfijo;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
class empleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Obtener todos los registros de la tabla 'articulo'
    $empleados = empleado::select(
        'empleado.*',
        'docente.iddocente',
        'docente.rfc',
        'docente.perfil'
    )
    ->leftJoin('docente', 'empleado.rfc', '=', 'docente.rfc')
    ->get();

    // Iterar sobre los empleados y obtener los artículos asignados a cada uno
    foreach ($empleados as $empleado) {
        $idDocente = $empleado->iddocente;
        $articulosAsignados = asignacion::where('iddocente', $idDocente)
        ->with('articulo')
            ->get();

        $empleado->articulos_asignados = $articulosAsignados;
    }

    // Devolver la respuesta JSON con la información incluida
    return response()->json($empleados);
}


    public function search($query)
{
    $results = empleado::leftJoin('docente', 'docente.rfc', '=', 'empleado.rfc')
                ->leftJoin('responsable', 'responsable.rfc', '=', 'empleado.rfc')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('empleado.nombre', 'ilike', '%' . $query . '%')
                                ->orWhere('empleado.apellido_p', 'ilike', '%' . $query . '%')
                                ->orWhere('empleado.apellido_m', 'ilike', '%' . $query . '%');
                })
                ->select('empleado.rfc', 'docente.perfil',
                         DB::raw("CONCAT(empleado.nombre, ' ', empleado.apellido_p, ' ', empleado.apellido_m) as nombre"),
                         
                         DB::raw("CONCAT(empleado.nombre, ' ', empleado.apellido_p, ' ', empleado.apellido_m) as nombre"))
                ->get();

    return response()->json($results);
}

    public function searchdetal($query)
{
    $results = empleado::leftJoin('docente', 'docente.rfc', '=', 'empleado.rfc')
                        ->leftJoin('responsable', 'responsable.rfc', '=', 'empleado.rfc')
                        ->leftJoin('usuarios', 'usuarios.rfc', '=', 'empleado.rfc')
                        ->where(function ($queryBuilder) use ($query) {
                            $queryBuilder->where('empleado.nombre', 'ilike', '%' . $query . '%')
                                ->orWhere('empleado.apellido_p', 'ilike', '%' . $query . '%')
                                ->orWhere('empleado.apellido_m', 'ilike', '%' . $query . '%');
                        })
                        ->select(
                            'empleado.*', 
                            'docente.perfil', 
                            'docente.no_cubiculo', 
                            'responsable.puesto',
                            'usuarios.idusuario',
                            'usuarios.rol',
                            'usuarios.restart',
                            \DB::raw("CASE 
                                WHEN docente.rfc IS NOT NULL THEN 'TipoB'
                                WHEN responsable.rfc IS NOT NULL THEN 'TipoA'
                                ELSE 'No definido'
                                END AS tipo")
                            )
                        ->get();

    return response()->json($results);
}


    
    public function searchEmpArt($query)
{
    $results = docente::join('empleado', 'docente.rfc', '=', 'empleado.rfc')
        ->where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('empleado.nombre', 'ilike', '%' . $query . '%')
                ->orWhere('empleado.apellido_p', 'ilike', '%' . $query . '%')
                ->orWhere('empleado.apellido_m', 'ilike', '%' . $query . '%');
        })
        ->select('docente.*', DB::raw("CONCAT(empleado.nombre, ' ', empleado.apellido_p, ' ', empleado.apellido_m) as nombre"))
        ->get();

    // Iterar sobre los resultados y obtener los artículos asignados a cada empleado-docente con fecha de asignación y relaciones
    foreach ($results as $docente) {
        $articulosAsignados = articulos_asignados::where('rfc_docente', $docente->rfc)
            ->with(['articulo', 'responsable','identificadores_articulo']) // Cargar relaciones con los modelos relacionados
            ->get();

        // Agregar el arreglo de artículos asignados al resultado
        $docente->articulos = $articulosAsignados;
    }

    return response()->json($results);
}



    


    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $rules = [
        'email' => 'required|email|unique:empleado,email', // La regla unique se encarga de verificar que el correo no esté repetido en la tabla 'empleados'
        'rfc' => 'unique:docente,rfc', // La regla unique se encarga de verificar que el RFC no esté repetido en la tabla 'docente'
    ];

    // Define los mensajes de error personalizados
    $messages = [
        'email.unique' => 'El correo electrónico ya está en uso.',
        'rfc.unique' => 'El RFC ya está en uso.'
    ];

    // Valida los datos del formulario
    $validator = Validator::make($request->all(), $rules, $messages);

    // Comprueba si hubo errores de validación
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    DB::beginTransaction();

    try {
        // Insertar en la tabla 'empleado'
        $empleadoData = [
            'rfc' => $request->input('rfc'),
            'nombre' => $request->input('nombre'),
            'apellido_m' => $request->input('apellido_m'),  
            'apellido_p' => $request->input('apellido_p'),  
            'email' => $request->input('email'),  
            'telefono' => $request->input('telefono')
        ];
        $empleado = empleado::create($empleadoData);
        $idempleado = $empleado->idempleado;
        if ($request->filled('idusuario') && $request->filled('password')) {
            // Lógica para insertar en la tabla 'usuario'
            $usuarioData = [
                'idusuario' => $request->input('idusuario'), 
                'rfc' => $request->input('rfc'),
                'password' => $request->input('password'),
                'rol' => $request->input('rol'),
                'restart' => 0 
                // Otros campos necesarios
            ];
            usuarios::create($usuarioData);
        

            // Lógica para insertar en la tabla 'responsable'
            $responsableData = [
                'rfc' => $request->input('rfc'), 
                'puesto' => $request->input('puesto'), 
            ];
            responsable::create($responsableData);
        }
        if ($request->filled('nocubiculo') && $request->filled('perfil')) {
            // Lógica para insertar en la tabla 'docente'
            $docenteData = [
                'rfc' => $request->input('rfc'),
                'no_cubiculo' => $request->input('nocubiculo'),       
                'perfil' => $request->input('perfil'), 
                // Otros campos necesarios
            ];
            docente::create($docenteData);
        }
        DB::commit();

        return response()->json($empleado, 201);
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
public function update(Request $request, string $rfc)
{
    
    DB::beginTransaction();

    try {
        // Buscar el empleado
        $empleado = empleado::findOrFail($rfc);

        // Actualizar los datos del empleado
        $empleado->nombre = $request->nombre;
        $empleado->apellido_p = $request->apellido_p;
        $empleado->apellido_m = $request->apellido_m;
        $empleado->email = $request->email;
        $empleado->telefono = $request->telefono;
        $empleado->update();

        $inprol=$request->input('puesto');
        if($inprol!=''){
            // Actualizar datos del responsable asociado al empleado (si existe)
            $responsable = responsable::where('rfc', $empleado->rfc)->first();
            if ($responsable) {
                // Actualizar datos del usuario asociado al responsable
                $usuarioResponsable = usuarios::where('rfc', $responsable->rfc)->first();
                if ($usuarioResponsable) {
                    $inppass= $request->input('password');
                    if ($inppass!='') {
                        $usuarioResponsable->update([
                            'password' => $request->input('password'),
                            'rol' => $request->input('rol'),
                            // Otros campos necesarios
                        ]);
                    }else{
                        $usuarioResponsable->update([
                            'rol' => $request->input('rol'),
                            // Otros campos necesarios
                        ]);
                    }

                    
                }
                // Actualizar datos del docente
                responsable::where('rfc', $request->input('rfc'))
                ->update(
                    ['puesto' =>$request->input('puesto')]);

            }else{
                $responsableData = [
                    'rfc' => $request->input('rfc'), 
                    'puesto' => $request->input('puesto'), 
                ];
                responsable::create($responsableData);
                $usuarioData = [
                    'idusuario' => $request->input('idusuario'), 
                    'rfc' => $request->input('rfc'),
                    'password' => $request->input('password'),
                    'rol' => $request->input('rol'),
                    'restart' => 0 
                    // Otros campos necesarios
                ];
                usuarios::create($usuarioData);
            }

           
         }

         $inrfc=$request->input('perfil');
        if($inrfc!=''){
            // Actualizar datos del docente asociado al empleado (si existe)
            $docente = docente::where('rfc', $empleado->rfc)->first();
            if ($docente) {
                // Actualizar datos del docente
                docente::where('rfc', $empleado->rfc)
                ->update(
                    ['perfil' =>$request->input('perfil')],
                ['no_cubiculo' =>$request->input('nocubiculo')]);
            }
            else{
                $docenteData = [
                    'rfc' => $request->input('rfc'),
                    'no_cubiculo' => $request->input('nocubiculo'),       
                    'perfil' => $request->input('perfil'), 
                    // Otros campos necesarios
                ];
                docente::create($docenteData);
            }
        }
        DB::commit();

        return response()->json($empleado, 200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['message' => 'Error en la transacción: ' . $e], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $rfc)
    {
        DB::beginTransaction();
        try {
            $empleado = empleado::findOrFail($rfc);
            $usuario = usuarios::where('rfc', $empleado->rfc)->first();
            if ($usuario) {
                $usuario->delete();
            }
            $empleado->delete();
            DB::commit();
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
    
