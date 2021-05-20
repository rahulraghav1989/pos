<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productstockgroup extends Model
{
    protected $table='productstockgroup';
    protected $primaryKey='productSGID';

    public function stockgrouptype()
	{
	    return $this->hasOne('App\masterstockgroup', 'stockgroupID', 'stockgroupID');
	}
}
