<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eqcomp extends Model
{
    use HasFactory;
    protected $table = 'eqcomp';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idarticulo', 'modelo'];

    public function articulo()
    {
        return $this->belongsTo(articulo::class, 'idarticulo');
    }
}
