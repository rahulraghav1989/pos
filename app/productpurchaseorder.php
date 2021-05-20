<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productpurchaseorder extends Model
{
    protected $table = 'productpurchaseorder';
    protected $primaryKey = 'poID';

    public function poitem()
	{
	    return $this->hasMany('App\productpurchaseorderitem', 'ponumber', 'ponumber');
	}

	public function posupplier()
	{
	    return $this->hasOne('App\mastersupplier', 'supplierID', 'supplierID');
	}

	public function get_store()
	{
	    return $this->hasOne('App\store', 'store_id', 'storeID');
	}

	public function get_user()
	{
	    return $this->hasOne('App\User', 'id', 'userID');
	}

	public function get_po_received()
	{
	    return $this->hasMany('App\productpurchasereceivedetails', 'ponumber', 'ponumber');
	}

	public function getproductstock()
	{
	    return $this->hasMany('App\productstock', 'ponumber', 'ponumber');
	}
}
