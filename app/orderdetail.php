<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderdetail extends Model
{
    protected $table = 'orderdetail';
    protected $primaryKey = 'orderIncID';

    public function customer()
	{
	    return $this->hasOne('App\customer', 'customerID', 'customerID');
	}

	public function orderpayment()
	{
	    return $this->hasMany('App\orderpayments', 'orderID', 'orderID');
	}

	public function orderitem()
	{
	    return $this->hasMany('App\orderitem', 'orderID', 'orderID');
	}

	public function getseller()
	{
		return $this->hasOne('App\user', 'id', 'userID');
	}

	public function getstore()
	{
		return $this->hasOne('App\store', 'store_id', 'storeID');
	}

	public function refundorder()
	{
		return $this->hasMany('App\refundorderdetail', 'orderID', 'orderID');
	}
	public function refundorderitemddata()
	{
		return $this->hasMany('App\refundorderitem', 'orderID', 'orderID');
	}
}
