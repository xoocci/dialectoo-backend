<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;


class usuarios extends Model  
{
    use HasApiTokens, HasFactory;
    protected $table = 'usuarios';
    protected $primaryKey = 'idusuario';
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [ 'idusuario','rfc','password','rol', 'restart'];

    public function responsable()
    {
        return $this->hasOne(responsable::class, 'idusuario');
    }
}
