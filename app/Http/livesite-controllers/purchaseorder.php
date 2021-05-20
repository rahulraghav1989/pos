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
use App\demoreceiveorder;
use App\demoreceiveorderitem;
use App\demoreceivedetail;
use App\demostock;

class purchaseorder extends Controller
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

        /***Filters****/
        $allbrands= masterbrand::where('brandstatus', '1')->get();
        $allcolours= mastercolour::where('colourstatus', '1')->get();
        $allmodels= mastermodel::where('modelstatus', '1')->get();
        $allstockgroup= masterstockgroup::where('stockgroupstatus', '1')->get();
        $allsuppliers= mastersupplier::where('supplierstatus', '1')->get();
        $alltaxs= mastertax::where('taxstatus', '1')->get();
        $allcategories= mastercategory::with('subcategory')->where('categorystatus', '1')->get();
        $allproducttype= masterproducttype::where('producttypestatus', '1')->get();
        $allstores= store::get();

        $filtersdata = array(
            'allbrands'=>$allbrands,
            'allcolours'=>$allcolours,
            'allmodels'=>$allmodels,
            'allstockgroup'=>$allstockgroup,
            'allsuppliers'=>$allsuppliers,
            'alltaxs'=>$alltaxs,
            'allcategories'=>$allcategories,
            'allproducttype'=>$allproducttype,
            'allstores'=>$allstores
        ); 
        session::put('filtersdata', $filtersdata);
        /***Filters****/

        $allstore= store::get();

        session::put('allstore', $allstore);
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function productpurchaseorderview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='N' ||session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('pofilterdata.postatus')=='')
            {
                $processstatus = '1';
            }
            else
            {
               $processstatus = session::get('pofilterdata.postatus'); 
            }

            if(!empty(session::get('pofilterdata.startdate')))
            {
                $firstday = date('Y-m-d', strtotime(session::get('pofilterdata.startdate')));
            }
            else
            {
                /*$firstday = date('Y')."-".date('m')."-01";*/
                $firstday = date('Y-m-d', strtotime('today - 30 days'));
            }

            if(!empty(session::get('pofilterdata.enddate')))
            {
                $lastday = date('Y-m-d', strtotime(session::get('pofilterdata.enddate')));
            }
            else
            {
                /*$lastday = date("Y-m-t", strtotime($firstday));*/
                $lastday = date('Y-m-d', strtotime($firstday.' + 50 days'));
            }

            $supplier= session::get('pofilterdata.supplier');
            $store= session::get('pofilterdata.store');

            if(session::get('loggindata.loggeduserstore')!='')
            {
                $allpurchaseorder=productpurchaseorder::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->where('productpurchaseorder.poprocessstatus', 'LIKE', '%'.$processstatus.'%')
                ->whereDate('productpurchaseorder.created_at', '>=', $firstday)
                ->whereDate('productpurchaseorder.created_at', '<=', $lastday)
                ->leftJoin('store', 'store.store_id','=','storeID')
                ->leftJoin('users', 'users.id','=','userID')
                ->leftJoin('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                ->leftJoin('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'productpurchaseorder.supplierID')
                ->where('productpurchaseorder.storeID', 'LIKE', '%'.$store.'%')
                ->where('productpurchaseorder.supplierID', 'LIKE', '%'.$supplier.'%')
                ->get(array(
                    'productpurchaseorder.poID',
                    'productpurchaseorder.storeID',
                    'productpurchaseorder.ponumber',
                    'productpurchaseorder.porefrencenumber',
                    'productpurchaseorder.poprocessstatus',
                    'productpurchaseorder.docketnumber',
                    'productpurchaseorder.docketnumber',
                    'productpurchaseorder.ponote',
                    'productpurchaseorder.supplierID',
                    'productpurchaseorder.userID',
                    'productpurchaseorder.created_at',
                    'mastersupplier.suppliername',
                    'users.name',
                    'store.store_name',
                    'products.productname',
                    'productpurchaseorderitem.productID',
                    'productpurchaseorderitem.poquantity',
                    'productpurchaseorderitem.receivequantity',
                    'productpurchaseorderitem.poppingst',
                    'productpurchaseorderitem.poitemstatus'
                ));
            }
            else
            {
                $allpurchaseorder= productpurchaseorder::
                where('poprocessstatus', 'LIKE', '%'.$processstatus.'%')
                ->whereDate('productpurchaseorder.created_at', '>=', $firstday)
                ->whereDate('productpurchaseorder.created_at', '<=', $lastday)
                ->leftJoin('store', 'store.store_id','=','storeID')
                ->leftJoin('users', 'users.id','=','userID')
                ->leftJoin('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                ->leftJoin('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'productpurchaseorder.supplierID')
                ->where('productpurchaseorder.storeID', 'LIKE', '%'.$store.'%')
                ->where('productpurchaseorder.supplierID', 'LIKE', '%'.$supplier.'%')
                ->get(array(
                    'productpurchaseorder.poID',
                    'productpurchaseorder.storeID',
                    'productpurchaseorder.ponumber',
                    'productpurchaseorder.porefrencenumber',
                    'productpurchaseorder.poprocessstatus',
                    'productpurchaseorder.docketnumber',
                    'productpurchaseorder.docketnumber',
                    'productpurchaseorder.ponote',
                    'productpurchaseorder.supplierID',
                    'productpurchaseorder.userID',
                    'productpurchaseorder.created_at',
                    'mastersupplier.suppliername',
                    'users.name',
                    'store.store_name',
                    'products.productname',
                    'productpurchaseorderitem.productID',
                    'productpurchaseorderitem.poquantity',
                    'productpurchaseorderitem.receivequantity',
                    'productpurchaseorderitem.poppingst',
                    'productpurchaseorderitem.poitemstatus'
                ));
            }
            //return $allpurchaseorder;
            $poviewdata = ['allpurchaseorder'=>$allpurchaseorder, 'firstday'=>$firstday, 'lastday'=>$lastday, 'processstatus'=>$processstatus, 'supplier'=>$supplier, 'store'=>$store];
            return view('purchaseorder')->with('poviewdata',$poviewdata);
        }
    }

    public function createpostepfirst(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addpurchaseorder')=='N' || session::get('loggindata.loggeduserpermission.addpurchaseorder')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'store'=>'required'
            ],[
                'store.required'=>'Store is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
            	$checkpo= productpurchaseorder::where('storeID', '=', $request->input('store'))->count();

            	$getstorecode = store::where('store_id', $request->input('store'))->first();

            	$checkpo = $checkpo+1;

            	$purchaseordernumber= $getstorecode->store_code.$checkpo;

            	$checkponumber = productpurchaseorder::where('ponumber', '=', $purchaseordernumber)->count();

            	if($checkponumber=='0')
            	{
            		$insertpo = new productpurchaseorder;
            		$insertpo->storeID = $request->input('store');
            		$insertpo->ponumber = $purchaseordernumber;
            		$insertpo->poprocessstatus = '0';
            		$insertpo->userID = session::get('loggindata.loggedinuser.id');
            		$insertpo->save();

            		if($insertpo->save())
            		{
            			return redirect()->route('purchaseordercreation',  ['id' => $purchaseordernumber]);
            		}
            	}
            	else
            	{
            		return redirect()->back()->with('poerror', 'Could not save purchase order.');
            	}
            }
        }
    }

    public function purchaseordercreationview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='N' ||session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getpo = productpurchaseorder::leftJoin('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
            ->leftJoin('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
            ->leftJoin('store', 'store.store_id', '=', 'productpurchaseorder.storeID')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'productpurchaseorder.supplierID')
            ->where('productpurchaseorder.ponumber', $id)
            ->get();

            //return $getpo;
            
            $allproducts = product::with('productbrand')
            ->with('productcolour')
            ->with('productmodel')
            ->with('productsupplier')
            ->where('products.productstatus', '1')
            ->get();

            $allbrands= masterbrand::where('brandstatus', '1')->get();
            $allcolours= mastercolour::where('colourstatus', '1')->get();
            $allmodels= mastermodel::where('modelstatus', '1')->get();
            $allstockgroup= masterstockgroup::where('stockgroupstatus', '1')->get();
            $allsuppliers= mastersupplier::where('supplierstatus', '1')->get();
            $alltaxs= mastertax::where('taxstatus', '1')->get();
            $allcategories= mastercategory::with('subcategory')->where('categorystatus', '1')->get();
            $allproducttype = masterproducttype::where('producttypestatus', '1')->get();
            
            $podata = ['id'=>$id,'getpo'=> $getpo, 'allproducts'=>$allproducts, 'allbrands'=>$allbrands, 'allcolours'=>$allcolours, 'allmodels'=>$allmodels, 'allstockgroup'=>$allstockgroup, 'allsuppliers'=>$allsuppliers, 'alltaxs'=>$alltaxs, 'allcategories'=>$allcategories, 'allproducttype'=>$allproducttype];
            return view('purchaseordercreate')->with('podata', $podata);
        }
    }

    public function addpositem(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addpurchaseorder')=='N' || session::get('loggindata.loggeduserpermission.addpurchaseorder')=='')
        {
            return redirect('404');
        }
        else
        { 
            $ponumber = $request->input('ponumber');
            $quantity = $request->input('quantity');
            $barcode = $request->input('barcode');
            $productid = $request->input('productid');
            $supplierid = $request->input('supplier');

            if($productid != '')
            {
            	$getproduct = product::where('productID', $productid)->first();

            	$checkpoitem = productpurchaseorderitem::where('ponumber', $ponumber)->where('productID', $getproduct->productID)->count();

            	if($checkpoitem == 0)
        		{
        			$insertpoitem = new productpurchaseorderitem;
            		$insertpoitem->ponumber= $ponumber;
            		$insertpoitem->productID= $getproduct->productID;
            		$insertpoitem->poquantity= '1';
            		$insertpoitem->popurchaseprice= $getproduct->ppexgst;
            		$insertpoitem->popptax= $getproduct->ppgst;
            		$insertpoitem->poppingst= $getproduct->ppingst;
                    $insertpoitem->poitemtotal= $getproduct->ppingst;
            		$insertpoitem->poitemstatus= '0';
            		$insertpoitem->save();

            		$updateposupplier= productpurchaseorder::where('ponumber', $ponumber)->first();

            		
        			$updateposupplier->supplierID = $request->input('supplier');
                    $updateposupplier->porefrencenumber = $request->input('reference');
                    $updateposupplier->ponote = $request->input('note');
            		$updateposupplier->save();

            		if($insertpoitem->save())
            		{
            			return redirect()->back()->with('poitemsuccess', 'Product added to purchase order');
            		}
            		else
            		{
            			return redirect()->back()->with('poitemerror', 'Failed to add product to purchase order');
            		}
        		}
        		else
        		{
        			$updatepoitem = productpurchaseorderitem::where('ponumber', $ponumber)->where('productID', $getproduct->productID)->first();
        			$updatedquantity = '1' + $updatepoitem->poquantity;
                    $itemtotal = $updatepoitem->poppingst * $updatedquantity;
        			$updatepoitem->poquantity = $updatedquantity;
                    $updatepoitem->poitemtotal = $itemtotal;
        			$updatepoitem->save();
        			if($updatepoitem->save())
            		{
            			return redirect()->back()->with('poitemsuccess', 'Product added to purchase order');
            		}
            		else
            		{
            			return redirect()->back()->with('poitemerror', 'Failed to add product to purchase order');
            		}
        		}
            }
            else if($barcode != '')
            {
            	$checkproduct = product::where('barcode', $barcode)->where('productstatus', '1')->count();

                if($checkproduct > 1)
                {
                    $getproduct = product::where('barcode', $barcode)
                    ->with('productbrand')
                    ->with('productcolour')
                    ->with('productmodel')
                    ->with('productsupplier')
                    ->get();

                    $multibarcodeopenmodel = '1';

                    $productdata = ['multibarcodeopenmodel'=>$multibarcodeopenmodel, 'getproduct'=>$getproduct, 'quantity'=>$quantity, 'ponumber'=>$ponumber, 'supplierid'=>$supplierid];

                    //return $productdata;

                    return redirect()->back()->with('productdata', $productdata);
                }
            	else if($checkproduct == 1)
            	{
            		$getproduct = product::where('barcode', $barcode)->where('productstatus', '1')->first();

            		$checkpoitem = productpurchaseorderitem::where('ponumber', $ponumber)->where('productID', $getproduct->productID)->count();

            		if($checkpoitem == 0)
            		{
            			$insertpoitem = new productpurchaseorderitem;
	            		$insertpoitem->ponumber= $ponumber;
	            		$insertpoitem->productID= $getproduct->productID;
	            		$insertpoitem->poquantity= $quantity;
	            		$insertpoitem->popurchaseprice= $getproduct->ppexgst;
	            		$insertpoitem->popptax= $getproduct->ppgst;
	            		$insertpoitem->poppingst= $getproduct->ppingst;
                        $insertpoitem->poitemtotal= $getproduct->ppingst * $quantity;
	            		$insertpoitem->poitemstatus= '0';
	            		$insertpoitem->save();

	            		$updateposupplier= productpurchaseorder::where('ponumber', $ponumber)->first();
	            		$updateposupplier->supplierID = $request->input('supplier');
                        $updateposupplier->porefrencenumber = $request->input('reference');
                        $updateposupplier->ponote = $request->input('note');
	            		$updateposupplier->save();

	            		if($insertpoitem->save())
	            		{
	            			return redirect()->back()->with('poitemsuccess', 'Product added to purchase order');
	            		}
	            		else
	            		{
	            			return redirect()->back()->with('poitemerror', 'Failed to add product to purchase order');
	            		}
            		}
            		else
            		{
            			$updatepoitem = productpurchaseorderitem::where('ponumber', $ponumber)->where('productID', $getproduct->productID)->first();
            			$updatedquantity = $quantity + $updatepoitem->poquantity;
                        $itemtotal = $updatepoitem->poppingst * $updatedquantity;
            			$updatepoitem->poquantity = $updatedquantity;
                        $updatepoitem->poitemtotal = $itemtotal;
            			$updatepoitem->save();
            			if($updatepoitem->save())
	            		{
	            			return redirect()->back()->with('poitemsuccess', 'Product added to purchase order');
	            		}
	            		else
	            		{
	            			return redirect()->back()->with('poitemerror', 'Failed to add product to purchase order');
	            		}
            		}
            	}
            	else
            	{
            		return redirect()->back()->with('poitemerror', 'Barcode not found.');
            	}

            }
            else
            {
            	return redirect()->back()->with('poitemerror', 'Barcode could not be empty.');
            }
        }
    }

    public function finalposubmission(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addpurchaseorder')=='N' ||session::get('loggindata.loggeduserpermission.addpurchaseorder')=='')
        {
            return redirect('404');
        } 
        else
        {
            $ponumber=$request->input('ponumber');
            $postatus=$request->input('postatus');

            $checkpoitem = productpurchaseorderitem::where('ponumber', $ponumber)->count();

            if($checkpoitem > 0)
            {
                $updatefindpo = productpurchaseorder::where('ponumber', $ponumber)->first();
                $updatefindpo->poprocessstatus = $postatus;
                $updatefindpo->save();

                return redirect('productpurchaseorder');
            }
            else
            {
                return redirect()->back()->with('poitemerror', 'Cannot process empty PO. Please add any product to PO.');
            }
        }
    }

    public function productreceiveorderview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='N' ||session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(!empty(session::get('poreceivefilter.startdate')))
            {
                $firstday = date('Y-m-d', strtotime(session::get('poreceivefilter.startdate')));
            }
            else
            {
                /*$firstday = date('Y')."-".date('m')."-01";*/
                $firstday = date('Y-m-d', strtotime('today - 30 days'));
            }

            if(!empty(session::get('poreceivefilter.enddate')))
            {
                $lastday = date('Y-m-d', strtotime(session::get('poreceivefilter.enddate')));
            }
            else
            {
                /*$lastday = date("Y-m-t", strtotime($firstday));*/
                $lastday = date('Y-m-d', strtotime($firstday.' + 50 days'));
            }

            $supplier= session::get('poreceivefilter.supplier');
            $store= session::get('poreceivefilter.store');

            if(session::get('loggindata.loggeduserstore')!='')
            {
                $allpurchaseorder=productpurchaseorder::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->where('poprocessstatus', '!=', '0')
                ->where('poprocessstatus', '!=', '2')
                ->where('poprocessstatus', '!=', '3')
                ->where('poprocessstatus', '!=', '4')
                ->join('store', 'store.store_id','=','storeID')
                ->join('users', 'users.id','=','userID')
                ->join('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                ->join('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->with('posupplier')
                ->where('productpurchaseorder.storeID', 'LIKE', '%'.$store.'%')
                ->where('productpurchaseorder.supplierID', 'LIKE', '%'.$supplier.'%')
                ->whereDate('productpurchaseorder.created_at', '>=', $firstday)
                ->whereDate('productpurchaseorder.created_at', '<=', $lastday)
                ->get();

                $stockinhand=productpurchaseorder::where('poprocessstatus', '1')
                ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->join('productpurchaseorderitem', 'productpurchaseorderitem.ponumber','=', 'productpurchaseorder.ponumber')
                ->join('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->whereIn('productpurchaseorderitem.poitemstatus', ['0', '1'])
                ->where('productpurchaseorder.storeID', 'LIKE', '%'.$store.'%')
                ->where('productpurchaseorder.supplierID', 'LIKE', '%'.$supplier.'%')
                ->whereDate('productpurchaseorder.created_at', '>=', $firstday)
                ->whereDate('productpurchaseorder.created_at', '<=', $lastday)
                ->get(array('productpurchaseorderitem.productID', 'productpurchaseorderitem.poquantity', 'productpurchaseorderitem.receivequantity', 'products.productname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'productpurchaseorder.ponumber','productpurchaseorder.storeID'));
                //return $stockinhand;
            }
            else
            {
                $allpurchaseorder= productpurchaseorder::where('poprocessstatus', '!=', '0')
                ->where('poprocessstatus', '!=', '2')
                ->where('poprocessstatus', '!=', '3')
                ->where('poprocessstatus', '!=', '4')
                ->join('store', 'store.store_id','=','storeID')
                ->join('users', 'users.id','=','userID')
                ->join('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                ->join('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->with('posupplier')
                ->where('productpurchaseorder.storeID', 'LIKE', '%'.$store.'%')
                ->where('productpurchaseorder.supplierID', 'LIKE', '%'.$supplier.'%')
                ->whereDate('productpurchaseorder.created_at', '>=', $firstday)
                ->whereDate('productpurchaseorder.created_at', '<=', $lastday)
                ->get();

                //return $allpurchaseorder;

                $stockinhand=productpurchaseorderitem::whereIn('poitemstatus', ['0','1'])
                ->join('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->join('productpurchaseorder', 'productpurchaseorder.ponumber','=','productpurchaseorderitem.ponumber')
                ->where('productpurchaseorder.storeID', 'LIKE', '%'.$store.'%')
                ->where('productpurchaseorder.supplierID', 'LIKE', '%'.$supplier.'%')
                ->whereDate('productpurchaseorder.created_at', '>=', $firstday)
                ->whereDate('productpurchaseorder.created_at', '<=', $lastday)
                ->get(array('productpurchaseorderitem.productID', 'productpurchaseorderitem.poquantity', 'productpurchaseorderitem.receivequantity', 'products.productname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'productpurchaseorder.ponumber','productpurchaseorder.storeID', 'productpurchaseorder.porefrencenumber'));

                //return $stockinhand;
            }

            $receivepodata = ['allpurchaseorder'=>$allpurchaseorder, 'stockinhand'=>$stockinhand, 'supplier'=>$supplier, 'store'=>$store, 'firstday'=>$firstday, 'lastday'=>$lastday];
            return view('purchaseorderreceived')->with('receivepodata', $receivepodata);
        }
    }

    public function purchaseorderreceiveitemview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='N' ||session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')!='')
            {
                $allpurchaseorderitem=productpurchaseorder::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->where('productpurchaseorder.ponumber', $id)
                ->join('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                ->join('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->get();
            }
            else
            {
                $allpurchaseorderitem= productpurchaseorder::where('productpurchaseorder.ponumber', $id)
                ->join('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                ->join('products', 'products.productID', '=', 'productpurchaseorderitem.productID')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->get();
            }

            //return $allpurchaseorderitem;

            return view('purchaseorderreceiveditems')->with('allpurchaseorderitem', $allpurchaseorderitem);
        }
    }

    public function partialpo(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='N' ||session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getpo = productpurchaseorder::where('ponumber', $request->input('ponumber'))->first();
            $getpo->poprocessstatus= '4';
            $getpo->save();

            $getpoitem= productpurchaseorderitem::where('ponumber', $request->input('ponumber'))->update(['poitemstatus'=>2]);

            return redirect()->back()->with('poreceivsuccess', 'PO successfully marked as partial received');
        }
    }

    public function purchaseorderincompleteview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='N' ||session::get('loggindata.loggeduserpermission.viewpurchaseorder')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(!empty(session::get('poincomfilter.startdate')))
            {
                $firstday = date('Y-m-d', strtotime(session::get('poincomfilter.startdate')));
            }
            else
            {
                $firstday = date('Y')."-".date('m')."-01";
            }

            if(!empty(session::get('poincomfilter.enddate')))
            {
                $lastday = date('Y-m-d', strtotime(session::get('poincomfilter.enddate')));
            }
            else
            {
                $lastday = date("Y-m-t", strtotime($firstday));
            }

            $store= session::get('poincomfilter.store');

            if(session::get('loggindata.loggeduserstore')!='')
            {

                $allincompletepurchaseorder=productpurchaseorder::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->where('poprocessstatus', '0')
                ->join('store', 'store.store_id','=','storeID')
                ->join('users', 'users.id','=','userID')
                ->with('poitem')
                ->with('posupplier')
                ->where('productpurchaseorder.storeID', 'LIKE', '%'.$store.'%')
                ->whereBetween('productpurchaseorder.created_at', [$firstday, $lastday])
                ->get(array(
                    'productpurchaseorder.poID',
                    'productpurchaseorder.ponumber',
                    'productpurchaseorder.porefrencenumber',
                    'productpurchaseorder.storeID',
                    'productpurchaseorder.docketnumber',
                    'productpurchaseorder.poprocessstatus',
                    'productpurchaseorder.ponote',
                    'productpurchaseorder.supplierID',
                    'productpurchaseorder.userID',
                    'productpurchaseorder.created_at',
                    'store.store_name',
                    'users.name'
                ));
            }
            else
            {
                $allincompletepurchaseorder= productpurchaseorder::where('poprocessstatus', '0')
                ->with('poitem')
                ->with('posupplier')
                ->join('store', 'store.store_id','=','storeID')
                ->join('users', 'users.id','=','userID')
                ->where('productpurchaseorder.storeID', 'LIKE', '%'.$store.'%')
                ->whereBetween('productpurchaseorder.created_at', [$firstday, $lastday])
                ->get(array(
                    'productpurchaseorder.poID',
                    'productpurchaseorder.ponumber',
                    'productpurchaseorder.porefrencenumber',
                    'productpurchaseorder.storeID',
                    'productpurchaseorder.docketnumber',
                    'productpurchaseorder.poprocessstatus',
                    'productpurchaseorder.ponote',
                    'productpurchaseorder.supplierID',
                    'productpurchaseorder.userID',
                    'productpurchaseorder.created_at',
                    'store.store_name',
                    'users.name'
                ));
            }
            //return $allincompletepurchaseorder;
            $with = array(
                'allincompletepurchaseorder'=>$allincompletepurchaseorder,
                'firstday'=>$firstday,
                'lastday'=>$lastday,
                'store'=>$store
            );
            return view('purchaseorderincomplete')->with($with);
        }
    }

    public function poreceivestep1(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='N' || session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'receivedquantity'=>'required',
            ],[
                'receivedquantity.required'=>'Please Enter Received Quantity'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                if(session::get('loggindata.loggeduserstore')=='')
                {
                    return redirect()->back()->with('poreceiverror', 'You are not a part of any store.');
                }
                else
                {
                    if(session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='Y')
                    {
                        if(session::get('loggindata.loggeduserstore.store_id')==$request->input('storeid'))
                        {
                            $getpoitem = productpurchaseorderitem::where('poitemID',$request->input('poitemid'))
                            ->with('poreceiveproduct')
                            ->first();

                            $checkquantity = $getpoitem->receivequantity + $request->input('receivedquantity');
                            if($getpoitem->poquantity >= $checkquantity)
                            {
                                if($getpoitem->poreceiveproduct['producttype']=='')
                                {
                                    $receivetype = "Single";
                                }
                                else
                                {
                                    $receivetype = "Multiple";
                                }

                                if($receivetype=='Multiple')
                                {
                                    if($getpoitem->poquantity >= $checkquantity)
                                    {
                                        $count = $request->input('receivedquantity');
                                        $docket = $request->input('docketnumber');
                                        $storeid = $request->input('storeid');
                                        $producttype = masterproducttype::where('producttypeID', $getpoitem->poreceiveproduct['producttype'])->first();
                                        $multiproductdata = ['count'=>$count, 'getpoitem'=>$getpoitem, 'docket'=>$docket, 'storeid'=>$storeid, 'producttype'=>$producttype];
                                        return view('receivemultiproduct')->with($multiproductdata);
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('poreceiverror', 'Receiveing Quantity Cannot Be Larger Than Ordered Quantity.');
                                    }
                                }
                                elseif($receivetype=='Single')
                                {
                                    $insertpprd = new productpurchasereceivedetails;
                                    $insertpprd->ponumber = $getpoitem->ponumber;
                                    $insertpprd->poitemID = $getpoitem->poitemID;
                                    $insertpprd->docketnumber = $request->input('docketnumber');
                                    $insertpprd->quantity = $request->input('receivedquantity');
                                    $insertpprd->receivedby = session::get('loggindata.loggedinuser.id');
                                    $insertpprd->save();

                                    $quantity= $getpoitem->receivequantity + $request->input('receivedquantity');

                                    if($getpoitem->poquantity == $quantity)
                                    {
                                        $poitemstatus = '2';
                                    }
                                    else
                                    {
                                        $poitemstatus = '1';
                                    }

                                    $updateitem = productpurchaseorderitem::find($getpoitem->poitemID);
                                    $updateitem->receivequantity = $quantity;
                                    $updateitem->poitemstatus = $poitemstatus;
                                    $updateitem->save();

                                    $checkproduct = productstock::where('productID', $getpoitem->productID)->where('storeID', $request->input('storeid'))->count();

                                    if($checkproduct==0)
                                    {
                                        $insertpstock = new productstock;
                                        $insertpstock->ponumber = $getpoitem->ponumber;
                                        $insertpstock->productID = $getpoitem->productID;
                                        $insertpstock->productquantity = $quantity;
                                        $insertpstock->ppexgst = $getpoitem->popurchaseprice;
                                        $insertpstock->pptax = $getpoitem->popptax;
                                        $insertpstock->ppingst = $getpoitem->poppingst;
                                        $insertpstock->storeID = $request->input('storeid');
                                        $insertpstock->save();
                                    }
                                    else
                                    {

                                        /***Average Purchase Price***/
                                        $allpo = productpurchaseorder::where('storeID', $request->input('storeid'))
                                        ->leftJoin('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                                        ->where('productpurchaseorderitem.productID', $getpoitem->productID)
                                        ->count();

                                        $allpoppexgst = productpurchaseorder::where('storeID', $request->input('storeid'))
                                        ->leftJoin('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                                        ->where('productpurchaseorderitem.productID', $getpoitem->productID)
                                        ->sum('popurchaseprice');

                                        $allpopptax = productpurchaseorder::where('storeID', $request->input('storeid'))
                                        ->leftJoin('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                                        ->where('productpurchaseorderitem.productID', $getpoitem->productID)
                                        ->sum('popptax');

                                        $allpopp = productpurchaseorder::where('storeID', $request->input('storeid'))
                                        ->leftJoin('productpurchaseorderitem', 'productpurchaseorderitem.ponumber', '=', 'productpurchaseorder.ponumber')
                                        ->where('productpurchaseorderitem.productID', $getpoitem->productID)
                                        ->sum('poppingst');

                                        $averageExPPprice = $allpoppexgst / $allpo;
                                        $averagepoptax = $allpopptax / $allpo;
                                        $averagePPprice = $allpopp / $allpo;

                                        /***Average Purchase Price***/
                                        
                                        $updatepstock = productstock::where('productID', $getpoitem->productID)
                                        ->where('storeID', $request->input('storeid'))->first();
                                        $updatepstock->productquantity = $updatepstock->productquantity + $request->input('receivedquantity');
                                        $updatepstock->ppexgst = $averageExPPprice;
                                        $updatepstock->pptax = $averagepoptax;
                                        $updatepstock->ppingst = $averagePPprice;
                                        $updatepstock->save();
                                    }

                                    $getponumber = productpurchaseorderitem::where('ponumber',$getpoitem->ponumber)->count();
                                    $getpostatus = productpurchaseorderitem::where('ponumber',$getpoitem->ponumber)->where('poitemstatus', '2')->count();

                                    if($getponumber == $getpostatus)
                                    {
                                    	$updatepoorder = productpurchaseorder::where('ponumber', $getpoitem->ponumber)
                                        ->first();
                                        $updatepoorder->poprocessstatus = '2';
                                        $updatepoorder->save();
                                    }

                                    $updatepoorderdocket = productpurchaseorder::where('ponumber', $getpoitem->ponumber)
                                        ->first();
                                        $updatepoorderdocket->docketnumber = $request->input('docketnumber');
                                        $updatepoorderdocket->save();

                                    return redirect()->back()->with('poreceivsuccess', 'Product Received.');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('poreceiverror', 'Receiveing Quantity Cannot Be Larger Than Ordered Quantity.');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('poreceiverror', 'You are not logged in same store that PO you are receving for.');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('poreceiverror', 'You are not permitted to receive purchase order.');
                    }
                }
            }
        }
    }

    public function addmultiproduct(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='N' || session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'imei'=>'required',
            ],[
                'imei.required'=>'Please Enter IMEI Number'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                if(count($request->input('imei'))==$request->input('imeitoenter'))
                {
                    if(session::get('loggindata.loggeduserstore')=='')
                    {
                        return redirect()->back()->with('multiproductaddrror', 'You are not a part of any store.');
                    }
                    else
                    {
                    if(session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='Y')
                        {
                            if(session::get('loggindata.loggeduserstore.store_id')==$request->input('storeid'))
                            {
                                $getpoitem = productpurchaseorderitem::where('poitemID',$request->input('poitemid'))
                                ->with('poreceiveproduct')
                                ->first();
                                
                                $checkquantity = $getpoitem->receivequantity + $request->input('imeitoenter');
                                if($getpoitem->poquantity >= $checkquantity)
                                {
                                    $imeicount = count($request->input('imei'));
                                    for ($i = 0; $i < $imeicount; $i++)
                                    {
                                        $po[] = [
                                            'ponumber' => $getpoitem->ponumber,
                                            'productID' => $getpoitem->productID,
                                            'productimei' => $request->input('imei')[$i],
                                            'simnumber' => $request->input('sim')[$i],
                                            'productquantity' => '1',
                                            'ppexgst'=>$getpoitem->popurchaseprice,
                                            'pptax'=>$getpoitem->popptax,
                                            'ppingst' => $getpoitem->poppingst,
                                            'storeID' => $request->input('storeid')
                                        ];
                                    }
                                    productstock::insert($po);

                                    $insertpprd = new productpurchasereceivedetails;
                                        $insertpprd->ponumber = $getpoitem->ponumber;
                                        $insertpprd->poitemID = $getpoitem->poitemID;
                                        $insertpprd->docketnumber = $request->input('docketnumber');
                                        $insertpprd->quantity = $request->input('receivedquantity');
                                        $insertpprd->receivedby = session::get('loggindata.loggedinuser.id');
                                        $insertpprd->save();

                                        $quantity= $getpoitem->receivequantity + $request->input('receivedquantity');

                                        if($getpoitem->poquantity == $quantity)
                                        {
                                            $poitemstatus = '2';
                                        }
                                        else
                                        {
                                            $poitemstatus = '1';
                                        }

                                        $updateitem = productpurchaseorderitem::find($getpoitem->poitemID);
                                        $updateitem->receivequantity = $quantity;
                                        $updateitem->poitemstatus = $poitemstatus;
                                        $updateitem->save();

                                        $getponumber = productpurchaseorderitem::where('ponumber',$getpoitem->ponumber)->count();
	                                    $getpostatus = productpurchaseorderitem::where('ponumber',$getpoitem->ponumber)->where('poitemstatus', '2')->count();

	                                    if($getponumber == $getpostatus)
	                                    {
	                                    	$updatepoorder = productpurchaseorder::where('ponumber', $getpoitem->ponumber)
	                                        ->first();
	                                        $updatepoorder->poprocessstatus = '2';
	                                        $updatepoorder->save();
	                                    }

                                        $updatepoorderocket = productpurchaseorder::where('ponumber', $getpoitem->ponumber)
                                            ->first();
                                            $updatepoorderocket->docketnumber = $request->input('docketnumber');
                                            $updatepoorderocket->save();

                                        return redirect()->route('purchaseorderreceiveitem', ['id'=>$getpoitem->ponumber])->with('poreceivsuccess', 'Product Received.');
                                }
                                else
                                {
                                    return redirect()->back()->with('poreceiverror', 'Receiveing Quantity Cannot Be Larger Than Ordered Quantity.');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('poreceiverror', 'You are not logged in same store that PO you are receving for.');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('poreceiverror', 'You are not permitted to receive purchase order.');
                        }
                        //return session::get('loggindata');
                    }
                }
                else
                {
                    return redirect()->back()->with('multiproductaddrror', 'Please Fill All IMEI Fields');
                }
                //return $getpoitem;
            }
        }
    }

    public function editpoitem(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.editpurchaseorderitem')=='N' ||session::get('loggindata.loggeduserpermission.editpurchaseorderitem')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getpoitem= productpurchaseorderitem::find($request->input('poitemid'));
            $itemtotal = $request->input('ppingst') * $request->input('quantity');
            $getpoitem->poquantity = $request->input('quantity');
            $getpoitem->popurchaseprice = $request->input('ppexgst');
            $getpoitem->popptax = $request->input('ppgst');
            $getpoitem->poppingst = $request->input('ppingst');
            $getpoitem->poitemtotal = $itemtotal;
            $getpoitem->save();

            if($getpoitem->save()) 
            {
            	return redirect()->back()->with('editpoitemsuccess', 'Item Updated Successfully');
            }
            else
            {
            	return redirect()->back()->with('editpoitemerror', 'Failed To Update Item');
            }
        }
    }

    public function deletepoitem(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.deletepurchaseorderitem')=='N' ||session::get('loggindata.loggeduserpermission.deletepurchaseorderitem')=='')
        {
            return redirect('404');
        } 
        else
        {
        	$delete_post = productpurchaseorderitem::where('poitemID',$request->input('poitemid'))->delete();

            if($delete_post) 
            {
            	return redirect()->back()->with('deletepoitemsuccess', 'Item Deleted Successfully');
            }
            else
            {
            	return redirect()->back()->with('deletepoitemerror', 'Failed To Delete Item');
            }
        }
    }

    public function editpostatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletepurchaseorder')=='N' || session::get('loggindata.loggeduserpermission.deletepurchaseorder')=='')
        {
            return redirect('404');
        }
        else
        { 
            $updatepostatus= productpurchaseorder::find($request->input('poid'));
            $updatepostatus->poprocessstatus = $request->processstatus;
            $updatepostatus->save();
            if($updatepostatus->save())
            {
               return redirect()->back()->with('statusposuccess','PO status changed successfully'); 
            }
            else
            {
                return redirect()->back()->with('statuspoerror','PO status not updated');
            }     
        }
    }

    public function ajaxupdateporeference(Request $request)
    {    
        if($request->get('ponumber'))
        {
          $ponumber = $request->get('ponumber');
          $username = $request->get('username');
          $data = productpurchaseorder::where('ponumber', $ponumber)->first();
          $data->porefrencenumber = $username;
          $data->save();
        }
    }

    public function ajaxupdateponote(Request $request)
    {    
        if($request->get('ponumber'))
        {
          $ponumber = $request->get('ponumber');
          $username = $request->get('username');
          $data = productpurchaseorder::where('ponumber', $ponumber)->first();
          $data->ponote = $username;
          $data->save();
        }
    }

    public function demoreceiveview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewdemoreceive')=='N' ||session::get('loggindata.loggeduserpermission.viewdemoreceive')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')!='')
            {

                $alldrreceiveorder=demoreceiveorder::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->whereIn('receiveorderstatus', ['0', '1', '2'])
                ->join('store', 'store.store_id','=','storeID')
                ->join('users', 'users.id','=','userID')
                ->with('demoitem')
                ->with('demosupplier')
                ->get();
                //return $alldrreceiveorder;
            }
            else
            {
                $alldrreceiveorder= demoreceiveorder::whereIn('receiveorderstatus', ['0', '1', '2'])
                ->with('demoitem')
                ->with('demosupplier')
                ->join('store', 'store.store_id','=','storeID')
                ->join('users', 'users.id','=','userID')
                ->get();
            }
            //return $allincompletepurchaseorder;
            return view('demo-receive')->with('alldrreceiveorder', $alldrreceiveorder);
        }
    }

    public function adddemoreceive(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.adddemoreceive')=='N' || session::get('loggindata.loggeduserpermission.adddemoreceive')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'store'=>'required'
            ],[
                'store.required'=>'Store is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                /*****Demo Invoice ID CREATION*****/
                $datefororderid = Carbon::now()->toDateTimeString();
                $orderidstoreid = store::where('store_id', session::get('loggindata.loggeduserstore.store_id'))->first();
                $orderiduserid = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

                $orderidtobe = $datefororderid.$orderidstoreid->store_id.$orderiduserid->id;
                
                $demoinvoiceid = preg_replace("/[^A-Za-z0-9]/","",$orderidtobe);
                /*****Demo Invoice ID CREATION*****/

                $insertdemoorder = new demoreceiveorder;
                $insertdemoorder->storeID = $request->input('store');
                $insertdemoorder->receiveInvoiceID = $demoinvoiceid;
                $insertdemoorder->receiveorderstatus = '0';
                $insertdemoorder->userID = session::get('loggindata.loggedinuser.id');
                $insertdemoorder->save();

                if($insertdemoorder->save())
                {
                    return redirect()->route('demoordercreation',  ['id' => $demoinvoiceid]);
                }
                else
                {
                    return redirect()->back()->with('error', 'Something went wrong. Please re-try again.');
                }
            }
        }
    }

    public function demoordercreationview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.adddemoreceive')=='N' ||session::get('loggindata.loggeduserpermission.adddemoreceive')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getpo = demoreceiveorder::leftJoin('demoreceiveorderitem', 'demoreceiveorderitem.receiveInvoiceID', '=', 'demoreceive.receiveInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'demoreceiveorderitem.productID')
            ->leftJoin('store', 'store.store_id', '=', 'demoreceive.storeID')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'demoreceive.supplierID')
            ->where('demoreceive.receiveInvoiceID', $id)
            ->get();

            //return $getpo;
            
            $allproducts = product::with('productbrand')
            ->with('productcolour')
            ->with('productmodel')
            ->with('productsupplier')
            ->get();

            $allbrands= masterbrand::where('brandstatus', '1')->get();
            $allcolours= mastercolour::where('colourstatus', '1')->get();
            $allmodels= mastermodel::where('modelstatus', '1')->get();
            $allstockgroup= masterstockgroup::where('stockgroupstatus', '1')->get();
            $allsuppliers= mastersupplier::where('supplierstatus', '1')->get();
            $alltaxs= mastertax::where('taxstatus', '1')->get();
            $allcategories= mastercategory::with('subcategory')->where('categorystatus', '1')->get();
            $allproducttype = masterproducttype::where('producttypestatus', '1')->get();
            
            $podata = ['id'=>$id,'getpo'=> $getpo, 'allproducts'=>$allproducts, 'allbrands'=>$allbrands, 'allcolours'=>$allcolours, 'allmodels'=>$allmodels, 'allstockgroup'=>$allstockgroup, 'allsuppliers'=>$allsuppliers, 'alltaxs'=>$alltaxs, 'allcategories'=>$allcategories, 'allproducttype'=>$allproducttype];
            return view('demo-ordercreate')->with('podata', $podata);
        }
    }

    public function ajaxupdatedrreference(Request $request)
    {    
        if($request->get('drreceive'))
        {
          $drreceive = $request->get('drreceive');
          $username = $request->get('username');
          $data = demoreceiveorder::where('receiveInvoiceID', $drreceive)->first();
          $data->referenceNumber = $username;
          $data->save();
        }
    }

    public function ajaxupdatedrnote(Request $request)
    {    
        if($request->get('drreceive'))
        {
          $drreceive = $request->get('drreceive');
          $username = $request->get('username');
          $data = demoreceiveorder::where('receiveInvoiceID', $drreceive)->first();
          $data->note = $username;
          $data->save();
        }
    }

    public function adddritem(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.adddemoreceive')=='N' || session::get('loggindata.loggeduserpermission.adddemoreceive')=='')
        {
            return redirect('404');
        }
        else
        { 
            $drnumber = $request->input('drreceive');
            $quantity = $request->input('quantity');
            $barcode = $request->input('barcode');
            $productid = $request->input('productid');

            if($productid != '')
            {
                $getproduct = product::where('productID', $productid)->first();

                $checkpoitem = demoreceiveorderitem::where('receiveInvoiceID', $drnumber)->where('productID', $getproduct->productID)->count();

                if($checkpoitem == 0)
                {
                    $insertpoitem = new demoreceiveorderitem;
                    $insertpoitem->receiveInvoiceID= $drnumber;
                    $insertpoitem->productID= $getproduct->productID;
                    $insertpoitem->orderitemquantity= '1';
                    $insertpoitem->drppexgst= $getproduct->ppexgst;
                    $insertpoitem->drpptax= $getproduct->ppgst;
                    $insertpoitem->drppingst= $getproduct->ppingst;
                    $insertpoitem->dritemtotal= $getproduct->ppingst;
                    $insertpoitem->dritemstatus= '0';
                    $insertpoitem->save();

                    $updateposupplier= demoreceiveorder::where('receiveInvoiceID', $drnumber)->first();
                    $updateposupplier->supplierID = $request->input('supplier');
                    $updateposupplier->referenceNumber = $request->input('reference');
                    $updateposupplier->note = $request->input('note');
                    $updateposupplier->save();

                    if($insertpoitem->save())
                    {
                        return redirect()->back()->with('success', 'Product added to purchase order');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to add product to purchase order');
                    }
                }
                else
                {
                    $updatepoitem = demoreceiveorderitem::where('receiveInvoiceID', $drnumber)->where('productID', $getproduct->productID)->first();
                    $updatedquantity = '1' + $updatepoitem->orderitemquantity;
                    $itemtotal = $updatepoitem->drppingst * $updatedquantity;
                    $updatepoitem->orderitemquantity = $updatedquantity;
                    $updatepoitem->dritemtotal = $itemtotal;
                    $updatepoitem->save();
                    if($updatepoitem->save())
                    {
                        return redirect()->back()->with('success', 'Product added to purchase order');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to add product to purchase order');
                    }
                }
            }
            else if($barcode != '')
            {
                $checkproduct = product::where('barcode', $barcode)->count();

                if($checkproduct > 0)
                {
                    $getproduct = product::where('barcode', $barcode)->first();

                    $checkpoitem = demoreceiveorderitem::where('receiveInvoiceID', $drnumber)->where('productID', $getproduct->productID)->count();

                    if($checkpoitem == 0)
                    {
                        $insertpoitem = new demoreceiveorderitem;
                        $insertpoitem->receiveInvoiceID= $drnumber;
                        $insertpoitem->productID= $getproduct->productID;
                        $insertpoitem->orderitemquantity= $quantity;
                        $insertpoitem->drppexgst= $getproduct->ppexgst;
                        $insertpoitem->drpptax= $getproduct->ppgst;
                        $insertpoitem->drppingst= $getproduct->ppingst;
                        $insertpoitem->dritemtotal= $getproduct->ppingst * $quantity;
                        $insertpoitem->dritemstatus= '0';
                        $insertpoitem->save();

                        $updateposupplier= demoreceiveorder::where('receiveInvoiceID', $drnumber)->first();
                        $updateposupplier->supplierID = $request->input('supplier');
                        $updateposupplier->referenceNumber = $request->input('reference');
                        $updateposupplier->note = $request->input('note');
                        $updateposupplier->save();

                        if($insertpoitem->save())
                        {
                            return redirect()->back()->with('success', 'Product added to purchase order');
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Failed to add product to purchase order');
                        }
                    }
                    else
                    {
                        $updatepoitem = demoreceiveorderitem::where('receiveInvoiceID', $drnumber)->where('productID', $getproduct->productID)->first();
                        $updatedquantity = $quantity + $updatepoitem->orderitemquantity;
                        $itemtotal = $updatepoitem->drppingst * $updatedquantity;
                        $updatepoitem->orderitemquantity = $updatedquantity;
                        $updatepoitem->dritemtotal = $itemtotal;
                        $updatepoitem->save();
                        if($updatepoitem->save())
                        {
                            return redirect()->back()->with('success', 'Product added to purchase order');
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Failed to add product to purchase order');
                        }
                    }
                }
                else
                {
                    return redirect()->back()->with('poitemerror', 'Barcode not found.');
                }

            }
            else
            {
                return redirect()->back()->with('poitemerror', 'Barcode could not be empty.');
            }
        }
    }

    public function finaldrsubmission(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.adddemoreceive')=='N' ||session::get('loggindata.loggeduserpermission.adddemoreceive')=='')
        {
            return redirect('404');
        } 
        else
        {
            $drnumber=$request->input('drnumber');
            $drstatus=$request->input('drstatus');

            $updatefindpo = demoreceiveorder::where('receiveInvoiceID', $drnumber)->first();
            $updatefindpo->receiveorderstatus = $drstatus;
            $updatefindpo->save();

            return redirect('demoreceive');
        }
    }

    public function demoreceiveitemview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.viewdemoreceive')=='N' ||session::get('loggindata.loggeduserpermission.viewdemoreceive')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')!='')
            {
                $alldrorderitem=demoreceiveorder::where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->leftJoin('demoreceiveorderitem', 'demoreceiveorderitem.receiveInvoiceID', '=', 'demoreceive.receiveInvoiceID')
                ->leftJoin('products', 'products.productID', '=', 'demoreceiveorderitem.productID')
                ->where('demoreceive.receiveInvoiceID', $id)
                ->get();
            }
            else
            {
                $alldrorderitem= demoreceiveorder::leftJoin('demoreceiveorderitem', 'demoreceiveorderitem.receiveInvoiceID', '=', 'demoreceive.receiveInvoiceID')
                ->leftJoin('products', 'products.productID', '=', 'demoreceiveorderitem.productID')
                ->where('demoreceive.receiveInvoiceID', $id)
                ->get();
            }

            //return $alldrorderitem;

            return view('demoreceiveorderitem')->with('alldrorderitem', $alldrorderitem);
        }
    }

    public function drreceivestep1(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.viewdemoreceive')=='N' || session::get('loggindata.loggeduserpermission.viewdemoreceive')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'receivedquantity'=>'required',
            ],[
                'receivedquantity.required'=>'Please Enter Received Quantity'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                if(session::get('loggindata.loggeduserstore')=='')
                {
                    return redirect()->back()->with('poreceiverror', 'You are not a part of any store.');
                }
                else
                {
                    if(session::get('loggindata.loggeduserpermission.receivepurchaseorder')=='Y')
                    {
                        if(session::get('loggindata.loggeduserstore.store_id')==$request->input('storeid'))
                        {
                            $getpoitem = demoreceiveorderitem::where('drorderitemID',$request->input('drorderitemID'))
                            ->with('drreceiveproduct')
                            ->first();

                            //return $getpoitem;

                            $checkquantity = $getpoitem->receivequantity + $request->input('receivedquantity');
                            if($getpoitem->orderitemquantity >= $checkquantity)
                            {
                                if($getpoitem->drreceiveproduct['producttype']=='')
                                {
                                    $receivetype = "Single";
                                }
                                else
                                {
                                    $receivetype = "Multiple";
                                }

                                if($receivetype=='Multiple')
                                {
                                    if($getpoitem->orderitemquantity >= $checkquantity)
                                    {
                                        $count = $request->input('receivedquantity');
                                        $docket = $request->input('docketnumber');
                                        $storeid = $request->input('storeid');
                                        $producttype = masterproducttype::where('producttypeID', $getpoitem->drreceiveproduct['producttype'])->first();
                                        $multiproductdata = ['count'=>$count, 'getpoitem'=>$getpoitem, 'docket'=>$docket, 'storeid'=>$storeid, 'producttype'=>$producttype];
                                        return view('demoreceivemultiple')->with($multiproductdata);
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('poreceiverror', 'Receiveing Quantity Cannot Be Larger Than Ordered Quantity.');
                                    }
                                }
                                elseif($receivetype=='Single')
                                {
                                    $insertpprd = new demoreceivedetail;
                                    $insertpprd->receiveInvoiceID = $getpoitem->receiveInvoiceID;
                                    $insertpprd->drorderitemID = $getpoitem->drorderitemID;
                                    $insertpprd->docketnumber = $request->input('docketnumber');
                                    $insertpprd->quantity = $request->input('receivedquantity');
                                    $insertpprd->receivedby = session::get('loggindata.loggedinuser.id');
                                    $insertpprd->save();

                                    $quantity= $getpoitem->receiveitemquantity + $request->input('receivedquantity');

                                    if($getpoitem->orderitemquantity == $quantity)
                                    {
                                        $dritemstatus = '2';
                                    }
                                    else
                                    {
                                        $dritemstatus = '1';
                                    }

                                    $updateitem = demoreceiveorderitem::find($getpoitem->drorderitemID);
                                    $updateitem->receiveitemquantity = $quantity;
                                    $updateitem->dritemstatus = $dritemstatus;
                                    $updateitem->save();

                                    $checkproduct = demostock::where('productID', $getpoitem->productID)->where('storeID', $request->input('storeid'))->count();

                                    if($checkproduct==0)
                                    {
                                        $insertpstock = new demostock;
                                        $insertpstock->receiveInvoiceID = $getpoitem->receiveInvoiceID;
                                        $insertpstock->productID = $getpoitem->productID;
                                        $insertpstock->productquantity = $quantity;
                                        $insertpstock->ppexgst = $getpoitem->drppexgst;
                                        $insertpstock->pptax = $getpoitem->drpptax;
                                        $insertpstock->ppingst = $getpoitem->drppingst;
                                        $insertpstock->storeID = $request->input('storeid');
                                        $insertpstock->save();
                                    }
                                    else
                                    {
                                        
                                        $updatepstock = demostock::where('productID', $getpoitem->productID)
                                        ->where('storeID', $request->input('storeid'))->first();
                                        $updatepstock->productquantity = $updatepstock->productquantity + $request->input('receivedquantity');
                                        $updatepstock->save();
                                    }

                                    $getponumber = demoreceiveorderitem::where('receiveInvoiceID',$getpoitem->receiveInvoiceID)->count();
                                    $getpostatus = demoreceiveorderitem::where('receiveInvoiceID',$getpoitem->receiveInvoiceID)->where('dritemstatus', '2')->count();

                                    if($getponumber == $getpostatus)
                                    {
                                        $updatepoorder = demoreceiveorder::where('receiveInvoiceID', $getpoitem->receiveInvoiceID)
                                        ->first();
                                        $updatepoorder->receiveorderstatus = '2';
                                        $updatepoorder->save();
                                    }

                                    return redirect()->back()->with('success', 'Product Received.');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error', 'Receiveing Quantity Cannot Be Larger Than Ordered Quantity.');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'You are not logged in same store that PO you are receving for.');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'You are not permitted to receive purchase order.');
                    }
                }
            }
        }
    }

    public function addmultidemo(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.adddemoreceive')=='N' || session::get('loggindata.loggeduserpermission.adddemoreceive')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'imei'=>'required',
            ],[
                'imei.required'=>'Please Enter IMEI Number'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                if(count($request->input('imei'))==$request->input('imeitoenter'))
                {
                    if(session::get('loggindata.loggeduserstore')=='')
                    {
                        return redirect()->back()->with('multiproductaddrror', 'You are not a part of any store.');
                    }
                    else
                    {
                    if(session::get('loggindata.loggeduserpermission.viewdemoreceive')=='Y')
                        {
                            if(session::get('loggindata.loggeduserstore.store_id')==$request->input('storeid'))
                            {
                                $getpoitem = demoreceiveorderitem::where('drorderitemID',$request->input('drorderitemID'))
                                ->with('drreceiveproduct')
                                ->first();
                                
                                $checkquantity = $getpoitem->receiveitemquantity + $request->input('imeitoenter');
                                if($getpoitem->orderitemquantity >= $checkquantity)
                                {
                                    $insertpprd = new demoreceivedetail;
                                        $insertpprd->receiveInvoiceID = $getpoitem->receiveInvoiceID;
                                        $insertpprd->drorderitemID = $getpoitem->drorderitemID;
                                        $insertpprd->docketnumber = $request->input('docketnumber');
                                        $insertpprd->quantity = $request->input('receivedquantity');
                                        $insertpprd->receivedby = session::get('loggindata.loggedinuser.id');
                                        $insertpprd->save();

                                        $quantity= $getpoitem->receiveitemquantity + $request->input('receivedquantity');

                                        if($getpoitem->orderitemquantity == $quantity)
                                        {
                                            $dritemstatus = '2';
                                        }
                                        else
                                        {
                                            $dritemstatus = '1';
                                        }

                                        $updateitem = demoreceiveorderitem::find($getpoitem->drorderitemID);
                                        $updateitem->receiveitemquantity = $quantity;
                                        $updateitem->dritemstatus = $dritemstatus;
                                        $updateitem->save();

                                        $imeicount = count($request->input('imei'));
                                        for ($i = 0; $i < $imeicount; $i++)
                                        {
                                            $po[] = [
                                                    'receiveInvoiceID' => $getpoitem->receiveInvoiceID,
                                                    'productID' => $getpoitem->productID,
                                                    'productimei' => $request->input('imei')[$i],
                                                    'productquantity' => '1',
                                                    'ppexgst'=>$getpoitem->drppexgst,
                                                    'pptax'=>$getpoitem->drpptax,
                                                    'ppingst' => $getpoitem->drppingst,
                                                    'storeID' => $request->input('storeid')
                                                ];
                                        }
                                        demostock::insert($po);

                                        $getponumber = demoreceiveorderitem::where('receiveInvoiceID',$getpoitem->receiveInvoiceID)->count();
                                        $getpostatus = demoreceiveorderitem::where('receiveInvoiceID',$getpoitem->receiveInvoiceID)->where('dritemstatus', '2')->count();

                                        if($getponumber == $getpostatus)
                                        {
                                            $updatepoorder = demoreceiveorder::where('receiveInvoiceID', $getpoitem->receiveInvoiceID)
                                            ->first();
                                            $updatepoorder->receiveorderstatus = '2';
                                            $updatepoorder->save();
                                        }
                                        return redirect()->route('demoreceiveitem', ['id'=>$getpoitem->receiveInvoiceID])->with('poreceivsuccess', 'Product Received.');
                                }
                                else
                                {
                                    return redirect()->back()->with('poreceiverror', 'Receiveing Quantity Cannot Be Larger Than Ordered Quantity.');
                                }
                            }
                            else
                            {

                                return redirect()->back()->with('poreceiverror', 'You are not logged in same store that PO you are receving for.');
                            }
                        }
                        else
                        {

                            return redirect()->back()->with('poreceiverror', 'You are not permitted to receive purchase order.');
                        }
                        //return session::get('loggindata');
                    }
                }
                else
                {

                    return redirect()->back()->with('multiproductaddrror', 'Please Fill All IMEI Fields');
                }
                //return $getpoitem;
            }
        }
    }

    public function ajaxcheckimei(Request $request)
    { 

        if($request->get('imei'))
        {
          $imei = $request->get('imei');
          $data = productstock::where('productimei', $imei)->count();
          
          if($data > 0)
          {
           echo 'not_unique';
          }
          else
          {
           echo 'unique';
          }
        }
    }

    public function ajaxchecksim(Request $request)
    { 

        if($request->get('imei'))
        {
          $imei = $request->get('imei');
          $data = productstock::where('simnumber', $imei)->count();
          
          if($data > 0)
          {
           echo 'not_unique';
          }
          else
          {
           echo 'unique';
          }
        }
    }

    public function deletedemopoitem(Request $request)
    {   
        $deletedemoitem = demoreceiveorderitem::where('drorderitemID',$request->input('demoitemid'))->delete();

        if($deletedemoitem) 
        {
            return redirect()->back()->with('success', 'Item Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Failed To Delete Item');
        }
    }

    public function editdemoitem(Request $request)
    {   
        $getpoitem= demoreceiveorderitem::find($request->input('demoitemid'));
        $itemtotal = $request->input('ppingst') * $request->input('quantity');
        $getpoitem->orderitemquantity = $request->input('quantity');
        $getpoitem->drppexgst = $request->input('ppexgst');
        $getpoitem->drpptax = $request->input('ppgst');
        $getpoitem->drppingst = $request->input('ppingst');
        $getpoitem->dritemtotal = $itemtotal;
        $getpoitem->save();

        if($getpoitem->save()) 
        {
            return redirect()->back()->with('success', 'Item Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Failed To Update Item');
        }
    }

    public function poaddbyproductid(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addpurchaseorder')=='N' || session::get('loggindata.loggeduserpermission.addpurchaseorder')=='')
        {
            return redirect('404');
        }
        else
        { 
            $ponumber = $request->input('ponumber');
            $quantity = $request->input('quantity');
            $productid = $request->input('productid');
            $supplierid = $request->input('supplier');

            //return $ponumber;

            if($productid != '')
            {
                $getproduct = product::where('productID', $productid)->first();

                if($getproduct->productstatus == 1)
                {
                    $checkpoitem = productpurchaseorderitem::where('ponumber', $ponumber)->where('productID', $getproduct->productID)->count();

                    if($checkpoitem == 0)
                    {
                        $insertpoitem = new productpurchaseorderitem;
                        $insertpoitem->ponumber= $ponumber;
                        $insertpoitem->productID= $getproduct->productID;
                        $insertpoitem->poquantity= '1';
                        $insertpoitem->popurchaseprice= $getproduct->ppexgst;
                        $insertpoitem->popptax= $getproduct->ppgst;
                        $insertpoitem->poppingst= $getproduct->ppingst;
                        $insertpoitem->poitemtotal= $getproduct->ppingst;
                        $insertpoitem->poitemstatus= '0';
                        $insertpoitem->save();

                        $updateposupplier= productpurchaseorder::where('ponumber', $ponumber)->first();

                        $updateposupplier->supplierID = $supplierid;
                        $updateposupplier->porefrencenumber = $request->input('reference');
                        $updateposupplier->ponote = $request->input('note');
                        $updateposupplier->save();

                        if($insertpoitem->save())
                        {
                            return redirect()->back()->with('poitemsuccess', 'Product added to purchase order');
                        }
                        else
                        {
                            return redirect()->back()->with('poitemerror', 'Failed to add product to purchase order');
                        }
                    }
                    else
                    {
                        $updatepoitem = productpurchaseorderitem::where('ponumber', $ponumber)->where('productID', $getproduct->productID)->first();
                        $updatedquantity = '1' + $updatepoitem->poquantity;
                        $itemtotal = $updatepoitem->poppingst * $updatedquantity;
                        $updatepoitem->poquantity = $updatedquantity;
                        $updatepoitem->poitemtotal = $itemtotal;
                        $updatepoitem->save();
                        if($updatepoitem->save())
                        {
                            return redirect()->back()->with('poitemsuccess', 'Product added to purchase order');
                        }
                        else
                        {
                            return redirect()->back()->with('poitemerror', 'Failed to add product to purchase order');
                        }
                    }
                }
                else
                {
                    return redirect()->back()->with('poitemerror', 'Product is in in-active states');
                }
            }
            else
            {
                return redirect()->back()->with('poitemerror', 'Product Id cannot be null');
            }
        }
    }
}
