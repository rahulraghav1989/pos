<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loggeduser extends Model
{
    protected $table='users';
    protected $primaryKey = 'id';
}
