<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumible extends Model
{
    use HasFactory;
    protected $table = 'consumible';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idarticulo', 'presentacion'];

    public function articulo()
    {
        return $this->belongsTo(articulo::class, 'idarticulo');
    }
}
