<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class empleado extends Model
{
    use HasFactory;
    protected $table = 'empleado';
    public $timestamps = false;
    protected $primaryKey = 'rfc';
    protected $keyType = 'string';
    protected $fillable=['rfc','nombre','apellido_p','apellido_m','email','telefono'];

    public function docente()
    {
        return $this->hasOne(docente::class, 'rfc');
    }
}
