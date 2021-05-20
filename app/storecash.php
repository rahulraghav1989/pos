<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class storecash extends Model
{
    protected $table = 'storecash';
    protected $primaryKey = 'storecashID';

    public function storecashoutuser()
	{
	    return $this->hasOne('App\User', 'id', 'storecashoutUser');
	}
}
