<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PassportAuthController extends Controller
{
    public function index()
    {
        return User::all();
    }
    public function register (Request $request){
        $this-›validate($request,[
            'idusuario' =>'required|min:4',
            'password' =>'required|min:4',
        ]);
        $user=User::create([
            'idusuario' =>'required|min:4',
            'password' =>'required|min:4',
        ]);
        $token= $user -> createToken('Laravel8PassportAuth')->accessToken;
        return response()->json(['token'=> $token], 200);
    }
    public function login(Request $request)
    {
        $data = [
            'idusuario' => $request->input('idusuario'),
            'password' => $request->input('password')
        ];
        if (Auth::attempt($data)) {
            //$user = User::where('idusuario', $idusuario)->first();///,'user_info' => $user, 
            $token = auth()->user()->createToken('Laravel8PassportAuth')->accessToken;

            return response()->json([ 'token' => $token], 200);
        }else{
            return response()->json([ 'error' => 'no Autorizado'], 401);
        }
    
    } 
    public function userInfo(){
        $user = auth()->user();
        return response()->json([ 'user' => $user], 200);
    }  

}
