<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class refundorderdetail extends Model
{
    protected $table = 'refundorderdetail';
    protected $primaryKey = 'refundInvIncID';

    public function customer()
	{
	    return $this->hasOne('App\customer', 'customerID', 'customerID');
	}

	public function orderitem()
	{
	    return $this->hasMany('App\refundorderitem', 'refundInvoiceID', 'refundInvoiceID');
	}

	public function orderpayment()
	{
	    return $this->hasMany('App\refundorderpayments', 'refundInvoiceID', 'refundInvoiceID');
	}

	public function refundbyuser()
	{
	    return $this->hasOne('App\loggeduser', 'id', 'refundBy');
	}

	public function refunditems()
	{
	    return $this->hasMany('App\refundorderitem', 'refundInvoiceID', 'refundInvoiceID');
	}
}
