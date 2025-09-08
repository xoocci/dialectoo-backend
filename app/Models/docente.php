<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class docente extends Model
{
    use HasFactory;
    protected $table = 'docente';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['rfc', 'perfil', 'no_cubiculo'];

    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'rfc');
    }
}
