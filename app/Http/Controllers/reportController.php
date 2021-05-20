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
use App\storecash;

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
            $loggeduserstore= store::where('store_id', session::get('storeid'))->get();
        }
        else if($loggedinuser->usertypeRole == 'Admin')
        {
            $loggeduserstore= storeuser::where('userID', $loggedinuser->id)
            ->join('store', 'store.store_id', '=', 'storeuser.store_id')
            ->get(array(
                'store.store_id',
                'storeuser.userID',
                'store.store_code',
                'store.store_name',
                'store.store_address',
                'store.store_pincode',
                'store.store_contact',
                'store.store_email',
                'store.storeIP'
            ));
        }
        else
        {
            $loggeduserstore= storeuser::where('userID', $loggedinuser->id)
            ->join('store', 'store.store_id', '=', 'storeuser.store_id')
            ->where('store.storeIP', $loggedip)
            ->get();    
        }

        $loggedinsubmenu= mainmenu::with('submenu')->orderBy('mainmenuSrNum', 'ASC')->get();

        $loggeduserpermission= userpermission::where('userID', $loggedinuser->id)->first();

        $loggindata= ['loggedinuser'=>$loggedinuser,'loggeduserstore'=>$loggeduserstore, 'loggedinsubmenu'=>$loggedinsubmenu, 'loggeduserpermission'=>$loggeduserpermission];

        session::put('loggindata', $loggindata);

        if(session::get('loggindata.loggeduserstore') == "" || count(session::get('loggindata.loggeduserstore')) == 0)
        {
            if(session::get('loggindata.loggedinuser.usertypeRole') != 'Admin')
            {
                Auth::logout();
                return redirect('login')->with('error', 'You are trying to log-in in wrong store');
            }
        }

        if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
        {
            if(session::get('storeid')!='')
            {
                $frencadminstore= storeuser::where('userID', $loggedinuser->id)
                ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                ->get(array(
                    'store.store_id',
                    'storeuser.userID',
                    'store.store_code',
                    'store.store_name',
                    'store.store_address',
                    'store.store_pincode',
                    'store.store_contact',
                    'store.store_email',
                    'store.storeIP'
                ));

                if(count($frencadminstore) > 0)
                {
                    $allstore= $frencadminstore;
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else if(count(session::get('loggindata.loggeduserstore')) > '0')
            {
                $allstore= session::get('loggindata.loggeduserstore');
            }
            else
            {
                $allstore = store::get();
            }
        }
        else
        {
            $allstore= store::get();
        }

        session::put('allstore', $allstore);
            return $next($request);
        });

        /*$this->middleware('auth');

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
        });*/
    }

    public function instockview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewinstock')=='N' ||session::get('loggindata.loggeduserpermission.viewinstock')=='')
        {
            return redirect('404');
        } 
        else
        {
        	if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $getdevice1 = product::where('producttype', '!=', '')
            ->leftJoin('productstock', 'productstock.productID', '=', 'products.productID')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
            ->where('productstock.productquantity', '1');
            if(count($storeID) > 0)
            {
                $getdevice1->where('productstock.storeID', $storeID);
            }
            $getdevice= $getdevice1->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'productstock.productimei', 'productstock.productquantity', 'productstock.simnumber', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'productstock.created_at'));

            $getquantityproducts1 = product::with('productstockgroup')
            ->leftJoin('productstock', 'productstock.productID', '=', 'products.productID')
            ->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
            ->where('productstock.productquantity', '!=', 0)
            ->whereNull('products.producttype');
            if(count($storeID) > 0)
            {
                $getquantityproducts1->where('productstock.storeID', $storeID);
            }
            $getquantityproducts = $getquantityproducts1->get();
            //return $getquantityproducts;
            /*$getoutrightaccess1= product::whereNull('producttype')
            ->join('productstock', 'productstock.productID', '=', 'products.productID')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
            ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
            ->whereNotIn('masterstockgroup.stockpriceeffect', ['0.00'])
            ->where('productstock.productquantity', '!=', 0);
            if($storeID!='')
            {
                $getquantityproducts1->where('productstock.storeID', session::get('loggindata.loggeduserstore.store_id'));
            }
            $getoutrightaccess = $getoutrightaccess1->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'productstock.productimei', 'productstock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'productstock.created_at'));*/

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
            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            //return $storeID;

            $getdevice1 = product::where('producttype', '!=', '')
            ->leftJoin('demostock', 'demostock.productID', '=', 'products.productID')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('store', 'store.store_id', '=', 'demostock.storeID')
            ->where('demostock.productquantity', '1');
            if(count($storeID) > 0)
            {
                $getdevice1->whereIn('demostock.storeID', $storeID);
            }
            $getdevice = $getdevice1->get(array('products.stockcode', 'products.productname', 'products.barcode', 'products.spingst', 'demostock.productimei', 'demostock.productquantity', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'store.store_name', 'demostock.created_at'));

            /*if(session::get('loggindata.loggeduserstore')!='')
            {
                

                
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
            }*/

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
            /*$storeID = session::get('loggindata.loggeduserstore.store_id');*/

            //return $storeID;
            //return count(session::get('loggindata.loggeduserstore'));
            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            //return $storeID;

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }
            
            
            $allsale1 = orderdetail::leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->with('customer')
            ->with('orderpayment')
            ->with('orderitem')
            ->whereDate('orderdetail.orderDate', '>=', $firstday)
            ->whereDate('orderdetail.orderDate', '<=', $lastday);
            if(count($storeID) > 0)
            {
                $allsale1->whereIn('orderdetail.storeID', $storeID);
            }
            if($userID!='')
            {
                $allsale1->where('orderdetail.userID', $userID);
            }
            $allsale = $allsale1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.created_at', 'orderdetail.customerID', 'orderdetail.userID', 'orderdetail.storeID', 'users.name', 'store.store_name'));

            $refundsale1 = refundorderdetail::leftJoin('users', 'users.id', '=', 'refundorderdetail.refundBy')
            ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
            ->with('customer')
            ->with('orderpayment')
            ->with('orderitem')
            ->whereDate('refundorderdetail.refundDate', '>=', $firstday)
            ->whereDate('refundorderdetail.refundDate', '<=', $lastday);
            if(count($storeID) > 0)
            {
                $refundsale1->whereIn('refundorderdetail.storeID', $storeID);
            }
            if($userID!='')
            {
                $refundsale1->where('refundorderdetail.userID', $userID);
            }
            $refundsale = $refundsale1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.created_at', 'refundorderdetail.customerID', 'refundorderdetail.userID', 'refundorderdetail.storeID', 'users.name', 'store.store_name'));

            //return session::get('loggindata.loggeduserstore');
            /*if(session::get('loggindata.loggeduserstore')!='')
            {
                $allsale = orderdetail::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
                ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
                ->with('customer')
                ->with('orderpayment')
                ->with('orderitem')
                ->whereBetween('orderdetail.orderDate', [$firstday, $lastday])
                ->where('orderdetail.userID', 'LIKE', '%'.$userID.'%')
                ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.created_at', 'orderdetail.customerID', 'orderdetail.userID', 'orderdetail.storeID', 'users.name', 'store.store_name'));

                $refundsale = refundorderdetail::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->leftJoin('users', 'users.id', '=', 'refundorderdetail.refundBy')
                ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
                ->with('customer')
                ->with('orderpayment')
                ->with('orderitem')
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
                ->with('orderitem')
                ->where('orderdetail.userID', 'LIKE', '%'.$userID.'%')
                ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.created_at', 'orderdetail.customerID', 'orderdetail.userID', 'orderdetail.storeID', 'orderdetail.orderDate', 'users.name', 'store.store_name'));

                $refundsale = refundorderdetail::whereBetween('refundDate', [$firstday, $lastday])
                ->leftJoin('users', 'users.id', '=', 'refundorderdetail.refundBy')
                ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
                ->with('customer')
                ->with('orderpayment')
                ->with('orderitem')
                ->where('refundorderdetail.userID', 'LIKE', '%'.$userID.'%') 
                ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.created_at', 'refundorderdetail.customerID', 'refundorderdetail.userID', 'refundorderdetail.storeID', 'users.name', 'store.store_name'));
            }*/

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
                ->with('refundorderitemddata')
                ->where('orderdetail.orderID', $id)
                ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'orderitem.orderitemID', 'orderitem.productID', 'orderitem.stockID', 'orderitem.planID', 'orderitem.planOrderID', 'orderitem.plandetails', 'orderitem.discountedType', 'orderitem.discount', 'orderitem.discountedAmount', 'orderitem.quantity', 'orderitem.stockgroup', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.planMobilenumber', 'orderitem.subTotal', 'products.productname', 'products.barcode', 'products.stockcode', 'products.colour', 'products.model', 'products.brand', 'plan.plantypeID', 'plan.planpropositionID', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'users.name', 'store.store_name', 'store.store_address', 'store.store_contact', 'store.store_email', 'productstock.productimei', 'productstock.simnumber', 'users.username'));

                $totalorderitem = orderitem::where('orderID', $id)->count();
                $totalreunditem = refundorderitem::where('orderID', $id)->count(); 

                //return $saledetail;
                $with = array(
                    'saledetail'=>$saledetail,
                    'totalorderitem'=>$totalorderitem,
                    'totalreunditem'=>$totalreunditem
                );

                return view('report-salehistorydetail')->with($with);
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
                ->with('refundorderitemddata')
                ->where('orderdetail.orderID', $id)
                ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'orderitem.orderitemID', 'orderitem.productID', 'orderitem.stockID', 'orderitem.planID', 'orderitem.planOrderID', 'orderitem.plandetails', 'orderitem.discountedType', 'orderitem.discount', 'orderitem.discountedAmount', 'orderitem.quantity', 'orderitem.stockgroup', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.subTotal', 'orderitem.planMobilenumber', 'products.productname', 'products.barcode', 'products.stockcode', 'products.colour', 'products.model', 'products.brand', 'plan.plantypeID', 'plan.planpropositionID', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'users.name', 'store.store_name', 'store.store_address', 'store.store_contact', 'store.store_email', 'productstock.productimei', 'productstock.simnumber', 'users.username'));

                //return $saledetail;

                $totalorderitem = orderitem::where('orderID', $id)->count();
                $totalreunditem = refundorderitem::where('orderID', $id)->count(); 

                //return $saledetail;
                $with = array(
                    'saledetail'=>$saledetail,
                    'totalorderitem'=>$totalorderitem,
                    'totalreunditem'=>$totalreunditem
                );

                return view('report-salehistorydetail')->with($with);
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

    public function timesheet(Request $request)
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

            if($request->input('startdate')!="")
            {
                $firstday = date("Y-m-d", strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if($request->input('enddate')!="")
            {
                $lastday = date("Y-m-d", strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            $gettimesheet = rostertimesheet::whereDate('timesheetDate', '>=', $firstday)
            ->whereDate('timesheetDate', '<=', $lastday)->where('userID', $userID)->get();

            $getuser = loggeduser::where('id', $userID)->first();

            $getstore = store::where('store_id', $storeID)->first();

            //return $gettimesheet;
            $timesheetdata = ['gettimesheet'=>$gettimesheet, 'getuser'=>$getuser, 'getstore'=>$getstore, 'month'=>$month, 'year'=>$year, 'firstday'=>$firstday, 'lastday'=>$lastday];

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

    public function rostermanagerview(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.viewrostermanager')=='N' ||session::get('loggindata.loggeduserpermission.viewrostermanager')=='')
        {
            return redirect('404');
        } 
        else
        {
            //$allusers = loggeduser::get();

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

            $userID= $request->input('userid');

            if($userID!="")
            {
                $getuser = loggeduser::where('id', $userID)->first();
            }
            else
            {
                $getuser = '';
            }

            if(!empty($request->input('month')))
            {
                $month = $request->input('month');
            }
            else
            {
                $month = '';
            }

            if(!empty($request->input('year')))
            {
                $year = $request->input('year');
            }
            else
            {
                $year = '';
            }

            if(!empty($request->input('datefrom')))
            {
                $datefrom = date('Y-m-d', strtotime($request->input('datefrom')));
            }
            else
            {
                $datefrom = date('Y-m').'-01';
            }

            if(!empty($request->input('dateto')))
            {
                $dateto = date('Y-m-d', strtotime($request->input('dateto')));
            }
            else
            {
                $dateto = date('Y-m').'-30';
            }
            //return $datefrom.'/'.$dateto;

            $gettimesheet1 = rostertimesheet::whereDate('timesheetDate', '>=', $datefrom)
            ->whereDate('timesheetDate', '<=', $dateto);
            if($userID!='')
            {
                $gettimesheet1->where('userID', $userID);
            }
            if($month!='')
            {
                $gettimesheet1->where('timesheetMonth', $month);
            }
            if($year!='')
            {
                $gettimesheet1->where('timesheetYear', $year);
            }
            $gettimesheet = $gettimesheet1->get();

            //return $year;

            $unpaidweekdayshours1 = rostertimesheet::where('timesheetPayStatus', 'Unpaid')
            ->whereNotIn('timesheetDay', ['Sat', 'Sun']);
            if($userID!='')
            {
                $unpaidweekdayshours1->where('userID', $userID);
            }
            if($month!='')
            {
                $unpaidweekdayshours1->where('timesheetMonth', $month);
            }
            if($year!='')
            {
                $unpaidweekdayshours1->where('timesheetYear', $year);
            }
            if($datefrom!='')
            {
                $unpaidweekdayshours1->whereDate('timesheetDate', '>=', $datefrom)
                ->whereDate('timesheetDate', '<=', $dateto);
            }
            $unpaidweekdayshours = $unpaidweekdayshours1->get('timesheetWorkinghours');

            $unpaidweekdaysamount1 = rostertimesheet::where('timesheetPayStatus', 'Unpaid')
            ->whereNotIn('timesheetDay', ['Sat', 'Sun']);
            if($userID!='')
            {
                $unpaidweekdaysamount1->where('userID', $userID);
            }
            if($month!='')
            {
                $unpaidweekdaysamount1->where('timesheetMonth', $month);
            }
            if($year!='')
            {
                $unpaidweekdaysamount1->where('timesheetYear', $year);
            }
            if($datefrom!='')
            {
                $unpaidweekdaysamount1->whereDate('timesheetDate', '>=', $datefrom)
                ->whereDate('timesheetDate', '<=', $dateto);
            }
            $unpaidweekdaysamount = $unpaidweekdaysamount1->sum('timesheetHoursAmount');

            $unpaidsaturdayhours1 = rostertimesheet::where('timesheetPayStatus', 'Unpaid')
            ->where('timesheetDay', 'Sat');
            if($userID!='')
            {
                $unpaidsaturdayhours1->where('userID', $userID);
            }
            if($month!='')
            {
                $unpaidsaturdayhours1->where('timesheetMonth', $month);
            }
            if($year!='')
            {
                $unpaidsaturdayhours1->where('timesheetYear', $year);
            }
            if($datefrom!='')
            {
                $unpaidsaturdayhours1->whereDate('timesheetDate', '>=', $datefrom)
                ->whereDate('timesheetDate', '<=', $dateto);
            }
            $unpaidsaturdayhours = $unpaidsaturdayhours1->get('timesheetWorkinghours');

            $unpaidsaturdayamount1 = rostertimesheet::where('timesheetPayStatus', 'Unpaid')
            ->where('timesheetDay', 'Sat');
            if($userID!='')
            {
                $unpaidsaturdayamount1->where('userID', $userID);
            }
            if($month!='')
            {
                $unpaidsaturdayamount1->where('timesheetMonth', $month);
            }
            if($year!='')
            {
                $unpaidsaturdayamount1->where('timesheetYear', $year);
            }
            if($datefrom!='')
            {
                $unpaidsaturdayamount1->whereDate('timesheetDate', '>=', $datefrom)
                ->whereDate('timesheetDate', '<=', $dateto);
            }
            $unpaidsaturdayamount = $unpaidsaturdayamount1->sum('timesheetHoursAmount');

            $unpaidsundayhours1 = rostertimesheet::where('timesheetPayStatus', 'Unpaid')
            ->where('timesheetDay', 'Sun');
            if($userID!='')
            {
                $unpaidsundayhours1->where('userID', $userID);
            }
            if($month!='')
            {
                $unpaidsundayhours1->where('timesheetMonth', $month);
            }
            if($year!='')
            {
                $unpaidsundayhours1->where('timesheetYear', $year);
            }
            if($datefrom!='')
            {
                $unpaidsundayhours1->whereDate('timesheetDate', '>=', $datefrom)
                ->whereDate('timesheetDate', '<=', $dateto);
            }
            $unpaidsundayhours = $unpaidsundayhours1->get('timesheetWorkinghours');

            $unpaidsundayamount1 = rostertimesheet::where('timesheetPayStatus', 'Unpaid')
            ->where('timesheetDay', 'Sun');
            if($userID!='')
            {
                $unpaidsundayamount1->where('userID', $userID);
            }
            if($month!='')
            {
                $unpaidsundayamount1->where('timesheetMonth', $month);
            }
            if($year!='')
            {
                $unpaidsundayamount1->where('timesheetYear', $year);
            }
            if($datefrom!='')
            {
                $unpaidsundayamount1->whereDate('timesheetDate', '>=', $datefrom)
                ->whereDate('timesheetDate', '<=', $dateto);
            }
            $unpaidsundayamount = $unpaidsundayamount1->sum('timesheetHoursAmount');

            $timesheetdata = ['gettimesheet'=>$gettimesheet, 'getuser'=>$getuser, 'month'=>$month, 'year'=>$year, 'unpaidweekdayshours'=>$unpaidweekdayshours, 'unpaidweekdaysamount'=>$unpaidweekdaysamount, 'unpaidsaturdayhours'=>$unpaidsaturdayhours, 'unpaidsaturdayamount'=>$unpaidsaturdayamount, 'unpaidsundayhours'=>$unpaidsundayhours, 'unpaidsundayamount'=>$unpaidsundayamount, 'allusers'=>$allusers, 'datefrom'=>$datefrom, 'dateto'=>$dateto, 'userID'=>$userID];

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
            ],[
                'payday.required'=>'Please select atleast one day to pay',
                'userID.required'=>'Something went wrong with fetching data for Staff',
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checksalary = rostertimesheet::whereIn('timesheetID', $request->input('payday'))->where('timesheetPayStatus', 'Unpaid')->where('userID', $request->input('userID'))->count();

                if($checksalary > 0)
                {
                    $getactualdata = rostertimesheet::whereIn('timesheetID', $request->input('payday'))->where('timesheetPayStatus', 'Unpaid')->where('userID', $request->input('userID'))->update(['timesheetPayStatus'=> 'Paid']);

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

    public function rosterreportview(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.reportroster')=='N' || session::get('loggindata.loggeduserpermission.reportroster')=='')
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

            $userID = $request->input('user');

            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

            $roster1 = rostertimesheet::leftJoin('users', 'users.id', '=', 'rostertimesheet.userID')
            ->whereDate('rostertimesheet.timesheetDate', '>=', $firstday)
            ->whereDate('rostertimesheet.timesheetDate', '<=', $lastday);
            if($userID!='')
            {
                $roster1->whereIn('rostertimesheet.userID', $userID);
            }
            
            $roster = $roster1->get(array('rostertimesheet.timesheetDate', 'rostertimesheet.timesheetWorkinghours', 'rostertimesheet.timesheetHoursAmount', 'rostertimesheet.timesheetPayStatus', 'users.name', 'users.id'));

            //return $roster;

            $with = array(
                'roster'=>$roster,
                'firstday'=>$firstday,
                'lastday'=>$lastday,
                'userID'=>$userID,
                'allusers'=>$allusers
            );

            return view('report-roster')->with($with);            
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

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }
            
            /**********Getting Last EOD Inserted date for store************/
            $getlastinserted = eod::whereIn('storeID', $storeID)->latest()->first();

            if($getlastinserted != "")
            {
                $lastinserteddate = strtotime($getlastinserted->eodDate);
                $addtionaldate = date('Y-m-d', strtotime('+1 day', $lastinserteddate));
            }
            else
            {
                $addtionaldate = $todaydate;
            }
            
            /**********Getting Last EOD Inserted date for store************/

            $gettotal1 = orderdetail::leftJoin('orderpayments', 'orderpayments.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'orderpayments.paymentType')
            ->where('orderstatus', '1')
            ->whereDate('orderdetail.orderDate', '>=', $addtionaldate)
            ->whereDate('orderdetail.orderDate', '<=', $todaydate);
            if(count($storeID) > 0)
            {
                $gettotal1->whereIn('orderdetail.storeID', $storeID);
            }
            $gettotal = $gettotal1->get();

            $getrefundtotal1 = refundorderdetail::leftJoin('refundorderpayments', 'refundorderpayments.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'refundorderpayments.paymentType')
            ->where('refundStatus', '1')
            ->whereDate('refundorderdetail.refundDate', '>=', $addtionaldate)
            ->whereDate('refundorderdetail.refundDate', '<=', $todaydate);
            if(count($storeID) > 0)
            {
                $getrefundtotal1->whereIn('refundorderdetail.storeID', $storeID);
            }
            $getrefundtotal =$getrefundtotal1->get();

            $geteoddone = eod::whereIn('storeID', $storeID)
            ->leftJoin('users', 'users.id', '=', 'storeeod.userID')
            ->groupBy('storeeod.eodDate')
            ->get();

            //return $geteoddone;

            $paymentoptions = paymentoptions::where('paymentstatus', '1')->whereIn('paymenttype', ['Offline', 'Online'])->get();

            $geteodamount = store::whereIn('store_id', $storeID)->first();

            $storecash = storecash::whereIn('storeID', $storeID)
            ->leftJoin('users', 'users.id', '=', 'storecash.storecashInUser')
            ->with('storecashoutuser')
            ->get();

            $eoddata = ['todaydate'=>$todaydate, 'paymentoptions'=>$paymentoptions, 'gettotal'=>$gettotal, 'geteoddone'=>$geteoddone, 'geteodamount'=>$geteodamount, 'getrefundtotal'=>$getrefundtotal, 'storecash'=>$storecash];

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

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $geteod = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->where('orderstatus', '1')
            ->where('orderdetail.orderDate', $todaydate)
            ->whereIn('store.store_id', $storeID)
            ->with('customer')
            ->with('orderpayment')
            ->get();

            $getrefundeod = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
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
            ->whereIn('store.store_id', $storeID)
            ->with('customer')
            ->with('orderpayment')
            ->get();

            //return $getrefundeod;

            $gettotal = orderdetail::leftJoin('orderpayments', 'orderpayments.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'orderpayments.paymentType')
            ->where('orderstatus', '1')
            ->where('orderdetail.orderDate', $todaydate)
            ->whereIn('orderdetail.storeID', $storeID)
            ->get();

            $getrefundtotal = refundorderdetail::leftJoin('refundorderpayments', 'refundorderpayments.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'refundorderpayments.paymentType')
            ->where('refundStatus', '1')
            ->where('refundorderdetail.refundDate', $todaydate)
            ->whereIn('refundorderdetail.storeID', $storeID)
            ->get();

            $paymentoptions = paymentoptions::where('paymentstatus', '1')->whereIn('paymenttype', ['Offline', 'Online'])->get();

            $geteodamount = store::whereIn('store_id', $storeID)->first();

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
            ->whereIn('orderdetail.storeID', $storeID)
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $refundproductcategorysales = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('refundorderitem.planID')
            ->whereIn('refundorderdetail.storeID', $storeID)
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            //return $productcategorysales;

            $getorderedplan = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->where('orderitem.planID', '!=', '')
            ->whereIn('orderdetail.storeID', $storeID)
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $getrefundorderedplan = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->where('refundorderitem.planID', '!=', '')
            ->whereIn('refundorderdetail.storeID', $storeID)
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

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
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
            ->whereIn('store.store_id', $storeID)
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
            ->whereIn('store.store_id', $storeID)
            ->with('customer')
            ->with('orderpayment')
            ->get();

            //return $getrefundeod;

            $gettotal = orderdetail::leftJoin('orderpayments', 'orderpayments.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'orderpayments.paymentType')
            ->where('orderstatus', '1')
            ->where('orderdetail.orderDate', $todaydate)
            ->whereIn('orderdetail.storeID', $storeID)
            ->get();

            $getrefundtotal = refundorderdetail::leftJoin('refundorderpayments', 'refundorderpayments.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('paymentoptions', 'paymentoptions.paymentname', '=', 'refundorderpayments.paymentType')
            ->where('refundStatus', '1')
            ->where('refundorderdetail.refundDate', $todaydate)
            ->whereIn('refundorderdetail.storeID', $storeID)
            ->get();

            $paymentoptions = paymentoptions::where('paymentstatus', '1')->whereIn('paymenttype', ['Offline', 'Online'])->get();

            $geteodamount = store::whereIn('store_id', $storeID)->first();

            $planproposition = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcID', '1')
            ->get(); 

            $productcategory = mastercategory::where('categorystatus', '1')->get();

            $productcategorysales = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('orderitem.planID')
            ->whereIn('orderdetail.storeID', $storeID)
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $refundproductcategorysales = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('refundorderitem.planID')
            ->whereIn('refundorderdetail.storeID', $storeID)
            ->where('refundorderdetail.refundDate', $todaydate)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            //return $productcategorysales;

            $getorderedplan = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->where('orderitem.planID', '!=', '')
            ->whereIn('orderdetail.storeID', $storeID)
            ->where('orderdetail.orderDate', $todaydate)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $getrefundorderedplan = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->where('refundorderitem.planID', '!=', '')
            ->whereIn('refundorderdetail.storeID', $storeID)
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
                if(count(session::get('loggindata.loggeduserstore')) == 1)
                {
                    if(count(session::get('loggindata.loggeduserstore'))=='0')
                    {
                        $storeID = session::get('loggindata.loggeduserstore');
                    }
                    else
                    {
                        foreach(session::get('loggindata.loggeduserstore') as $stores)
                        {
                            $storeID[] = $stores->store_id; 
                        }
                    }

                    $getstore = store::whereIn('store_id', $storeID)->first();

                    $checkeod = eod::where('eodDate', $request->input('eoddate'))->whereIn('storeID', $storeID)->count();

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
                                    'storeReconAmount' => $request->input('eodamount')[$i],
                                    'eodAmount' => $request->input('tillamount')[$i],
                                    'eodNote' => $request->input('eodnote'),
                                    'storeID' => $getstore->store_id,
                                    'userID' => session::get('loggindata.loggedinuser.id')
                                ];
                        }
                        eod::insert($po);

                        $inserteod = new eod;
                        $inserteod->eodDate = $request->input('eoddate');
                        $inserteod->eodPaymentType = $request->input('storecredit');
                        $inserteod->storeReconAmount = $request->input('storeamount');
                        $inserteod->eodAmount = $request->input('storeamountrecon');
                        $inserteod->eodNote = $request->input('eodnote');
                        $inserteod->storeID = $getstore->store_id;
                        $inserteod->userID = session::get('loggindata.loggedinuser.id');
                        $inserteod->save();

                        $updatestore = store::where('store_id', $getstore->store_id)->first();
                        $updatestore->storeEODAmount = $request->input('storeamountrecon');
                        $updatestore->save();

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

    public function storecashin(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.reportEODtill')=='N' || session::get('loggindata.loggeduserpermission.reportEODtill')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'store'=>'required',
            'cashin'=>'required'
            ],[
                'store.required'=>'store id is required',
                'cashin.required'=>'Amount required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $date = date('Y-m-d');
                $store = $request->input('store');
                $cashin = $request->input('cashin');

                $checkstorecash = storecash::where('storecashdate', $date)->where('storeID', $store)->first();

                if($checkstorecash != "" && $checkstorecash->storecashIn != "")
                {
                    return redirect()->back()->with('error', 'You already enter cash IN. Please fill Cash out');
                }
                else
                {
                    $insertcash = new storecash;
                    $insertcash->storecashIn = $cashin;
                    $insertcash->storecashInUser = session::get('loggindata.loggedinuser.id');
                    $insertcash->storeID = $store;
                    $insertcash->storecashdate = $date;
                    $insertcash->save();

                    if($insertcash->save())
                    {
                        $updatestoreeodamount = store::where('store_id', $store)->first();
                        $updatestoreeodamount->storeEODAmount = $cashin;
                        $updatestoreeodamount->save();

                        return redirect()->back()->with('success', 'Cash IN submitted successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to submit Cash IN');
                    }
                }
            }
        }
    }

    public function storecashout(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.reportEODtill')=='N' || session::get('loggindata.loggeduserpermission.reportEODtill')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'store'=>'required',
            'cashout'=>'required'
            ],[
                'store.required'=>'store id is required',
                'cashout.required'=>'Amount required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $date = date('Y-m-d');
                $store = $request->input('store');
                $cashout = $request->input('cashout');

                $checkstorecash = storecash::where('storecashdate', $date)->first();

                if($checkstorecash == "")
                {
                    return redirect()->back()->with('error', 'Please submit Cash IN first. Wihtout that you cant submit cash OUT');
                }
                else if ($checkstorecash != "" && $checkstorecash->storecashOut != "") 
                {
                    return redirect()->back()->with('error', 'You already submitted Cash OUT.');
                }
                else
                {
                    $updatecash = storecash::where('storecashdate', $date)->where('storeID', $store)->first();
                    $updatecash->storecashOut = $cashout;
                    $updatecash->storecashoutUser = session::get('loggindata.loggedinuser.id');
                    $updatecash->storeID = $store;
                    $updatecash->save();

                    if($updatecash->save())
                    {
                        $updatestoreeodamount = store::where('store_id', $store)->first();
                        $updatestoreeodamount->storeEODAmount = $cashout;
                        $updatestoreeodamount->save();

                        return redirect()->back()->with('success', 'Cash OUT submitted successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to submit Cash OUT');
                    }
                }
            }
        }
    }

    public function reportpaymenttally($date, $storeid)
    {
        if(session::get('loggindata.loggeduserpermission.reportEODtill')=='N' || session::get('loggindata.loggeduserpermission.reportEODtill')=='')
        {
            return redirect('404');
        }
        else
        { 
            $dateeod = eod::where('eodDate', $date)->where('storeID', $storeid)->get();

            return view('report-paymenttally-by-date')->with('dateeod', $dateeod);
        }
    }

    public function storeeodreportview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.storeeodreport')=='N' ||session::get('loggindata.loggeduserpermission.storeeodreport')=='')
        {
            return redirect('404');
        } 
        else
        {
            if($request->input('eoddate') != "")
            {
                $todaydate = date('Y-m-d', strtotime($request->input('eoddate')));
            }
            else
            {
                $todaydate = date('Y-m-d')/*'2019-12-03'*/;
            }

            //return $request->input('store');

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            $getalleod1 = eod::where('eodDate', $todaydate)
            ->leftJoin('store', 'store.store_id', '=', 'storeeod.storeID')
            ->leftJoin('users', 'users.id', '=', 'storeeod.userID');
            if(count($storeID) > 0)
            {
                $getalleod1->whereIn('storeeod.storeID', $storeID);
            }
            $getalleod = $getalleod1->get();

            $eoddata = ['todaydate'=>$todaydate, 'getalleod'=>$getalleod, 'allstore'=>$allstore, 'storeID'=>$storeID];

            return view('report-store-eod')->with('eoddata', $eoddata);
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
            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }
            //$allusers = loggeduser::get();
            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

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
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->where('orderitem.planID', '!=', '')
            ->where('orderdetail.userID', $userID)
            ->where('orderdetail.orderMonth', $month)
            ->where('orderdetail.orderYear', $year)
            ->where('orderdetail.orderstatus', '1')
            ->get();

            $getrefundorderedplan = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderdetail.userID', $userID)
            ->where('refundorderdetail.refundMonth', $month)
            ->where('refundorderdetail.refundYear', $year)
            ->where('refundorderdetail.refundStatus', '1')
            ->get();

            $usertarget = personaltarget::where('userID', $userID)->where('month', $month)->where('year', $year)->get();

            //return $usertarget;

            $rostertimesheet = rostertimesheet::where('userID', $userID)->where('timesheetMonth', $month)->where('timesheetYear', $year)->get();

            $total = 0;

            foreach($rostertimesheet as $weekdayhours)
            {
                $temp = explode(":", $weekdayhours->timesheetWorkinghours);

                

                $total+= (int) $temp[0] * 3600;
                $total+= (int) $temp[1] * 60;
            }
            $formatted = sprintf('%02d:%02d',  
                            ($total / 3600), 
                            ($total / 60 % 60)); 
            

            $comissiondata = ['planproposition'=>$planproposition, 'getorderedplan'=>$getorderedplan, 'productcategorysales'=>$productcategorysales, 'allusers'=>$allusers, 'getuser'=>$getuser, 'month'=>$month, 'year'=>$year, 'userID'=>$userID, 'productcategory'=>$productcategory, 'usertarget'=>$usertarget, 'refundproductcategorysales'=>$refundproductcategorysales, 'getrefundorderedplan'=>$getrefundorderedplan, 'formatted'=>$formatted];

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

            /*$allusers = loggeduser::where('userstatus', '1')
            ->leftJoin('storeuser', 'storeuser.userID', '=', 'id')
            ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
            ->get();*/

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

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
            $updatetarget = personaltarget::where('userID', $request->input('userid'))->where('targetcategory', $request->input('category'))->where('month', $request->input('month'))->where('year', $request->input('year'))->first();

            if($updatetarget != "")
            {
                $updatetarget->target = $request->input('target');
                $updatetarget->save();

                if($updatetarget->save())
                {
                    $result = 'Success';
                    return $result;
                }
                else
                {
                    $result = 'Failed';
                    return $result;
                }
            }
            else
            {
                $inserttarget = new personaltarget;
                $inserttarget->userID = $request->input('userid');
                $inserttarget->targetcategory = $request->input('category');
                $inserttarget->target = $request->input('target');
                $inserttarget->month = $request->input('month');
                $inserttarget->year = $request->input('year');
                $inserttarget->save();

                if($inserttarget->save())
                {
                    $result = 'Success';
                    return $result;
                }
                else
                {
                    $result = 'Failed';
                    return $result;
                } 
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
                $storeID[] = session::get('storetrackerfilter.storeID');
            }
            else
            {
                if(count(session::get('loggindata.loggeduserstore'))=='0')
                {
                    $storeID = session::get('loggindata.loggeduserstore');
                }
                else
                {
                    foreach(session::get('loggindata.loggeduserstore') as $stores)
                    {
                        $storeID[] = $stores->store_id; 
                    }
                }
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

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allstore = store::get();
            }
            else
            {
                $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                ->join('store', 'store.store_id', '=', 'storeuser.store_id')
                ->get();

                if(count($frencadminstore) > 0)
                {
                    $allstore = $frencadminstore;
                }
                else
                {
                    $allstore = store::get();
                }
            }

            if(count(session::get('loggindata.loggeduserstore'))=='1')
            {
                $getstore = store::where('store_id', $storeID)->first();
            }
            else
            {
                $getstore = '';
            }

            $productcategory = mastercategory::where('categorystatus', '1')->get();

            $productcategorysales1 = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('orderitem.planID')
            ->whereNull('orderitem.planOrderID')
            ->where('orderdetail.orderMonth', $month)
            ->where('orderdetail.orderYear', $year)
            ->where('orderdetail.orderstatus', '1');
            if(count($storeID) > 0)
            {
                $productcategorysales1->whereIn('orderdetail.storeID', $storeID);
            }
            $productcategorysales = $productcategorysales1->get();

            $refundproductcategorysales1 = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->whereNull('refundorderitem.planID')
            ->whereNull('refundorderitem.planOrderID')
            ->where('refundorderdetail.refundMonth', $month)
            ->where('refundorderdetail.refundYear', $year)
            ->where('refundorderdetail.refundStatus', '1');
            if(count($storeID) > 0)
            {
                $refundproductcategorysales1->where('refundorderdetail.storeID', $storeID);
            }
            $refundproductcategorysales = $refundproductcategorysales1->get();

            $getorderedplan1 = orderdetail::leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->where('orderitem.planID', '!=', '')
            ->where('orderdetail.orderMonth', $month)
            ->where('orderdetail.orderYear', $year)
            ->where('orderdetail.orderstatus', '1');
            if(count($storeID) > 0)
            {
                $getorderedplan1->where('orderdetail.storeID', $storeID);
            }
            $getorderedplan = $getorderedplan1->get();

            $getrefundorderedplan1 = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
            ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderdetail.refundMonth', $month)
            ->where('refundorderdetail.refundYear', $year)
            ->where('refundorderdetail.refundStatus', '1');
            if(count($storeID) > 0)
            {
                $getrefundorderedplan1->where('refundorderdetail.storeID', $storeID);
            }
            $getrefundorderedplan = $getrefundorderedplan1->get();

            $storetarget1 = storetarget::where('month', $month)->where('year', $year);
            if(count($storeID) > 0)
            {
                $storetarget1->whereIn('storeID', $storeID);
            }
            $storetarget = $storetarget1->get();

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

            //$allstore = store::where('storestatus', '1')->get();
            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allstore = store::where('storestatus', '1')->get();
            }
            else
            {
                $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                ->join('store', 'store.store_id', '=', 'storeuser.store_id')
                ->get();

                if(count($frencadminstore) > 0)
                {
                    $allstore = $frencadminstore;
                }
                else
                {
                    $allstore = store::get();
                }
            }

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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');
            $paymenttype = $request->input('payoptions');
            $saletype = $request->input('saletype');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }
            $allpayoption = paymentoptions::get();

            $getsales1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderpayments', 'orderpayments.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1');

            if(count($storeID) > 0)
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

            if(count($storeID) > 0)
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');
            $supplier = $request->input('supplier');
            $category = $request->input('category');
            $brand = $request->input('brand');
            $model = $request->input('model');
            $colour = $request->input('colour');
            $subcategorys = $request->input('subcategory');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }
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
            if(count($storeID) > 0)
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
            if(count($storeID) > 0)
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

    public function salescombinemasterreportview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportsalesmastercombin')=='N' || session::get('loggindata.loggeduserpermission.viewreportsalesmastercombin')=='')
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');
            $supplier = $request->input('supplier');
            $category = $request->input('category');
            $brand = $request->input('brand');
            $model = $request->input('model');
            $colour = $request->input('colour');
            $subcategorys = $request->input('subcategory');

            $plantype = $request->input('plantype');
            $planproposition = $request->input('planproposition');
            $plancategory = $request->input('plancategory');
            $planterm = $request->input('planterm');
            $planhandsetterm = $request->input('planhandsetterm');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }
            $allsupplier= mastersupplier::get();
            $allcategory= mastercategory::get();
            $allbrand= masterbrand::get();
            $allmodel= mastermodel::get();
            $allcolour = mastercolour::get();
            $allsubcategory = mastersubcategory::get();
            $allplantype = masterplantype::get();
            $allplanproposition = masterplanpropositiontype::get();
            $allplancategory = masterplancategory::get();
            $allplanterm = masterplanterm::get();
            $allplanhandsetterm = masterplanhandsetterm::get();

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
            if(count($storeID) > 0)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getconnection1->whereIn('orderdetail.userID', $userID);
            }
            if(count($storeID) > 0)
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
            
            $getconnection = $getconnection1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername'));

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
            if(count($storeID) > 0)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getrefundconnection1->whereIn('refundorderdetail.userID', $userID);
            }
            if(count($storeID) > 0)
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
            
            $getrefundconnection = $getrefundconnection1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername'));

            //return $getsales;

            $masterdata = ['getsales'=>$getsales, 'firstday'=>$firstday, 'lastday'=>$lastday, 'allusers'=>$allusers, 'allstore'=>$allstore, 'userID'=>$userID, 'storeID'=>$storeID, 'getrefund'=>$getrefund, 'allsupplier'=>$allsupplier, 'allcategory'=>$allcategory, 'allbrand'=>$allbrand, 'allmodel'=>$allmodel, 'allcolour'=>$allcolour, 'supplier'=>$supplier, 'category'=>$category, 'brand'=>$brand, 'model'=>$model, 'colour'=>$colour, 'allsubcategory'=>$allsubcategory, 'subcategorys'=>$subcategorys, 'getconnection'=>$getconnection, 'getrefundconnection'=>$getrefundconnection, 'allplantype'=>$allplantype, 'allplanproposition'=>$allplanproposition, 'allplancategory'=>$allplancategory, 'allplanterm'=>$allplanterm, 'allplanhandsetterm'=>$allplanhandsetterm, 'plantype'=>$plantype, 'planproposition'=>$planproposition, 'plancategory'=>$plancategory, 'planterm'=>$planterm, 'planhandsetterm'=>$planhandsetterm];

            return view('sales_report_sales_master_combine')->with('masterdata', $masterdata);
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');
            $plantype = $request->input('plantype');
            $planproposition = $request->input('planproposition');
            $plancategory = $request->input('plancategory');
            $planterm = $request->input('planterm');
            $planhandsetterm = $request->input('planhandsetterm');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getconnection1->whereIn('orderdetail.userID', $userID);
            }
            if(count($storeID) > 0)
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
            
            $getconnection = $getconnection1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername'));

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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getrefundconnection1->whereIn('refundorderdetail.userID', $userID);
            }
            if(count($storeID) > 0)
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
            
            $getrefundconnection = $getrefundconnection1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername'));

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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();

            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

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
            if(count($storeID) > 0)
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
            if(count($storeID) > 0)
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');
            $categoryID = $request->input('category');
            $subcategoryID = $request->input('subcategory');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();
            
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

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
            if(count($storeID) > 0)
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
            if(count($storeID) > 0)
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');
            $plantype = $request->input('plantype');
            $planproposition = $request->input('planproposition');
            $plancategory = $request->input('plancategory');
            $planterm = $request->input('planterm');
            $planhandsetterm = $request->input('planhandsetterm');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();
            
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getconnection1->whereIn('orderdetail.userID', $userID);
            }
            if(count($storeID) > 0)
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

            $getconnection = $getconnection1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.productID', 'products.barcode', 'products.productname', 'products.spingst', 'products.barcode', 'products.stockcode', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'orderitem.plandiscount', 'orderitem.Comission', 'orderitem.planexgstamount', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'plan.plancomission', 'plan.planaddtionalcomission', 'orderitem.actualcomission', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername'));

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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '');
            if($userID!='')
            {
                $getrefundconnection1->whereIn('refundorderdetail.userID', $userID);
            }
            if(count($storeID) > 0)
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
            
            $getrefundconnection = $getrefundconnection1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.productID', 'products.barcode', 'products.productname', 'products.spingst', 'products.barcode', 'products.stockcode', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'refundorderitem.plandiscount', 'refundorderitem.Comission', 'refundorderitem.planexgstamount', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'plan.plancomission', 'plan.planaddtionalcomission', 'refundorderitem.actualcomission', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername'));

            //return $getsales;

            $connectiondata = ['getconnection'=>$getconnection, 'userID'=>$userID, 'storeID'=>$storeID, 'allstore'=>$allstore, 'allusers'=>$allusers, 'firstday'=>$firstday, 'lastday'=>$lastday, 'getrefundconnection'=>$getrefundconnection, 'allplantype'=>$allplantype, 'allplanproposition'=>$allplanproposition, 'allplancategory'=>$allplancategory, 'allplanterm'=>$allplanterm, 'allplanhandsetterm'=>$allplanhandsetterm, 'plantype'=>$plantype, 'planproposition'=>$planproposition, 'plancategory'=>$plancategory, 'planterm'=>$planterm, 'planhandsetterm'=>$planhandsetterm];

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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            //return count($storeID);

            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $alluser = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $alluser = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

            $allsupplier = mastersupplier::get();
            $allbrand = masterbrand::get();
            $allmodel = mastermodel::get();
            $allcolour = mastercolour::get();
            $allcategory = mastercategory::get();
            $allsubcategory = mastersubcategory::get();

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
            if(count($storeID) > 0) { 
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

            //return $userID;

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
            if (count($storeID) > 0) {
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();

            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

            $getorderdetail1 = orderdetail::whereDate('orderDate', '>=', $firstday)
            ->whereDate('orderDate', '<=', $lastday)
            ->leftJoin('orderitem', 'orderitem.orderID', '=', 'orderdetail.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'orderitem.stockID')
            ->leftJoin('customer', 'customer.customerID', '=', 'orderdetail.customerID')
            ->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
            ->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
            ->where('orderdetail.orderstatus', '1');
            if(count($storeID) > 0)
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

            //$allstore = store::get();
            //$alluser = loggeduser::get();

            if($request->input('store')!='')
            {
                $storeID = $request->input('store');
            }
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $alluser = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $alluser = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

            
            $userID = $request->input('user');

            $purchaseorder1 = productpurchaseorder::with('poitem')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'productpurchaseorder.supplierID')
            ->leftJoin('store', 'store.store_id', '=', 'productpurchaseorder.storeID')
            ->leftJoin('users', 'users.id', '=', 'productpurchaseorder.userID')
            ->where('productpurchaseorder.poprocessstatus', '!=', 0)
            ->whereDate('productpurchaseorder.created_at', '>=', $firstday)
            ->whereDate('productpurchaseorder.created_at', '<=', $lastday);
            if(count($storeID) > 0)
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
        $get_productpurchaseorder = productpurchaseorder::with('get_user')->with('poitem')->with('get_po_received')->with('posupplier')->with('get_store')->with('getproductstock')->where('ponumber',$ponumber)->first();
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }

            $getstocktransfer1 = stocktransfer::whereDate('stocktransferDate', '>=', $firstday)
            ->whereDate('stocktransferDate', '<=', $lastday)
            ->leftJoin('stocktransferitems', 'stocktransferitems.stocktransferID', '=', 'stocktransfer.stocktransferID')
            ->leftJoin('products', 'products.productID', '=', 'stocktransferitems.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'stocktransferitems.stockID')
            ->leftJoin('store', 'store.store_id', '=', 'stocktransfer.fromStoreID')
            ->leftJoin('users', 'users.id', '=', 'stocktransfer.fromUserID')
            ->with('tostore')
            ->with('touser');
            if(count($storeID) > 0)
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $userID = $request->input('user');
            $supplierID = $request->input('supplier');
            $returnstatus = $request->input('returnstatus');

            //$allstore = store::get();
            //$allusers = User::whereIn('userstatus', ['0', '1'])->get();
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

            if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $allusers = loggeduser::where('userstatus', '1')->get();
            }
            else
            {
                $allusers = storeuser::leftJoin('users', 'users.id', '=', 'storeuser.userID')
                ->whereIn('storeuser.store_id', $storeID)
                ->where('users.userstatus', '1')
                ->get();
            }
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
            if(count($storeID) > 0)
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
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $supplierID = $request->input('supplier');
            $categoryID = $request->input('category');
            $subcategoryID = $request->input('subcategory');

            //$allstore = store::get();
            if(session::get('loggindata.loggedinuser.usertypeRole') == 'Admin')
            {
                if(session::get('storeid')!='')
                {
                    $frencadminstore= storeuser::where('userID', session::get('loggindata.loggedinuser.id'))
                    ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
                    ->get(array(
                        'store.store_id',
                        'storeuser.userID',
                        'store.store_code',
                        'store.store_name',
                        'store.store_address',
                        'store.store_pincode',
                        'store.store_contact',
                        'store.store_email',
                        'store.storeIP'
                    ));

                    if(count($frencadminstore) > 0)
                    {
                        $allstore= $frencadminstore;
                    }
                    else
                    {
                        $allstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $allstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $allstore = store::get();
                }
            }
            else
            {
                $allstore= store::get();
            }

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
            if(count($storeID) > 0)
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

    public function upfrontdashboardview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.viewreportstockholding')=='N' || session::get('loggindata.loggeduserpermission.viewreportstockholding')=='')
        {
            return redirect('404');
        }
        else
        {
            return view('stock_report_stockholding');
        }
    }

    public function upfrontdetailedreportview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.upfrontreport')=='N' || session::get('loggindata.loggeduserpermission.upfrontreport')=='')
        {
            return redirect('404');
        }
        else
        {

            if($request->input('daterange')!="")
            {
                $date= explode('-', $request->input('daterange'));

                $firstday1 = date('Y-m-d', strtotime($date[0]));
                $lastday1 = date('Y-m-d', strtotime($date[1]));
            }

            if(!empty($firstday1))
            {
                $firstday = $firstday1;
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($lastday1))
            {
                $lastday = $lastday1;
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [1, 2, 3, 10])
            ->whereIn('masterplancategory.pcID', [1, 2]);
            
            $getconnection = $getconnection1->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'orderitem.actualcomission', 'orderitem.Comission', 'orderitem.planexgstamount', 'orderitem.plandiscount', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'plan.plancomission','plan.planaddtionalcomission','masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype'));

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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [1, 2, 3, 10])
            ->whereIn('masterplancategory.pcID', [1, 2]);
            
            $getrefundconnection = $getrefundconnection1->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                        'refundorderitem.Comission', 
                'refundorderitem.planexgstamount',
                'refundorderitem.plandiscount',
                'refundorderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'));

            $getconnection2 = orderdetail::whereDate('orderDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [2])
            ->whereIn('masterplancategory.pcID', [1])
            ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                'orderitem.Comission', 
                'orderitem.planexgstamount',
                'orderitem.plandiscount',
                'orderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'
            ));

            $getrefundconnection2 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [2])
            ->whereIn('masterplancategory.pcID', [1])
            ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                'refundorderitem.Comission', 
                'refundorderitem.planexgstamount',
                'refundorderitem.plandiscount',
                'refundorderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'
                ));

            $getconnection3 = orderdetail::whereDate('orderDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [2])
            ->whereIn('masterplancategory.pcID', [2])
            ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                'orderitem.Comission', 
                'orderitem.planexgstamount',
                'orderitem.plandiscount',
                'orderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'
                ));

            $getrefundconnection3 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [2])
            ->whereIn('masterplancategory.pcID', [2])
            ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                'refundorderitem.Comission', 
                'refundorderitem.planexgstamount',
                'refundorderitem.plandiscount',
                'refundorderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'
                ));

            $getconnection4 = orderdetail::whereDate('orderDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [3])
            ->whereIn('masterplancategory.pcID', [1])
            ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                'orderitem.Comission', 
                'orderitem.planexgstamount',
                'orderitem.plandiscount',
                'orderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'
                ));

            $getrefundconnection4 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [3])
            ->whereIn('masterplancategory.pcID', [1])
            ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                'refundorderitem.Comission', 
                'refundorderitem.planexgstamount',
                'refundorderitem.plandiscount',
                'refundorderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'
                ));

            $getconnection5 = orderdetail::whereDate('orderDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [10])
            ->whereIn('masterplancategory.pcID', [1])
            ->get(array('orderdetail.orderID', 'orderdetail.orderType', 'orderdetail.orderstatus', 'orderdetail.salenote', 'orderdetail.customerID', 'orderdetail.storeID', 'orderdetail.userID', 'orderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'orderitem.quantity', 'orderitem.plandetails', 'orderitem.planOrderID', 'orderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                'orderitem.Comission', 
                'orderitem.planexgstamount',
                'orderitem.plandiscount',
                'orderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'
                ));

            $getrefundconnection5 = refundorderdetail::whereDate('refundDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '')
            ->whereIn('masterplanpropositiontype.planpropositionID', [10])
            ->whereIn('masterplancategory.pcID', [1])
            ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.salenote', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.created_at', 'customer.customerfirstname', 'customer.customerlastname', 'customer.customermobilenumber', 'users.name', 'store.store_name', 'products.barcode', 'products.productname', 'products.stockcode', 'refundorderitem.quantity', 'refundorderitem.plandetails', 'refundorderitem.planOrderID', 'refundorderitem.subTotal', 'plan.plancode', 'plan.planname', 'plan.ppingst', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname', 'masterplantype.plantypename', 'masterplanterm.plantermname', 'masterplanhandsetterm.planhandsettermname', 'masterstockgroup.stockgroupname', 'productstock.productimei', 'mastersupplier.suppliername', 'store.store_code', 'customer.customertype',
                'refundorderitem.Comission', 
                'refundorderitem.planexgstamount',
                'refundorderitem.plandiscount',
                'refundorderitem.actualcomission',
                'plan.plancomission',
                'plan.planaddtionalcomission'
                ));

            $with = array(
                'getconnection'=>$getconnection,
                'getrefundconnection'=>$getrefundconnection,
                'getconnection2'=>$getconnection2,
                'getrefundconnection2'=>$getrefundconnection2,
                'getconnection3'=>$getconnection3,
                'getrefundconnection3'=>$getrefundconnection3,
                'getconnection4'=>$getconnection4,
                'getrefundconnection4'=>$getrefundconnection4,
                'getconnection5'=>$getconnection5,
                'getrefundconnection5'=>$getrefundconnection5,
                'firstday'=>$firstday,
                'lastday'=>$lastday
            );

            return view('upfront-detailedreport')->with($with);
        }
    }

    public function upfrontstoresummaryview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.upfrontreport')=='N' || session::get('loggindata.loggeduserpermission.upfrontreport')=='')
        {
            return redirect('404');
        }
        else
        {
            if($request->input('daterange')!="")
            {
                $date= explode('-', $request->input('daterange'));

                $firstday1 = date('Y-m-d', strtotime($date[0]));
                $lastday1 = date('Y-m-d', strtotime($date[1]));
            }

            if(!empty($firstday1))
            {
                $firstday = $firstday1;
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($lastday1))
            {
                $lastday = $lastday1;
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }
            
            $getconnection = orderdetail::whereDate('orderDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '')
            ->get();

            $getrefundconnection = refundorderdetail::whereDate('refundDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '')
            ->get();

            $with = array(
                'getconnection'=>$getconnection,
                'getrefundconnection'=>$getrefundconnection,
                'firstday'=>$firstday,
                'lastday'=>$lastday
            );

            return view('upfront-storesummary')->with($with);
        }
    }

    public function eodreportview(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.upfrontreport')=='N' || session::get('loggindata.loggeduserpermission.upfrontreport')=='')
        {
            return redirect('404');
        }
        else
        {
            if($request->input('daterange')!="")
            {
                $date= explode('-', $request->input('daterange'));

                $firstday1 = date('Y-m-d', strtotime($date[0]));
                $lastday1 = date('Y-m-d', strtotime($date[1]));
            }

            if(!empty($firstday1))
            {
                $firstday = $firstday1;
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty($lastday1))
            {
                $lastday = $lastday1;
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }
            
            $getconnection = orderdetail::whereDate('orderDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('orderdetail.orderstatus', '1')
            ->where('orderitem.planID', '!=', '')
            ->where('orderitem.planOrderID', '!=', '')
            ->get();

            $getrefundconnection = refundorderdetail::whereDate('refundDate', '>=', $firstday)
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
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->where('refundorderdetail.refundStatus', '1')
            ->where('refundorderitem.planID', '!=', '')
            ->where('refundorderitem.planOrderID', '!=', '')
            ->get();

            $with = array(
                'getconnection'=>$getconnection,
                'getrefundconnection'=>$getrefundconnection,
                'firstday'=>$firstday,
                'lastday'=>$lastday
            );

            return view('upfront-storesummary')->with($with);
        }
    }
}
