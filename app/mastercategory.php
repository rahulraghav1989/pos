<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mastercategory extends Model
{
    protected $table='mastercategory';
    protected $primaryKey='categoryID';

    public function subcategory()
	{
	    return $this->hasMany('App\mastersubcategory', 'categoryID');
	}

	public function get_mastercategory_by_id($categoryID)
    {
        return mastercategory::find($categoryID);
    }
}
