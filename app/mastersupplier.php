<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mastersupplier extends Model
{
    protected $table= 'mastersupplier';
    protected $primaryKey= 'supplierID';

    public function get_mastersupplier_by_id($supplierID)
    {
        return mastersupplier::find($supplierID);
    }
}
