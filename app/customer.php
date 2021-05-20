<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customerID';

    public function get_customer_by_id($customerID)
    {
        return customer::find($customerID);
    }
}
