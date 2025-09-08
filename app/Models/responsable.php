<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class responsable extends Model
{
    use HasFactory;
    protected $table = 'responsable';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['rfc', 'puesto'];

    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'rfc');
    }

    public function usuario()
    {
        return $this->belongsTo(usuario::class, 'idusuario');
    }
}
