<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class refundorderitem extends Model
{
    protected $table = 'refundorderitem';
    protected $primaryKey = 'refunditemID';

    public function productstock()
	{
	    return $this->hasOne('App\productstock', 'psID', 'stockID');
	}
}
