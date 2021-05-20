<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesByUser;

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
use App\productpurchaseorder;
use App\stocktransfer;
use App\stocktransferitem;

use App\productstock;
use App\productpurchaseorderitem;
use App\stockreturnitems;
use App\productsupplier;

class searchproductController extends Controller
{
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

    public function searchproductview()
    {   
        if(session::get('loggindata.loggeduserpermission.searchproducts')=='N' ||session::get('loggindata.loggeduserpermission.searchproducts')=='')
        {
            return redirect('404');
        } 
        else
        {
        	$firstday = '';//date('Y')."-".date('m')."-01";
        	$lastday = '';//date("Y-m-t", strtotime($firstday));
        	$getdetail = [];
        	$allstore = store::get();

        	$with = array(
        		'firstday'=>$firstday,
        		'lastday'=>$lastday,
        		'getdetail'=>$getdetail,
        		'allstore'=>$allstore
        	);

        	return view('searchproducts')->with($with);
        }
    }

    public function searchentity(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.searchproducts')=='N' ||session::get('loggindata.loggeduserpermission.searchproducts')=='')
        {
            return redirect('404');
        } 
        else
        {
        	$imei = $request->input('imei');
        	$barcode = $request->input('barcode');
            $instockbarcode = $request->input('instockbarcode');
        	if($request->input('startdate')!="")
            {
                $firstday = date('Y-m-d', strtotime($request->input('startdate')));
            }
            else
            {
                $firstday = '';
            }

            if($request->input('enddate')!="")
            {
                $lastday = date('Y-m-d', strtotime($request->input('enddate')));
            }
            else
            {
                $lastday = '';
            }

        	if(!empty($request->input('store')))
        	{
        		$storeID = $request->input('store');
        	}
        	else
        	{
        		$storeID = session::get('loggindata.loggeduserstore.store_id');
        	}
        	
        	$allstore = store::get();

        	if(!empty($imei) && !empty($barcode))
        	{
        		return redirect()->back()->with('error', 'Cannot Search Device and Barcode at same time.');
        	}
        	else if (!empty($imei))
        	{
        		/*$modifiedimei = preg_replace('/\s+/', ',', $imei);
        		$imeiarray = explode(',', $modifiedimei);*/

        		//return $imeiarray;
                $searchimei = productstock::where('productimei', $imei)->first();

                if($searchimei!="")
                {
                   $getdetail1 = productstock::where('productimei', $imei)
                    ->leftJoin('products', 'products.productID', '=', 'productstock.productID')
                    ->leftJoin('productpurchaseorder', 'productpurchaseorder.ponumber', '=', 'productstock.ponumber')
                    ->leftJoin('productpurchasereceivedetails', 'productpurchasereceivedetails.ponumber', '=', 'productstock.ponumber')
                    ->leftJoin('orderitem', 'orderitem.stockID', '=', 'productstock.psID')
                    ->leftJoin('orderdetail', 'orderdetail.orderID', '=', 'orderitem.orderID')
                    ->leftJoin('stocktransferitems', 'stocktransferitems.stockID', '=', 'productstock.psID')
                    ->leftJoin('stocktransfer', 'stocktransfer.stocktransferID', '=', 'stocktransferitems.stocktransferID')
                    ->leftJoin('refundorderitem', 'refundorderitem.stockID', '=', 'productstock.psID')
                    ->leftJoin('refundorderdetail', 'refundorderdetail.refundInvoiceID', '=', 'refundorderitem.refundInvoiceID')
                    ->leftJoin('stockreturnitems', 'stockreturnitems.stockID', '=', 'productstock.psID')
                    ->leftJoin('stockreturn', 'stockreturn.stockreturnID', '=', 'stockreturnitems.stockreturnID')
                    ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
                    ->leftJoin('store', 'store.store_id', '=', 'productpurchaseorder.storeID')
                    ->leftJoin('users', 'users.id', '=', 'orderdetail.userID');
                    if($storeID!='')
                    {
                        $getdetail1->where('productstock.storeID', $storeID);
                    }
                    
                    $getdetail = $getdetail1->get(array(
                        'productstock.psID',
                        'productstock.ponumber',
                        'productstock.productID',
                        'productstock.productimei',
                        'productstock.ppingst',
                        'productstock.productquantity',
                        'products.productname',
                        'products.barcode',
                        'products.spingst',
                        'products.supplierID',
                        'mastersupplier.suppliername',
                        'productpurchaseorder.ponumber',
                        'productpurchaseorder.poprocessstatus',
                        'productpurchaseorder.docketnumber',
                        'productpurchaseorder.porefrencenumber',
                        'productpurchasereceivedetails.receivedby',
                        'productpurchasereceivedetails.created_at',
                        'productpurchaseorder.storeID',
                        'orderdetail.orderID',
                        'orderdetail.orderDate',
                        'orderdetail.userID',
                        'refundorderdetail.refundInvoiceID',
                        'refundorderdetail.refundDate',
                        'refundorderdetail.refundBy',
                        'stocktransfer.stocktransferID',
                        'stocktransfer.toStoreID',
                        'stocktransfer.fromUserID',
                        'stocktransfer.toUserID',
                        'stocktransfer.stocktransferDate',
                        'stocktransfer.receivetrasnsferDate',
                        'stockreturn.stockreturnID',
                        'stockreturn.raNumber',
                        'stockreturn.stockreturnDate',
                        'stockreturn.userID',
                        'store.store_name',
                        'users.name'
                    ));

                    //$getbarcodedetail = [];

                    //return $getdetail;

                    $with = array(
                        'getdetail'=>$getdetail,
                        'firstday'=>$firstday,
                        'lastday'=>$lastday,
                        'allstore'=>$allstore
                    ); 

                    return view('searchproduct-imei')->with($with);
                }
                else
                {
                    return redirect()->back()->with('error', 'IMEI not found');   
                }
        	}
        	else if (!empty($barcode))
        	{
        		/*$modifiedbarcode = preg_replace('/\s+/', ',', $barcode);
        		$barcodearray = explode(',', $modifiedbarcode);*/
                //return $request->all();
                $productbarcode= product::where('barcode', $barcode)
                ->leftJoin('productsupplierdetail', 'productsupplierdetail.productID', '=', 'products.productID')
                ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'productsupplierdetail.productsupplier')
                ->first(array(
                    'products.productID',
                    'products.stockcode',
                    'products.productname',
                    'products.barcode',
                    'productsupplierdetail.product_name',
                    'productsupplierdetail.product_description',
                    'productsupplierdetail.productsku',
                    'productsupplierdetail.productsupplier',
                    'mastersupplier.suppliername',
                    'mastersupplier.supplierdescription'
                ));

                //return $productbarcode;
                if($productbarcode!="")
                {
                    $productstock1 = productstock::where('productID', $productbarcode->productID);
                    if($storeID!='')
                    {
                        $productstock1->where('productstock.storeID', $storeID);
                    }
                    $productstock = $productstock1->sum('productquantity');

                    $productpo1= productpurchaseorderitem::where('productID', $productbarcode->productID)
                    ->leftJoin('productpurchaseorder', 'productpurchaseorder.ponumber', '=', 'productpurchaseorderitem.ponumber')
                    ->leftJoin('productpurchasereceivedetails', 'productpurchasereceivedetails.poitemID', '=', 'productpurchaseorderitem.poitemID');
                    if($storeID!="")
                    {
                        $productpo1->where('productpurchaseorder.storeID', $storeID);
                    }
                    if($firstday!="" && $lastday!="")
                    {
                        $productpo1->whereDate('productpurchaseorder.created_at', '>=', $firstday);
                        $productpo1->whereDate('productpurchaseorder.created_at', '<=', $lastday);
                    }
                    $productpo = $productpo1->get(array(
                        'productpurchaseorder.ponumber',
                        'productpurchaseorder.poprocessstatus',
                        'productpurchaseorder.created_at',
                        'productpurchaseorderitem.receivequantity',
                        'productpurchasereceivedetails.receivedby'
                    ));

                    $productorderdetail1 = orderitem::where('productID', $productbarcode->productID)
                    ->leftJoin('orderdetail', 'orderdetail.orderID', '=', 'orderitem.orderID');
                    if($storeID!="")
                    {
                        $productorderdetail1->where('orderdetail.storeID', $storeID);
                    }
                    if($firstday!="" && $lastday!="")
                    {
                        $productorderdetail1->whereDate('orderdetail.orderDate', '>=', $firstday);
                        $productorderdetail1->whereDate('orderdetail.orderDate', '<=', $lastday);
                    }
                    $productorderdetail = $productorderdetail1->get();

                    $productrefund1 = refundorderitem::where('productID', $productbarcode->productID)
                    ->leftJoin('refundorderdetail', 'refundorderdetail.refundInvoiceID', '=', 'refundorderitem.refundInvoiceID')
                    ->where('refundorderdetail.refundStatus', '!=', '0');
                    if($storeID!="")
                    {
                        $productrefund1->where('refundorderdetail.storeID', $storeID);
                    }
                    if($firstday!="" && $lastday!="")
                    {
                        $productrefund1->whereDate('refundorderdetail.refundDate', '>=', $firstday);
                        $productrefund1->whereDate('refundorderdetail.refundDate', '<=', $lastday);
                    }
                    $productrefund = $productrefund1->get();

                    $productstockreturn1 = stockreturnitems::where('productID', $productbarcode->productID)
                    ->leftJoin('stockreturn', 'stockreturn.stockreturnID', '=', 'stockreturnitems.stockreturnID')
                    ->where('stockreturn.stockreturnStatus', '!=', '0');
                    if($storeID!='')
                    {
                        $productstockreturn1->where('stockreturn.storeID', $storeID);
                    }
                    if($firstday!="" && $lastday!="")
                    {
                        $productstockreturn1->whereDate('stockreturn.stockreturnDate', '>=', $firstday);
                        $productstockreturn1->whereDate('stockreturn.stockreturnDate', '<=', $lastday);
                    }
                    $productstockreturn = $productstockreturn1->get();

                    $productstocktransfer1 = stocktransferitem::where('productID', $productbarcode->productID)
                    ->leftJoin('stocktransfer', 'stocktransfer.stocktransferID', '=', 'stocktransferitems.stocktransferID')
                    ->where('stocktransfer.stocktransferStatus', '!=', '0');
                    if($storeID!="")
                    {
                        $productstocktransfer1->where('stocktransfer.fromStoreID', $storeID); 
                    }
                    if($firstday!="" && $lastday!="")
                    {
                        $productstocktransfer1->whereDate('stocktransfer.stocktransferDate', '>=', $firstday);
                        $productstocktransfer1->whereDate('stocktransfer.stocktransferDate', '<=', $lastday);
                    }
                    $productstocktransfer = $productstocktransfer1->get();

                    $productstockintransfer1 = stocktransferitem::where('productID', $productbarcode->productID)
                    ->leftJoin('stocktransfer', 'stocktransfer.stocktransferID', '=', 'stocktransferitems.stocktransferID')
                    ->where('stocktransfer.stocktransferStatus', '!=', '0');
                    if($storeID!="")
                    {
                        $productstockintransfer1->where('stocktransfer.toStoreID', $storeID); 
                    }
                    if($firstday!="" && $lastday!="")
                    {
                        $productstockintransfer1->whereDate('stocktransfer.stocktransferDate', '>=', $firstday);
                        $productstockintransfer1->whereDate('stocktransfer.stocktransferDate', '<=', $lastday);
                    }
                    $productstockintransfer = $productstockintransfer1->get();

                    /*$getbarcodedetail1 = product::where('barcode', $barcode)
                    ->leftJoin('productstock', 'productstock.productID', '=', 'products.productID')
                    ->leftJoin('productpurchaseorderitem', 'productpurchaseorderitem.productID', '=', 'products.productID')
                    ->leftJoin('productpurchaseorder', 'productpurchaseorder.ponumber', '=', 'productpurchaseorderitem.ponumber')
                    ->leftJoin('productpurchasereceivedetails', 'productpurchasereceivedetails.poitemID', '=', 'productpurchaseorderitem.poitemID')
                    ->leftJoin('orderitem', 'orderitem.stockID', '=', 'productstock.psID')
                    ->leftJoin('orderdetail', 'orderdetail.orderID', '=', 'orderitem.orderID')
                    ->leftJoin('refundorderitem', 'refundorderitem.stockID', '=', 'productstock.psID')
                    ->leftJoin('refundorderdetail', 'refundorderdetail.refundInvoiceID', '=', 'refundorderitem.refundInvoiceID')
                    ->leftJoin('stockreturnitems', 'stockreturnitems.stockID', '=', 'productstock.psID')
                    ->leftJoin('stockreturn', 'stockreturn.stockreturnID', '=', 'stockreturnitems.stockreturnID')
                    ->leftJoin('stocktransferitems', 'stocktransferitems.stockID', '=', 'productstock.psID')
                    ->leftJoin('stocktransfer', 'stocktransfer.stocktransferID', '=', 'stocktransferitems.stocktransferID')
                    ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
                    ->leftJoin('store', 'store.store_id', '=', 'productpurchaseorder.storeID')
                    ->leftJoin('users', 'users.id', '=', 'orderdetail.userID');
                    if($storeID!='')
                    {
                        $getbarcodedetail1->where('productstock.storeID', $storeID);
                        $getbarcodedetail1->where('productpurchaseorder.storeID', $storeID);
                        $getbarcodedetail1->where('orderdetail.storeID', $storeID);
                    }
                    if($firstday!="" && $lastday!="")
                    {
                        $getbarcodedetail1->whereDate('orderdetail.orderDate', '>=', $firstday);
                        $getbarcodedetail1->whereDate('orderdetail.orderDate', '<=', $lastday);
                    }
                    
                    $getbarcodedetail= $getbarcodedetail1->get(array(
                        'productstock.psID',
                        'productstock.ponumber',
                        'productstock.productID',
                        'productstock.productimei',
                        'productstock.ppingst',
                        'productstock.productquantity',
                        'products.productname',
                        'products.barcode',
                        'products.spingst',
                        'products.supplierID',
                        'mastersupplier.suppliername',
                        'productpurchaseorder.ponumber',
                        'productpurchaseorder.poprocessstatus',
                        'productpurchaseorder.docketnumber',
                        'productpurchaseorder.porefrencenumber',
                        'productpurchasereceivedetails.receivedby',
                        'productpurchasereceivedetails.created_at',
                        'productpurchaseorder.storeID',
                        'orderdetail.orderID',
                        'orderdetail.orderDate',
                        'orderdetail.userID',
                        'orderdetail.orderstatus',
                        'refundorderdetail.refundInvoiceID',
                        'refundorderdetail.refundDate',
                        'refundorderdetail.refundBy',
                        'refundorderdetail.refundStatus',
                        'stocktransfer.stocktransferID',
                        'stocktransfer.toStoreID',
                        'stocktransfer.fromUserID',
                        'stocktransfer.toUserID',
                        'stocktransfer.stocktransferStatus',
                        'stocktransfer.stocktransferDate',
                        'stocktransfer.receivetrasnsferDate',
                        'stockreturn.stockreturnID',
                        'stockreturn.raNumber',
                        'stockreturn.stockreturnDate',
                        'stockreturn.userID',
                        'stockreturn.stockreturnStatus',
                        'store.store_name',
                        'users.name'
                    ));*/

                    //return $request->all();
                    //return $getbarcodedetail;
                    //$getdetail = [];

                    /*$with = array(
                        'getbarcodedetail'=>$getbarcodedetail,
                        'firstday'=>$firstday,
                        'lastday'=>$lastday,
                        'allstore'=>$allstore,
                        'storeID'=>$storeID
                    );*/

                    $with = array(
                        'productbarcode'=>$productbarcode,
                        'productstock'=>$productstock,
                        'productpo'=>$productpo,
                        'productorderdetail'=>$productorderdetail,
                        'productrefund'=>$productrefund,
                        'productstockreturn'=>$productstockreturn,
                        'productstocktransfer'=>$productstocktransfer,
                        'productstockintransfer'=>$productstockintransfer,
                        'firstday'=>$firstday,
                        'lastday'=>$lastday,
                        'allstore'=>$allstore,
                        'storeID'=>$storeID
                    );

                    if($productbarcode=="")
                    {
                        return redirect()->back()->with('error', 'Barcode not found');
                    }
                    else
                    {
                        return view('searchproduct-barcode')->with($with);
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Barcode not found');
                }
        	}
            else if (!empty($instockbarcode))
            {
                $product = product::where('barcode', $instockbarcode)->where('productstatus', '1')->first();

                if($product == "")
                {
                    return redirect()->back()->with('error', 'Barcode not found');
                }
                else
                {
                    $productstock = productstock::where('productID', $product->productID)
                    ->leftJoin('store', 'store.store_id', '=', 'productstock.storeID')
                    ->get();

                    $with = array(
                            'product'=>$product,
                            'productstock'=>$productstock
                        );
                    return view('searchproduct-instock')->with($with);
                }

            }
        }
    }
}
