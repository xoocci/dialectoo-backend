<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulos_asignados extends Model
{
    use HasFactory;
    protected $table = 'articulos_asignados';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idalmacen','rfc_docente', 'rfc_responsable', 'idarticulo', 'identificador_articulo', 'fecha','estado'];

    public function articulo()
    {
        return $this->hasOne(articulo::class, 'idarticulo', 'idarticulo');
    }

    public function almacen(){
        return $this->hasOne(almacen::class, 'idalmacen','idalamacen');
    }

    public function responsable()
    {
        return $this->hasOne(empleado::class, 'rfc', 'rfc_responsable');
    }
    
    public function docente()
    {
        return $this->hasOne(empleado::class)
                    ->join('docente', 'empleado.rfc', '=', 'docente.rfc')
                    ->where('docente.rfc', '=', 'articulos_asignados.rfc_docente');
    }

    public function identificadores_articulo()
    {
        return $this->hasOne(identificadores_articulo::class, 'identificador_articulo', 'identificador_articulo');
    }

    public function responsableAsi()
    {
        return $this->belongsTo(responsable::class, 'rfc_responsable');
    }

}
