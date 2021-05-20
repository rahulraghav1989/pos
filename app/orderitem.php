<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderitem extends Model
{
    protected $table = 'orderitem';
    protected $primaryKey = 'orderitemID';

    public function productstock()
	{
	    return $this->hasOne('App\productstock', 'psID', 'stockID');
	}

	public function get_orderdetail()
	{
		return $this->belongsTo('App\orderdetail','orderID','orderID');
	}

	public function get_product()
	{
		return $this->belongsTo('App\product','productID','productID');
	}

	public function orderpayment()
	{
	    return $this->hasMany('App\orderpayments', 'orderID', 'orderID');
	}
}
