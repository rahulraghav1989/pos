<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productpurchaseorderitem extends Model
{
    protected $table = 'productpurchaseorderitem';
    protected $primaryKey = 'poitemID';

    public function poreceiveproduct()
	{
	    return $this->hasOne('App\product', 'productID', 'productID');
	}

	public function poreceivepurchaseorder()
	{
	    return $this->hasOne('App\productpurchaseorder', 'ponumber', 'ponumber');
	}
}
