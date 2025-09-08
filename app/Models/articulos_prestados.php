<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulos_prestados extends Model
{
    use HasFactory;
    protected $table = 'articulos_prestados';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idalmacen','rfc_docente','rfc_responsable','idarticulo','identificador_articulo','fecha','hora','materia','aula','observaciones','estado'];

    public function almacen(){
        return $this->hasOne(almacen::class, 'idalmacen','idalamacen');
    }
}
