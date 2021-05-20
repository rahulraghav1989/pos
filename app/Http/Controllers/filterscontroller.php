<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use Tracker;
use Session;
use Validator;

class filterscontroller extends Controller
{
    public function pofilter(Request $request)
    {
        $store = $request->input('store');
        $supplier = $request->input('supplier');
        $postatus = $request->input('postatus');
    	$startdate = $request->input('startdate');
    	$enddate = $request->input('enddate');

    	$pofilterdata = ['store'=>$store, 'supplier'=>$supplier, 'postatus'=>$postatus, 'startdate'=>$startdate, 'enddate'=>$enddate];

    	session::put('pofilterdata', $pofilterdata);

    	return redirect()->back();
    }

    public function poreceivefilter(Request $request)
    {
        $store = $request->input('store');
        $supplier = $request->input('supplier');
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');

        $poreceivefilter = ['store'=>$store, 'supplier'=>$supplier, 'startdate'=>$startdate, 'enddate'=>$enddate];

        session::put('poreceivefilter', $poreceivefilter);

        return redirect()->back();
    }

    public function poincomfilter(Request $request)
    {
        $store = $request->input('store');
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');

        $poincomfilter = ['store'=>$store, 'startdate'=>$startdate, 'enddate'=>$enddate];

        session::put('poincomfilter', $poincomfilter);

        return redirect()->back();
    }

    public function timesheetfilter(Request $request)
    {
    	$userID = $request->input('userid');
    	$month = $request->input('month');
    	$year = $request->input('year');
        $datefrom = $request->input('datefrom');
        $dateto = $request->input('dateto');

    	$timesheetfilterdata = ['userID'=>$userID, 'month'=>$month, 'year'=>$year, 'datefrom'=>$datefrom, 'dateto'=>$dateto];

    	session::put('timesheetfilterdata', $timesheetfilterdata);

    	return redirect()->back();
    }

    public function cleartimesheetfilter(Request $request)
    {
    	session::forget('timesheetfilterdata');
    	//session::put('timesheetfilterdata', $timesheetfilterdata);

    	return redirect()->back();
    }

    public function usertrackerfilter(Request $request)
    {
        $userID = $request->input('userid');
        $month = $request->input('month');
        $year = $request->input('year');

        $usertrackerfilter = ['userID'=>$userID, 'month'=>$month, 'year'=>$year];

        session::put('usertrackerfilter', $usertrackerfilter);

        return redirect()->back();
    }

    public function storetrackerfilter(Request $request)
    {
        $storeID = $request->input('storeid');
        $month = $request->input('month');
        $year = $request->input('year');

        $storetrackerfilter = ['storeID'=>$storeID, 'month'=>$month, 'year'=>$year];

        session::put('storetrackerfilter', $storetrackerfilter);

        return redirect()->back();
    }

    public function personaltargetfilter(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $personaltargetfilter = ['month'=>$month, 'year'=>$year];

        session::put('personaltargetfilter', $personaltargetfilter);

        return redirect()->back();
    }

    public function storetargetfilter(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $storetargetfilter = ['month'=>$month, 'year'=>$year];

        session::put('storetargetfilter', $storetargetfilter);

        return redirect()->back();
    }

    public function salesbyuserfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');
        $supplier = $request->input('supplier');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $colour = $request->input('colour');
        $category = $request->input('category');

        $salesbyuserfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user, 'supplier'=>$supplier, 'brand'=>$brand, 'model'=>$model, 'colour'=>$colour, 'category'=>$category];

        session::put('salesbyuserfilter', $salesbyuserfilter);

        return redirect()->back();
    }

    public function salesbypaymentmethodfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');
        $payoptions = $request->input('payoptions');
        $saletype = $request->input('saletype');

        $salesbypaymentmethodfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user, 'payoptions'=>$payoptions, 'saletype'=>$saletype];

        session::put('salesbypaymentmethodfilter', $salesbypaymentmethodfilter);

        return redirect()->back();
    }

    public function salesmasterfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');
        $supplier = $request->input('supplier');
        $category = $request->input('category');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $colour = $request->input('colour');

        $salesmasterfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user, 'supplier'=>$supplier, 'category'=>$category, 'brand'=>$brand, 'model'=>$model, 'colour'=>$colour];

        session::put('salesmasterfilter', $salesmasterfilter);

        return redirect()->back();
    }

    public function salesconnectionfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');

        $plantype = $request->input('plantype');
        $planproposition = $request->input('planproposition');
        $plancategory = $request->input('plancategory');
        $planterm = $request->input('planterm');
        $planhandsetterm = $request->input('planhandsetterm');


        $salesconnectionfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user, 'plantype'=>$plantype, 'planproposition'=>$planproposition, 'plancategory'=>$plancategory, 'planterm'=>$planterm, 'planhandsetterm'=>$planhandsetterm];

        session::put('salesconnectionfilter', $salesconnectionfilter);

        return redirect()->back();
    }

    public function userprofitfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');

        $userprofitfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user];

        session::put('userprofitfilter', $userprofitfilter);

        return redirect()->back();
    }

    public function categoryprofitfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');

        $categoryprofitfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user];

        session::put('categoryprofitfilter', $categoryprofitfilter);

        return redirect()->back();
    }

    public function connectionprofitfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');

        $connectionprofitfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user];

        session::put('connectionprofitfilter', $connectionprofitfilter);

        return redirect()->back();
    }

    public function stockhistoryfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');

        $stockhistoryfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user];

        session::put('stockhistoryfilter', $stockhistoryfilter);

        return redirect()->back();
    }

    public function reportstocktransferfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');

        $reportstocktransferfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user];

        session::put('reportstocktransferfilter', $reportstocktransferfilter);

        return redirect()->back();
    }

    public function stockreturnfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $supplier = $request->input('supplier');
        $returnstatus = $request->input('returnstatus');

        $stockreturnfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'supplier'=>$supplier, 'returnstatus'=>$returnstatus];

        session::put('stockreturnfilter', $stockreturnfilter);

        return redirect()->back();
    }

    public function reportstockreturnfilter(Request $request)
    {
        $firstday = $request->input('startdate');
        $lastday = $request->input('enddate');
        $store = $request->input('store');
        $user = $request->input('user');
        $supplier = $request->input('supplier');
        $returnstatus = $request->input('returnstatus');

        $reportstockreturnfilter = ['firstday'=>$firstday, 'lastday'=>$lastday, 'store'=>$store, 'user'=>$user, 'supplier'=>$supplier, 'returnstatus'=>$returnstatus];

        session::put('reportstockreturnfilter', $reportstockreturnfilter);

        return redirect()->back();
    }

    public function reportstockholdingfilter(Request $request)
    {
        $store = $request->input('store');
        $supplier = $request->input('supplier');
        $category = $request->input('category');

        $reportstockholdingfilter = ['store'=>$store, 'supplier'=>$supplier, 'category'=>$category];

        session::put('reportstockholdingfilter', $reportstockholdingfilter);

        return redirect()->back();
    }

    public function salehistoryfilter(Request $request)
    {
        $users = $request->input('users');
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');

        $salehistoryfilter = ['users'=>$users, 'startdate'=>$startdate, 'enddate'=>$enddate];

        session::put('salehistoryfilter', $salehistoryfilter);

        return redirect()->back();
    }

    public function productsfilters(Request $request)
    {
        $supplier = $request->input('supplier');
        $category = $request->input('category');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $colour = $request->input('colour');
        /*$stockgroup = $request->input('stockgroup');*/


        $productsfilters = ['supplier'=>$supplier, 'category'=>$category, 'brand'=>$brand, 'model'=>$model, 'colour'=>$colour/*, 'stockgroup'=>$stockgroup*/];

        session::put('productsfilters', $productsfilters);

        return redirect()->back();
    }

    public function planfilters(Request $request)
    {
        $planprice = $request->input('planprice');
        $plantype = $request->input('plantype');
        $propositiontype = $request->input('propositiontype');
        $planterm = $request->input('planterm');
        $planhandsetterm = $request->input('planhandsetterm');
        $planstockgroup = $request->input('planstockgroup');


        $planfilters = ['planprice'=>$planprice, 'plantype'=>$plantype, 'propositiontype'=>$propositiontype, 'planterm'=>$planterm, 'planhandsetterm'=>$planhandsetterm, 'planstockgroup'=>$planstockgroup];

        session::put('planfilters', $planfilters);

        return redirect()->back();
    }

    public function stocktransferoutfilters(Request $request)
    {
        $startdate = $request->input('startdate');
        $enddate = $request->input('enddate');
        $transferto = $request->input('transferto');


        $stocktransferoutfilters = ['startdate'=>$startdate, 'enddate'=>$enddate, 'transferto'=>$transferto];

        session::put('stocktransferoutfilters', $stocktransferoutfilters);

        return redirect()->back();
    }

    public function bulkcomissionfilter(Request $request)
    {
        $supplier = $request->input('supplier');
        $category = $request->input('category');
        $subcategory = $request->input('subcategory');
        $stockgroup = $request->input('stockgroup');


        $bulkcomissionfilter = ['supplier'=>$supplier, 'category'=>$category, 'subcategory'=>$subcategory, 'stockgroup'=>$stockgroup];

        session::put('bulkcomissionfilter', $bulkcomissionfilter);

        return redirect()->back();
    }

    public function eodfilter(Request $request)
    {
        
        $eoddate = $request->input('eoddate');


        $eodfilter = ['eoddate'=>$eoddate];

        session::put('eodfilter', $eodfilter);

        return redirect()->back();
    }
}
