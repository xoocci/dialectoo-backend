<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class almacen extends Model
{
    use HasFactory;
    protected $table = 'almacen';
    protected $primaryKey = 'idalmacen';
    public $timestamps = false;
    protected $fillable=['nombre'];
}

