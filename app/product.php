<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $table='products';
    protected $primaryKey='productID';

    public function productbrand()
	{
	    return $this->hasOne('App\masterbrand', 'brandID', 'brand');
	}

	public function productcolour()
	{
	    return $this->hasOne('App\mastercolour', 'colourID', 'colour');
	}

	public function productmodel()
	{
	    return $this->hasOne('App\mastermodel', 'modelID', 'model');
	}

	public function productcategory()
	{
	    return $this->hasOne('App\mastercategory', 'categoryID', 'categories');
	}

	public function productsubcategory()
	{
	    return $this->hasOne('App\mastersubcategory', 'subcategoryID', 'subcategory');
	}

	public function productsupplier()
	{
	    return $this->hasOne('App\mastersupplier', 'supplierID', 'supplierID');
	}

	public function productstock()
	{
	    return $this->hasMany('App\productstock', 'productID', 'productID');
	}

	public function producttypedata()
	{
	    return $this->hasOne('App\masterproducttype', 'producttypeID', 'producttype');
	}

	public function productstockgroup()
	{
	    return $this->hasMany('App\productstockgroup', 'productID', 'productID');
	}

	public function productaddedby()
	{
	    return $this->hasOne('App\loggeduser', 'id', 'userID');
	}
}
