<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class personaltarget extends Model
{
    protected $table = 'personaltarget';
    protected $primaryKey = 'personaltargetID';
    protected $fillable = ['userID', 'month', 'year'];
}
