<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class demoreceiveorder extends Model
{
    protected $table = 'demoreceive';
    protected $primaryKey = 'receiveID';

    public function demoitem()
	{
	    return $this->hasMany('App\demoreceiveorderitem', 'receiveInvoiceID', 'receiveInvoiceID');
	}

	public function demosupplier()
	{
	    return $this->hasOne('App\mastersupplier', 'supplierID', 'supplierID');
	}
}
