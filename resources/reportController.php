<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesByUser;
use App\Exports\PersonalTargetExcelExport;
use App\Exports\StoreTargetExcelExport;
use App\Imports\PersonalTargetExcelImport;
use App\Imports\StoreTargetExcelImport;

ob_end_clean(); // this
ob_start(); // and this

use Cookie;
use Tracker;
use Session;
use Validator;

use App\loggeduser;
use App\mainmenu;
use App\storeuser;
use App\submenu;
use App\userpermission;
use App\usergroup;
use App\store;
use App\storetype;

use App\product;
use App\orderdetail;
use App\orderitem;
use App\orderpayments;
use App\paymentoptions;
use App\refundorderdetail;
use App\refundorderitem;
use App\refundorderpayments;

use App\rostertimesheet;
use App\eod;

use App\masterplanpropositiontype;
use App\personaltarget;
use App\storetarget;
use App\mastercategory;
use App\User;
use App\customer;
use App\mastersupplier;
use App\masterbrand;
use App\mastercolour;
use App\mastermodel;
use App\productstock;
use App\productpurchaseorder;
use App\stocktransfer;
use App\stocktransferitem;
use App\stockreturn;
use App\stockreturnitems;
use App\stockreturnpayments;
use App\masterplantype;
use App\masterplancategory;
use App\masterplanterm;
use App\masterplanhandsetterm;
use App\demostock;
use App\mastersubcategory;

class reportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');

         $this->middleware(function ($request, $next)
         {
            $this->id = Auth::user()->id;
            $loggedinuser= loggeduser::where('id', $this->id)
        ->join('userlogintype','userlogintype.userID','=','id')
        ->join('usertype','usertype.usertypeID','=','userlogintype.usertypeID')
        ->first();

        $loggedip = request()->ip();

        if(session::get('storeid')!='')
        {
            $loggeduserstore= store::where('store_id', session::get('storeid'))->first();
        }
        else
        {
            $loggeduserstore= storeuser::where('userID', $loggedinuser->id)
            ->join('store', 'store.store_id', '=', 'storeuser.store_id')
            ->where('store.storeIP', $loggedip)
            ->first();    
        }

        $loggedinsubmenu= mainmenu::with('submenu')->where('usertypeID', $loggedinuser->usertypeID)->orderBy('mainmenuSrNum', 'ASC')->get();

        $loggeduserpermission= userpermission::where('userID', $loggedinuser->id)->first();

        $loggindata= ['loggedinuser'=>$loggedinuser,'loggeduserstore'=>$loggeduserstore, 'loggedinsubmenu'=>$loggedinsubmenu, 'loggeduserpermission'=>$loggeduserpermission];

        session::put('loggindata', $loggindata);

        if(session::get('loggindata.loggeduserstore') == "")
        {
            if(session::get('loggindata.loggedinuser.usertypeRole') != 'Admin')
            {
                Auth::logout();
                return redirect('login')->with('error', 'You are trying to log-in in wrong store');
            }
        }

        $allstore= store::get();

        session::put('allstore', $allstore);
            return $next($request);
        });
    }

    public function instockview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewinstock')=='N' ||session::get('loggindata.loggeduserpermission.viewinstock')=='')
        {
            return redirect('404');
        } 
        else
        {
        	if(session::get('loggindata.loggeduserstore')!='')
            {
            	$getdevice = product::where('producttype', '!=', '')
            	->leftJoin('productstock', 'productstock.productID', '=', 'products.productID')
            	->where('productstock.storeID', session::get('loggindata.loggeduserstore.store_id'))
            	->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            	->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            	->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            	->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
                ->where('productstock.productquantity', '1')
            	->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'productstock.productimei', 'productstock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'productstock.created_at'));

            	$getquantityproducts = product::whereNull('producttype')
            	->join('productstock', 'productstock.productID', '=', 'products.productID')
            	->where('productstock.storeID', session::get('loggindata.loggeduserstore.store_id'))
            	->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            	->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            	->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            	->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
                ->where('productstock.productquantity', '!=', 0)
            	->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'productstock.productimei', 'productstock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'productstock.created_at'));
            }
            else
            {
            	$getdevice = product::where('producttype', '!=', '')
            	->leftJoin('productstock', 'productstock.productID', '=', 'products.productID')
            	->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            	->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            	->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            	->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
                ->where('productstock.productquantity', '1')
            	->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'productstock.productimei', 'productstock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'productstock.created_at'));

            	$getquantityproducts = product::whereNull('producttype')
            	->join('productstock', 'productstock.productID', '=', 'products.productID')
            	->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            	->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            	->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            	->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
                ->where('productstock.productquantity', '!=', 0)
            	->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'productstock.productimei', 'productstock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'productstock.created_at'));
            }

            //return $getquantityproducts;

            $instockdata = ['getdevice'=>$getdevice, 'getquantityproducts'=>$getquantityproducts];

            return view('report-instock')->with('instockdata', $instockdata);
        }
    }

    public function demostockview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewdemostock')=='N' ||session::get('loggindata.loggeduserpermission.viewdemostock')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')!='')
            {
                $getdevice = product::where('producttype', '!=', '')
                ->leftJoin('demostock', 'demostock.productID', '=', 'products.productID')
                ->where('demostock.storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->leftJoin('store', 'store.store_id', '=', 'demostock.storeID')
                ->where('demostock.productquantity', '1')
                ->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'demostock.productimei', 'demostock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'demostock.created_at'));

                /*$getquantityproducts = product::whereNull('producttype')
                ->join('productstock', 'productstock.productID', '=', 'products.productID')
                ->where('productstock.storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
                ->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'productstock.productimei', 'productstock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'productstock.created_at'));*/
            }
            else
            {
                $getdevice = product::where('producttype', '!=', '')
                ->leftJoin('demostock', 'demostock.productID', '=', 'products.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->leftJoin('store', 'store.store_id', '=', 'demostock.storeID')
                ->where('demostock.productquantity', '1')
                ->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'demostock.productimei', 'demostock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'demostock.created_at'));

                /*$getquantityproducts = product::whereNull('producttype')
                ->join('productstock', 'productstock.productID', '=', 'products.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
                ->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'productstock.productimei', 'productstock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'productstock.created_at'));*/
            }

            //return $getquantityproducts;

            $demostock = ['getdevice'=>$getdevice];

            return view('report-demostock')->with('demostock', $demostock);
        }
    }

    public function salehistoryview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewsalehistory')=='N' ||session::get('loggindata.loggeduserpermission.viewsalehistory')=='')
        {
            return redirect('404');
        } 
        else
        {
            $allusers = loggeduser::where('userstatus', '1')->get();

            if(!empty(session::get('salehistoryfilter.startdate')))
            {
                $firstday = date('Y-m-d', strtotime(session::get('salehistoryfilter.startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty(session::get('salehistoryfilter.enddate')))
            {
                $lastday = date('Y-m-d', strtotime(session::get('salehistoryfilter.enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            $userID = session::get('salehistoryfilter.users');

        	if(session::get('loggindata.loggeduserstore')!='')
            {
            	$allsale = orderdetail::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
            	->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            	->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            	->with('customer')
            	->with('orderpayment')
                ->whereBetween('orderdetail.orderDate', [$firstday, $lastday])
                ->where('orderdetail.userID', 'LIKE', '%'.$userID.'%')
            	->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.created_at', 'orderdetail.customerID', 'orderdetail.userID', 'orderdetail.storeID', 'users.name', 'store.store_name'));

                $refundsale = refundorderdetail::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->leftJoin('users', 'users.id', '=', 'refundorderdetail.refundBy')
                ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
                ->with('customer')
                ->with('orderpayment')
                ->whereBetween('refundorderdetail.refundDate', [$firstday, $lastday])
                ->where('refundorderdetail.userID', 'LIKE', '%'.$userID.'%')
                ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.created_at', 'refundorderdetail.customerID', 'refundorderdetail.userID', 'refundorderdetail.storeID', 'users.name', 'store.store_name'));
            }
            else
            {
            	$allsale = orderdetail::whereBetween('orderDate', [$firstday, $lastday])
                ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            	->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            	->with('customer')
            	->with('orderpayment')
                ->where('orderdetail.userID', 'LIKE', '%'.$userID.'%')
            	->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.created_at', 'orderdetail.customerID', 'orderdetail.userID', 'orderdetail.storeID', 'orderdetail.orderDate', 'users.name', 'store.store_name'));

                $refundsale = refundorderdetail::whereBetween('refundDate', [$firstday, $lastday])
                ->leftJoin('users', 'users.id', '=', 'refundorderdetail.refundBy')
                ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
                ->with('customer')
                ->with('orderpayment')
                ->where('refundorderdetail.userID', 'LIKE', '%'.$userID.'%') 
                ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.created_at', 'refundorderdetail.customerID', 'refundorderdetail.userID', 'refundorderdetail.storeID', 'users.name', 'store.store_name'));
            }

            //return $allsale;
            $with = array(
                    'allsale'=>$allsale,
                    'refundsale'=>$refundsale,
                    'allusers'=>$allusers,
                    'firstday'=>$firstday,
                    'lastday'=>$lastday,
                    'userID'=>$userID
                );
            return view('report-salehistory')->with($with);
        }
    }

    public function salehistorydetailview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.viewsalehistory')=='N' ||session::get('loggindata.loggeduserpermission.viewsalehistory')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')!='')
            {
                $saledetail = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
                ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
                ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
                ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->with('customer')
                ->with('orderpayment')
                ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
                ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
                ->with('refundorder')
                ->where('orderdetail.orderID', $id)
                ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'orderitem.orderitemID', 'orderitem.productID', 'orderitem.stockID', 'orderitem.planID', 'orderitem.planOrderID', 'orderitem.plandetails', 'orderitem.discountedType', 'orderitem.discount', 'orderitem.quantity', 'orderitem.stockgroup', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.planMobilenumber', 'orderitem.subTotal', 'products.productname', 'products.barcode', 'products.stockcode', 'products.colour', 'products.model', 'products.brand', 'plan.plantypeID', 'plan.planpropositionID', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'users.name', 'store.store_name', 'store.store_address', 'store.store_contact', 'store.store_email', 'productstock.productimei', 'users.username'));

                //return $saledetail;

                return view('report-salehistorydetail')->with('saledetail', $saledetail);
            }
            else
            {
                $saledetail = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
                ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
                ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
                ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->with('customer')
                ->with('orderpayment')
                ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
                ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
                ->with('refundorder')
                ->where('orderdetail.orderID', $id)
                ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'orderitem.orderitemID', 'orderitem.productID', 'orderitem.stockID', 'orderitem.planID', 'orderitem.planOrderID', 'orderitem.plandetails', 'orderitem.discountedType', 'orderitem.discount', 'orderitem.quantity', 'orderitem.stockgroup', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.subTotal', 'orderitem.planMobilenumber', 'products.productname', 'products.barcode', 'products.stockcode', 'products.colour', 'products.model', 'products.brand', 'plan.plantypeID', 'plan.planpropositionID', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'users.name', 'store.store_name', 'store.store_address', 'store.store_contact', 'store.store_email', 'productstock.productimei', 'users.username'));

                //return $saledetail;

                return view('report-salehistorydetail')->with('saledetail', $saledetail);
            }
        }
    }


    public function printsale($id)
    {   
        if(session::get('loggindata.loggeduserpermission.viewsalehistory')=='N' ||session::get('loggindata.loggeduserpermission.viewsalehistory')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')!='')
            {
                $saledetail = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
                ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
                ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
                ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->with('customer')
                ->with('orderpayment')
                ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
                ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
                ->with('refundorder')
                ->where('orderdetail.orderID', $id)
                ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'orderitem.orderitemID', 'orderitem.productID', 'orderitem.stockID', 'orderitem.planID', 'orderitem.planOrderID', 'orderitem.plandetails', 'orderitem.discountedType', 'orderitem.discount', 'orderitem.quantity', 'orderitem.stockgroup', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.planMobilenumber', 'orderitem.subTotal', 'products.productname', 'products.barcode', 'products.stockcode', 'products.colour', 'products.model', 'products.brand', 'plan.plantypeID', 'plan.planpropositionID', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'users.name', 'store.store_name', 'store.store_address', 'store.store_contact', 'store.store_email', 'productstock.productimei', 'users.username'));

                //return $saledetail;

                return view('report-print-sale')->with('saledetail', $saledetail);
            }
            else
            {
                $saledetail = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
                ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
                ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
                ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->with('customer')
                ->with('orderpayment')
                ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
                ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
                ->with('refundorder')
                ->where('orderdetail.orderID', $id)
                ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'orderitem.orderitemID', 'orderitem.productID', 'orderitem.stockID', 'orderitem.planID', 'orderitem.planOrderID', 'orderitem.plandetails', 'orderitem.discountedType', 'orderitem.discount', 'orderitem.quantity', 'orderitem.stockgroup', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.subTotal', 'orderitem.planMobilenumber', 'products.productname', 'products.barcode', 'products.stockcode', 'products.colour', 'products.model', 'products.brand', 'plan.plantypeID', 'plan.planpropositionID', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'users.name', 'store.store_name', 'store.store_address', 'store.store_contact', 'store.store_email', 'productstock.productimei', 'users.username'));

                //return $saledetail;

                return view('report-print-sale')->with('saledetail', $saledetail);
            }
        }
    }

    public function timesheet()
    {   
        if(session::get('loggindata.loggeduserpermission.viewtimesheet')=='N' ||session::get('loggindata.loggeduserpermission.viewtimesheet')=='')
        {
            return redirect('404');
        } 
        else
        {
            $userID = session::get('loggindata.loggedinuser.id');
            $storeID = session::get('loggindata.loggeduserstore.store_id');
            $month = date('m');
            $year = date('Y');

            $gettimesheet = rostertimesheet::where('timesheetMonth', $month)->where('timesheetYear', $year)->where('userID', $userID)->get();

            $getuser = loggeduser::where('id', $userID)->first();

            $getstore = store::where('store_id', $storeID)->first();

            //return $gettimesheet;
            $timesheetdata = ['gettimesheet'=>$gettimesheet, 'getuser'=>$getuser, 'getstore'=>$getstore, 'month'=>$month, 'year'=>$year];

            return view('report-timesheet')->with('timesheetdata', $timesheetdata);
        }
    }

    public function addtimesheet(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addtimesheet')=='N' || session::get('loggindata.loggeduserpermission.addtimesheet')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'starttime'=>'required',
            'endtime'=>'required',
            'breaktime'=>'required'
            ],[
                'starttime.required'=>'Start time is required',
                'endtime.required'=>'End time is required',
                'breaktime.required'=>'Break time is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $todaydate = $request->input('timesheetdate');
                $currentmonth = date('m', strtotime($request->input('timesheetdate')));
                $currentyear = date('Y', strtotime($request->input('timesheetdate')));
                $currentday = date('D', strtotime($request->input('timesheetdate')));

                $checkdate = rostertimesheet::where('timesheetDate', $todaydate)->where('userID', session::get('loggindata.loggedinuser.id'))->count();

                $getuser = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

                if($checkdate > 0)
                {
                    return redirect()->back()->with('error', 'You already filled timesheet for the day');
                }
                else
                {
                    /****Calculation Working Time*******/
                    $encodestarttime = strtotime($todaydate.' '.$request->input('starttime'));
                    $encodeendtime = strtotime($todaydate.' '.$request->input('endtime'));

                    $totaltime = round(abs($encodeendtime - $encodestarttime) / 60, 2);

                    $workinghours = $totaltime - $request->input('breaktime');

                    $exactworkinghours = intdiv($workinghours, 60).':'. ($workinghours % 60);
                    /****Calculation Working Time*******/

                    /****Calculation Per Day Amount To Pay******/
                    if($currentday == 'Sat')
                    {
                        $perhourprice = $getuser->saturdayrate;
                    }
                    else if($currentday == 'Sun')
                    {
                        $perhourprice = $getuser->sundayrate;
                    }
                    else
                    {
                        $perhourprice = $getuser->normalrate;
                    }

                    $hourstominute = $workinghours;
                    $perminAmount = $perhourprice / 60;

                    $amountopay = round($hourstominute * $perminAmount + $getuser->feul, 2);

                    //return $amountopay;
                    /****Calculation Per Day Amount To Pay******/

                    
                    $inserttimesheet = new rostertimesheet;
                    $inserttimesheet->userID = session::get('loggindata.loggedinuser.id');
                    $inserttimesheet->timesheetDate = $todaydate;
                    $inserttimesheet->timesheetDay = $currentday;
                    $inserttimesheet->timesheetMonth = $currentmonth;
                    $inserttimesheet->timesheetYear = $currentyear;
                    $inserttimesheet->timesheetStarttime = $request->input('starttime');
                    $inserttimesheet->timesheetEndtime = $request->input('endtime');
                    $inserttimesheet->timesheetBreaktime = $request->input('breaktime');
                    $inserttimesheet->timesheetWorkinghours = $exactworkinghours;
                    $inserttimesheet->timesheetHoursAmount = $amountopay;
                    $inserttimesheet->timesheetPayStatus = 'Unpaid';
                    $inserttimesheet->timesheetNote = $request->input('timesheetnote');
                    $inserttimesheet->save();

                    if($inserttimesheet->save())
                    {
                        return redirect()->back()->with('success', 'Your timesheet updated for the day.');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to add to your timesheet');
                    }
                }
            }
        }
    }

    public function edittimesheet(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addtimesheet')=='N' || session::get('loggindata.loggeduserpermission.addtimesheet')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'starttime'=>'required',
            'endtime'=>'required',
            'breaktime'=>'required'
            ],[
                'starttime.required'=>'Start time is required',
                'endtime.required'=>'End time is required',
                'breaktime.required'=>'Break time is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $todaydate = $request->input('timesheetdate');
                $currentmonth = date('m', strtotime($request->input('timesheetdate')));
                $currentyear = date('Y', strtotime($request->input('timesheetdate')));
                $currentday = date('D', strtotime($request->input('timesheetdate')));

                $checkdate = rostertimesheet::where('timesheetDate', $todaydate)->where('userID', session::get('loggindata.loggedinuser.id'))->count();

                $getuser = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

                if($checkdate > 0)
                {
                    /****Calculation Working Time*******/
                    $encodestarttime = strtotime($todaydate.' '.$request->input('starttime'));
                    $encodeendtime = strtotime($todaydate.' '.$request->input('endtime'));

                    $totaltime = round(abs($encodeendtime - $encodestarttime) / 60, 2);

                    $workinghours = $totaltime - $request->input('breaktime');

                    $exactworkinghours = intdiv($workinghours, 60).':'. ($workinghours % 60);
                    /****Calculation Working Time*******/

                    /****Calculation Per Day Amount To Pay******/
                    if($currentday == 'Sat')
                    {
                        $perhourprice = $getuser->saturdayrate;
                    }
                    else if($currentday == 'Sun')
                    {
                        $perhourprice = $getuser->sundayrate;
                    }
                    else
                    {
                        $perhourprice = $getuser->normalrate;
                    }

                    $hourstominute = $workinghours;
                    $perminAmount = $perhourprice / 60;

                    $amountopay = round($hourstominute * $perminAmount + $getuser->feul, 2);

                    //return $amountopay;
                    /****Calculation Per Day Amount To Pay******/

                    $inserttimesheet = rostertimesheet::where('timesheetDate', $request->input('timesheetdate'))->where('userID', session::get('loggindata.loggedinuser.id'))->first();
                    $inserttimesheet->timesheetDate = $todaydate;
                    $inserttimesheet->timesheetDay = $currentday;
                    $inserttimesheet->timesheetMonth = $currentmonth;
                    $inserttimesheet->timesheetYear = $currentyear;
                    $inserttimesheet->timesheetStarttime = $request->input('starttime');
                    $inserttimesheet->timesheetEndtime = $request->input('endtime');
                    $inserttimesheet->timesheetBreaktime = $request->input('breaktime');
                    $inserttimesheet->timesheetWorkinghours = $exactworkinghours;
                    $inserttimesheet->timesheetHoursAmount = $amountopay;
                    $inserttimesheet->timesheetPayStatus = 'Unpaid';
                    $inserttimesheet->timesheetNote = $request->input('timesheetnote');
                    $inserttimesheet->save();

                    if($inserttimesheet->save())
                    {
                        return redirect()->back()->with('success', 'Your timesheet updated for the day.');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to add to your timesheet');
                    }
                }
                else
                {
                   return redirect()->back()->with('error', 'No date found'); 
                }
            }
        }
    }

    public function rostermanagerview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewrostermanager')=='N' ||session::get('loggindata.loggeduserpermission.viewrostermanager')=='')
        {
            return redirect('404');
        } 
        else
        {
            $allusers = loggeduser::get();

            /*if(!empty(session::get('timesheetfilterdata')))
            {
                $userID = session::get('timesheetfilterdata.userID');
                //$storeID = session::get('loggindata.loggeduserstore.store_id');
                $month = session::get('timesheetfilterdata.month');
                $year = session::get('timesheetfilterdata.year');

                $getuser = loggeduser::where('id', $userID)
                ->leftJoin('storeuser', 'storeuser.userID', '=', 'users.id')
                ->first();

                $getstore = store::where('store_id', $getuser->store_id)->first();

                $gettimesheet = rostertimesheet::where('timesheetMonth', $month)->where('timesheetYear', $year)->where('userID', $userID)->get();

                $unpaidweekdayshours = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->whereNotIn('timesheetDay', ['Sat', 'Sun'])
                ->sum('timesheetWorkinghours');

                //return $unpaidweekdayshours;

                $unpaidweekdaysamount = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->whereNotIn('timesheetDay', ['Sat', 'Sun'])
                ->sum('timesheetHoursAmount');

                $unpaidsaturdayhours = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->where('timesheetDay', 'Sat')
                ->sum('timesheetWorkinghours');

                $unpaidsaturdayamount = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->where('timesheetDay', 'Sat')
                ->sum('timesheetHoursAmount');

                $unpaidsundayhours = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->where('timesheetDay', 'Sun')
                ->sum('timesheetWorkinghours');

                $unpaidsundayamount = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->where('timesheetDay', 'Sun')
                ->sum('timesheetHoursAmount');
            }
            else
            {
                $userID = session::get('loggindata.loggedinuser.id');
                $storeID = session::get('loggindata.loggeduserstore.store_id');
                $month = date('m');
                $year = date('Y');

                $gettimesheet = rostertimesheet::where('timesheetMonth', $month)->where('timesheetYear', $year)->where('userID', $userID)->get();

                $unpaidweekdayshours = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->whereNotIn('timesheetDay', ['Sat', 'Sun'])
                ->sum('timesheetWorkinghours');

                //return $unpaidweekdayshours;

                $unpaidweekdaysamount = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->whereNotIn('timesheetDay', ['Sat', 'Sun'])
                ->sum('timesheetHoursAmount');

                $unpaidsaturdayhours = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->where('timesheetDay', 'Sat')
                ->sum('timesheetWorkinghours');

                $unpaidsaturdayamount = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->where('timesheetDay', 'Sat')
                ->sum('timesheetHoursAmount');

                $unpaidsundayhours = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->where('timesheetDay', 'Sun')
                ->sum('timesheetWorkinghours');

                $unpaidsundayamount = rostertimesheet::where('timesheetMonth', $month)
                ->where('timesheetYear', $year)
                ->where('userID', $userID)
                ->where('timesheetPayStatus', 'Unpaid')
                ->where('timesheetDay', 'Sun')
                ->sum('timesheetHoursAmount');

                //return $unpaidweekdaysamount;

                $getuser = loggeduser::where('id', $userID)->first();

                $getstore = store::where('store_id', $storeID)->first();
            }*/
            //return session::get('timesheetfilterdata');
            
            if(!empty(session::get('timesheetfilterdata.userID')))
            {
                $userID = session::get('timesheetfilterdata.userID');
                $storeID = storeuser::where('userID', $userID)->first();
            }
            else
            {
                $userID = session::get('loggindata.loggedinuser.id');
                $storeID = session::get('loggindata.loggeduserstore.store_id');    
            }

            if(!empty(session::get('timesheetfilterdata.month')))
            {
                $month = session::get('timesheetfilterdata.month');
            }
            else
            {
                $month = date('m');
            }

            if(!empty(session::get('timesheetfilterdata.year')))
            {
                $year = session::get('timesheetfilterdata.year');
            }
            else
            {
                $year = date('Y');
            }

            if(!empty(session::get('timesheetfilterdata.datefrom')))
            {
                $datefrom = session::get('timesheetfilterdata.datefrom');
            }
            else
            {
                $datefrom = date('Y-m').'-01';
            }

            if(!empty(session::get('timesheetfilterdata.dateto')))
            {
                $dateto = session::get('timesheetfilterdata.dateto');
            }
            else
            {
                $dateto = date('Y-m').'-30';
            }

            $gettimesheet = rostertimesheet::where('timesheetMonth', $month)
            ->where('timesheetYear', $year)
            ->where('userID', $userID)
            ->whereBetween('timesheetDate', [$datefrom, $dateto])
            ->get();

            //return $year;

            $getuser = loggeduser::where('id', $userID)->first();

            $getstore = store::where('store_id', $storeID)->first();

            $unpaidweekdayshours = rostertimesheet::where('timesheetMonth', $month)
            ->where('timesheetYear', $year)
            ->where('userID', $userID)
            ->whereDate('timesheetDate', '>=', $datefrom)
            ->whereDate('timesheetDate', '<=', $dateto)
            ->where('timesheetPayStatus', 'Unpaid')
            ->whereNotIn('timesheetDay', ['Sat', 'Sun'])
            ->get('timesheetWorkinghours');

            $unpaidweekdaysamount = rostertimesheet::where('timesheetMonth', $month)
            ->where('timesheetYear', $year)
            ->where('userID', $userID)
            ->whereBetween('timesheetDate', [$datefrom, $dateto])
            ->where('timesheetPayStatus', 'Unpaid')
            ->whereNotIn('timesheetDay', ['Sat', 'Sun'])
            ->sum('timesheetHoursAmount');

            $unpaidsaturdayhours = rostertimesheet::where('timesheetMonth', $month)
            ->where('timesheetYear', $year)
            ->where('userID', $userID)
            ->whereBetween('timesheetDate', [$datefrom, $dateto])
            ->where('timesheetPayStatus', 'Unpaid')
            ->where('timesheetDay', 'Sat')
            ->get('timesheetWorkinghours');

            $unpaidsaturdayamount = rostertimesheet::where('timesheetMonth', $month)
            ->where('timesheetYear', $year)
            ->where('userID', $userID)
            ->whereBetween('timesheetDate', [$datefrom, $dateto])
            ->where('timesheetPayStatus', 'Unpaid')
            ->where('timesheetDay', 'Sat')
            ->sum('timesheetHoursAmount');

            $unpaidsundayhours = rostertimesheet::where('timesheetMonth', $month)
            ->where('timesheetYear', $year)
            ->where('userID', $userID)
            ->whereBetween('timesheetDate', [$datefrom, $dateto])
            ->where('timesheetPayStatus', 'Unpaid')
            ->where('timesheetDay', 'Sun')
            ->get('timesheetWorkinghours');

            $unpaidsundayamount = rostertimesheet::where('timesheetMonth', $month)
            ->where('timesheetYear', $year)
            ->where('userID', $userID)
            ->whereBetween('timesheetDate', [$datefrom, $dateto])
            ->where('timesheetPayStatus', 'Unpaid')
            ->where('timesheetDay', 'Sun')
            ->sum('timesheetHoursAmount');

            //return $unpaidweekdaysamount;

            $timesheetdata = ['gettimesheet'=>$gettimesheet, 'getuser'=>$getuser, 'getstore'=>$getstore, 'month'=>$month, 'year'=>$year, 'unpaidweekdayshours'=>$unpaidweekdayshours, 'unpaidweekdaysamount'=>$unpaidweekdaysamount, 'unpaidsaturdayhours'=>$unpaidsaturdayhours, 'unpaidsaturdayamount'=>$unpaidsaturdayamount, 'unpaidsundayhours'=>$unpaidsundayhours, 'unpaidsundayamount'=>$unpaidsundayamount, 'allusers'=>$allusers];

            //return $timesheetdata;

            return view('report-rostermanager')->with('timesheetdata', $timesheetdata);
        }
    }

    public function paysalary(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.rostermanagerpay')=='N' || session::get('loggindata.loggeduserpermission.rostermanagerpay')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'payday'=>'required',
            'userID'=>'required',
            'month'=>'required',
            'year'=>'required',
            ],[
                'payday.required'=>'Please select atleast one day to pay',
                'userID.required'=>'Something went wrong with fetching data for Staff',
                'month.required'=>'Something went wrong with fetching data for Staff on the month',
                'year.required'=>'Something went wrong with fetching data for Staff on the year'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checksalary = rostertimesheet::whereIn('timesheetID', $request->input('payday'))->where('timesheetPayStatus', 'Unpaid')->where('userID', $request->input('userID'))->where('timesheetMonth', $request->input('month'))->where('timesheetYear',  $request->input('year'))->count();

                if($checksalary > 0)
                {
                    $getactualdata = rostertimesheet::whereIn('timesheetID', $request->input('payday'))->where('timesheetPayStatus', 'Unpaid')->where('userID', $request->input('userID'))->where('timesheetMonth', $request->input('month'))->where('timesheetYear',  $request->input('year'))->update(['timesheetPayStatus'=> 'Paid']);

                    if($getactualdata > 0)
                    {
                        return redirect()->back()->with('success', 'Salary paid successfully.');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'No salary data found');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Salary paid for given dates');
                }
            }
        }
    }

    public function endofdayview()
    {   
        if(session::get('loggindata.loggeduserpermission.reportEOD')=='N' ||session::get('loggindata.loggeduserpermission.reportEOD')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(!empty(session::get('eodfilter.eoddate')))
            {
                $todaydate =session::get('eodfilter.eoddate');
            }
            else
            {
                $todaydate = date('Y-m-d')/*'2019-12-03'*/;
            }
            

            $gettotal = orderdetail::leftJoin('orderpayments', 'orderpayments.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'orderpayments.paymentType')
            ->where('orderstatus', '1')
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->get();

            $getrefundtotal = refundorderdetail::leftJoin('refundorderpayments', 'refundorderpayments.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'refundorderpayments.paymentType')
            ->where('refundStatus', '1')
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->get();

            //return $geteod;

            $geteoddone = eod::where('eodDate', $todaydate)->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->leftJoin('users', 'users.id', '=', 'storeeod.userID')
            ->get();

            //return $geteoddone;

            $paymentoptions = paymentoptions::where('paymentstatus', '1')->whereIn('paymenttype', ['Offline', 'Online'])->get();

            $geteodamount = store::where('store_id', session::get('loggindata.loggeduserstore.store_id'))->first();

            $eoddata = ['todaydate'=>$todaydate, 'paymentoptions'=>$paymentoptions, 'gettotal'=>$gettotal, 'geteoddone'=>$geteoddone, 'geteodamount'=>$geteodamount, 'getrefundtotal'=>$getrefundtotal];

            return view('report-endofday')->with('eoddata', $eoddata);
        }
    }

    public function todayendofdayview(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.reportEOD')=='N' ||session::get('loggindata.loggeduserpermission.reportEOD')=='')
        {
            return redirect('404');
        } 
        else
        {
            if($request->input('todaydate')!="")
            {
                $todaydate = $request->input('todaydate');
            }
            else
            {
                $todaydate = date('Y-m-d');
            }

            $geteod = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->where('orderstatus', '1')
            ->where('orderdetail.orderDate', $todaydate)
            ->where('store.store_id', session::get('loggindata.loggeduserstore.store_id'))
            ->with('customer')
            ->with('orderpayment')
            ->get();

            $getrefundeod = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.orderID', '=', 'refundorderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'refundorderitem.stockID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'refundorderitem.stockgroup')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->where('refundStatus', '1')
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('store.store_id', session::get('loggindata.loggeduserstore.store_id'))
            ->with('customer')
            ->with('orderpayment')
            ->get();

            //return $getrefundeod;

            $gettotal = orderdetail::leftJoin('orderpayments', 'orderpayments.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'orderpayments.paymentType')
            ->where('orderstatus', '1')
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->get();

            $getrefundtotal = refundorderdetail::leftJoin('refundorderpayments', 'refundorderpayments.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'refundorderpayments.paymentType')
            ->where('refundStatus', '1')
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->get();

            $paymentoptions = paymentoptions::where('paymentstatus', '1')->whereIn('paymenttype', ['Offline', 'Online'])->get();

            $geteodamount = store::where('store_id', session::get('loggindata.loggeduserstore.store_id'))->first();

            /*$planproposition = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcID', '1')
            ->get();*/ 

            $planproposition = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcstatus', '1')
            ->get();

            $productcategory = mastercategory::where('categorystatus', '1')->get();

            $productcategorysales = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('orderitem.planID')
            ->where('orderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $refundproductcategorysales = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('refundorderitem.planID')
            ->where('refundorderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            //return $productcategorysales;

            $getorderedplan = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->where('orderitem.planID', '!=', '')
            ->where('orderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $getrefundorderedplan = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            $eoddata = ['geteod'=>$geteod, 'getrefundeod'=>$getrefundeod, 'getrefundtotal'=>$getrefundtotal, 'todaydate'=>$todaydate, 'paymentoptions'=>$paymentoptions,'geteodamount'=>$geteodamount, 'gettotal'=>$gettotal, 'planproposition'=>$planproposition, 'productcategory'=>$productcategory, 'productcategorysales'=>$productcategorysales, 'getorderedplan'=>$getorderedplan, 'refundproductcategorysales'=>$refundproductcategorysales, 'getrefundorderedplan'=>$getrefundorderedplan];

            return view('report-today-endofday')->with('eoddata', $eoddata);
        }
    }

    public function eodtodayprintview()
    {   
        if(session::get('loggindata.loggeduserpermission.reportEOD')=='N' ||session::get('loggindata.loggeduserpermission.reportEOD')=='')
        {
            return redirect('404');
        } 
        else
        {
            $todaydate = date('Y-m-d')/*'2019-12-03'*/;

            $geteod = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->where('orderstatus', '1')
            ->where('orderdetail.orderDate', $todaydate)
            ->where('store.store_id', session::get('loggindata.loggeduserstore.store_id'))
            ->with('customer')
            ->with('orderpayment')
            ->get();

            $getrefundeod = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.orderID', '=', 'refundorderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'refundorderitem.stockID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'refundorderitem.stockgroup')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->where('refundStatus', '1')
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('store.store_id', session::get('loggindata.loggeduserstore.store_id'))
            ->with('customer')
            ->with('orderpayment')
            ->get();

            //return $getrefundeod;

            $gettotal = orderdetail::leftJoin('orderpayments', 'orderpayments.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'orderpayments.paymentType')
            ->where('orderstatus', '1')
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->get();

            $getrefundtotal = refundorderdetail::leftJoin('refundorderpayments', 'refundorderpayments.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'refundorderpayments.paymentType')
            ->where('refundStatus', '1')
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->get();

            $paymentoptions = paymentoptions::where('paymentstatus', '1')->whereIn('paymenttype', ['Offline', 'Online'])->get();

            $geteodamount = store::where('store_id', session::get('loggindata.loggeduserstore.store_id'))->first();

            $planproposition = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcID', '1')
            ->get(); 

            $productcategory = mastercategory::where('categorystatus', '1')->get();

            $productcategorysales = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('orderitem.planID')
            ->where('orderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $refundproductcategorysales = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('refundorderitem.planID')
            ->where('refundorderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            //return $productcategorysales;

            $getorderedplan = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->where('orderitem.planID', '!=', '')
            ->where('orderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $getrefundorderedplan = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            $eoddata = ['geteod'=>$geteod, 'getrefundeod'=>$getrefundeod, 'getrefundtotal'=>$getrefundtotal, 'todaydate'=>$todaydate, 'paymentoptions'=>$paymentoptions,'geteodamount'=>$geteodamount, 'gettotal'=>$gettotal, 'planproposition'=>$planproposition, 'productcategory'=>$productcategory, 'productcategorysales'=>$productcategorysales, 'getorderedplan'=>$getorderedplan, 'refundproductcategorysales'=>$refundproductcategorysales, 'getrefundorderedplan'=>$getrefundorderedplan];

            return view('report-eodtoday-print')->with('eoddata', $eoddata);
        }
    }

    public function eodrecon(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.reportEODtill')=='N' || session::get('loggindata.loggeduserpermission.reportEODtill')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'amounttype'=>'required',
            'eodamount'=>'required'
            ],[
                'amounttype.required'=>'reportEODtill required',
                'eodamount.required'=>'eodamount required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                //return $request->all();
                if(!empty(session::get('loggindata.loggeduserstore.store_id')))
                {
                    $checkeod = eod::where('eodDate', $request->input('eoddate'))->where('storeID', session::get('loggindata.loggeduserstore.store_id'))->count();

                    if($checkeod > 0)
                    {
                        return redirect()->back()->with('error', 'EOD already closed for the date.');
                    }
                    else
                    {
                        $imeicount = count($request->input('amounttype'));
                        for ($i = 0; $i < $imeicount; $i++)
                        {
                            $po[] = [
                                    'eodDate' => $request->input('eoddate'),
                                    'eodPaymentType' => $request->input('amounttype')[$i],
                                    'storeReconAmount' => $request->input('eodamount'),
                                    'eodAmount' => $request->input('tillamount')[$i],
                                    'eodNote' => $request->input('eodnote'),
                                    'storeID' => session::get('loggindata.loggeduserstore.store_id'),
                                    'userID' => session::get('loggindata.loggedinuser.id')
                                ];
                        }
                        eod::insert($po);

                        return redirect()->back()->with('success', 'EOD closed.');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Please choose a store');
                }
            }
        }
    }

    public function trackerview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewtracker')=='N' ||session::get('loggindata.loggeduserpermission.viewtracker')=='')
        {
            return redirect('404');
        } 
        else
        {

            if(!empty(session::get('usertrackerfilter.userID')))
            {
                $userID = session::get('usertrackerfilter.userID');
            }
            else
            {
                $userID = session::get('loggindata.loggedinuser.id');
            }

            if(!empty(session::get('usertrackerfilter.month')))
            {
                $month = session::get('usertrackerfilter.month');
            }
            else
            {
                $month = date('m');
            }

            if(!empty(session::get('usertrackerfilter.year')))
            {
                $year = session::get('usertrackerfilter.year');
            }
            else
            {
                $year = date('Y');
            }

            $planproposition = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcstatus', '1')
            ->get(); 

            //return $planproposition;

            $allusers = loggeduser::get();

            $getuser = loggeduser::where('id', $userID)->first(); 

            $productcategory = mastercategory::where('categorystatus', '1')->get();

            $productcategorysales = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('orderitem.planID')
            ->where('orderdetail.userID', $userID)
            ->where('orderdetail.orderMonth', $month)
            ->where('orderdetail.orderYear', $year)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $refundproductcategorysales = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('refundorderitem.planID')
            ->where('refundorderdetail.userID', $userID)
            ->where('refundorderdetail.refundMonth', $month)
            ->where('refundorderdetail.refundYear', $year)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            $getorderedplan = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->where('orderitem.planID', '!=', '')
            ->where('orderdetail.userID', $userID)
            ->where('orderdetail.orderMonth', $month)
            ->where('orderdetail.orderYear', $year)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $getrefundorderedplan = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderdetail.userID', $userID)
            ->where('refundorderdetail.refundMonth', $month)
            ->where('refundorderdetail.refundYear', $year)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            $usertarget = personaltarget::where('userID', $userID)->where('month', $month)->where('year', $year)->get();

            //return $usertarget;

            $comissiondata = ['planproposition'=>$planproposition, 'getorderedplan'=>$getorderedplan, 'productcategorysales'=>$productcategorysales, 'allusers'=>$allusers, 'getuser'=>$getuser, 'month'=>$month, 'year'=>$year, 'userID'=>$userID, 'productcategory'=>$productcategory, 'usertarget'=>$usertarget, 'refundproductcategorysales'=>$refundproductcategorysales, 'getrefundorderedplan'=>$getrefundorderedplan];

            return view('report-tracker')->with('comissiondata', $comissiondata);
        }
    }

    public function setpersonaltargetview()
    {
        if(session::get('loggindata.loggeduserpermission.addpersonaltarget')=='N' || session::get('loggindata.loggeduserpermission.addpersonaltarget')=='')
        {
            return redirect('404');
        }
        else
        {
            if(!empty(session::get('personaltargetfilter.month')))
            {
                $month = session::get('personaltargetfilter.month');
            }
            else
            {
                $month = date('m');
            }

            if(!empty(session::get('personaltargetfilter.year')))
            {
                $year = session::get('personaltargetfilter.year');
            }
            else
            {
                $year = date('Y');
            }

            $allusers = loggeduser::where('userstatus', '1')
            ->leftJoin('storeuser', 'storeuser.userID', '=', 'id')
            ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
            ->get();

            $allpropositiontype = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcstatus', '1')
            ->get();

            $allproductcategory = mastercategory::where('categorystatus', '1')->get();

            $personaltarget = personaltarget::where('month', $month)->where('year', $year)->get();

            $personaltargetdata = ['allusers'=>$allusers, 'allpropositiontype'=>$allpropositiontype, 'allproductcategory'=>$allproductcategory, 'personaltarget'=>$personaltarget, 'month'=>$month, 'year'=>$year];

            return view('report-personaltarget')->with('personaltargetdata', $personaltargetdata);
        }
    }

    public function addpersonaltarget(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.addpersonaltarget')=='N' || session::get('loggindata.loggeduserpermission.addpersonaltarget')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'month'=>'required',
            'year'=>'required'
            ],[
                'month.required'=>'Month is required',
                'year.required'=>'Year is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkmonthNyear = personaltarget::where('month', $request->input('month'))->where('year', $request->input('year'))->count();

                if($checkmonthNyear > 0)
                {
                    //return $request->input('target');
                    foreach($request->input('personaltargetid') as $key => $value)
                    {
                        //dd($key.'='.$value);
                        $updatetarget = personaltarget::
                        where('personaltargetID', $request->input('personaltargetid')[$key])
                        ->first();

                        if($updatetarget != "")
                        {
                            $updatetarget->target = $request->input('target')[$key];
                            $updatetarget->save();
                        }
                        else
                        {
                            $newtarget = new personaltarget;
                            $newtarget->userID = $request->input('userid')[$key];
                            $newtarget->targetcategory = $request->input('category')[$key];
                            $newtarget->target = $request->input('target')[$key];
                            $newtarget->month = $request->input('month');
                            $newtarget->year = $request->input('year');
                            $newtarget->save();
                        }
                    }

                    if($updatetarget->save())
                    {
                        return redirect()->back()->with('success', 'Target Updated Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to update target');
                    }

                    if($newtarget->save())
                    {
                        return redirect()->back()->with('success', 'New Target Added Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed To Add New Target');
                    }
                }
                else
                {
                    $imeicount = count($request->input('category'));
                    for ($i = 0; $i < $imeicount; $i++)
                    {  
                        $po[] = [
                                    'userID' => $request->input('userid')[$i],
                                    'targetcategory' => $request->input('category')[$i],
                                    'target' => $request->input('target')[$i],
                                    'month' => $request->input('month'),
                                    'year' => $request->input('year')
                                ];
                    }
                    personaltarget::insert($po);
                }
                return redirect()->back()->with('success', 'Target set for the month.');
            }
        }
    }

    public function exceldownloadpersonaltarget(Request $request)
    {
        return Excel::download(new PersonalTargetExcelExport, 'personaltarget.csv');
    }

    public function excelimportpersonaltarget(Request $request)
    {
        /*return Excel::download(new PersonalTargetExcelExport, 'personaltarget.csv');*/
        Excel::import(new PersonalTargetExcelImport,request()->file('personaltargetfile'));
           
        return back();
    }

    public function storetrackerview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewstoretracker')=='N' ||session::get('loggindata.loggeduserpermission.viewstoretracker')=='')
        {
            return redirect('404');
        } 
        else
        {

            if(!empty(session::get('storetrackerfilter.storeID')))
            {
                $storeID = session::get('storetrackerfilter.storeID');
            }
            else
            {
                $storeID = session::get('loggindata.loggeduserstore.store_id');
            }

            if(!empty(session::get('storetrackerfilter.month')))
            {
                $month = session::get('storetrackerfilter.month');
            }
            else
            {
                $month = date('m');
            }

            if(!empty(session::get('storetrackerfilter.year')))
            {
                $year = session::get('storetrackerfilter.year');
            }
            else
            {
                $year = date('Y');
            }

            $planproposition = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcstatus', '1')
            ->get(); 

            $allstore = store::get();
            
            $getstore = store::where('store_id', $storeID)->first();

            $productcategory = mastercategory::where('categorystatus', '1')->get();

            $productcategorysales = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('orderitem.planID')
            ->whereNull('orderitem.planOrderID')
            ->where('orderdetail.storeID', $storeID)
            ->where('orderdetail.orderMonth', $month)
            ->where('orderdetail.orderYear', $year)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $refundproductcategorysales = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('refundorderitem.planID')
            ->whereNull('refundorderitem.planOrderID')
            ->where('refundorderdetail.storeID', $storeID)
            ->where('refundorderdetail.refundMonth', $month)
            ->where('refundorderdetail.refundYear', $year)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            $getorderedplan = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->where('orderitem.planID', '!=', '')
            ->where('orderdetail.storeID', $storeID)
            ->where('orderdetail.orderMonth', $month)
            ->where('orderdetail.orderYear', $year)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $getrefundorderedplan = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderdetail.storeID', $storeID)
            ->where('refundorderdetail.refundMonth', $month)
            ->where('refundorderdetail.refundYear', $year)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            $storetarget = storetarget::where('storeID', $storeID)->where('month', $month)->where('year', $year)->get();

            $comissiondata = ['planproposition'=>$planproposition, 'getorderedplan'=>$getorderedplan, 'productcategorysales'=>$productcategorysales, 'allstore'=>$allstore, 'getstore'=>$getstore, 'month'=>$month, 'year'=>$year, 'storeID'=>$storeID, 'productcategory'=>$productcategory, 'storetarget'=>$storetarget, 'refundproductcategorysales'=>$refundproductcategorysales, 'getrefundorderedplan'=>$getrefundorderedplan];

            //return $comissiondata;
            return view('report-storetracker')->with('comissiondata', $comissiondata);
        }
    }

    public function setstoretargetview()
    {
        if(session::get('loggindata.loggeduserpermission.addstoretarget')=='N' || session::get('loggindata.loggeduserpermission.addstoretarget')=='')
        {
            return redirect('404');
        }
        else
        {
            if(!empty(session::get('storetargetfilter.month')))
            {
                $month = session::get('storetargetfilter.month');
            }
            else
            {
                $month = date('m');
            }

            if(!empty(session::get('storetargetfilter.year')))
            {
                $year = session::get('storetargetfilter.year');
            }
            else
            {
                $year = date('Y');
            }

            $allstore = store::where('storestatus', '1')->get();

            $allpropositiontype = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcstatus', '1')
            ->get();
            $allproductcategory = mastercategory::where('categorystatus', '1')->get();

            $storetarget = storetarget::where('month', $month)->where('year', $year)->get();

            $storetargetdata = ['allstore'=>$allstore, 'allpropositiontype'=>$allpropositiontype, 'allproductcategory'=>$allproductcategory, 'storetarget'=>$storetarget, 'month'=>$month, 'year'=>$year];

            return view('report-storetarget')->with('storetargetdata', $storetargetdata);
        }
    }

    public function addstoretarget(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.addstoretarget')=='N' || session::get('loggindata.loggeduserpermission.addstoretarget')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'month'=>'required',
            'year'=>'required'
            ],[
                'month.required'=>'Month is required',
                'year.required'=>'Year is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkmonthNyear = storetarget::where('month', $request->input('month'))->where('year', $request->input('year'))->count();

                if($checkmonthNyear > 0)
                {
                    //return $request->input('storetargetid');
                    foreach($request->input('storetargetid') as $key => $value)
                    {
                        //dd($value);
                        $updatetarget = storetarget::
                        where('storetargetID', $request->input('storetargetid')[$key])->first();

                        if($updatetarget != "")
                        {
                            $updatetarget->target = $request->input('target')[$key];
                            $updatetarget->save();
                        }
                        else
                        {
                            $newtarget = new storetarget;
                            $newtarget->storeID = $request->input('storeid')[$key];
                            $newtarget->targetcategory = $request->input('category')[$key];
                            $newtarget->target = $request->input('target')[$key];
                            $newtarget->month = $request->input('month');
                            $newtarget->year = $request->input('year');
                            $newtarget->save();
                        }
                    }

                    if($updatetarget->save())
                    {
                        return redirect()->back()->with('success', 'Target Updated Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to update target');
                    }

                    if($newtarget->save())
                    {
                        return redirect()->back()->with('success', 'Target Updated Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to update target');
                    }
                }
                else
                {
                    //dd($request->all());
                    $imeicount = count($request->input('category'));
                    for ($i = 0; $i < $imeicount; $i++)
                    {  
                        $po[] = [
                                    'storeID' => $request->input('storeid')[$i],
                                    'targetcategory' => $request->input('category')[$i],
                                    'target' => $request->input('target')[$i],
                                    'month' => $request->input('month'),
                                    'year' => $request->input('year')
                                ];
                    }
                    storetarget::insert($po);
                }
                return redirect()->back()->with('success', 'Target set for the month.');
            }
        }
    }

    public function exceldownloadstoretarget(Request $request)
    {
        return Excel::download(new StoreTargetExcelExport, 'storetarget.csv');
    }

    public function excelimportstoretarget(Request $request)
    {
        /*return Excel::download(new PersonalTargetExcelExport, 'personaltarget.csv');*/
        Excel::import(new StoreTargetExcelImport,request()->file('storetargetfile'));
           
        return back();
    }

    public function salebypaymentmethodview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportsalespaymentmethod')=='N' || session::get('loggindata.loggeduserpermission.viewreportsalespaymentmethod')=='')
        {
            return redirect('404');
        }
        else
        {
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            
            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }

            $userID = $request->input('user');
            $paymenttype = $request->input('payoptions');
            $saletype = $request->input('saletype');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();
            $allpayoption = paymentoptions::get();

            $getsales1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderpayments', 'orderpayments.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1');

            if($storeID!='')
            {
                $getsales1->whereIn('orderdetail.storeID', $storeID);
            }
            if($userID!='')
            {
                $getsales1->whereIn('orderdetail.userID', $userID);
            }
            if($paymenttype!='')
            {
                $getsales1->whereIn('orderpayments.paymentType', $paymenttype);
            }
            if($saletype!='')
            {
                $getsales1->whereIn('orderdetail.orderType', $saletype);
            }

            $getsales = $getsales1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.orderDate', 'orderdetail.created_at', 'orderpayments.paymentType', 'orderpayments.paidAmount', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'orderdetail.orderType', 'orderpayments.paymentType', 'orderdetail.userID', 'orderdetail.storeID'));

            $getrefund1 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
            ->whereDate('refundDate', '<=', $lastday)
            ->leftJoin('refundorderpayments', 'refundorderpayments.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('customer', 'customer.customerID', '=', 'refundorderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'refundorderdetail.userID')
            ->where('refundorderdetail.refundStatus', '1');

            if($storeID!='')
            {
                $getrefund1->whereIn('refundorderdetail.storeID', $storeID);
            }
            if($userID!='')
            {
                $getrefund1->whereIn('refundorderdetail.userID', $userID);
            }
            if($paymenttype!='')
            {
                $getrefund1->whereIn('refundorderpayments.paymentType', $paymenttype);
            }
            if($saletype!='')
            {
                $getrefund1->whereIn('refundorderdetail.orderType', $saletype);
            }

            $getrefund = $getrefund1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.refundDate', 'refundorderdetail.created_at', 'refundorderpayments.paymentType', 'refundorderpayments.paidAmount', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name'));

            //return $getsales;

            $paymentmethoddata = ['getsales'=>$getsales, 'firstday'=>$firstday, 'lastday'=>$lastday, 'allstore'=>$allstore, 'allusers'=>$allusers, 'getrefund'=>$getrefund, 'userID'=>$userID, 'storeID'=>$storeID, 'allpayoption'=>$allpayoption, 'paymenttype'=>$paymenttype, 'saletype'=>$saletype];

            return view('sales_report_by_payment_method')->with('paymentmethoddata', $paymentmethoddata);
        }
    }

    public function salesmasterreportview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportsalesmaster')=='N' || session::get('loggindata.loggeduserpermission.viewreportsalesmaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }

            $userID = $request->input('user');
            $supplier = $request->input('supplier');
            $category = $request->input('category');
            $brand = $request->input('brand');
            $model = $request->input('model');
            $colour = $request->input('colour');
            $subcategorys = $request->input('subcategory');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();
            $allsupplier= mastersupplier::get();
            $allcategory= mastercategory::get();
            $allbrand= masterbrand::get();
            $allmodel= mastermodel::get();
            $allcolour = mastercolour::get();
            $allsubcategory = mastersubcategory::get();

            $getsales1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1')
            ->whereNull('orderitem.planID')
            ->whereNull('orderitem.planOrderID');
            if($userID!='')
            {
                $getsales1->whereIn('orderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $getsales1->whereIn('orderdetail.storeID', $storeID);
            }
            if($supplier!='')
            {
                $getsales1->whereIn('products.supplierID', $supplier);
            }
            if($category!='')
            {
                $getsales1->whereIn('products.categories', $category);
            }
            if($brand!='')
            {
                $getsales1->whereIn('products.brand', $brand);
            }
            if($model!='')
            {
                $getsales1->whereIn('products.model', $model);
            }
            if($colour!='')
            {
                $getsales1->whereIn('products.colour', $colour);
            }
            if($subcategorys!='')
            {
                $getsales1->whereIn('products.subcategory', $subcategorys);
            }
            
            $getsales = $getsales1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'orderitem.quantity', 'mastersupplier.suppliername', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.subTotal', 'orderitem.discountedAmount'));

            $getrefund1 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
            ->whereDate('refundDate', '<=', $lastday)
            ->leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->leftJoin('customer', 'customer.customerID', '=', 'refundorderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'refundorderdetail.userID')
            ->where('refundorderdetail.refundStatus', '1')
            ->whereNull('refundorderitem.planID')
            ->whereNull('refundorderitem.planOrderID');
            if($userID!='')
            {
                $getrefund1->whereIn('refundorderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $getrefund1->whereIn('refundorderdetail.storeID', $storeID);
            }
            if($supplier!='')
            {
                $getrefund1->whereIn('products.supplierID', $supplier);
            }
            if($category!='')
            {
                $getrefund1->whereIn('products.categories', $category);
            }
            if($brand!='')
            {
                $getrefund1->whereIn('products.brand', $brand);
            }
            if($model!='')
            {
                $getrefund1->whereIn('products.model', $model);
            }
            if($colour!='')
            {
                $getrefund1->whereIn('products.colour', $colour);
            }
            if($subcategorys!='')
            {
                $getrefund1->whereIn('products.subcategory', $subcategorys);
            }

            $getrefund = $getrefund1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'refundorderitem.quantity', 'mastersupplier.suppliername', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'refundorderitem.ppingst', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.subTotal', 'refundorderitem.discountedAmount'));

            //return $getsales;

            $masterdata = ['getsales'=>$getsales, 'firstday'=>$firstday, 'lastday'=>$lastday, 'allusers'=>$allusers, 'allstore'=>$allstore, 'userID'=>$userID, 'storeID'=>$storeID, 'getrefund'=>$getrefund, 'allsupplier'=>$allsupplier, 'allcategory'=>$allcategory, 'allbrand'=>$allbrand, 'allmodel'=>$allmodel, 'allcolour'=>$allcolour, 'supplier'=>$supplier, 'category'=>$category, 'brand'=>$brand, 'model'=>$model, 'colour'=>$colour, 'allsubcategory'=>$allsubcategory, 'subcategorys'=>$subcategorys];

            return view('sales_report_sales_master')->with('masterdata', $masterdata);
        }
    }

    public function salesconnectionview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportsalesconnection')=='N' || session::get('loggindata.loggeduserpermission.viewreportsalesconnection')=='')
        {
            return redirect('404');
        }
        else
        { 
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }
            $userID = $request->input('user');
            $plantype = $request->input('plantype');
            $planproposition = $request->input('planproposition');
            $plancategory = $request->input('plancategory');
            $planterm = $request->input('planterm');
            $planhandsetterm = $request->input('planhandsetterm');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();
            $allplantype = masterplantype::get();
            $allplanproposition = masterplanpropositiontype::get();
            $allplancategory = masterplancategory::get();
            $allplanterm = masterplanterm::get();
            $allplanhandsetterm = masterplanhandsetterm::get(); 

            $getconnection1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
            ->leftJoin('masterplanpropositiontype', 'masterplanpropositiontype.planpropositionID', '=', 'plan.planpropositionID')
            ->leftJoin('masterplantype', 'masterplantype.plantypeID', '=', 'plan.plantypeID')
            ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'plan.planhandsetterm')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getconnection1->whereIn('orderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $getconnection1->whereIn('orderdetail.storeID', $storeID);
            }
            if($plantype!='')
            {
                $getconnection1->whereIn('plan.plantypeID', $plantype);
            }
            if($planproposition!='')
            {
                $getconnection1->whereIn('plan.planpropositionID', $planproposition);
            }
            if($plancategory!='')
            {
                $getconnection1->whereIn('plan.plancategoryID', $plancategory);
            }
            if($planterm!='')
            {
                $getconnection1->whereIn('plan.planterm', $planterm);
            }
            if($planhandsetterm!='')
            {
                $getconnection1->whereIn('plan.planhandsetterm', $planhandsetterm);
            }
            
            $getconnection = $getconnection1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei'));

            $getrefundconnection1 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
            ->whereDate('refundDate', '<=', $lastday)
            ->leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
            ->leftJoin('masterplanpropositiontype', 'masterplanpropositiontype.planpropositionID', '=', 'plan.planpropositionID')
            ->leftJoin('masterplantype', 'masterplantype.plantypeID', '=', 'plan.plantypeID')
            ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'plan.planhandsetterm')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'refundorderitem.stockgroup')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'refundorderitem.stockID')
            ->leftJoin('customer', 'customer.customerID', '=', 'refundorderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'refundorderdetail.userID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getrefundconnection1->whereIn('refundorderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $getrefundconnection1->whereIn('refundorderdetail.storeID', $storeID);
            }
            if($plantype!='')
            {
                $getrefundconnection1->whereIn('plan.plantypeID', $plantype);
            }
            if($planproposition!='')
            {
                $getrefundconnection1->whereIn('plan.planpropositionID', $planproposition);
            }
            if($plancategory!='')
            {
                $getrefundconnection1->whereIn('plan.plancategoryID', $plancategory);
            }
            if($planterm!='')
            {
                $getrefundconnection1->whereIn('plan.planterm', $planterm);
            }
            if($planhandsetterm!='')
            {
                $getrefundconnection1->whereIn('plan.planhandsetterm', $planhandsetterm);
            }
            
            $getrefundconnection = $getrefundconnection1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei'));

            $connectiondata = ['getconnection'=>$getconnection, 'userID'=>$userID, 'storeID'=>$storeID, 'firstday'=>$firstday, 'lastday'=>$lastday, 'allstore'=>$allstore, 'allusers'=>$allusers, 'getrefundconnection'=>$getrefundconnection, 'allplantype'=>$allplantype, 'allplanproposition'=>$allplanproposition, 'allplancategory'=>$allplancategory, 'allplanterm'=>$allplanterm, 'allplanhandsetterm'=>$allplanhandsetterm, 'plantype'=>$plantype, 'planproposition'=>$planproposition, 'plancategory'=>$plancategory, 'planterm'=>$planterm, 'planhandsetterm'=>$planhandsetterm];

            return view('sales_report_sales_connection')->with('connectiondata', $connectiondata);
        }
    }

    public function profitbyuserview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportprofitbyuser')=='N' || session::get('loggindata.loggeduserpermission.viewreportprofitbyuser')=='')
        {
            return redirect('404');
        }
        else
        { 
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }

            $userID = $request->input('user');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();

            $userprofit1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1')
            ->whereNull('orderitem.planID')
            ->whereNull('orderitem.planOrderID');
            if($userID!='')
            {
                $userprofit1->whereIn('orderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $userprofit1->whereIn('orderdetail.storeID', $storeID);
            }

            $userprofit = $userprofit1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'orderitem.quantity', 'mastersupplier.suppliername', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.subTotal', 'orderitem.discountedAmount'));

            $userrefund1 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
            ->whereDate('refundDate', '<=', $lastday)
            ->leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->leftJoin('customer', 'customer.customerID', '=', 'refundorderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'refundorderdetail.userID')
            ->where('refundorderdetail.refundStatus', '1')
            ->whereNull('refundorderitem.planID')
            ->whereNull('refundorderitem.planOrderID');
            if($userID!='')
            {
                $userrefund1->whereIn('refundorderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $userrefund1->whereIn('refundorderdetail.storeID', $storeID);
            }

            $userrefund = $userrefund1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'refundorderitem.quantity', 'mastersupplier.suppliername', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'refundorderitem.ppingst', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.subTotal', 'refundorderitem.discountedAmount'));

            //return $userprofit;

            $userprofitdata = ['userprofit'=>$userprofit, 'firstday'=>$firstday, 'lastday'=>$lastday, 'userrefund'=>$userrefund, 'allstore'=>$allstore, 'allusers'=>$allusers, 'userID'=>$userID, 'storeID'=>$storeID];

            return view('profit_report_profit_by_user')->with('userprofitdata', $userprofitdata);
        }
    }

    public function profitbycategoryview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportprofitbycategory')=='N' || session::get('loggindata.loggeduserpermission.viewreportprofitbycategory')=='')
        {
            return redirect('404');
        }
        else
        {
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }

            $userID = $request->input('user');
            $categoryID = $request->input('category');
            $subcategoryID = $request->input('subcategory');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();
            $allcategory = mastercategory::get();
            $allsubcategory = mastersubcategory::get();

            $userprofit1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1')
            ->whereNull('orderitem.planID')
            ->whereNull('orderitem.planOrderID');
            if($userID!='')
            {
                $userprofit1->whereIn('orderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $userprofit1->whereIn('orderdetail.storeID', $storeID);
            }
            if($categoryID!='')
            {
                $userprofit1->whereIn('products.categories', $categoryID);
            }
            if($subcategoryID!='')
            {
                $userprofit1->whereIn('products.subcategory', $subcategoryID);
            }

            $userprofit = $userprofit1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'orderitem.quantity', 'mastersupplier.suppliername', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.subTotal', 'orderitem.discountedAmount'));

            $userrefund1 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
            ->whereDate('refundDate', '<=', $lastday)
            ->leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->leftJoin('customer', 'customer.customerID', '=', 'refundorderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'refundorderdetail.userID')
            ->where('refundorderdetail.refundStatus', '1')
            ->whereNull('refundorderitem.planID')
            ->whereNull('refundorderitem.planOrderID');
            if($userID!='')
            {
                $userrefund1->whereIn('refundorderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $userrefund1->whereIn('refundorderdetail.storeID', $storeID);
            }
            if($categoryID!='')
            {
                $userrefund1->whereIn('products.categories', $categoryID);
            }
            if($subcategoryID!='')
            {
                $userrefund1->whereIn('products.subcategory', $subcategoryID);
            }

            $userrefund = $userrefund1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'refundorderitem.quantity', 'mastersupplier.suppliername', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'refundorderitem.ppingst', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.subTotal', 'refundorderitem.discountedAmount'));

            //return $userprofit;

            $userprofitdata = ['userprofit'=>$userprofit, 'firstday'=>$firstday, 'lastday'=>$lastday, 'userID'=>$userID, 'storeID'=>$storeID, 'allstore'=>$allstore, 'allusers'=>$allusers, 'allcategory'=>$allcategory, 'userrefund'=>$userrefund, 'categoryID'=>$categoryID, 'subcategoryID'=>$subcategoryID, 'allsubcategory'=>$allsubcategory];

            return view('profit_report_profit_by_category')->with('userprofitdata', $userprofitdata);
        }
    }

    public function profitbyconnectionview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportprofitbyconnection')=='N' || session::get('loggindata.loggeduserpermission.viewreportprofitbyconnection')=='')
        {
            return redirect('404');
        }
        else
        { 
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }

            $userID = $request->input('user');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();

            $getconnection1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
            ->leftJoin('masterplanpropositiontype', 'masterplanpropositiontype.planpropositionID', '=', 'plan.planpropositionID')
            ->leftJoin('masterplantype', 'masterplantype.plantypeID', '=', 'plan.plantypeID')
            ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'plan.planhandsetterm')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getconnection1->whereIn('orderdetail.userID', $userID);
            }
            if($storeID!='')
            {
                $getconnection1->whereIn('orderdetail.storeID', $storeID);
            }

            $getconnection = $getconnection1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.spingst', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'orderitem.Comission', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'plan.plancomission', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei'));

            $getrefundconnection1 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
            ->whereDate('refundDate', '<=', $lastday)
            ->leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
            ->leftJoin('masterplanpropositiontype', 'masterplanpropositiontype.planpropositionID', '=', 'plan.planpropositionID')
            ->leftJoin('masterplantype', 'masterplantype.plantypeID', '=', 'plan.plantypeID')
            ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'plan.planhandsetterm')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'refundorderitem.stockgroup')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'refundorderitem.stockID')
            ->leftJoin('customer', 'customer.customerID', '=', 'refundorderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'refundorderdetail.userID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getrefundconnection1->whereIn('refundorderdetail.userID', $userID);
            }
            if($storeID)
            {
                $getrefundconnection1->whereIn('refundorderdetail.storeID', $storeID);
            }
            
            $getrefundconnection = $getrefundconnection1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.spingst', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'refundorderitem.Comission', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'plan.plancomission', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei'));

            //return $getsales;

            $connectiondata = ['getconnection'=>$getconnection, 'userID'=>$userID, 'storeID'=>$storeID, 'allstore'=>$allstore, 'allusers'=>$allusers, 'firstday'=>$firstday, 'lastday'=>$lastday, 'getrefundconnection'=>$getrefundconnection];

            return view('profit_report_profit_by_connection')->with('connectiondata', $connectiondata);
        }
    }

    public function salesbyuserview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportsalesbyuser')=='N' || session::get('loggindata.loggeduserpermission.viewreportsalesbyuser')=='')
        {
            return redirect('404');
        }
        else
        {
            if($request->input('startdate') != "")
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if($request->input('enddate') != "")
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            
            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }

            $userID= $request->input('user');
            $suppliers= $request->input('supplier');
            $brands = $request->input('brand');
            $models = $request->input('model');
            $colours = $request->input('colour');
            $categorys = $request->input('category');
            $subcategorys = $request->input('subcategory');

            $getusersales1 = orderitem::leftJoin('orderdetail','orderdetail.orderID', '=', 'orderitem.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->whereDate('orderdetail.orderDate', '>=', $firstday)
            ->whereDate('orderdetail.orderDate', '<=', $lastday)
            ->where('orderdetail.orderstatus', '1')
            ->whereNull('orderitem.planID')
            ->whereNull('orderitem.planOrderID');
            if($storeID!='') { 
                $getusersales1->whereIn('orderdetail.storeID', $storeID);
            }
            if($userID!='') {
                $getusersales1->whereIn('orderdetail.userID', $userID);
            }
            if($suppliers!='')
            {
                $getusersales1->whereIn('products.supplierID', $suppliers);
            }
            if($brands!='')
            {
                $getusersales1->whereIn('products.brand', $brands);
            }
            if($models!='')
            {
                $getusersales1->whereIn('products.model', $models);
            }
            if($colours!='')
            {
                $getusersales1->whereIn('products.colour', $colours);
            }
            if($categorys!='')
            {
                $getusersales1->whereIn('products.categories', $categorys);
            }
            if($subcategorys!='')
            {
                $getusersales1->whereIn('products.subcategory', $subcategorys);
            }

            $getusersales = $getusersales1->get(array('orderdetail.orderID', 'orderdetail.orderDate', 'orderdetail.created_at', 'products.barcode', 'products.productname', 'orderitem.quantity', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.discountedAmount', 'orderitem.subTotal', 'mastersupplier.suppliername', 'mastercolour.colourname', 'masterbrand.brandname', 'mastermodel.modelname', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname', 'orderdetail.storeID', 'orderdetail.userID', 'products.supplierID', 'products.brand', 'products.model', 'products.colour', 'products.categories', 'productstock.productimei', 'products.subcategory'));

            $getuserrefund1 = refundorderitem::leftJoin('refundorderdetail','refundorderdetail.refundInvoiceID', '=', 'refundorderitem.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'refundorderitem.stockID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->whereDate('refundorderdetail.refundDate', '>=', $firstday)
            ->whereDate('refundorderdetail.refundDate', '<=', $lastday)
            ->where('refundorderdetail.refundStatus', '1')
            ->whereNull('refundorderitem.planID')
            ->whereNull('refundorderitem.planOrderID');
            if ($storeID!='') {
                $getuserrefund1->whereIn('refundorderdetail.storeID', $storeID);
            }
            if ($userID!='') {
                $getuserrefund1->whereIn('refundorderdetail.refundBy', $userID);
            }
            if($suppliers!='')
            {
                $getuserrefund1->whereIn('products.supplierID', $suppliers);
            }
            if($brands!='')
            {
                $getuserrefund1->whereIn('products.brand', $brands);
            }
            if($models!='')
            {
                $getuserrefund1->whereIn('products.model', $models);
            }
            if($colours!='')
            {
                $getuserrefund1->whereIn('products.colour', $colours);
            }
            if($categorys!='')
            {
                $getuserrefund1->whereIn('products.categories', $categorys);
            }
            if($subcategorys!='')
            {
                $getuserrefund1->whereIn('products.subcategory', $subcategorys);
            }

            $getuserrefund = $getuserrefund1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.refundDate', 'refundorderdetail.created_at', 'products.barcode', 'products.productname', 'refundorderitem.quantity', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.discountedAmount', 'refundorderitem.subTotal', 'mastersupplier.suppliername', 'mastercolour.colourname', 'masterbrand.brandname', 'mastermodel.modelname', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname', 'refundorderdetail.storeID', 'refundorderdetail.refundBy', 'products.supplierID', 'products.brand', 'products.model', 'products.colour', 'products.categories','productstock.productimei', 'products.subcategory'));

            $alluser = User::get();
            $allstore = store::get();
            $allsupplier = mastersupplier::get();
            $allbrand = masterbrand::get();
            $allmodel = mastermodel::get();
            $allcolour = mastercolour::get();
            $allcategory = mastercategory::get();
            $allsubcategory = mastersubcategory::get();

            //return $getusersales;
            $with = array(
                'getusersales'=>$getusersales,
                'getuserrefund'=>$getuserrefund,
                'alluser'=>$alluser,
                'allstore'=>$allstore,
                'allsupplier'=>$allsupplier,
                'allbrand'=>$allbrand,
                'allmodel'=>$allmodel,
                'allcolour'=>$allcolour,
                'allcategory'=>$allcategory,
                'userID'=>$userID,
                'storeID'=>$storeID,
                'firstday'=>$firstday,
                'lastday'=>$lastday,
                'suppliers'=>$suppliers,
                'brands'=>$brands,
                'models'=>$models,
                'colours'=>$colours,
                'categorys'=>$categorys,
                'allsubcategory'=>$allsubcategory,
                'subcategorys'=>$subcategorys
            );
            return view('sales_report_by_user')->with($with);
        }
    }

    public function salesbyuserexcelexport(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportsalesbyuser')=='N' || session::get('loggindata.loggeduserpermission.viewreportsalesbyuser')=='')
        {
            return redirect('404');
        }
        else
        {
            $firstday = $request->input('firstday');
            $lastday = $request->input('lastday');
            $userID = $request->input('userid');
            $storeID = $request->input('storeid');

            $getusersales = orderitem::leftJoin('orderdetail','orderdetail.orderID', '=', 'orderitem.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->whereBetween('orderdetail.orderDate', [$firstday, $lastday])
            ->where('orderdetail.userID', $userID)
            ->where('orderdetail.orderstatus', '1')
            ->whereNull('orderitem.planID')
            ->whereNull('orderitem.planOrderID')
            ->where('orderdetail.storeID', 'LIKE', '%'.$storeID.'%')
            ->get(array('orderdetail.orderID', 'orderdetail.orderDate', 'orderdetail.created_at', 'products.barcode', 'products.productname', 'orderitem.quantity', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.discountedAmount', 'orderitem.subTotal', 'mastersupplier.suppliername', 'mastercolour.colourname', 'masterbrand.brandname', 'mastermodel.modelname', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname'));

            $getuserrefund = refundorderitem::leftJoin('refundorderdetail','refundorderdetail.refundInvoiceID', '=', 'refundorderitem.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->whereBetween('refundorderdetail.refundDate', [$firstday, $lastday])
            ->where('refundorderdetail.refundBy', $userID)
            ->where('refundorderdetail.refundStatus', '1')
            ->whereNull('refundorderitem.planID')
            ->whereNull('refundorderitem.planOrderID')
            ->where('refundorderdetail.storeID', 'LIKE', '%'.$storeID.'%')
            ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.refundDate', 'refundorderdetail.created_at', 'products.barcode', 'products.productname', 'refundorderitem.quantity', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.discountedAmount', 'refundorderitem.subTotal', 'mastersupplier.suppliername', 'mastercolour.colourname', 'masterbrand.brandname', 'mastermodel.modelname', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname'));

            $with = array(
                'getusersales'=>$getusersales,
                'getuserrefund'=>$getuserrefund
            );

            //return $getusersales;

             /*return Excel::create('excel_data', function($excel) use ($getusersales) {
            $excel->sheet('mySheet', function($sheet) use ($getusersales)
            {
                $sheet->fromArray($getusersales);
            });
        })->export('csv');*/
            

            return Excel::download($with, 'users.xlsx');

            //return $getusersales->download('users.csv');
        }
    }

    public function stockhistoryview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportstockhistory')=='N' || session::get('loggindata.loggeduserpermission.viewreportstockhistory')=='')
        {
            return redirect('404');
        }
        else
        {
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }
            $userID = $request->input('user');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();

            $getorderdetail1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1');
            if($storeID!='')
            {
                $getorderdetail1->whereIn('orderdetail.storeID', $storeID);
            }
            if($userID!='')
            {
                $getorderdetail1->whereIn('orderdetail.userID', $userID);
            }

            $getorderdetail = $getorderdetail1->get(array('orderdetail.orderID', 'orderdetail.created_at', 'orderitem.quantity', 'orderitem.spingst', 'orderitem.discountedAmount', 'orderitem.salePrice', 'orderitem.subTotal', 'products.barcode', 'products.productname', 'store.store_name', 'users.name'));

            $with = array(
                'getorderdetail'=>$getorderdetail,
                'storeID'=>$storeID,
                'userID'=>$userID,
                'firstday'=>$firstday,
                'lastday'=>$lastday,
                'allstore'=>$allstore,
                'allusers'=>$allusers
            );
            return view('stock_report_stockhistory')->with($with);
        }
    }

    public function productreceivedview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportproductreceive')=='N' || session::get('loggindata.loggeduserpermission.viewreportproductreceive')=='')
        {
            return redirect('404');
        }
        else
        {
            /*$userID = session::get('loggindata.loggedinuser.id');
            $get_store_rep = User::get();
            $get_productpurchaseorder = productpurchaseorder::with('poitem')->with('posupplier')->with('get_store')->where('poprocessstatus','!=',0)->get();*/
            //return $get_productpurchaseorder;

            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            $allstore = store::get();
            $alluser = loggeduser::get();

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }
            $userID = $request->input('user');

            $purchaseorder1 = productpurchaseorder::with('poitem')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'productpurchaseorder.supplierID')
            ->leftJoin('store', 'store.store_id', '=', 'productpurchaseorder.storeID')
            ->leftJoin('users', 'users.id', '=', 'productpurchaseorder.userID')
            ->where('productpurchaseorder.poprocessstatus', '!=', 0)
            ->whereDate('productpurchaseorder.created_at', '>=', $firstday)
            ->whereDate('productpurchaseorder.created_at', '<=', $lastday);
            if($storeID!='')
            {
                $purchaseorder1->whereIn('productpurchaseorder.storeID', $storeID);
            }
            if($userID!='')
            {
                $purchaseorder1->whereIn('productpurchaseorder.userID', $userID);
            }
            
            $purchaseorder = $purchaseorder1->get(array(
                'productpurchaseorder.ponumber',
                'productpurchaseorder.porefrencenumber',
                'productpurchaseorder.docketnumber',
                'productpurchaseorder.poprocessstatus',
                'productpurchaseorder.ponote',
                'productpurchaseorder.supplierID',
                'productpurchaseorder.userID',
                'productpurchaseorder.storeID',
                'productpurchaseorder.created_at',
                'store.store_name',
                'users.name'
            ));

            $with = array(
                'purchaseorder'=>$purchaseorder,
                'firstday'=>$firstday,
                'lastday'=>$lastday,
                'allstore'=>$allstore,
                'alluser'=>$alluser,
                'storeID'=>$storeID,
                'userID'=>$userID
            );

            return view('stock_report_productreceive')->with($with);
        }
    }

    public function productreceived_detail($ponumber)
    {
        $userID = session::get('loggindata.loggedinuser.id');
        $get_store_rep = User::get();
        $get_productpurchaseorder = productpurchaseorder::with('get_user')->with('poitem')->with('get_po_received')->with('posupplier')->with('get_store')->where('ponumber',$ponumber)->first();
        $get_product = new product;
        $get_user = new User();
        $with = array(
            'get_productpurchaseorder'=>$get_productpurchaseorder,
            'get_product' => $get_product,
            'get_user' => $get_user
        );
        return view('stock_report_product_received_detail')->with($with);
    }

    public function reportstocktransferview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.viewreportstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        {
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }
            $userID = $request->input('user');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();

            $getstocktransfer1 = stocktransfer::whereDate('stocktransferDate', '>=', $firstday)
            ->whereDate('stocktransferDate', '<=', $lastday)
            ->leftJoin('stocktransferitems', 'stocktransferitems.stocktransferID', '=', 'stocktransfer.stocktransferID')
            ->leftJoin('products', 'products.productID', '=', 'stocktransferitems.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'stocktransferitems.stockID')
            ->leftJoin('store', 'store.store_id', '=', 'stocktransfer.fromStoreID')
            ->leftJoin('users', 'users.id', '=', 'stocktransfer.fromUserID')
            ->with('tostore')
            ->with('touser');
            if($storeID!='')
            {
                $getstocktransfer1->whereIn('stocktransfer.fromStoreID', $storeID);
            }
            if($userID!='')
            {
                $getstocktransfer1->whereIn('stocktransfer.fromUserID', $userID);
            }
            
            $getstocktransfer = $getstocktransfer1->get(array('stocktransfer.created_at', 'stocktransfer.stocktransferID', 'stocktransfer.stocktransferType', 'store.store_name', 'users.name', 'products.productname', 'products.barcode', 'stocktransferitems.quantity', 'stocktransfer.toStoreID', 'stocktransfer.toUserID', 'stocktransfer.stocktransferStatus', 'stocktransfer.consignmentnumber', 'stocktransfer.transfernote', 'stocktransfer.receivetrasnsferDate', 'stocktransferitems.receiveStatus'));

            //return $getstocktransfer;

            $with = array(
                'getstocktransfer'=>$getstocktransfer,
                'storeID'=>$storeID,
                'userID'=>$userID,
                'firstday'=>$firstday,
                'lastday'=>$lastday,
                'allstore'=>$allstore,
                'allusers'=>$allusers
            );
            return view('stock_report_stocktransfer')->with($with);
        }
    }

    public function stockreturnreportview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportstockreturn')=='N' || session::get('loggindata.loggeduserpermission.viewreportstockreturn')=='')
        {
            return redirect('404');
        }
        else
        {
            if(!empty($request->input('startdate')))
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($request->input('enddate')))
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }
            $userID = $request->input('user');
            $supplierID = $request->input('supplier');
            $returnstatus = $request->input('returnstatus');

            $allstore = store::get();
            $allusers = User::whereIn('userstatus', ['0', '1'])->get();
            $allsupplier = mastersupplier::get();

            $getstockreturn1 = stockreturn::whereDate('stockreturnDate', '>=', $firstday)
            ->whereDate('stockreturnDate', '<=', $lastday)
            ->leftJoin('stockreturnitems', 'stockreturnitems.stockreturnID', '=', 'stockreturn.stockreturnID')
            ->leftJoin('stockreturnpayments', 'stockreturnpayments.stockreturnID', '=', 'stockreturn.stockreturnID')
            ->leftJoin('products', 'products.productID', '=', 'stockreturnitems.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'stockreturnitems.stockID')
            ->leftJoin('demostock', 'demostock.demostockID', '=', 'stockreturnitems.demostockID')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'stockreturn.supplierID')
            ->leftJoin('store', 'store.store_id', '=', 'stockreturn.storeID')
            ->leftJoin('users', 'users.id', '=', 'stockreturn.userID');
            if($storeID!='')
            {
                $getstockreturn1->whereIn('stockreturn.storeID', $storeID);
            }
            if($userID!='')
            {
                $getstockreturn1->whereIn('stockreturn.userID', $userID);
            }
            if($supplierID!='')
            {   
                $getstockreturn1->whereIn('stockreturn.supplierID', $supplierID);
            }
            if($returnstatus!='')
            {
                $getstockreturn1->whereIn('stockreturn.stockreturnStatus', $returnstatus);
            }

            $getstockreturn = $getstockreturn1->get(array(
                'stockreturn.stockreturnID',
                'stockreturn.raNumber',
                'stockreturn.returnNote',
                'stockreturn.supplierID',
                'stockreturn.storeID',
                'stockreturn.userID',
                'stockreturn.stockreturnStatus',
                'stockreturn.stockreturnDate',
                'stockreturnitems.stockID',
                'stockreturnitems.refundItemID',
                'stockreturnitems.demostockID',
                'stockreturnitems.quantity',
                'stockreturnitems.ppingst',
                'stockreturnitems.total',
                'stockreturnpayments.returnamount',
                'products.barcode',
                'products.productname',
                'productstock.productimei',
                'mastersupplier.suppliername',
                'store.store_name',
                'users.name'
            ));

            $with = array(
                'getstockreturn'=>$getstockreturn,
                'storeID'=>$storeID,
                'userID'=>$userID,
                'firstday'=>$firstday,
                'lastday'=>$lastday,
                'allstore'=>$allstore,
                'allusers'=>$allusers,
                'allsupplier'=>$allsupplier,
                'supplierID'=>$supplierID,
                'returnstatus'=>$returnstatus
            );
            return view('stock_report_stockreturn')->with($with);
        }
    }

    public function reportstockholdingsview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportstockholding')=='N' || session::get('loggindata.loggeduserpermission.viewreportstockholding')=='')
        {
            return redirect('404');
        }
        else
        {
            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            elseif(session::get('loggindata.loggeduserstore.store_id')!='')
            {
                $storeID = [session::get('loggindata.loggeduserstore.store_id')];
            }
            else
            {
                $storeID = $request->input('store');
            }
            $supplierID = $request->input('supplier');
            $categoryID = $request->input('category');
            $subcategoryID = $request->input('subcategory');

            $allstore = store::get();
            $allsupplier = mastersupplier::get();
            $allcategory = mastercategory::get();
            $allsubcategory = mastersubcategory::get();

            $getstockholding1 = productstock::where('productquantity', '>', '0')
            ->leftJoin('products', 'products.productID', '=', 'productstock.productID')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('store', 'store.store_id', '=', 'productstock.storeID');
            if($storeID!='')
            {
                $getstockholding1->whereIn('productstock.storeID', $storeID);
            }
            if($supplierID!='')
            {
                $getstockholding1->whereIn('mastersupplier.supplierID', $supplierID);
            }
            if($categoryID!='')
            {
                $getstockholding1->whereIn('mastercategory.categoryID', $categoryID);
            }
            if($subcategoryID!='')
            {
                $getstockholding1->whereIn('products.subcategory', $subcategoryID);
            }

            $getstockholding = $getstockholding1->get(array(
                'productstock.productID',
                'productstock.productimei',
                'productstock.productquantity',
                'productstock.ppexgst',
                'productstock.pptax',
                'productstock.ppingst',
                'products.barcode',
                'products.productname',
                'products.spexgst',
                'products.spgst',
                'products.spingst',
                'products.colour',
                'products.model',
                'products.brand',
                'products.categories',
                'products.subcategory',
                'products.supplierID',
                'mastersupplier.suppliername',
                'mastercategory.categoryname',
                'masterbrand.brandname',
                'mastermodel.modelname',
                'mastercolour.colourname',
                'store.store_name'
            ));

            //return $getstocktransfer;

            $with = array(
                'getstockholding'=>$getstockholding,
                'storeID'=>$storeID,
                'allstore'=>$allstore,
                'allsupplier'=>$allsupplier,
                'supplierID'=>$supplierID,
                'categoryID'=>$categoryID,
                'allcategory'=>$allcategory,
                'subcategoryID'=>$subcategoryID,
                'allsubcategory'=>$allsubcategory
            );
            return view('stock_report_stockholding')->with($with);
        }
    }
}
