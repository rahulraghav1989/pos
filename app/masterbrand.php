<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class masterbrand extends Model
{
   protected $table= 'masterbrand';
   protected $primaryKey= 'brandID';

	public function get_masterbrand_by_id($brandID)
	{
		return masterbrand::find($brandID);
	}
}
