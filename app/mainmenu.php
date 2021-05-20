<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mainmenu extends Model
{
    protected $table= 'mainmenu';
    protected $primaryKey = 'mainmenuID';

    public function submenu()
	{
	    return $this->hasMany('App\submenu', 'mainmenuID');

	}
}
