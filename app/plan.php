<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class plan extends Model
{
    protected $table = 'plan';
    protected $primaryKey= 'planID';

    public function plantypere()
	{
	    return $this->hasOne('App\masterplantype', 'plantypeID', 'plantypeID');
	}

	public function plantermre()
	{
	    return $this->hasOne('App\masterplanterm', 'plantermID', 'planterm');
	}

	public function plancategoryre()
	{
	    return $this->hasOne('App\masterplancategory', 'pcID', 'plancategoryID');
	}

	public function planpropositionre()
	{
	    return $this->hasOne('App\masterplanpropositiontype', 'planpropositionID', 'planpropositionID');
	}

	public function planstockgroupre()
	{
	    //return $this->hasOne('App\masterstockgroup');
			//return masterstockgroup::whereIn('stockgroupID', [1,2]);
	    /*Event::whereHas('participants', function ($query) 
	    {
	    		$query->where('IDUser', '=', 1);
		})
		->get();*/

		/*masterstockgroup::whereIn('stockgroupID', function ($query) 
	    {

	    		$query->{
	    			plan::select('stockgroupID')->where('planstockgroup', '=', 'planstockgroup')};
	    		
		})
		->get();*/
	}
}
