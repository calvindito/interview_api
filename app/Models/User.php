<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract {

    use Authenticatable, Authorizable, HasApiTokens;

    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $hidden     = ['password'];
    protected $fillable   = [
        'email',
        'password'
    ];

}
