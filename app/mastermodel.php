<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mastermodel extends Model
{
    protected $table= 'mastermodel';
    protected $primaryKey= 'modelID';

    public function get_mastermodel_by_id($modelID)
    {
        return mastermodel::find($modelID);
    }
}
