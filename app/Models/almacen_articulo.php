<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class almacen_articulo extends Model
{
    use HasFactory;

    protected $table = 'almacen_articulo';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idalmacen', 'idarticulo', 'cantidad'];

    public function almacen()
    {
        return $this->belongsTo(almacen::class, 'idalmacen');
    }
}
