<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productpurchasereceivedetails extends Model
{
    protected $table = 'productpurchasereceivedetails';
    protected $primaryKey = 'pprdID';

    public function get_productpurchaseorder()
    {
    	return $this->belongsTo('App\productpurchaseorder','ponumber','ponumber');
    }
}
