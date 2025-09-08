<?php

// app/Models/usuario.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';
    protected $primaryKey = 'idusuario';
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['idusuario','rfc' ,'password','rol', 'restart'];
}

