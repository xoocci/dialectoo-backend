<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articulo extends Model
{
    use HasFactory;
    protected $table = 'articulo';
    protected $primaryKey = 'idarticulo';
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable=['idarticulo','nombre','descripcion','marca','color','stock','stockmin','stockmax'];

    public function eqcomp()
    {
        return $this->hasOne(Eqcomp::class, 'idarticulo');
    }

    public function actfijo()
    {
        return $this->hasOne(Actfijo::class, 'idarticulo');
    }

    public function consumible()
    {
        return $this->hasOne(Consumible::class, 'idarticulo');
    }

    public function almacen_articulo()
{
    return $this->hasMany(almacen_articulo::class, 'idarticulo')
                ->join('almacen', 'almacen.idalmacen', '=', 'almacen_articulo.idalmacen')
                ->select('almacen_articulo.*', 'almacen.nombre as nombre_almacen');
}

   public function identificadoresArticulo()
{
    return $this->hasMany(identificadores_articulo::class, 'idarticulo')->where('estado', 'Disponible');
}
public function identificadoresArticuloComp()
{
    return $this->hasMany(identificadores_articulo::class, 'idarticulo');
}
}
