<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actfijo extends Model
{
    use HasFactory;
    protected $table = 'actfijo';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['idarticulo', 'tipo'];

    public function articulo()
    {
        return $this->belongsTo(articulo::class, 'idarticulo');
    }
}
