<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usuarioController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
//empleado
Route::resource('Empleado', 'App\Http\Controllers\empleadoController');
Route::resource('Docente', 'App\Http\Controllers\docenteController');
Route::get('/Docente/getByEmpleadoId/{idEmpleado}', 'App\Http\Controllers\docenteController@getByEmpleadoId');
Route::resource('Responsable', 'App\Http\Controllers\responsableController');
Route::get('/Responsable/getByEmpleadoId/{idEmpleado}', 'App\Http\Controllers\responsableController@getByEmpleadoId');
Route::get('/Empleado/search/{query}', 'App\Http\Controllers\empleadoController@search');
Route::get('/Empleado/searchdetal/{query}', 'App\Http\Controllers\empleadoController@searchdetal');

Route::get('/Empleado/searchEmpArt/{query}', 'App\Http\Controllers\empleadoController@searchEmpArt');
//usuario
Route::resource('Usuario', 'App\Http\Controllers\usuarioController');
//login fail
Route::post('passport', 'App\Http\Controllers\API\PassportAuthController@login');
//articulos
Route::resource('Articulo', 'App\Http\Controllers\articuloController');
Route::resource('Eqcomp', 'App\Http\Controllers\eqcompController');
Route::resource('Actfijo', 'App\Http\Controllers\actfijoController');
Route::resource('Consumible', 'App\Http\Controllers\consumibleController');
Route::get('/Articulo/search/{query}', 'App\Http\Controllers\articuloController@search');
Route::get('/Articulo/search1/{query}', 'App\Http\Controllers\articuloController@search1');
Route::get('/Articulo/searchinf/{query}', 'App\Http\Controllers\articuloController@searchinf');
Route::get('/Articulo/search11/{query}', 'App\Http\Controllers\articuloController@search11');
Route::get('/Articulo/search111/{query}', 'App\Http\Controllers\articuloController@search111');

Route::get('/Articulo/search111C/{query}', 'App\Http\Controllers\articuloController@search111C');




Route::get('/Articulo/search111CC/{query}', 'App\Http\Controllers\articuloController@search111CC');
//
Route::get('/Eqcomp/getByArticuloId/{idArticulo}', 'App\Http\Controllers\eqcompController@getByArticuloId');
Route::get('/Actfijo/getByArticuloId/{idArticulo}', 'App\Http\Controllers\actfijoController@getByArticuloId');
Route::get('/Consumible/getByArticuloId/{idArticulo}', 'App\Http\Controllers\consumibleController@getByArticuloId');

//almacen
Route::resource('Almacen', 'App\Http\Controllers\almacenController');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//asignacion
Route::resource('Asignacion', 'App\Http\Controllers\asignacionController');
Route::get('/Asignacion/getEmpleadoDocenteById/{idDocente}', 'App\Http\Controllers\asignacionController@getEmpleadoDocenteById');
Route::get('/Asignacion/getEmpleadoResponsableById/{idResponsable}', 'App\Http\Controllers\asignacionController@getEmpleadoResponsableById');
Route::get('/Asignacion/getArticuloById/{idArticulo}', 'App\Http\Controllers\asignacionController@getArticuloById');
//pretamo
Route::resource('Prestamo', 'App\Http\Controllers\articulos_prestadosController');

//devolucion
Route::resource('Devolucion', 'App\Http\Controllers\devolucionController');

//salida
Route::resource('Salida', 'App\Http\Controllers\salidaController');
Route::resource('DetalleSalida', 'App\Http\Controllers\detalle_salidaController');
//ingreso
Route::resource('Ingreso', 'App\Http\Controllers\ingresoController');
Route::resource('DetalleIngreso', 'App\Http\Controllers\detalle_ingresoController');
//registro
Route::resource('Registro', 'App\Http\Controllers\registroController');
//baja
Route::resource('Baja', 'App\Http\Controllers\bajaController');
//Login
//Route::post('/auth/authenticate', 'App\Http\Controllers\Auth\LoginController');

Route::post('/login', [usuarioController::class, 'login']);
Route::post('/usuarios', [usuarioController::class, 'store']);
Route::put('/usuarios/{id}', [usuarioController::class, 'update']);
Route::delete('/usuarios/{id}', [usuarioController::class, 'destroy']);

Route::get('/Articulo_eqc', function () {
    return DB::table('articulos_eqcomp')->get();
});

Route::get('/prestamos_vencidos', function () {
    return DB::table('prestamos_vencidos')->get();
});

Route::get('/Articulo_act', function () {
    return DB::table('articulos_actfijo')->get();
});
Route::get('/Inventario', function () {
    return DB::table('inventario')->get();
});

Route::get('/Salida', function(){
    return DB::table('salida')->get();
});

Route::get('/Ingreso', function(){
    return DB::table('entrada')->get();
});

Route::get('/Articulo_cons', function () {
    return DB::table('articulos_consumible')->get();
});

Route::get('/Articulo_alm_ca/{idarticulo}', function ($idarticulo) {
    return DB::table('articulos_en_almacenes')->where('idarticulo', $idarticulo)->get();
});

Route::get('/Articulo_e_idt_u/{idarticulo}', function ($idarticulo) {
    return DB::table('articulos_identificadores_unicos')->where('idarticulo', $idarticulo)->get();
});

Route::get('/art_y_s_alm/{idarticulo}', function ($idarticulo) {
    return DB::table('almacenes_contienen_articulos')->where('idarticulo', $idarticulo)->get();
});

Route::get('/ingresos', function (Request $request) {
    $fechaInicio = $request->input('fechaInicio');
    $fechaFin = $request->input('fechaFin');

    // Consulta a la base de datos filtrando por las fechas
    $ingresos = DB::table('ingresos')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->get();

    return $ingresos;
});

Route::get('/emp_asig_pres_art/{query}', function ($query) {
    return DB::table('emplados_asignacion_prestamo_articulos')
                ->where('rfc_docente', '=', $query)
                ->get();
});
Route::get('/emp_art_asig/{query}', function ($query) {
    return DB::table('empleado_articulos_asignados_sd')
                ->where('rfc_docente', '=', $query)
                ->get();
});

Route::get('/idt_t_arts/{query}', function ($query) {
    return DB::table('identifcadores_total_articulos')
                ->where('tipo', '=', $query)
                ->get();
});

Route::get('/idt_t_artstockEQ', function () {
    return DB::table('total_articulos_eqcomp')
                ->get();
});

Route::get('/idt_t_artstockAF', function () {
    return DB::table('total_articulos_actfijo')
                ->get();
});

Route::get('/idt_t_artsT', function () {
    return DB::table('identifcadores_total_articulos')
                ->get();
});

    Route::get('/totl_artlos_cnsbles', function () {
        return DB::table('total_articulos_consumibles')
                    ->get();
    });


Route::get('/emp_asig_dvlts_art_comp', function () {
    return DB::table('emplados_asignacion_devueltas_articulos')->get();
});

Route::get('/emp_prest_art_comp', function () {
    return DB::table('emplados_prestamos_articulos')->get();
});

Route::get('/empls_devn_arts', function () {
    return DB::table('emplados_devolucion_articulos')->get();
});
Route::get('/cargarNI', function () {
    return DB::table('cargarni')->get();
});
Route::get('/cargarNS', function () {
    return DB::table('cargarns')->get();
});

//->where('rfc_docente', '=', $query)


Route::resource('almacen_articulos', 'App\Http\Controllers\almacen_articuloController');

Route::get('/emp_con_resp', function () {
    return DB::table('empleado_con_responsable')->get();
});
Route::get('/emp_con_doce', function () {
    return DB::table('empleado_con_docente')->get();
});
Route::get('/almacenes', function () {
    return DB::table('almacen')->get();
});
Route::get('/dev_emp_arti', function () {
    return DB::table('emplados_devolucion_articulos')->get();
});

