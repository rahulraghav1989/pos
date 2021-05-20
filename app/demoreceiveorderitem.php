<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class demoreceiveorderitem extends Model
{
    protected $table = 'demoreceiveorderitem';
    protected $primaryKey = 'drorderitemID';

    public function drreceiveproduct()
	{
	    return $this->hasOne('App\product', 'productID', 'productID');
	}
}
