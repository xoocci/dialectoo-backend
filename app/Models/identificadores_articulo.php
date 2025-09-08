<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class identificadores_articulo extends Model
{
    use HasFactory;

    protected $table = 'identificadores_articulo';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idarticulo', 'identificador_articulo', 'numero_serie', 'estado'];
}
