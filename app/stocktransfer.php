<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stocktransfer extends Model
{
    protected $table = 'stocktransfer';
    protected $primaryKey = 'stocktransferAutoID';

    public function tostore()
	{
	    return $this->hasOne('App\store', 'store_id', 'toStoreID');
	}

	public function fromstore()
	{
	    return $this->hasOne('App\store', 'store_id', 'fromStoreID');
	}

	public function touser()
	{
	    return $this->hasOne('App\User', 'id', 'toUserID');
	}
}
