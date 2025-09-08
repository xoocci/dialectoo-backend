<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulos_devueltos extends Model
{
    use HasFactory;
    protected $table = 'articulos_devueltos';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idalmacen','rfc_docente', 'rfc_responsable', 'idarticulo', 'identificador_articulo', 'fecha'];

    public function almacen(){
        return $this->hasOne(almacen::class, 'idalmacen','idalamacen');
    }
    public function responsable()
    {
        return $this->belongsTo(responsable::class, 'rfc_responsable');
    }
    public function detalleResponsableDev()
    {
        return $this->belongsTo(responsable::class, 'rfc_responsable');
    }

}
