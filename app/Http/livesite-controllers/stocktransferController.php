<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Cookie;
use Tracker;
use Session;
use Validator;
use Carbon\Carbon;

use App\loggeduser;
use App\mainmenu;
use App\storeuser;
use App\submenu;
use App\userpermission;
use App\usergroup;
use App\store;
use App\storetype;

use App\stocktransfer;
use App\stocktransferitem;
use App\product;
use App\productstock;

class stocktransferController extends Controller
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

    public function stocktransferview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewstocktransferout')=='N' ||session::get('loggindata.loggeduserpermission.viewstocktransferout')=='')
        {
            return redirect('404');
        } 
        else
        {

            if(!empty(session::get('stocktransferoutfilters.startdate')))
            {
                $firstday = date('Y-m-d', strtotime(session::get('stocktransferoutfilters.startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty(session::get('stocktransferoutfilters.enddate')))
            {
                $lastday = date('Y-m-d', strtotime(session::get('stocktransferoutfilters.enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            $transferto = session::get('stocktransferoutfilters.transferto');

            if(session::get('loggindata.loggeduserstore')!='')
            {
                $gettostore = store::whereNotIn('store_id', [session::get('loggindata.loggeduserstore.store_id')])->get();

                $getstocktransfer = stocktransfer::where('fromStoreID', session::get('loggindata.loggeduserstore.store_id'))
                ->leftJoin('users', 'users.id', '=', 'fromUserID')
                ->leftJoin('store', 'store.store_id', '=', 'toStoreID')
                ->with('fromstore')
                ->whereBetween('stocktransferDate', [$firstday, $lastday])
                ->where('stocktransfer.toStoreID', 'LIKE', '%'.$transferto.'%')
                ->get();
            }
            else
            {
                $gettostore = store::get();

                $getstocktransfer = stocktransfer::leftJoin('users', 'users.id', '=', 'fromUserID')
                ->leftJoin('store', 'store.store_id', '=', 'toStoreID')
                ->with('fromstore')
                ->whereBetween('stocktransferDate', [$firstday, $lastday])
                ->where('stocktransfer.toStoreID', 'LIKE', '%'.$transferto.'%')
                ->get();
            }

        	$stocktransferdata = ['getstocktransfer'=>$getstocktransfer, 'gettostore'=>$gettostore, 'firstday'=>$firstday, 'lastday'=>$lastday, 'transferto'=>$transferto];

            return view('stocktransfer')->with('stocktransferdata', $stocktransferdata);
        }
    }

    public function incomingtransferview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewstocktransferin')=='N' ||session::get('loggindata.loggeduserpermission.viewstocktransferin')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getincomeingtransfer = stocktransfer::where('toStoreID', session::get('loggindata.loggeduserstore.store_id'))
            ->whereIn('stocktransferStatus', [1, 2])
            ->leftJoin('users', 'users.id', '=', 'fromUserID')
            ->leftJoin('store', 'store.store_id', '=', 'fromStoreID')
            ->get();

            $stocktransferdata = ['getincomeingtransfer'=>$getincomeingtransfer];

            return view('stocktransfer-incoming')->with('stocktransferdata', $stocktransferdata);
        }
    }

    public function startstocktransfer()
    {   
        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' ||session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        } 
        else
        {
        	/*****Stock Transfer ID CREATION*****/
            $datefororderid = Carbon::now()->toDateTimeString();
            $orderidstoreid = store::where('store_id', session::get('loggindata.loggeduserstore.store_id'))->first();
            $orderiduserid = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

            $orderidtobe = $datefororderid.$orderidstoreid->store_id.$orderiduserid->id;
            
            $stocktransferid = preg_replace("/[^A-Za-z0-9]/","",$orderidtobe);
            /*****Stock Transfer ID CREATION*****/
            
            //return view('stocktransfer');

            $checktransfer = stocktransfer::where('stocktransferID', $stocktransferid)->count();

            if($checktransfer > 0)
            {
            	return redirect()->back()->with('error', 'Something went wrong or stock transfer id already exists');
            }
            else
            {
            	$createstocktransfer = new stocktransfer;
            	$createstocktransfer->stocktransferID = $stocktransferid;
            	$createstocktransfer->stocktransferType = 'Outgoing Transfer';
            	$createstocktransfer->fromStoreID = session::get('loggindata.loggeduserstore.store_id');
            	$createstocktransfer->fromUserID= session::get('loggindata.loggedinuser.id');
            	$createstocktransfer->stocktransferStatus = '0';
            	$createstocktransfer->stocktransferDate = date('Y-m-d');
            	$createstocktransfer->save();

            	if ($createstocktransfer->save())
            	{
            		return redirect()->route('createstocktransfer', ['id'=> $createstocktransfer->stocktransferID]);
            	}
            	else
            	{
            		return redirect()->back()->with('error', 'Something went wrong or couldnt save the data.');
            	}
            }
        }
    }

    public function createstocktransfer($id)
    {   
        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' ||session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        } 
        else
        {
            $checktransfer = stocktransfer::where('stocktransferID', $id)->where('stocktransferStatus', '0')
            ->leftJoin('users', 'users.id', '=', 'stocktransfer.fromUserID')
            ->leftJoin('store', 'store.store_id', '=', 'stocktransfer.toStoreID')
            ->where('stocktransfer.fromStoreID', session::get('loggindata.loggeduserstore.store_id'))
            ->first();

            $transferitem = stocktransferitem::where('stocktransferID', $id)
            ->leftJoin('products', 'products.productID', '=', 'stocktransferitems.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'stocktransferitems.stockID')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->get();

            $createstocktransferdata = ['checktransfer'=>$checktransfer, 'transferitem'=>$transferitem];

        	return view('stocktransfer-create')->with('createstocktransferdata', $createstocktransferdata);
        }
    }

    public function addtransferstore(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'tostoreid'=>'required'
            ],[
                'tostoreid.required'=>'Something went wrong while selecting store.'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkid = stocktransfer::where('stocktransferID', $request->input('stocktransferid'))->count();

                if($checkid == 1)
                {
                	$updatest = stocktransfer::where('stocktransferID', $request->input('stocktransferid'))->first();
                	$updatest->toStoreID = $request->input('tostoreid');
                	$updatest->save();

                	if($updatest->save())
                	{
                		return redirect()->back()->with('success', 'To store has been updated');
                	}
                	else
                	{
                		return redirect()->back()->with('error', 'Fail to update to store');
                	}
                }
                else
                {
                	return redirect()->back()->with('error', 'Couldnt fetch data regarding stock transfer id');
                }
            }
        }
    }

    public function ajaxupdateconsignmentnumber(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            if($request->get('stid'))
		    {
		      $stid = $request->get('stid');
		      $username = $request->get('stid');
		      $data = stocktransfer::where('stocktransferID', $stid)->first();
		      $data->consignmentnumber = $request->get('username');
		      $data->save();
		    }
        }
    }

    public function ajaxupdatetransfernote(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            if($request->get('stid'))
		    {
		      $stid = $request->get('stid');
		      $username = $request->get('stid');
		      $data = stocktransfer::where('stocktransferID', $stid)->first();
		      $data->transfernote = $request->get('username');
		      $data->save();
		    }
        }
    }

    public function stbybarcode(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'barcode'=>'required',
            'quantity'=>'required'
            ],[
                'barcode.required'=>'Please enter barcode',
                'quantity.required'=>'Please enter quantity'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkbarcode = product::where('barcode', $request->input('barcode'))
                ->where('productstatus', '1')
                ->first();

                $quantity = $request->input('quantity');
                $stocktransferid = $request->input('stocktransferid');

                if($checkbarcode != "")
                {
                	$getbarcodecount = product::where('barcode', $request->input('barcode'))
                	->where('productstatus', '1')
                	->whereNull('producttype')
                	->count();

                	if($getbarcodecount > 1)
                	{
                		$getproductdetail = product::where('barcode', $request->input('barcode'))
                		->where('productstatus', '1')
                		->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
                		->get();

                		$openmultibarcode = '1';

                		$multibarcodedata = ['getproductdetail'=>$getproductdetail, 'openmultibarcode'=>$openmultibarcode, 'quantity'=>$quantity];

                		return redirect()->back()->with('multibarcodedata', $multibarcodedata);
                	}
                	else if($getbarcodecount == 1)
                	{
                		$getproductdetail = product::where('barcode', $request->input('barcode'))
                		->where('productstatus', '1')
                		->first();

                		$getproductstock = productstock::where('productID', $getproductdetail->productID)
                		->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                		->first();

                		if($getproductstock != '')
                		{
                			if($getproductstock->productquantity > 0)
                			{
                				if($getproductstock->productquantity >= $quantity)
                				{
                					$insertstitem = new stocktransferitem;
		                			$insertstitem->stocktransferID = $stocktransferid;
		                			$insertstitem->productID = $getproductdetail->productID;
		                			$insertstitem->stockID = $getproductstock->psID;
		                			$insertstitem->quantity = $quantity;
		                			$insertstitem->save();

		                			if($insertstitem->save())
		                			{
		                				$remainquantity = $getproductstock->productquantity - $quantity;

		                				$getproductstock->productquantity = $remainquantity;
		                				$getproductstock->save();

		                				return redirect()->back()->with('success', 'Product added to stock transfer (SuccessCode - 002)');

		                			}
		                			else
		                			{
		                				return redirect()->back()->with('error', 'Failed to add product to stock transfer (ErrorCode - 010)');
		                			}
                				}
                				else
                				{
                					return redirect()->back()->with('error', 'Do not have sufficient quantity to make stock transfer - Available Quantity '.$getproductstock->productquantity.' (ErrorCode - 009)');
                				}
                			}
                			else
                			{
                				return redirect()->back()->with('error', 'Do not have sufficient quantity to make stock transfer (ErrorCode - 008)');
                			}
                		}
                		else
                		{
                			return redirect()->back()->with('error', 'No stock found related to this barcode. (ErrorCode - 007)');
                		}
                	}
                	else if($getbarcodecount == 0)
                	{
                		return redirect()->back()->with('error', 'Only quantity products can be added by barcode. For unique products. Please use IMEI or Serial (ErrorCode - 002)');
                	}
                }
                else
                {
                	return redirect()->back()->with('error', 'No product found related to this barcode. (ErrorCode - 001)');
                }
            }
        }
    }

    public function stbyproductid(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'productid'=>'required',
            'quantity'=>'required',
            'stocktransferid'=>'required'
            ],[
                'productid.required'=>'Please enter productid',
                'quantity.required'=>'Please enter quantity',
                'stocktransferid.required'=>'Please enter stock transfer id'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
            	$productid = $request->input('productid');
            	$quantity = $request->input('quantity');
            	$stocktransferid = $request->input('stocktransferid');

                $checkproductstock = productstock::where('productID', $productid)
                ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->first();

                if($checkproductstock != '')
                {
                	if($checkproductstock->productquantity > 0)
                	{
                		if($checkproductstock->productquantity >= $quantity)
                		{
                			$insertstitem = new stocktransferitem;
                			$insertstitem->stocktransferID = $stocktransferid;
                			$insertstitem->productID = $productid;
                			$insertstitem->stockID = $checkproductstock->psID;
                			$insertstitem->quantity = $quantity;
                			$insertstitem->save();

                			if($insertstitem->save())
                			{
                				$remainquantity = $checkproductstock->productquantity - $quantity;

                				$checkproductstock->productquantity = $remainquantity;
                				$checkproductstock->save();

                				return redirect()->back()->with('success', 'Product added to stock transfer (SuccessCode - 001)');

                			}
                			else
                			{
                				return redirect()->back()->with('error', 'Failed to add product to stock transfer (ErrorCode - 006)');
                			}
                		}
                		else
                		{
                			return redirect()->back()->with('error', 'Do not have sufficient quantity to make stock transfer - Available Quantity '.$checkproductstock->productquantity.' (ErrorCode - 005)');
                		}
                	}
                	else
                	{
                		return redirect()->back()->with('error', 'Do not have sufficient quantity to make stock transfer (ErrorCode - 004)');
                	}
                }
                else
                {
                	return redirect()->back()->with('error', 'No stock found related to this barcode. (ErrorCode - 003)');
                }
            }
        }
    }

    public function stbyimei(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'stocktransferid'=>'required',
            'imei'=>'required'
            ],[
                'stocktransferid.required'=>'Please enter stock transfer id',
                'imei.required'=>'Please enter IMEI'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
            	$imei = $request->input('imei');
            	$stocktransferid = $request->input('stocktransferid');

                $checkimei = productstock::where('productimei', $imei)
                ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->first();

                if($checkimei != '')
                {
                	if($checkimei->productquantity > 0)
                	{
                		$insertstitem = new stocktransferitem;
                		$insertstitem->stocktransferID = $stocktransferid;
                		$insertstitem->productID = $checkimei->productID;
                		$insertstitem->stockID = $checkimei->psID;
                		$insertstitem->quantity = '1';
                		$insertstitem->save();

                		if($insertstitem->save())
            			{
            				$checkimei->productquantity = '0';
            				$checkimei->save();

            				return redirect()->back()->with('success', 'IMEI added to stock transfer (SuccessCode - 003)');

            			}
            			else
            			{
            				return redirect()->back()->with('error', 'Failed to add IMEI to stock transfer (ErrorCode - 013)');
            			}
                	}
                	else
                	{
                		return redirect()->back()->with('error', 'IMEI sold out OR in transfer. (ErrorCode - 012)');
                	}
                }
                else
                {
                	return redirect()->back()->with('error', 'No IMEI found. (ErrorCode - 011)');
                }
            }
        }
    }

    public function stbyserial(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'stocktransferid'=>'required',
            'imei'=>'required'
            ],[
                'stocktransferid.required'=>'Please enter stock transfer id',
                'imei.required'=>'Please enter IMEI'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
            	$imei = $request->input('imei');
            	$stocktransferid = $request->input('stocktransferid');

                $checkimei = productstock::where('productimei', $imei)
                ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->first();

                if($checkimei != '')
                {
                	if($checkimei->productquantity > 0)
                	{
                		$insertstitem = new stocktransferitem;
                		$insertstitem->stocktransferID = $stocktransferid;
                		$insertstitem->productID = $checkimei->productID;
                		$insertstitem->stockID = $checkimei->psID;
                		$insertstitem->quantity = '1';
                		$insertstitem->save();

                		if($insertstitem->save())
            			{
            				$checkimei->productquantity = '0';
            				$checkimei->save();

            				return redirect()->back()->with('success', 'Serial added to stock transfer (SuccessCode - 004)');

            			}
            			else
            			{
            				return redirect()->back()->with('error', 'Failed to add Serial to stock transfer (ErrorCode - 016)');
            			}
                	}
                	else
                	{
                		return redirect()->back()->with('error', 'Serial sold out OR in transfer. (ErrorCode - 015)');
                	}
                }
                else
                {
                	return redirect()->back()->with('error', 'No Serial found. (ErrorCode - 014)');
                }
            }
        }
    }

    public function editquantity(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'itemid'=>'required',
            'quantity'=>'required'
            ],[
                'itemid.required'=>'Please enter item id',
                'quantity.required'=>'Please enter quantity'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkproductstock = productstock::where('psID', $request->input('stockid'))->first();

                if($checkproductstock != '')
                {
                    if($checkproductstock->productquantity >= $request->input('quantity'))
                    {
                        $itemid = $request->input('itemid');

                        $item = stocktransferitem::find($itemid);
                        $quantitytoupdate = $item->quantity + $request->input('quantity');
                        $item->quantity = $quantitytoupdate;
                        $item->save();

                        if($item->save())
                        {
                            $remainquantity = $checkproductstock->productquantity - $request->input('quantity');
                            $checkproductstock->productquantity = $remainquantity;
                            $checkproductstock->save();

                            return redirect()->back()->with('success', 'Quantity updated successfully. (SuccessCode - 015)');
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Failed to update quantity (ErrorCode - 017)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Cannot transfer more than available quantity (ErrorCode - 019)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Stock not found (ErrorCode - 018)');
                }
            }
        }
    }

    public function deleteitem(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'itemid'=>'required',
            'quantity'=>'required'
            ],[
                'itemid.required'=>'Please enter item id',
                'quantity.required'=>'Please enter quantity'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkproductstock = productstock::where('psID', $request->input('stockid'))->first();

                if($checkproductstock != '')
                {
                    $quantitytoupdate= $checkproductstock->productquantity + $request->input('quantity');
                    $checkproductstock->productquantity = $quantitytoupdate;
                    $checkproductstock->save();

                    if($checkproductstock->save())
                    {
                        $deleteitem = stocktransferitem::find($request->input('itemid'))->delete();

                        return redirect()->back()->with('success', 'Item Removed (SuccessCode - 016)');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to return quantity (ErrorCode - 021)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Stock not found (ErrorCode - 020)');
                }
            }
        }
    }

    public function proceedtransfer(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'transferid'=>'required'
            ],[
                'transferid.required'=>'Please enter transfer id'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checktransfer = stocktransfer::where('stocktransferID', $request->input('transferid'))->where('fromStoreID', session::get('loggindata.loggeduserstore.store_id'))->first();

                if($checktransfer->toStoreID != "")
                {
                    $checktransferitem = stocktransferitem::where('stocktransferID', $checktransfer->stocktransferID)->count();

                    if($checktransferitem > 0)
                    {
                        $checktransfer->stocktransferStatus = '1';
                        $checktransfer->save();

                        if($checktransfer->save())
                        {
                            return redirect()->route('stocktransfer')->with('success', 'Transfer proccessed successfully. (successCode - 017)');
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Failed to proccess transfer (ErrorCode - 023)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Cannot proccess empty transfer. (ErrorCode - 022)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Please select a store to transfer stock. (ErrorCode - 123)');
                }
            }
        }
    }

    public function stocktransferinvoice($id)
    {   
        if(session::get('loggindata.loggeduserpermission.viewstocktransferout')=='N' ||session::get('loggindata.loggeduserpermission.viewstocktransferout')=='')
        {
            return redirect('404');
        } 
        else
        {
            $checktransfer = stocktransfer::where('stocktransferID', $id)
            ->whereIn('stocktransferStatus', [1, 2])
            ->leftJoin('users', 'users.id', '=', 'stocktransfer.toUserID')
            ->leftJoin('store', 'store.store_id', '=', 'stocktransfer.toStoreID')
            ->with('fromstore')
            ->first();

            //return $checktransfer;

            $transferitem = stocktransferitem::where('stocktransferID', $id)
            ->leftJoin('products', 'products.productID', '=', 'stocktransferitems.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'stocktransferitems.stockID')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->get();

            $invoicestocktransferdata = ['checktransfer'=>$checktransfer, 'transferitem'=>$transferitem];

            return view('stocktransfer-invoice')->with('invoicestocktransferdata', $invoicestocktransferdata);
        }
    }

    public function stocktransferreceive($id)
    {   
        if(session::get('loggindata.loggeduserpermission.viewstocktransferin')=='N' ||session::get('loggindata.loggeduserpermission.viewstocktransferin')=='')
        {
            return redirect('404');
        } 
        else
        {
            $checktransfer = stocktransfer::where('stocktransferID', $id)->whereIn('stocktransferStatus', [1, 2])
            ->leftJoin('users', 'users.id', '=', 'stocktransfer.fromUserID')
            ->leftJoin('store', 'store.store_id', '=', 'stocktransfer.fromStoreID')
            ->where('stocktransfer.toStoreID', session::get('loggindata.loggeduserstore.store_id'))
            ->first();

            $transferitem = stocktransferitem::where('stocktransferID', $id)
            ->leftJoin('products', 'products.productID', '=', 'stocktransferitems.productID')
            ->leftJoin('productstock', 'productstock.psID', '=', 'stocktransferitems.stockID')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->get();

            $createstocktransferdata = ['checktransfer'=>$checktransfer, 'transferitem'=>$transferitem];

            return view('stocktransfer-receive')->with('createstocktransferdata', $createstocktransferdata);
        }
    }

    public function receivestocktransfer(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.viewstocktransferin')=='N' || session::get('loggindata.loggeduserpermission.viewstocktransferin')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'transferid'=>'required'
            ],[
                'transferid.required'=>'Please enter transfer id'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checktransfer = stocktransfer::where('stocktransferID', $request->input('transferid'))->where('toStoreID', session::get('loggindata.loggeduserstore.store_id'))->first();

                $checktransferitem = stocktransferitem::where('stocktransferID', $checktransfer->stocktransferID)
                ->where('productID', $request->input('productid'))
                ->where('stockID', $request->input('stockid'))
                ->whereNull('receiveStatus')
                ->first();

                if($checktransferitem != '')
                {
                    if($request->input('imei') != '')
                    {
                        $findproductstock = productstock::where('psID', $request->input('stockid'))
                        ->where('productID', $request->input('productid'))
                        ->where('productimei', $request->input('imei'))
                        ->first();
                        $findproductstock->productquantity = '1';
                        $findproductstock->storeID = $request->input('acceptstore');
                        $findproductstock->save();

                        if($findproductstock->save())
                        {
                            $checktransferitem->receiveStatus = '1';
                            $checktransferitem->save();

                            if($checktransferitem->save())
                            {
                                $checktransfer->toUserID = session::get('loggindata.loggedinuser.id');
                                $checktransfer->receivetrasnsferDate = date('Y-m-d');
                                $checktransfer->stocktransferStatus = 2;
                                $checktransfer->save();
                                return redirect()->back()->with('success', 'Item Received successfully (SuccessCode - 018)');
                            }
                            else
                            {
                                return redirect()->back()->with('error', 'Failed to get product stock. (ErrorCode - 026)');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Failed to receive item. (ErrorCode - 025)');
                        }
                    }
                    else
                    {  
                        $findproductstock = productstock::where('productID', $request->input('productid'))
                        ->where('storeID', $request->input('acceptstore'))
                        ->count();

                        if($findproductstock > 0)
                        {
                            $updateproductstock = productstock::where('productID', $request->input('productid'))
                            ->where('storeID', $request->input('acceptstore'))
                            ->first();

                            $quantitytoupdate = $updateproductstock->productquantity + $request->input('quantity');
                            $updateproductstock->productquantity = $quantitytoupdate;
                            $updateproductstock->save();

                            if($updateproductstock->save())
                            {
                                $checktransferitem->receiveStatus = '1';
                                $checktransferitem->save();

                                if($checktransferitem->save())
                                {
                                    $checktransfer->toUserID = session::get('loggindata.loggedinuser.id');
                                    $checktransfer->receivetrasnsferDate = date('Y-m-d');
                                    $checktransfer->stocktransferStatus = 2;
                                    $checktransfer->save();

                                    if($checktransfer->save())
                                    {
                                        return redirect()->back()->with('success', 'Item Received successfully. (SuccessCode - 020)');
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('error', 'faile to update transfer. (ErrorCode - 029)');
                                    }
                                }
                                else
                                {
                                    return redirect()->back()->with('error', 'faile to update item. (ErrorCode - 027)');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error', 'Failed to get product stock (ErrorCode - 028)');
                            }
                        }
                        else
                        {
                            $productstockdetail = productstock::where('psID', $request->input('stockid'))
                            ->where('productID', $request->input('productid'))
                            ->first();

                            $insertproductstock = new productstock;
                            $insertproductstock->ponumber = $productstockdetail->ponumber;
                            $insertproductstock->productID = $request->input('productid');
                            $insertproductstock->productquantity = $request->input('quantity');
                            $insertproductstock->ppingst = $productstockdetail->ppingst;
                            $insertproductstock->storeID = $request->input('acceptstore');
                            $insertproductstock->save();

                            if($insertproductstock->save())
                            {
                                $checktransferitem->receiveStatus = '1';
                                $checktransferitem->save();

                                if($checktransferitem->save())
                                {
                                    $checktransfer->toUserID = session::get('loggindata.loggedinuser.id');
                                    $checktransfer->stocktransferStatus = '2';
                                    $checktransfer->receivetrasnsferDate = date('Y-m-d');
                                    $checktransfer->save();

                                    if($checktransfer->save())
                                    {
                                        return redirect()->back()->with('success', 'Item received successfully. (SuccessCode - 032)');
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('error', 'Failed to update transfer. (ErrorCode - 032)');
                                    }
                                }
                                else
                                {
                                    return redirect()->back()->with('error', 'Failed to update item. (ErrorCode - 031)');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error', 'Failed to receive item. (ErrorCode - 030)');
                            }
                        }
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Couldnot found item in transfer. (ErrorCode - 024)');
                }
            }
        }
    }

    public function cancelstocktransfer(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addstocktransfer')=='N' || session::get('loggindata.loggeduserpermission.addstocktransfer')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'stocktransferid'=>'required'
            ],[
                'stocktransferid.required'=>'Please enter transfer id'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checktransfer = stocktransfer::where('stocktransferID', $request->input('stocktransferid'))->where('fromStoreID', session::get('loggindata.loggeduserstore.store_id'))->first();

                if($checktransfer != "")
                {
                    if($checktransfer->stocktransferStatus == 0)
                    {
                        $checktransferitem = stocktransferitem::where('stocktransferID', $checktransfer->stocktransferID)
                        ->count();

                        if($checktransferitem == 0)
                        {
                            $deletestocktransfer = stocktransfer::where('stocktransferID', $checktransfer->stocktransferID)->where('fromStoreID', session::get('loggindata.loggeduserstore.store_id'))->delete();

                            if($deletestocktransfer == 1)
                            {
                                return redirect('stocktransfer')->with('success', 'Stock transfer cancelled successfully. (SuccessCode - 101)');
                            }
                            else
                            {
                                return redirect()->back()->with('error', 'Failed to cancel stock transfer. (ErrorCode - 104)');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Please delete all items from transfer and try cancel transfer. (ErrorCode - 103)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Stock Transfer cannot be cancel as stock transfer is processed. (ErrorCode - 102)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Stock Transfer not found. (ErrorCode - 101)');
                }
            }
        }
    }
}
