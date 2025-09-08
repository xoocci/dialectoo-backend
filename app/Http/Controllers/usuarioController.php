<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\empleado;
use App\Models\responsable;
use App\Models\usuarios;
use App\Models\User;

class usuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $usuarios = User::select(
        'usuario.*',
        'usuario.idusuario as nombreusuario'
    )->get();
    
    foreach ($usuarios as $usuario) {
        //$responsable = responsable::where('idusuario', $usuario->idusuario)->first();
       // $empleado = empleado::where('idempleado', $responsable->idempleado)->first();
        //$usuario->responsable = $responsable;
       // $usuario->empleado = $empleado;
    }

    return response()->json($usuarios);
}



    public function login(Request $request)
{
    try {
        $idusuario = $request->input('idusuario');
        $password = $request->input('password');

        //$credentials = compact('idusuario', 'password');
        //return response()->json(Auth::attempt($credentials));
        //if (Auth::attempt($credentials)) {
            
            $user = User::where('idusuario', $idusuario)->first();

            if ($user) {
                // Si el usuario existe, comprobar si la contraseña coincide
                if ($password === $user->password) {
                    $empleado = empleado::where('email', $idusuario)
                    ->first();
                    $responsable = responsable::where('rfc', $empleado->rfc)
                    ->first();
                    return response()->json([
                        //'token' => $token,
                        'user_info' => $user,
                        'empleado_info' => $empleado,
                        'responsable_info' => $responsable,
                    ], 200);
                }else {
                    // La contraseña proporcionada es incorrecta
                    return response()->json(['error' => 'Contraseña incorrecta'], 401);
                }
            }
                // El usuario no existe
                return response()->json(['error' => 'Usuario no encontrado'], 401);
    
           
       // }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error interno del servidor', 'message' => $e->getMessage()], 500);
    }
}




    public function getByResponsableId($idusuario)
{
          // Convertir $idEmpleado a entero
    $idEmpleado = (int)$idusuario;
    // Buscar un docente por el idempleado
    $usuario = resp::where('idusuario', $idusuario)->first();

    // Verificar si se encontró un docente
    if ($usuario) {
        return response()->json($usuario);
    } else {
        // Si no se encuentra, devolver una respuesta adecuada (puede ser un código HTTP 404)
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }
}
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return usuarios::create($request->all());
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
        $us= usuarios::findOrFail($id);
        $us->password=$request->password;
        $us->rol=$request->rol;
        $us->restart=$request->restart;
        $us->update();
        return $us;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $us= User::findOrFail($id);
        $us->delete();
    }
}
