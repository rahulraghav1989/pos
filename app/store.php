<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class store extends Model
{
    protected $table='store';
    protected $primaryKey = 'store_id';

    public function get_store_by_id($store_id)
    {
    	return Store::find($store_id);
    }
}
