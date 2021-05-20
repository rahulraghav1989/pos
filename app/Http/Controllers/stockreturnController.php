<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Cookie;
use Tracker;
use Session;
use Validator;
use DB;
use Carbon\Carbon;

use App\loggeduser;
use App\mainmenu;
use App\storeuser;
use App\submenu;
use App\userpermission;
use App\masterbrand;
use App\mastercolour;
use App\mastermodel;
use App\mastersupplier;
use App\mastersubcategory;
use App\masterprreceivetype;
use App\usergroup;
use App\store;
use App\storetype;
use App\masterstockgroup;
use App\mastertax;
use App\mastercategory;
use App\product;
use App\productstockgroup;
use App\productsupplier;
use App\productqtyalert;
use App\productpurchaseorder;
use App\productpurchaseorderitem;
use App\productpurchasereceivedetails;
use App\productstock;
use App\masterproducttype;
use App\stockreturn;
use App\stockreturnitems;
use App\stockreturnpayments;
use App\refundorderdetail;
use App\refundorderitem;
use App\demostock;

class stockreturnController extends Controller
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

        $loggedinsubmenu= mainmenu::with('submenu')->where('usertypeID', $loggedinuser->usertypeID)->orderBy('mainmenuSrNum', 'ASC')->get();

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function stockreturnview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.viewstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
        	if(!empty(session::get('stockreturnfilter.firstday')))
            {
                $firstday = date('Y-m-d', strtotime(session::get('stockreturnfilter.firstday')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty(session::get('stockreturnfilter.lastday')))
            {
                $lastday = date('Y-m-d', strtotime(session::get('stockreturnfilter.lastday')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            if(!empty(session::get('stockreturnfilter.store')))
            {
                $storeID[] = session::get('stockreturnfilter.store');
            }
            else if(count(session::get('loggindata.loggeduserstore'))=='0')
            {
                $storeID[] = session::get('loggindata.loggeduserstore');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $stores)
                {
                    $storeID[] = $stores->store_id; 
                }
            }

            $supplierID = session::get('stockreturnfilter.supplier');
			$returnstatus = session::get('stockreturnfilter.returnstatus');

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

        	$getstockreturn1 = stockreturn::leftJoin('store', 'store.store_id', '=', 'stockreturn.storeID')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'stockreturn.supplierID')
            ->whereDate('stockreturn.stockreturnDate', '>=', $firstday)
            ->whereDate('stockreturn.stockreturnDate', '<=', $lastday);
            if(count($storeID) > 0)
            {
                $getstockreturn1->whereIn('stockreturn.storeID', $storeID);
            }
            if($supplierID !='')
            {
            	$getstockreturn1->where('mastersupplier.supplierID', $supplierID);
            }
            if($returnstatus != '')
            {
            	$getstockreturn1->where('stockreturn.stockreturnStatus', $returnstatus);
            }
            $getstockreturn = $getstockreturn1->get();

        	
        	//$allusers = loggeduser::whereIn('userstatus', ['0', '1'])->get();
        	$allsupplier = mastersupplier::where('supplierstatus', '1')->get();

        	//$storedetail = store::where('store_id', $storeID)->first();

        	$with = array(
        		'getstockreturn'=>$getstockreturn,
        		'allstore'=>$allstore,
        		'allsupplier'=>$allsupplier,
        		'firstday'=>$firstday,
        		'lastday'=>$lastday,
        		'storeID'=>$storeID,
        		'supplierID'=>$supplierID,
        		'returnstatus'=>$returnstatus
        	);

            return view('stock-return')->with($with);
        }
    }

    public function createstockreturn(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' || session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'storeid'=>'required'
            ],[
                'storeid.required'=>'Failed to fetch store'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                /*****ORDER ID CREATION*****/
                $dateforreturnstockid = Carbon::now()->toDateTimeString();
                $returnstockidstoreid = $request->input('storeid');
                $returnstockiduserid = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

                $returnstockidtobe = $dateforreturnstockid.$returnstockidstoreid.$returnstockiduserid->id;
                
                $returnstockid = preg_replace("/[^A-Za-z0-9]/","",$returnstockidtobe);
                /*****ORDER ID CREATION*****/

                $checkid = stockreturn::where('stockreturnID', $returnstockid)->count();

                if($checkid > 0)
                {
                    return redirect()->back()->with('error', 'Stock return id already exists.');
                }
                else
                {
                    $insertstockreturn = new stockreturn;
                    $insertstockreturn->stockreturnID = $returnstockid;
                    $insertstockreturn->storeID = $request->input('storeid');
                    $insertstockreturn->userID = $returnstockiduserid->id;
                    $insertstockreturn->stockreturnStatus = '0';
                    $insertstockreturn->save();

                    if($insertstockreturn->save())
                    {
                        return redirect()->route('stockreturncreation', ['sid'=> $returnstockid]);
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to create stock return invoice.');
                    }
                }
            }     
        }
    }

    public function stockreturncreationview($sid)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
        	$stockreturnid = $sid;

        	$getstockreturn = stockreturn::where('stockreturnID', $stockreturnid)->first();

        	$getstockreturnitems = stockreturnitems::where('stockreturnID', $stockreturnid)
        	->leftJoin('products', 'products.productID', '=', 'stockreturnitems.productID')
        	->leftJoin('productstock', 'productstock.psID', '=', 'stockreturnitems.stockID')
        	->leftJoin('demostock', 'demostock.demostockID', '=', 'stockreturnitems.demostockID')
        	->get(array(
        		'stockreturnitems.stockreturnitemID',
        		'stockreturnitems.productID',
        		'stockreturnitems.stockID',
        		'stockreturnitems.refundItemID',
        		'stockreturnitems.demostockID',
        		'stockreturnitems.quantity',
        		'stockreturnitems.ppexgst',
        		'stockreturnitems.gst',
        		'stockreturnitems.ppingst',
        		'stockreturnitems.total',
        		'products.barcode',
        		'products.productname',
        		'productstock.productimei',
                'productstock.simnumber'
        	));

        	$getstockreturnpayments = stockreturnpayments::where('stockreturnID', $stockreturnid)->get();

        	$productcategory = mastercategory::where('categorystatus', '1')
            ->leftJoin('products', 'products.categories', '=', 'mastercategory.categoryID')
            ->leftJoin('productstock', 'productstock.productID', '=', 'products.productID')
            ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
            ->where('masterstockgroup.stockpriceeffect', 'sellingprice')
            ->where('masterstockgroup.productqtyeffect', '1')
            ->whereNull('products.producttype')
            ->where('productstock.storeID', $getstockreturn->storeID)
            ->get(array('mastercategory.categoryID', 'mastercategory.categoryname', 'mastercategory.categorystatus', 'products.productID', 'products.productname', 'products.barcode', 'products.stockcode', 'productstock.ppexgst', 'productstock.pptax', 'productstock.ppingst', 'products.spexgst', 'products.spgst', 'products.spingst', 'productstock.psID', 'productstock.productquantity', 'productstock.simnumber'));

        	$supplier = mastersupplier::get();

        	$producttype = masterproducttype::where('producttypestatus', '1')->get();

        	$faultyitems = refundorderdetail::where('refundorderdetail.storeID', $getstockreturn->storeID)
        	->leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
        	->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
        	->leftJoin('productstock', 'productstock.psID', '=', 'refundorderitem.stockID')
        	->where('refundorderitem.refundReason', 'Faulty')
        	->get(array(
        		'refundorderdetail.refundInvIncID',
        		'refundorderdetail.refundInvoiceID',
        		'refundorderitem.refunditemID',
        		'refundorderitem.productID',
        		'refundorderitem.stockID',
        		'refundorderitem.quantity',
        		'products.barcode',
        		'products.productname',
        		'productstock.productimei',
        		'productstock.ppexgst',
        		'productstock.pptax',
        		'productstock.ppingst',
                'productstock.simnumber'
        	));

        	$demoproducts = demostock::where('storeID', $getstockreturn->storeID)
        	->where('productquantity', '!=', '')
        	->leftJoin('products', 'products.productID', '=', 'demostock.productID')
        	->get(array(
        		'demostock.demostockID',
        		'demostock.productID',
        		'demostock.productimei',
        		'demostock.productquantity',
        		'products.barcode',
        		'products.productname'
        	));

            $with = array(
                'stockreturnid'=>$stockreturnid,
                'getstockreturn'=>$getstockreturn,
                'supplier'=>$supplier,
                'getstockreturnitems'=>$getstockreturnitems,
                'productcategory'=>$productcategory,
                'producttype'=>$producttype,
                'faultyitems'=>$faultyitems,
                'getstockreturnpayments'=>$getstockreturnpayments,
                'demoproducts'=>$demoproducts
            );
            return view('stock-return-create')->with($with);
        }
    }

    public function ajaxupdateranumber(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' || session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        }
        else
        { 
        	if($request->get('stockreturnid'))
            {
              $stockreturnid = $request->get('stockreturnid');
              $ranumber = $request->get('ranumber');
              $data = stockreturn::where('stockreturnID', $stockreturnid)->first();
              $data->raNumber = $ranumber;
              $data->save();
            }  
        }
    }

    public function ajaxupdatenote(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' || session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        }
        else
        { 
        	if($request->get('stockreturnid'))
            {
              $stockreturnid = $request->get('stockreturnid');
              $note = $request->get('note');
              $data = stockreturn::where('stockreturnID', $stockreturnid)->first();
              $data->returnNote = $note;
              $data->save();
            }  
        }
    }

    public function ajaxupdatedate(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' || session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        }
        else
        { 
        	if($request->get('stockreturnid'))
            {
              $stockreturnid = $request->get('stockreturnid');
              $date = $request->get('date');
              $formatteddate = date('Y-m-d', strtotime($date));
              $month = date('m', strtotime($date));
              $year = date('Y', strtotime($date));
              $data = stockreturn::where('stockreturnID', $stockreturnid)->first();
              $data->stockreturnDate = $formatteddate;
              $data->stockreturnMonth = $month;
              $data->stockreturnYear = $year;
              $data->save();
            }  
        }
    }

    public function ajaxupdatesupplier(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' || session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        }
        else
        { 
        	if($request->get('stockreturnid'))
            {
              $stockreturnid = $request->get('stockreturnid');
              $supplier = $request->get('supplier');

              $data = stockreturn::where('stockreturnID', $stockreturnid)->first();
              $data->supplierID = $supplier;
              $data->save();
            }  
        }
    }

    public function stockreturnbybarcode(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'quantity'=>'required',
            'barcode'=>'required',
            'stockreturnid'=>'required'
            ],[
                'quantity.required'=>'Quantity is required',
                'barcode.required'=>'Barcode is required',
                'stockreturnid.required'=>'Fail to get Return ID'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $quantity = $request->input('quantity');
                $barcode = $request->input('barcode');
                $stockreturnid = $request->input('stockreturnid');
                $storeID = $request->input('storeid');

                $countproduct = product::where('barcode', $barcode)->where('productstatus', '1')->count();

                if($countproduct > 0)
                {
                    $countaccessoriesproduct = product::where('barcode', $barcode)
                    ->whereNull('producttype')
                    ->where('productstatus', '1')
                    ->count();

                    if($countaccessoriesproduct > 0)
                    {
                        if($countaccessoriesproduct > 1)
                        {
                            $getproduct = product::where('barcode', $barcode)
                            ->whereNull('producttype')
                            ->with('productbrand')
                            ->with('productcolour')
                            ->with('productmodel')
                            ->with('productsupplier')
                            ->where('productstatus', '1')
                            ->get();

                            $multibarcodeopenmodel = '1';

                            $productdata = ['multibarcodeopenmodel'=>$multibarcodeopenmodel, 'getproduct'=>$getproduct, 'quantity'=>$quantity];

                            //return $productdata;

                            return redirect()->back()->with('productdata', $productdata);
                        }
                        else
                        {
                            $getproduct = product::where('barcode', $barcode)
                            ->whereNull('producttype')
                            ->where('productstatus', '1')
                            ->first();

                            $getproductstockgroup= productstockgroup::where('productID', $getproduct->productID)
                            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', 'productstockgroup.stockgroupID')
                            ->where('stockpriceeffect', 'sellingprice')
                            ->where('productqtyeffect', '1')
                            ->where('stockgroupstatus', '1')
                            ->first();

                            if($getproductstockgroup != '')
                            {
                                $getproductstock = productstock::where('productID', $getproduct->productID)
                                ->where('productquantity', '!=', 0)
                                ->where('storeID', $storeID)
                                ->first();

                                if($getproductstock != "")
                                {
                                    if($quantity <= $getproductstock->productquantity)
                                    {
                                        $stockreturn = stockreturn::where('stockreturnID', $stockreturnid)
                                        ->where('storeID', $storeID)
                                        ->count();

                                        if($stockreturn > 0)
                                        {
                                            $stockreturnitems = stockreturnitems::where('stockreturnID', $stockreturnid)
                                            ->where('productID', $getproduct->productID)
                                            ->where('stockID', $getproductstock->psID)
                                            ->whereNull('refundItemID')
                                            ->first();

                                            if($stockreturnitems == "")
                                            {
                                                /******Total Calculation******/
                                                $total = $getproductstock->ppingst * $quantity;
                                                /******Total Calculation******/

                                                $insertreturnitem = new stockreturnitems;
                                                $insertreturnitem->stockreturnID = $stockreturnid;
                                                $insertreturnitem->productID = $getproduct->productID;
                                                $insertreturnitem->stockID = $getproductstock->psID;
                                                $insertreturnitem->quantity = $quantity;
                                                $insertreturnitem->ppexgst = $getproductstock->ppexgst;
                                                $insertreturnitem->gst = $getproductstock->pptax;
                                                $insertreturnitem->ppingst = $getproductstock->ppingst;
                                                $insertreturnitem->total = $total;

                                                $insertreturnitem->save();

                                                if($insertreturnitem->save())
                                                {
                                                    $subtractquantity = $getproductstock->productquantity - $quantity;

                                                    $getproductstock->productquantity = $subtractquantity;
                                                    $getproductstock->save();

                                                    return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 001)');
                                                }
                                                else
                                                {
                                                    return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 007)');
                                                }
                                            }
                                            else
                                            {
                                                /******total******/
                                                $total = $stockreturnitems->ppingst * $quantity;
                                                /******total******/
                                                
                                                $stockreturnitems->quantity = $stockreturnitems->quantity + $quantity;
                                                $stockreturnitems->total = $stockreturnitems->total + $total;
                                                $stockreturnitems->save();

                                                if($stockreturnitems->save())
                                                {
                                                    $subtractquantity = $getproductstock->productquantity - $quantity;

                                                    $getproductstock->productquantity = $subtractquantity;
                                                    $getproductstock->save();

                                                    return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 002)');
                                                }
                                                else
                                                {
                                                    return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 008)');
                                                }
                                            }
                                        }
                                        else
                                        {
                                            return redirect()->back()->with('error','Failed to get order detail. (ErrorCode - 005)');
                                        }
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('error','Not sufficient quantity for this barcode. (ErrorCode - 004)');
                                    }
                                }
                                else
                                {
                                    return redirect()->back()->with('error','Product stock not available. (ErrorCode - 003)');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error','Product cannot be sold as outright. (ErrorCode - 006)');
                            }
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error','By barcode.! you can add quantity products only. for unique products. Please use other options (ErrorCode - 002)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error','No product found related to this barcode (ErrorCode - 001)');
                }
            }
        }
    }

    public function stockreturnaddallbyproductid(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'productid'=>'required'
            ],[
                'productid.required'=>'Product ID is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $productid = $request->input('productid');
                $stockreturnid = $request->input('stockreturnid');
                $quantity = $request->input('quantity');
                $storeID = $request->input('storeid');

                $getproduct = product::where('productID', $productid)
                ->where('productstatus', '1')
                ->first();

                $getproductstockgroup= productstockgroup::where('productID', $getproduct->productID)
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', 'productstockgroup.stockgroupID')
                ->where('stockpriceeffect', 'sellingprice')
                ->where('productqtyeffect', '1')
                ->where('stockgroupstatus', '1')
                ->first();

                if($getproductstockgroup != '')
                {
                    $getproductstock = productstock::where('productID', $getproduct->productID)
                    ->where('productquantity', '!=', 0)
                    ->where('storeID', $storeID)
                    ->first();

                    if($getproductstock != "")
                    {
                        if($quantity <= $getproductstock->productquantity)
                        {
                            $stockreturn = stockreturn::where('stockreturnID', $stockreturnid)
                            ->where('storeID', $storeID)
                            ->count();

                            if($stockreturn > 0)
                            {
                                $stockreturnitems = stockreturnitems::where('stockreturnID', $stockreturnid)
                                ->where('productID', $getproduct->productID)
                                ->where('stockID', $getproductstock->psID)
                                ->whereNull('refundItemID')
                                ->first();

                                if($stockreturnitems == "")
                                {
                                    /******Total Calculation******/
                                    $total = $getproductstock->ppingst * $quantity;
                                    /******Total Calculation******/

                                    $insertstcokreturnitems = new stockreturnitems;
                                    $insertstcokreturnitems->stockreturnID = $stockreturnid;
                                    $insertstcokreturnitems->productID = $getproduct->productID;
                                    $insertstcokreturnitems->stockID = $getproductstock->psID;
                                    $insertstcokreturnitems->quantity = $quantity;
                                    $insertstcokreturnitems->ppexgst = $getproductstock->ppexgst;
                                    $insertstcokreturnitems->gst = $getproductstock->pptax;
                                    $insertstcokreturnitems->ppingst = $getproductstock->ppingst;
                                    $insertstcokreturnitems->total = $total;
                                    $insertstcokreturnitems->save();

                                    if($insertstcokreturnitems->save())
                                    {
                                        $subtractquantity = $getproductstock->productquantity - $quantity;

                                        $getproductstock->productquantity = $subtractquantity;
                                        $getproductstock->save();

                                        return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 008)');
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 028)');
                                    }
                                }
                                else
                                {
                                    /******Total Calculation******/
                                    $total = $stockreturnitems->ppingst * $quantity;
                                    /******Total Calculation******/

                                    $stockreturnitems->quantity = $stockreturnitems->quantity + $quantity;
                                    $stockreturnitems->total = $stockreturnitems->total + $total;

                                    $stockreturnitems->save();

                                    if($stockreturnitems->save())
                                    {
                                        $subtractquantity = $getproductstock->productquantity - $quantity;

                                        $getproductstock->productquantity = $subtractquantity;
                                        $getproductstock->save();

                                        return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 007)');
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 027)');
                                    }
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error','Failed to get order detail. (ErrorCode - 026)');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error','Not sufficient quantity for this barcode. (ErrorCode - 025)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error','Product stock not available. (ErrorCode - 024)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error','Product cannot be sold as outright. (ErrorCode - 023)');
                }
            }
        }
    }

    public function stockreturnaddimeinumber(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'number'=>'required',
            'producttype'=>'required'
            ],[
                'number.required'=>'Enter Number, It is required',
                'producttype.required'=>'Product Type is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $number = $request->input('number');
                $producttype = $request->input('producttype');
                $stockreturnid = $request->input('stockreturnid');
                $storeID = $request->input('storeid');

                $getproduct= productstock::where('productimei', $number)
                ->where('productquantity', '!=', '0')
                ->where('storeID', $storeID)
                ->leftJoin('products', 'products.productID', '=', 'productstock.productID')
                ->where('products.producttype', $producttype)
                ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                ->where('stockpriceeffect', 'sellingprice')
                ->where('productqtyeffect', '1')
                ->leftJoin('masterproducttype', 'masterproducttype.producttypeID', '=', 'products.producttype')
                ->count();

                //return $getproduct;

                if($getproduct == 0)
                {
                    return redirect()->back()->with('error', 'Product Could not be added to invoice. Reasons:- (1)  number doesnot match. (2) Product already sold out or may be added to another invoice. (3) Product cannot be sold as outright sell.');
                }
                else
                {
                    $getproduct= productstock::where('productimei', $number)
                    ->where('productquantity', '!=', '0')
                    ->where('storeID', $storeID)
                    ->leftJoin('products', 'products.productID', '=', 'productstock.productID')
                    ->where('products.producttype', $producttype)
                    ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                    ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                    ->where('stockpriceeffect', 'sellingprice')
                    ->where('productqtyeffect', '1')
                    ->leftJoin('masterproducttype', 'masterproducttype.producttypeID', '=', 'products.producttype')
                    ->first(array(
                    	'productstock.psID',
                    	'productstock.productID',
                    	'productstock.productimei',
                    	'productstock.productquantity',
                    	'productstock.ppexgst',
                    	'productstock.pptax',
                    	'productstock.ppingst',
                    	'productstock.storeID',
                    	'products.producttype',
                    ));

                    $insertstockreturnitems = new stockreturnitems;
                    $insertstockreturnitems->stockreturnID = $stockreturnid;
                    $insertstockreturnitems->productID = $getproduct->productID;
                    $insertstockreturnitems->stockID = $getproduct->psID;
                    $insertstockreturnitems->quantity = '1';
                    $insertstockreturnitems->ppexgst = $getproduct->ppexgst;
                    $insertstockreturnitems->gst = $getproduct->pptax;
                    $insertstockreturnitems->ppingst = $getproduct->ppingst;
                    $insertstockreturnitems->total = $getproduct->ppingst;
                    $insertstockreturnitems->save();

                    if($insertstockreturnitems->save())
                    {
                        $getproduct->productquantity = '0';
                        $getproduct->productimei = "R-".$getproduct->productimei;
                        $getproduct->save();

                        if($getproduct->save())
                        {
                            return redirect()->back()->with('success', 'Product added to invoice (SuccessCode - 009)');
                        } 
                        else
                        {
                            return redirect()->back()->with('error', 'Failed to update product stock (ErrorCode - 35)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Product could not be added to invoice. (ErrorCode - 34)');
                    } 
                }
            }
        }
    }

    public function stockreturnaddfaultyproduct(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'productid'=>'required'
            ],[
                'productid.required'=>'Product ID is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $productid = $request->input('productid');
                $stockreturnid = $request->input('stockreturnid');
                $quantity = $request->input('quantity');
                $storeID = $request->input('storeid');
                $refunditemid = $request->input('refunditemid');

                $getproduct = product::where('productID', $productid)
                ->where('productstatus', '1')
                ->first();

                $getproductstockgroup= productstockgroup::where('productID', $getproduct->productID)
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', 'productstockgroup.stockgroupID')
                ->where('stockpriceeffect', 'sellingprice')
                ->where('productqtyeffect', '1')
                ->where('stockgroupstatus', '1')
                ->first();

                if($getproductstockgroup != '')
                {
                    $getproductstock = productstock::where('productID', $getproduct->productID)
                    ->where('productquantity', '!=', 0)
                    ->where('storeID', $storeID)
                    ->first();

                    if($getproductstock != "")
                    {
                        if($quantity <= $getproductstock->productquantity)
                        {
                            $stockreturn = stockreturn::where('stockreturnID', $stockreturnid)
                            ->where('storeID', $storeID)
                            ->count();

                            if($stockreturn > 0)
                            {
                                $stockreturnitems = stockreturnitems::where('stockreturnID', $stockreturnid)
                                ->where('productID', $getproduct->productID)
                                ->where('stockID', $getproductstock->psID)
                                ->first();

                                /******Total Calculation******/
                                $total = $getproductstock->ppingst * $quantity;
                                /******Total Calculation******/

                                $insertstcokreturnitems = new stockreturnitems;
                                $insertstcokreturnitems->stockreturnID = $stockreturnid;
                                $insertstcokreturnitems->productID = $getproduct->productID;
                                $insertstcokreturnitems->stockID = $getproductstock->psID;
                                $insertstcokreturnitems->refundItemID = $refunditemid;
                                $insertstcokreturnitems->quantity = $quantity;
                                $insertstcokreturnitems->ppexgst = $getproductstock->ppexgst;
                                $insertstcokreturnitems->gst = $getproductstock->pptax;
                                $insertstcokreturnitems->ppingst = $getproductstock->ppingst;
                                $insertstcokreturnitems->total = $total;
                                $insertstcokreturnitems->save();

                                if($insertstcokreturnitems->save())
                                {
                                    $subtractquantity = $getproductstock->productquantity - $quantity;

                                    $getproductstock->productquantity = $subtractquantity;
                                    $getproductstock->save();

                                    $updaterefunditem = refundorderitem::where('refunditemID', $refunditemid)->first();
                                    $updaterefunditem->refundReason = 'Faulty Returned';
                                    $updaterefunditem->save();

                                    return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 008)');
                                }
                                else
                                {
                                    return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 028)');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error','Failed to get order detail. (ErrorCode - 026)');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error','Not sufficient quantity for this barcode. (ErrorCode - 025)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error','Product stock not available. (ErrorCode - 024)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error','Product cannot be sold as outright. (ErrorCode - 023)');
                }
            }
        }
    }

    public function stockreturnadddemoproduct(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'demoid'=>'required'
            ],[
                'demoid.required'=>'Demo ID is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $demoid = $request->input('demoid');
                $productid = $request->input('productid');
                $stockreturnid = $request->input('stockreturnid');
                $quantity = $request->input('quantity');
                $storeID = $request->input('storeid');

                $getproduct = product::where('productID', $productid)
                ->first();

                $getproductstockgroup= productstockgroup::where('productID', $getproduct->productID)
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', 'productstockgroup.stockgroupID')
                ->where('stockpriceeffect', 'sellingprice')
                ->where('productqtyeffect', '1')
                ->where('stockgroupstatus', '1')
                ->first();

                if($getproductstockgroup != '')
                {
                    $getdemostock = demostock::where('demostockID', $demoid)
                    ->where('productquantity', '!=', 0)
                    ->where('storeID', $storeID)
                    ->first();

                    if($getdemostock != "")
                    {
                        if($quantity <= $getdemostock->productquantity)
                        {
                            $stockreturn = stockreturn::where('stockreturnID', $stockreturnid)
                            ->where('storeID', $storeID)
                            ->count();

                            if($stockreturn > 0)
                            {
                                $stockreturnitems = stockreturnitems::where('stockreturnID', $stockreturnid)
                                ->where('productID', $getproduct->productID)
                                ->where('demostockID', $getdemostock->demostockID)
                                ->first();

                                /******Total Calculation******/
                                $total = $getdemostock->ppingst * $quantity;
                                /******Total Calculation******/

                                $insertstcokreturnitems = new stockreturnitems;
                                $insertstcokreturnitems->stockreturnID = $stockreturnid;
                                $insertstcokreturnitems->productID = $getproduct->productID;
                                $insertstcokreturnitems->demostockID = $getdemostock->demostockID;
                                $insertstcokreturnitems->quantity = $quantity;
                                $insertstcokreturnitems->ppexgst = $getdemostock->ppexgst;
                                $insertstcokreturnitems->gst = $getdemostock->pptax;
                                $insertstcokreturnitems->ppingst = $getdemostock->ppingst;
                                $insertstcokreturnitems->total = $total;
                                $insertstcokreturnitems->save();

                                if($insertstcokreturnitems->save())
                                {
                                    $subtractquantity = $getdemostock->productquantity - $quantity;

                                    $getdemostock->productquantity = $subtractquantity;
                                    $getdemostock->save();

                                    return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 008)');
                                }
                                else
                                {
                                    return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 028)');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error','Failed to get order detail. (ErrorCode - 026)');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error','Not sufficient quantity for this barcode. (ErrorCode - 025)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error','Product stock not available. (ErrorCode - 024)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error','Product cannot be sold as outright. (ErrorCode - 023)');
                }
            }
        }
    }

    public function stockreturnconfirm(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'stockreturnid'=>'required'
            ],[
                'stockreturnid.required'=>'Stock return id is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $stockreturnid = $request->input('stockreturnid');

                $checkstockreturn = stockreturn::where('stockreturnID', $stockreturnid)
                ->first();

                if($checkstockreturn != '')
                {
                	if($checkstockreturn->raNumber != '' && $checkstockreturn->supplierID != '' && $checkstockreturn->stockreturnDate != '')
                	{
                		$checkstockreturnitems = stockreturnitems::where('stockreturnID', $stockreturnid)->count();
                		if($checkstockreturnitems > 0)
                		{
                			$checkstockreturn->stockreturnStatus = '1';
                            $checkstockreturn->returnAdminApproval = '0';
                			$checkstockreturn->save();

                			if($checkstockreturn->save())
                			{
                				return redirect('stockreturn');
                			}	
                			else
                			{
                				return redirect()->back()->with('error', 'Something went wrong while returning stock. Try-again.');
                			}
                		}
                		else
                		{
                			return redirect()->back()->with('error', 'Stock return cannot be empty. Please add some item to finish stock return.');
                		}
                	}
                	else
                	{
                		return redirect()->back()->with('error', 'Ra Number, Supplier and Return Date is required');
                	}
                }
                else
                {
                	return redirect()->back()->with('error', 'No stock return found');
                }
            }
        }
    }

    public function stockreturncreditamount(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'stockreturnid'=>'required'
            ],[
                'stockreturnid.required'=>'Stock return id is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $stockreturnid = $request->input('stockreturnid');
                $creditamount = $request->input('creditamount');
                $totalamount = $request->input('totalamount');

                $checkpayments = stockreturnpayments::where('stockreturnID', $stockreturnid)->sum('returnamount');

                if($checkpayments == $totalamount)
                {
                	return redirect()->back()->with('success', 'Full amount credited');
                }
                else
                {
                	if($checkpayments == 0)
                	{
                		if($totalamount >= $creditamount)
	                	{
	                		$insertstockreturnpayments = new stockreturnpayments;
		                	$insertstockreturnpayments->stockreturnID = $stockreturnid;
		                	$insertstockreturnpayments->returnamount = $creditamount;
		                	$insertstockreturnpayments->save();

		                	if($insertstockreturnpayments->save())
		                	{
		                		return redirect()->back()->with('success', 'Amount credited successfully');
		                	}
		                	else
		                	{
		                		return redirect()->back()->with('error', 'Failed to credit amount');
		                	}
	                	}
	                	else
	                	{
	                		return redirect()->back()->with('error', 'Credit Amount cannot be larger than total amount');
	                	}
                	}
                	else
                	{
                		$remainingamount = $totalamount - $checkpayments;

                		if($remainingamount >= $creditamount)
                		{
                			$insertstockreturnpayments = new stockreturnpayments;
		                	$insertstockreturnpayments->stockreturnID = $stockreturnid;
		                	$insertstockreturnpayments->returnamount = $creditamount;
		                	$insertstockreturnpayments->save();

		                	if($insertstockreturnpayments->save())
		                	{
		                		return redirect()->back()->with('success', 'Amount credited successfully');
		                	}
		                	else
		                	{
		                		return redirect()->back()->with('error', 'Failed to credit amount');
		                	}
                		}
                		else
                		{
                			return redirect()->back()->with('error', 'Credit amount cannot larger than remaining credit amount.');
                		}
                	}
                }
            }
        }
    }

    public function editstockreturnitem(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.editstockreturnitem')=='N' ||session::get('loggindata.loggeduserpermission.editstockreturnitem')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'stockreturnitemid'=>'required'
            ],[
                'stockreturnitemid.required'=>'Stock return id is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $stockreturnitemid = $request->input('stockreturnitemid');
                $ppexgst = $request->input('ppexgst');
                $ppgst = $request->input('ppgst');
                $ppingst = $request->input('ppingst');

                $getitem = stockreturnitems::find($stockreturnitemid);

                if($getitem != '')
                {
                	/*********Calculate Total Amount**********/
	                $totalamount = $ppingst * $getitem->quantity;
	                /*********Calculate Total Amount**********/

	                $getitem->ppexgst = $ppexgst;
	                $getitem->gst = $ppgst;
	                $getitem->ppingst = $ppingst;
	                $getitem->total= $totalamount;
	                $getitem->save();

	                if($getitem->save())
	                {
	                	return redirect()->back()->with('success', 'Price for return stock item has been updated.');
	                }
	                else
	                {
	                	return redirect()->back()->with('error', 'Failed to update Price for return stock item.');
	                }
                }
                else
                {
                	return redirect()->back()->with('error', 'Item Not found.');
                }
            }
        }
    }

    public function deletestockreturnitem(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.deletestockreturnitem')=='N' ||session::get('loggindata.loggeduserpermission.deletestockreturnitem')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'stockreturnitemid'=>'required'
            ],[
                'stockreturnitemid.required'=>'Stock return id is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $stockreturnitemid = $request->input('stockreturnitemid');
                
                $getitem = stockreturnitems::find($stockreturnitemid);

                if($getitem != '')
                {
                	if($getitem->stockID != '')
                	{
                		$getproductstock = productstock::where('psID', $getitem->stockID)->first();

                        if($getproductstock->productimei != "")
                        {
                            $getproductstock->productimei = substr($getproductstock->productimei, 2);
                        }

                		$quantity = $getproductstock->productquantity + $getitem->quantity;
                		$getproductstock->productquantity = $quantity;
                		$getproductstock->save();

                		if($getitem->refundItemID != '')
	                	{
	                		$refunditem = refundorderitem::where('refunditemID', $getitem->refundItemID)->first();
	                		$refunditem->refundReason = 'Faulty';
	                		$refunditem->save();
	                	}
                	}
                	else if($getitem->demostockID != '')
                	{
                		$getdemostock = demostock::where('demostockID', $getitem->demostockID)->first();
                		$quantity = $getdemostock->productquantity + $getitem->quantity;
                		$getdemostock->productquantity = $quantity;
                		$getdemostock->save();
                	}
                	
                	$deleteitem = stockreturnitems::find($stockreturnitemid)->delete();

                	return redirect()->back()->with('success', 'Item Deleted successfully.');
                }
                else
                {
                	return redirect()->back()->with('error', 'Item not found.');
                }
            }
        }
    }

    public function cancelstockreturn(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addstockreturn')=='N' ||session::get('loggindata.loggeduserpermission.addstockreturn')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'stockreturnid'=>'required'
            ],[
                'stockreturnid.required'=>'Stock return id is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $stockreturnid = $request->input('stockreturnid');
                
                $checkreturnitems = stockreturnitems::where('stockreturnID', $stockreturnid)->count();

                if($checkreturnitems > 0)
                {
                	return redirect()->back()->with('error', 'Please remove all the items are added in this invoice. Then re-try to cancel the stock return.');
                }
                else
                {
                	$deletestockreturn = stockreturn::where('stockreturnID', $stockreturnid)->delete();

                	$deletestockreturnpayment = stockreturnpayments::where('stockreturnID', $stockreturnid)->delete();

                	return redirect('stockreturn');
                }
            }
        }
    }

    public function adminapproval(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.stockreturnAdminAprroval')=='N' ||session::get('loggindata.loggeduserpermission.stockreturnAdminAprroval')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'adminstatus'=>'required'
            ],[
                'adminstatus.required'=>'Stock return id is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $stockreturnid = $request->input('stockreturnid');
                $adminstatus = $request->input('adminstatus');

                $adminapproval = stockreturn::where('stockreturnID', $stockreturnid)->first();
                $adminapproval->returnAdminApproval = $adminstatus;
                $adminapproval->save();

                return redirect()->back()->with('success', 'Status Upadted');
            }
        }
    }
}
