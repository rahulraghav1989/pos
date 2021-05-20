<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mastercolour extends Model
{
    protected $table= 'mastercolour';
    protected $primaryKey= 'colourID';

    public function get_mastercolour_by_id($colourID)
	{
		return mastercolour::find($colourID);
	}
}
