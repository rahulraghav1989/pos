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
use App\orderdetail;
use App\customer;
use App\product;
use App\mastercategory;
use App\mastersubcategory;
use App\masterprreceivetype;
use App\productstock;
use App\productstockgroup;
use App\masterstockgroup;
use App\orderitem;
use App\masterplantype;
use App\masterplanpropositiontype;
use App\masterplancategory;
use App\masterplanterm;
use App\masterplanhandsetterm;
use App\plan;
use App\masterproducttype;
use App\paymentoptions;
use App\orderpayments;

class newsalecontroller extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function startnewsale()
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
        	if(session::get('loggindata.loggeduserstore')=='')
        	{
        		return redirect('newsalestorechange');
        	}
        	else
        	{
                /*****ORDER ID CREATION*****/
                $datefororderid = Carbon::now()->toDateTimeString();
                $orderidstoreid = store::where('store_id', session::get('loggindata.loggeduserstore.store_id'))->first();
                $orderiduserid = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

                $orderidtobe = $datefororderid.$orderidstoreid->store_id.$orderiduserid->id;
                
                $orderid = preg_replace("/[^A-Za-z0-9]/","",$orderidtobe);
                /*****ORDER ID CREATION*****/

                $generatesale = orderdetail::where('orderID', $orderid)->count();

                if($generatesale == 0)
                {
                    $newsale = new orderdetail;
                    $newsale->orderID = $orderid;
                    $newsale->orderstatus = '0';
                    $newsale->storeID = session::get('loggindata.loggeduserstore.store_id');
                    $newsale->userID = session::get('loggindata.loggedinuser.id');
                    $newsale->orderMonth = date('m');
                    $newsale->orderYear = date('Y');
                    $newsale->orderDate = date('Y-m-d');
                    $newsale->save();

                    return redirect()->route('newsale', ['id'=> $newsale->orderID]);
                }
                else
                {

                }
        	}
        }
    }

    public function newsalestorechangeview()
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserpermission.changestore')=='N' ||session::get('loggindata.loggeduserpermission.changestore')=='')
            {
                return redirect('404');
            }
            else
            {
                if(session::get('loggindata.loggeduserstore')=='')
                {
                    return view('newsalestorechange');
                }
                else
                {
                    return redirect()->route('startnewsale');
                }
            }
        }
    }

    public function savecustomer(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'id'=>'required',
            'orderid'=>'required'
            ],[
                'id.required'=>'Customer ID is required',
                'orderid.required'=>'Order ID is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $orderid = $request->input('orderid');
                $customerid = $request->input('id');

                $findorder = orderdetail::where('orderID', $orderid)->first();
                $findorder->customerID = $customerid;
                $findorder->save();

                if($findorder->save())
                {
                    return 'success';
                }
                else
                {
                    return 'error';   
                }
            }
        }
    }

    public function newsaleview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
                $getorderdetails = orderdetail::where('orderID', $id)
                ->where('orderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->where('orderdetail.userID', session::get('loggindata.loggedinuser.id'))
                ->first();

                $getorderitems = orderitem::where('orderID', $id)
                ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
                ->with('productstock')
                ->leftJoin('plan', 'plan.planID', '=', 'orderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->leftJoin('masterplanpropositiontype', 'masterplanpropositiontype.planpropositionID', '=', 'plan.planpropositionID')
                ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
                ->get(array('orderitem.orderitemID', 'orderitem.orderID', 'orderitem.productID', 'orderitem.stockID', 'orderitem.planID', 'orderitem.planOrderID', 'orderitem.plandetails', 'orderitem.discountedType', 'orderitem.discount', 'orderitem.quantity', 'orderitem.ppingst', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.subTotal', 'products.stockcode', 'products.productname', 'products.barcode', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'masterplancategory.pcname', 'masterplanpropositiontype.planpropositionname'));

                //return $getorderitems;

                $getorderitemssum = orderitem::where('orderID', $id)->sum('subTotal');

                $getorderdiscountsum = orderitem::where('orderID', $id)->sum('discountedAmount');

                $orderpaidamount = orderpayments::where('orderID', $id)->sum('paidAmount');

                /*$getorderitems_plans = orderitem::where('orderID', $id)
                ->join('plan', 'plan.planID', '=', 'orderitem.planID')
                ->join('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
                ->join('masterplanpropositiontype', 'masterplanpropositiontype.planpropositionID', '=', 'plan.planpropositionID')
                ->join('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'orderitem.stockgroup')
                ->get();*/

                //return $getorderitems;

                //return $getorderitems_plans;

                if($getorderdetails == '')
                {
                    return redirect()->back()->with('error','Inappropriate Behaviuor Deducted. You will be blocked next time.');
                }
                else
                {
                    $getcustomer = customer::get();
                    $getstore = store::where('store_id', $getorderdetails->storeID)->first();
                    $getuser = loggeduser::where('id', $getorderdetails->userID)->first();
                    $getsavedcustomer = customer::where('customerID', $getorderdetails->customerID)->first();

                    $plantype = masterplantype::where('plantypestatus', '1')->get();
                    $planprositiontype = masterplanpropositiontype::where('planpropositionstatus', '1')->get();
                    $plancategory = masterplancategory::where('pcstatus', '1')->get();
                    $planterm = masterplanterm::where('plantermstatus', '1')->get();
                    $planhandsetterm = masterplanhandsetterm::where('planhandsettermstatus', '1')->get();
                    $planstockgroup = masterstockgroup::where('stockgroupstatus', '1')->get();
                    $planamount = plan::groupBy('ppingst')->get();
                    $productcategory = mastercategory::where('categorystatus', '1')
                    ->leftJoin('products', 'products.categories', '=', 'mastercategory.categoryID')
                    ->leftJoin('productstock', 'productstock.productID', '=', 'products.productID')
                    ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                    ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                    ->where('masterstockgroup.stockpriceeffect', 'sellingprice')
                    ->where('masterstockgroup.productqtyeffect', '1')
                    ->whereNull('products.producttype')
                    ->where('productstock.storeID', session::get('loggindata.loggeduserstore.store_id'))
                    ->get(array('mastercategory.categoryID', 'mastercategory.categoryname', 'mastercategory.categorystatus', 'products.productID', 'products.productname', 'products.barcode', 'products.stockcode', 'productstock.ppexgst', 'productstock.pptax', 'productstock.ppingst', 'products.spexgst', 'products.spgst', 'products.spingst', 'productstock.psID', 'productstock.productquantity', 'productstock.storeID'));

                    $recharge = product::whereNull('producttype')
                    ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
                    ->where('mastercategory.categoryname', 'Recharge')
                    ->orWhere('mastercategory.categoryname', 'recharge')
                    ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                    ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                    ->where('masterstockgroup.stockpriceeffect', '0.00')
                    ->where('masterstockgroup.productqtyeffect', '0')
                    ->get(array('mastercategory.categoryID', 'mastercategory.categoryname', 'mastercategory.categorystatus', 'products.productID', 'products.productname', 'products.barcode', 'products.stockcode', 'products.ppexgst', 'products.ppgst', 'products.ppingst', 'products.spexgst', 'products.spgst', 'products.spingst'));

                    /*$recharge = mastercategory::where('categorystatus', '1')
                    ->where('categoryname', 'Recharge')
                    ->orWhere('categoryname', 'recharge')
                    ->leftJoin('products', 'products.categories', '=', 'mastercategory.categoryID')
                    ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                    ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                    ->where('masterstockgroup.stockpriceeffect', '0.00')
                    ->where('masterstockgroup.productqtyeffect', '0')
                    ->whereNull('products.producttype')
                    ->get(array('mastercategory.categoryID', 'mastercategory.categoryname', 'mastercategory.categorystatus', 'products.productID', 'products.productname', 'products.barcode', 'products.stockcode', 'products.ppexgst', 'products.ppgst', 'products.ppingst', 'products.spexgst', 'products.spgst', 'products.spingst'));*/

                    //return $recharge;

                    $planinsurance = plan::where('planpropositionID', '5')
                    ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'plan.planstockgroup')
                    ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
                    ->leftJoin('masterplantype', 'masterplantype.plantypeID', '=', 'plan.plantypeID')
                    ->get();

                    $producttype = masterproducttype::where('producttypestatus', '1')->get();
                    $paymentoptions = paymentoptions::where('paymentstatus', '1')
                    ->whereIn('paymenttype', ['Offline', 'Online'])
                    ->get();
                    $paymentoptionaccount = paymentoptions::where('paymentstatus', '1')
                    ->whereIn('paymenttype', ['Account'])
                    ->get();

                    //return $productcategory;

                    $app_plans = plan::where('planpropositionID', '4')
                    ->whereIn('planstockgroup', [2,4])
                    ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'plan.planstockgroup')
                    ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
                    ->leftJoin('masterplantype', 'masterplantype.plantypeID', '=', 'plan.plantypeID')
                    ->get();

                    /*return $app_plans;*/

                    $invoicedata = ['getorderdetails'=>$getorderdetails, 'getcustomer'=>$getcustomer, 'getstore'=>$getstore, 'getuser'=>$getuser, 'getorderitems'=>$getorderitems, 'getsavedcustomer'=>$getsavedcustomer, 'plantype'=>$plantype, 'planprositiontype'=>$planprositiontype, 'plancategory'=>$plancategory, 'planterm'=>$planterm, 'planhandsetterm'=>$planhandsetterm, 'planstockgroup'=>$planstockgroup, /*'getorderitems_plans'=>$getorderitems_plans,*/ 'productcategory'=>$productcategory, 'producttype'=>$producttype, 'paymentoptions'=>$paymentoptions, 'getorderitemssum'=>$getorderitemssum, 'orderpaidamount'=>$orderpaidamount, 'planinsurance'=>$planinsurance, 'paymentoptionaccount'=>$paymentoptionaccount, 'getorderdiscountsum'=>$getorderdiscountsum, 'recharge'=>$recharge, 'planamount'=>$planamount, 'app_plans'=>$app_plans];
                    return view('newsale')->with('invoicedata', $invoicedata);
                }
            }
        }
    }

    public function cancelorder(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'orderid'=>'required'
            ],[
                'orderid.required'=>'Order ID is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $orderid = $request->input('orderid');

                $findorderitems = orderitem::where('orderID', $orderid)->count();
                //return $findorderitems;
                if($findorderitems > 0)
                {
                    $opendeletemodel = '1';
                    $errormsg = "You cant cancel this order, You have items in this order. Please remove all items, than cancel the order";
                    $deletedata = ['opendeletemodel'=>$opendeletemodel, 'errormsg'=>$errormsg];

                    return redirect()->back()->with('deletedata', $deletedata);
                }
                else
                {
                    $deleteorder = orderdetail::where('orderID', $orderid)->where('orderstatus', '0')->delete();
                    
                    return redirect('salehistory');
                }
            }
        }
    }

    public function customeradd(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'customertype'=>'required',
                'firstname'=>'required',
                'lastname'=>'required',
                'mobilenumber'=>'required',
                'invoiceid'=>'required'
                ],[
                    'customertype.required'=>'Customer Type is required',
                    'firstname.required'=>'Customer First Name is required',
                    'lastname.required'=>'Customer Last Name is required',
                    'mobilenumber.required'=>'Customer Mobile Number is required',
                    'invoiceid.required'=>'Failed to fetch invoice id'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    $checkcustomer = customer::where('customermobilenumber', $request->input('mobilenumber'))->count();

                    if($checkcustomer == 0)
                    {
                        if(empty($request->input('onaccount')))
                        {
                            $onaccount = 0;
                        }
                        else
                        {
                            $onaccount = 1;
                        }

                        $insertcustomer= new customer;
                        $insertcustomer->customertype= $request->input('customertype');
                        $insertcustomer->customertitle= $request->input('title');
                        $insertcustomer->customerfirstname= $request->input('firstname');
                        $insertcustomer->customerlastname= $request->input('lastname');
                        $insertcustomer->customermobilenumber= $request->input('mobilenumber');
                        $insertcustomer->customerhomenumber= $request->input('homenumber');
                        $insertcustomer->customeraltcontactnumber= $request->input('altcontactnumber');
                        $insertcustomer->customerdob= $request->input('dob');
                        $insertcustomer->customeremail= $request->input('email');
                        $insertcustomer->customerbusinessname= $request->input('businessname');
                        $insertcustomer->customeracnabn= $request->input('acnabn');
                        $insertcustomer->customerbusinessemail= $request->input('businessemail');
                        $insertcustomer->customerbusinesswebsite= $request->input('businesswebsite');
                        $insertcustomer->customeraddress= $request->input('address');
                        $insertcustomer->customerpostcode= $request->input('postcode');
                        $insertcustomer->customersuburbname= $request->input('suburbname');
                        $insertcustomer->customerstate= $request->input('state');
                        $insertcustomer->customernote= $request->input('note');
                        $insertcustomer->onAccountPayment= $onaccount;
                        $insertcustomer->userID= session::get('loggindata.loggedinuser.id');
                        $insertcustomer->storeID= session::get('loggindata.loggeduserstore.store_id');
                        
                        $insertcustomer->save();

                        if($insertcustomer->save())
                        {
                           $updatecustomerTOorder = orderdetail::where('orderID', $request->input('invoiceid'))->first();
                           $updatecustomerTOorder->customerID = $insertcustomer->customerID;
                           $updatecustomerTOorder->save(); 
                           return redirect()->back()->with('newsalesucess','Customer Added Successfully'); 
                        }
                        else
                        {
                            return redirect()->back()->with('newsaleerror','Fail to add customer');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('newsaleerror','Mobile number already exists. Couldnot add customer.');
                    }
                } 
            }
        }
    }

    public function addbybarcode(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'quantity'=>'required',
                'barcode'=>'required',
                'orderid'=>'required'
                ],[
                    'quantity.required'=>'Quantity is required',
                    'barcode.required'=>'Barcode is required',
                    'orderid.required'=>'Fail to get Invoice ID'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    $quantity = $request->input('quantity');
                    $barcode = $request->input('barcode');
                    $orderid = $request->input('orderid');

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
                                    ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                                    ->first();

                                    if($getproductstock != "")
                                    {
                                        if($quantity <= $getproductstock->productquantity)
                                        {
                                            $orderdetail = orderdetail::where('orderID', $orderid)
                                            ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                                            ->count();

                                            if($orderdetail > 0)
                                            {
                                                $orderitem = orderitem::where('orderID', $orderid)
                                                ->where('productID', $getproduct->productID)
                                                ->where('stockID', $getproductstock->psID)
                                                ->first();

                                                if($orderitem == "")
                                                {
                                                    /******Subtotal Calculation******/
                                                    $subtotal = $getproduct->spingst * $quantity;
                                                    /******Subtotal Calculation******/

                                                    /******GST Calculation******/
                                                    $gstpercent = $getproduct->spgst;
                                                    $spingst = $getproduct->spingst;

                                                    $gstamount = $spingst * $gstpercent / 100;
                                                    $gstamountotal = $gstamount * $quantity;
                                                    /******GST Calculation******/

                                                    /******Commission Calculation******/
                                                    if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                                    {
                                                        $ppexgst = $getproductstock->ppexgst;
                                                        $spexgst = $getproduct->spexgst;

                                                        $diffprofit = $spexgst - $ppexgst;

                                                        $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                                    {
                                                        $spingst = $getproduct->spingst;
                                                        $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                                    {
                                                        $dealermargin = $getproductstockgroup->dealermargin;
                                                        $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'fixed')
                                                    {
                                                        $staffbonus = $getproductstockgroup->staffbonus;
                                                        $comission = $staffbonus * $quantity;
                                                    }
                                                    else
                                                    {
                                                        $comission = 0.00;
                                                    }

                                                    /******Comission Calculation******/

                                                    $insertorderitem = new orderitem;
                                                    $insertorderitem->orderID = $orderid;
                                                    $insertorderitem->productID = $getproduct->productID;
                                                    $insertorderitem->stockID = $getproductstock->psID;
                                                    $insertorderitem->quantity = $quantity;
                                                    $insertorderitem->stockgroup = $getproductstockgroup->stockgroupID;
                                                    $insertorderitem->ppingst = $getproductstock->ppingst;
                                                    $insertorderitem->spingst = $getproduct->spingst;
                                                    $insertorderitem->salePrice = $getproduct->spingst;
                                                    $insertorderitem->gstamount = $gstamountotal;
                                                    $insertorderitem->subTotal = $subtotal;
                                                    $insertorderitem->Comission = $comission;
                                                    $insertorderitem->save();

                                                    if($insertorderitem->save())
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
                                                    
                                                    /******Subtotal Calculation******/
                                                    $subtotal = $orderitem->salePrice * $quantity;
                                                    /******Subtotal Calculation******/

                                                    /******GST Calculation******/
                                                    $gstpercent = $getproduct->spgst;
                                                    $salePrice = $orderitem->salePrice;

                                                    $gstamount = $salePrice * $gstpercent / 100;
                                                    $gstamountotal = $gstamount * $quantity;
                                                    /******GST Calculation******/

                                                    /******Commission Calculation******/
                                                    if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                                    {
                                                        $CCppexgst = $getproductstock->ppexgst;
                                                        $CCspingst = $orderitem->salePrice;
                                                        $CCsptax = $getproduct->spgst;

                                                        $CCspexgst = $CCspingst - $CCspingst * $CCsptax / 100;

                                                        $diffprofit = $CCspexgst - $CCppexgst;

                                                        $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                                    {
                                                        $salePrice = $orderitem->salePrice;
                                                        $profitmargin = $salePrice * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                                    {
                                                        $dealermargin = $getproductstockgroup->dealermargin;
                                                        $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'fixed')
                                                    {
                                                        $staffbonus = $getproductstockgroup->staffbonus;
                                                        $comission = $staffbonus * $quantity;
                                                    }
                                                    else
                                                    {
                                                        $comission = 0.00;
                                                    }

                                                    /******Comission Calculation******/

                                                    
                                                    $orderitem->quantity = $orderitem->quantity + $quantity;
                                                    $orderitem->gstamount = $orderitem->gstamount + $gstamountotal;
                                                    $orderitem->subTotal = $orderitem->subTotal + $subtotal;
                                                    $orderitem->Comission = $orderitem->Comission + $comission;
                                                    $orderitem->save();

                                                    if($orderitem->save())
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
    }

    public function addbyproductid(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
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
                    $orderid = $request->input('orderid');
                    $quantity = $request->input('quantity');

                    $getproduct = product::where('productID', $productid)
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
                        ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                        ->first();

                        if($getproductstock != "")
                        {
                            if($quantity <= $getproductstock->productquantity)
                            {
                                $orderdetail = orderdetail::where('orderID', $orderid)
                                ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                                ->count();

                                if($orderdetail > 0)
                                {
                                    $orderitem = orderitem::where('orderID', $orderid)
                                    ->where('productID', $getproduct->productID)
                                    ->where('stockID', $getproductstock->psID)
                                    ->first();

                                    if($orderitem == "")
                                    {
                                        /******Subtotal Calculation******/
                                        $subtotal = $getproduct->spingst * $quantity;
                                        /******Subtotal Calculation******/

                                        /******GST Calculation******/
                                        $gstpercent = $getproduct->spgst;
                                        $spingst = $getproduct->spingst;

                                        $gstamount = $spingst * $gstpercent / 100;
                                        $gstamountotal = $gstamount * $quantity;
                                        /******GST Calculation******/

                                        /******Commission Calculation******/
                                        if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                        {
                                            $ppexgst = $getproductstock->ppexgst;
                                            $spexgst = $getproduct->spexgst;

                                            $diffprofit = $spexgst - $ppexgst;

                                            $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                        {
                                            $spingst = $getproduct->spingst;
                                            $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                        {
                                            $dealermargin = $getproductstockgroup->dealermargin;
                                            $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'fixed')
                                        {
                                            $staffbonus = $getproductstockgroup->staffbonus;
                                            $comission = $staffbonus * $quantity;
                                        }
                                        else
                                        {
                                            $comission = 0.00;
                                        }

                                        /******Comission Calculation******/

                                        $insertorderitem = new orderitem;
                                        $insertorderitem->orderID = $orderid;
                                        $insertorderitem->productID = $getproduct->productID;
                                        $insertorderitem->stockID = $getproductstock->psID;
                                        $insertorderitem->quantity = $quantity;
                                        $insertorderitem->stockgroup = $getproductstockgroup->stockgroupID;
                                        $insertorderitem->ppingst = $getproductstock->ppingst;
                                        $insertorderitem->spingst = $getproduct->spingst;
                                        $insertorderitem->salePrice = $getproduct->spingst;
                                        $insertorderitem->gstamount = $gstamountotal;
                                        $insertorderitem->subTotal = $subtotal;
                                        $insertorderitem->Comission = $comission;
                                        $insertorderitem->save();

                                        if($insertorderitem->save())
                                        {
                                            $subtractquantity = $getproductstock->productquantity - $quantity;

                                            $getproductstock->productquantity = $subtractquantity;
                                            $getproductstock->save();

                                            return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 004)');
                                        }
                                        else
                                        {
                                            return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 014)');
                                        }
                                    }
                                    else
                                    {
                                        /******Subtotal Calculation******/
                                        $subtotal = $orderitem->salePrice * $quantity;
                                        /******Subtotal Calculation******/

                                        /******GST Calculation******/
                                        $gstpercent = $getproduct->spgst;
                                        $salePrice = $orderitem->salePrice;

                                        $gstamount = $salePrice * $gstpercent / 100;
                                        $gstamountotal = $gstamount * $quantity;
                                        /******GST Calculation******/

                                        /******Commission Calculation******/
                                        if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                        {
                                            $CCppexgst = $getproductstock->ppexgst;
                                            $CCspingst = $orderitem->salePrice;
                                            $CCsptax = $getproduct->spgst;

                                            $CCspexgst = $CCspingst - $CCspingst * $CCsptax / 100;

                                            $diffprofit = $CCspexgst - $CCppexgst;

                                            $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                        {
                                            $salePrice = $orderitem->salePrice;
                                            $profitmargin = $salePrice * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                        {
                                            $dealermargin = $getproductstockgroup->dealermargin;
                                            $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'fixed')
                                        {
                                            $staffbonus = $getproductstockgroup->staffbonus;
                                            $comission = $staffbonus * $quantity;
                                        }
                                        else
                                        {
                                            $comission = 0.00;
                                        }

                                        /******Comission Calculation******/

                                        
                                        $orderitem->quantity = $orderitem->quantity + $quantity;
                                        $orderitem->gstamount = $orderitem->gstamount + $gstamountotal;
                                        $orderitem->subTotal = $orderitem->subTotal + $subtotal;
                                        $orderitem->Comission = $orderitem->Comission + $comission;
                                        $orderitem->save();

                                        if($orderitem->save())
                                        {
                                            $subtractquantity = $getproductstock->productquantity - $quantity;

                                            $getproductstock->productquantity = $subtractquantity;
                                            $getproductstock->save();

                                            return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 003)');
                                        }
                                        else
                                        {
                                            return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 013)');
                                        }
                                    }
                                }
                                else
                                {
                                    return redirect()->back()->with('error','Failed to get order detail. (ErrorCode - 012)');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error','Not sufficient quantity for this barcode. (ErrorCode - 011)');
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error','Product stock not available. (ErrorCode - 010)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error','Product cannot be sold as outright. (ErrorCode - 009)');
                    }
                } 
            }
        }
    }

    public function addstockbyid(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'quantity'=>'required',
                'stockcode'=>'required',
                'orderid'=>'required'
                ],[
                    'quantity.required'=>'Quantity is required',
                    'stockcode.required'=>'Barcode is required',
                    'orderid.required'=>'Fail to get Invoice ID'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    $quantity = $request->input('quantity');
                    $stockcode = $request->input('stockcode');
                    $orderid = $request->input('orderid');

                    $countproduct = product::where('stockcode', $stockcode)->where('productstatus', '1')->count();

                    if($countproduct > 0)
                    {
                        $countaccessoriesproduct = product::where('stockcode', $stockcode)
                        ->whereNull('producttype')
                        ->where('productstatus', '1')
                        ->count();

                        if($countaccessoriesproduct > 0)
                        {
                            if($countaccessoriesproduct > 1)
                            {
                                $getproduct = product::where('stockcode', $stockcode)
                                ->whereNull('producttype')
                                ->with('productbrand')
                                ->with('productcolour')
                                ->with('productmodel')
                                ->with('productsupplier')
                                ->where('productstatus', '1')
                                ->get();

                                $multistockcodeopenmodel = '1';

                                $stockcodeproductdata = ['multistockcodeopenmodel'=>$multistockcodeopenmodel, 'getproduct'=>$getproduct, 'quantity'=>$quantity];

                                //return $productdata;

                                return redirect()->back()->with('stockcodeproductdata', $stockcodeproductdata);
                            }
                            else
                            {
                                $getproduct = product::where('stockcode', $stockcode)
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
                                    ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                                    ->first();

                                    if($getproductstock != "")
                                    {
                                        if($quantity <= $getproductstock->productquantity)
                                        {
                                            $orderdetail = orderdetail::where('orderID', $orderid)
                                            ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                                            ->count();

                                            if($orderdetail > 0)
                                            {
                                                $orderitem = orderitem::where('orderID', $orderid)
                                                ->where('productID', $getproduct->productID)
                                                ->where('stockID', $getproductstock->psID)
                                                ->first();

                                                if($orderitem == "")
                                                {
                                                    /******Subtotal Calculation******/
                                                    $subtotal = $getproduct->spingst * $quantity;
                                                    /******Subtotal Calculation******/

                                                    /******GST Calculation******/
                                                    $gstpercent = $getproduct->spgst;
                                                    $spingst = $getproduct->spingst;

                                                    $gstamount = $spingst * $gstpercent / 100;
                                                    $gstamountotal = $gstamount * $quantity;
                                                    /******GST Calculation******/

                                                    /******Commission Calculation******/
                                                    if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                                    {
                                                        $ppexgst = $getproductstock->ppexgst;
                                                        $spexgst = $getproduct->spexgst;

                                                        $diffprofit = $spexgst - $ppexgst;

                                                        $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                                    {
                                                        $spingst = $getproduct->spingst;
                                                        $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                                    {
                                                        $dealermargin = $getproductstockgroup->dealermargin;
                                                        $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'fixed')
                                                    {
                                                        $staffbonus = $getproductstockgroup->staffbonus;
                                                        $comission = $staffbonus * $quantity;
                                                    }
                                                    else
                                                    {
                                                        $comission = 0.00;
                                                    }

                                                    /******Comission Calculation******/

                                                    $insertorderitem = new orderitem;
                                                    $insertorderitem->orderID = $orderid;
                                                    $insertorderitem->productID = $getproduct->productID;
                                                    $insertorderitem->stockID = $getproductstock->psID;
                                                    $insertorderitem->quantity = $quantity;
                                                    $insertorderitem->stockgroup = $getproductstockgroup->stockgroupID;
                                                    $insertorderitem->ppingst = $getproductstock->ppingst;
                                                    $insertorderitem->spingst = $getproduct->spingst;
                                                    $insertorderitem->salePrice = $getproduct->spingst;
                                                    $insertorderitem->gstamount = $gstamountotal;
                                                    $insertorderitem->subTotal = $subtotal;
                                                    $insertorderitem->Comission = $comission;
                                                    $insertorderitem->save();

                                                    if($insertorderitem->save())
                                                    {
                                                        $subtractquantity = $getproductstock->productquantity - $quantity;

                                                        $getproductstock->productquantity = $subtractquantity;
                                                        $getproductstock->save();

                                                        return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 006)');
                                                    }
                                                    else
                                                    {
                                                        return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 022)');
                                                    }
                                                }
                                                else
                                                {
                                                    /******Subtotal Calculation******/
                                                    $subtotal = $orderitem->salePrice * $quantity;
                                                    /******Subtotal Calculation******/

                                                    /******GST Calculation******/
                                                    $gstpercent = $getproduct->spgst;
                                                    $salePrice = $orderitem->salePrice;

                                                    $gstamount = $salePrice * $gstpercent / 100;
                                                    $gstamountotal = $gstamount * $quantity;
                                                    /******GST Calculation******/

                                                    /******Commission Calculation******/
                                                    if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                                    {
                                                        $CCppexgst = $getproductstock->ppexgst;
                                                        $CCspingst = $orderitem->salePrice;
                                                        $CCsptax = $getproduct->spgst;

                                                        $CCspexgst = $CCspingst - $CCspingst * $CCsptax / 100;

                                                        $diffprofit = $CCspexgst - $CCppexgst;

                                                        $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                                    {
                                                        $salePrice = $orderitem->salePrice;
                                                        $profitmargin = $salePrice * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                                    {
                                                        $dealermargin = $getproductstockgroup->dealermargin;
                                                        $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                                        $comission = $profitmargin * $quantity;
                                                    }
                                                    else if($getproductstockgroup->staffbonustype == 'fixed')
                                                    {
                                                        $staffbonus = $getproductstockgroup->staffbonus;
                                                        $comission = $staffbonus * $quantity;
                                                    }
                                                    else
                                                    {
                                                        $comission = 0.00;
                                                    }

                                                    /******Comission Calculation******/

                                                    
                                                    $orderitem->quantity = $orderitem->quantity + $quantity;
                                                    $orderitem->gstamount = $orderitem->gstamount + $gstamountotal;
                                                    $orderitem->subTotal = $orderitem->subTotal + $subtotal;
                                                    $orderitem->Comission = $orderitem->Comission + $comission;
                                                    $orderitem->save();

                                                    if($orderitem->save())
                                                    {
                                                        $subtractquantity = $getproductstock->productquantity - $quantity;

                                                        $getproductstock->productquantity = $subtractquantity;
                                                        $getproductstock->save();

                                                        return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 005)');
                                                    }
                                                    else
                                                    {
                                                        return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 021)');
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                return redirect()->back()->with('error','Failed to get order detail. (ErrorCode - 020)');
                                            }
                                        }
                                        else
                                        {
                                            return redirect()->back()->with('error','Not sufficient quantity for this barcode. (ErrorCode - 019)');
                                        }
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('error','Product stock not available. (ErrorCode - 018)');
                                    }
                                }
                                else
                                {
                                    return redirect()->back()->with('error','Product cannot be sold as outright. (ErrorCode - 017)');
                                }
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error','By stock code.! you can add quantity products only. for unique products. Please use other options (ErrorCode - 016)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error','No product found related to this stock code (ErrorCode - 015)');
                    }
                } 
            }
        }
    }

    public function addallbyproductid(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
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
                    $orderid = $request->input('orderid');
                    $quantity = $request->input('quantity');

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
                        ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                        ->first();

                        if($getproductstock != "")
                        {
                            if($quantity <= $getproductstock->productquantity)
                            {
                                $orderdetail = orderdetail::where('orderID', $orderid)
                                ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                                ->count();

                                if($orderdetail > 0)
                                {
                                    $orderitem = orderitem::where('orderID', $orderid)
                                    ->where('productID', $getproduct->productID)
                                    ->where('stockID', $getproductstock->psID)
                                    ->first();

                                    if($orderitem == "")
                                    {
                                        /******Subtotal Calculation******/
                                        $subtotal = $getproduct->spingst * $quantity;
                                        /******Subtotal Calculation******/

                                        /******GST Calculation******/
                                        $gstpercent = $getproduct->spgst;
                                        $spingst = $getproduct->spingst;

                                        $gstamount = $spingst * $gstpercent / 100;
                                        $gstamountotal = $gstamount * $quantity;
                                        /******GST Calculation******/

                                        /******Commission Calculation******/
                                        if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                        {
                                            $ppexgst = $getproductstock->ppexgst;
                                            $spexgst = $getproduct->spexgst;

                                            $diffprofit = $spexgst - $ppexgst;

                                            $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                        {
                                            $spingst = $getproduct->spingst;
                                            $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                        {
                                            $dealermargin = $getproductstockgroup->dealermargin;
                                            $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'fixed')
                                        {
                                            $staffbonus = $getproductstockgroup->staffbonus;
                                            $comission = $staffbonus * $quantity;
                                        }
                                        else
                                        {
                                            $comission = 0.00;
                                        }

                                        /******Comission Calculation******/

                                        $insertorderitem = new orderitem;
                                        $insertorderitem->orderID = $orderid;
                                        $insertorderitem->productID = $getproduct->productID;
                                        $insertorderitem->stockID = $getproductstock->psID;
                                        $insertorderitem->quantity = $quantity;
                                        $insertorderitem->stockgroup = $getproductstockgroup->stockgroupID;
                                        $insertorderitem->ppingst = $getproductstock->ppingst;
                                        $insertorderitem->spingst = $getproduct->spingst;
                                        $insertorderitem->salePrice = $getproduct->spingst;
                                        $insertorderitem->gstamount = $gstamountotal;
                                        $insertorderitem->subTotal = $subtotal;
                                        $insertorderitem->Comission = $comission;
                                        $insertorderitem->save();

                                        if($insertorderitem->save())
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
                                        /******Subtotal Calculation******/
                                        $subtotal = $orderitem->salePrice * $quantity;
                                        /******Subtotal Calculation******/

                                        /******GST Calculation******/
                                        $gstpercent = $getproduct->spgst;
                                        $salePrice = $orderitem->salePrice;

                                        $gstamount = $salePrice * $gstpercent / 100;
                                        $gstamountotal = $gstamount * $quantity;
                                        /******GST Calculation******/

                                        /******Commission Calculation******/
                                        if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                        {
                                            $CCppexgst = $getproductstock->ppexgst;
                                            $CCspingst = $orderitem->salePrice;
                                            $CCsptax = $getproduct->spgst;

                                            $CCspexgst = $CCspingst - $CCspingst * $CCsptax / 100;

                                            $diffprofit = $CCspexgst - $CCppexgst;

                                            $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                        {
                                            $salePrice = $orderitem->salePrice;
                                            $profitmargin = $salePrice * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                        {
                                            $dealermargin = $getproductstockgroup->dealermargin;
                                            $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                            $comission = $profitmargin * $quantity;
                                        }
                                        else if($getproductstockgroup->staffbonustype == 'fixed')
                                        {
                                            $staffbonus = $getproductstockgroup->staffbonus;
                                            $comission = $staffbonus * $quantity;
                                        }
                                        else
                                        {
                                            $comission = 0.00;
                                        }

                                        /******Comission Calculation******/

                                        
                                        $orderitem->quantity = $orderitem->quantity + $quantity;
                                        $orderitem->gstamount = $orderitem->gstamount + $gstamountotal;
                                        $orderitem->subTotal = $orderitem->subTotal + $subtotal;
                                        $orderitem->Comission = $orderitem->Comission + $comission;
                                        $orderitem->save();

                                        if($orderitem->save())
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
    }

    public function searchplan(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            $plantype = $request->input('plantype');
            $planpropositiontype = $request->input('planpropositiontype');
            $plancategory = $request->input('plancategory');
            $planterm = $request->input('planterm');
            $planhandsetterm = $request->input('planhandsetterm');
            $planstockgroup = $request->input('planstockgroup');
            $planamount = $request->input('planamount');

            //return $request->all();
            
            $findplan = plan::where('planstatus', '1')
            ->where('planpropositionID', 'LIKE', '%'.$planpropositiontype.'%')
            ->where('planterm', 'LIKE', '%'.$planterm.'%')
            ->where('plancategoryID', 'LIKE', '%'.$plancategory.'%')
            ->where('planhandsetterm', 'LIKE', '%'.$planhandsetterm.'%')
            ->where('plantypeID', 'LIKE', '%'.$plantype.'%')
            ->where('ppingst', 'LIKE', '%'.$planamount.'%')
            ->where('plan.planstockgroup', 'LIKE', '%'.$planstockgroup.'%')
            ->with('plantypere')
            ->with('planpropositionre')
            ->with('plantermre')
            ->with('plancategoryre')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'plan.planstockgroup')
            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'plan.planhandsetterm')
            ->get();
            

            if($planstockgroup != "")
            {
                $stockgroup = masterstockgroup::where('stockgroupID', $planstockgroup)->first();
            }
            else
            {
                $stockgroup = "";
            }

            $openplanmodel = '1';
            $plandata = ['openplanmodel'=>$openplanmodel, 'findplan'=>$findplan, 'stockgroup'=>$stockgroup];
            
            return redirect()->back()->with('plandata', $plandata);
        }
    }

    public function addbyplanid(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            $planid = $request->input('planid');
            $stockgroups = $request->input('planstockgroup');

            //return $stockgroups;

            $getplan = plan::where('planID', $planid)->first();

            $showdevicedropdown = productstockgroup::where('stockgroupID', $stockgroups)->count();

            //return $showdevicedropdown;

            if($showdevicedropdown > 0)
            {
                $showdropdown = '1';
            }
            else
            {
                $showdropdown = '0';
            }

            $stockproducts = product::where('productstatus', '1')
            ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
            ->where('productstockgroup.stockgroupID', $stockgroups)
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
            ->where('masterstockgroup.stockpriceeffect', '0.00')
            ->where('masterstockgroup.productqtyeffect', '1')
            ->leftJoin('productstock', 'productstock.productID', '=', 'products.productID')
            ->where('productstock.productquantity', '!=', '0')
            ->where('productstock.storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->with('producttypedata')
            ->get();

            $orderdetail = orderdetail::where('orderID', $request->input('invoiceid'))
            ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
            ->first();

            $getcustomer = customer::where('customerID', $orderdetail->customerID)->first();

            $planMobilenumber = $getcustomer->customermobilenumber;

            $orderitem = orderitem::where('orderID', $orderdetail->orderID)
            ->where('planID', '!=', '')
            ->first();

            if($orderitem != "")
            {
                $planorderid = $orderitem->planOrderID;
                $planfullfillid = $orderitem->planFullfillmentOrderid;
            }
            else
            {
                $planorderid = '';
                $planfullfillid = '';
            }

            if($getplan->planhandsetterm==1)
            {
                $deffereddevice = product::whereNull('producttype')
                ->where('productstatus', '1')
                ->where('categories', '2')
                ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                ->where('masterstockgroup.productqtyeffect', '0')
                ->where('masterstockgroup.stockpriceeffect', '0.00')
                ->where('masterstockgroup.stockgroupstatus', '1')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->where('productstockgroup.stockgroupID', $stockgroups)
                ->get();
            }
            else
            {
                $deffereddevice = product::where('producttype', '!=', '')
                ->where('productstatus', '1')
                ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                ->where('productstockgroup.stockgroupID', $stockgroups)
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                ->where('masterstockgroup.productqtyeffect', '0')
                ->where('masterstockgroup.stockpriceeffect', '0.00')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->where('productstockgroup.stockgroupID', $stockgroups)
                ->get();
            }

            $openplanstockmodel = '1';

            $planstockdata = ['openplanstockmodel'=>$openplanstockmodel, 'planid'=>$planid, 'stockgroups'=>$stockgroups, 'stockproducts'=>$stockproducts, 'deffereddevice'=>$deffereddevice, 'showdropdown'=>$showdropdown, 'orderitem'=>$orderitem, 'planorderid'=>$planorderid, 'planfullfillid'=>$planfullfillid, 'planMobilenumber'=>$planMobilenumber, 'getplan'=>$getplan];
            //return $planstockdata;

            return redirect()->back()->with('planstockdata', $planstockdata);
        }
    }

    public function addplandetail(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            $orderid = $request->input('orderid');
            $planid = $request->input('planid');
            $ordernumber = $request->input('ordernumber');
            $fulfilmentorder = $request->input('fulfilmentorder');
            $mobilenumber = $request->input('mobilenumber');
            $stockgroup = $request->input('stockgroup');
            $planinsurance = $request->input('planinsurance');

            if($request->input('postpaiddevice') != '')
            {
                $checkplanorder = orderitem::where('planOrderID', $ordernumber)->count();

                if($checkplanorder == 0)
                {
                    $checkproductstock = productstock::where('psID', $request->input('postpaiddevice'))
                    ->where('productquantity', '>', '0')
                    ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                    ->count();

                    if($checkproductstock == 0)
                    {
                        return redirect()->back()->with('error', 'Plan consist device already out of stock (ErrorCode - P01)');
                    }
                    else
                    {
                        $productstock = productstock::where('psID', $request->input('postpaiddevice'))
                        ->where('productquantity', '>', '0')
                        ->leftJoin('products', 'products.productID', '=', 'productstock.productID')
                        ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                        ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                        ->where('masterstockgroup.stockpriceeffect', '0.00')
                        ->where('masterstockgroup.productqtyeffect', '1')
                        ->first();

                        if($planinsurance != "")
                        {
                            $insurance = product::leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                            ->where('masterstockgroup.stockpriceeffect', '0.00')
                            ->where('masterstockgroup.productqtyeffect', '0')
                            ->where('products.productID', $planinsurance)
                            ->first();

                            $insurancename= $insurance->productname;

                            if($insurance->staffbonustype == 'percentage_profitmargin')
                            {
                                $ppexgst = $insurance->ppexgst;
                                $spexgst = $insurance->spexgst;

                                $diffprofit = $spexgst - $ppexgst;

                                $profitmargin = $diffprofit * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'percentage_saleprice')
                            {
                                $spingst = $insurance->spingst;
                                $profitmargin = $spingst * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'percentage_dealermargin')
                            {
                                $dealermargin = $insurance->dealermargin;
                                $profitmargin = $dealermargin * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'fixed')
                            {
                                $staffbonus = $insurance->staffbonus;
                                $inscomission = $staffbonus * 1;
                            }
                            else
                            {
                                $inscomission = 0.00;
                            }

                            $insertinsurance = new orderitem;
                            $insertinsurance->orderID = $orderid;
                            $insertinsurance->productID = $planinsurance;
                            $insertinsurance->planOrderID = $ordernumber;
                            $insertinsurance->planFullfillmentOrderid = $fulfilmentorder;
                            $insertinsurance->planMobilenumber= $mobilenumber;
                            $insertinsurance->quantity = '1';
                            $insertinsurance->stockgroup = $insurance->stockgroupID;
                            $insertinsurance->ppingst = $insurance->ppingst;
                            $insertinsurance->spingst = $insurance->spingst;
                            $insertinsurance->salePrice = $insurance->spingst;
                            $insertinsurance->subTotal = $insurance->spingst;
                            $insertinsurance->Comission = $inscomission;
                            $insertinsurance->save();

                            if($insertinsurance->save())
                            {
                                $insuranceid = $insertinsurance->orderitemID;
                            }
                            else
                            {
                                $insuranceid = '';   
                            }
                        }
                        else
                        {
                            $insurancename= "";
                            $insuranceid = '';
                        }

                        $getplan = plan::where('planID', $planid)
                        ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'planhandsetterm')
                        ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
                        ->first();
                        $planname= $getplan->planname;

                        $getstockgroup = masterstockgroup::find($stockgroup);
                        //return $getstockgroup;
                        $stockgroupname = $getstockgroup->stockgroupname;
                        $stockpriceeffect = $getstockgroup->stockpriceeffect;

                        if($stockpriceeffect=='sellingprice')
                        {
                            $planppingst= $getplan->ppingst;
                            $planspinget = $getplan->ppingst;
                        }
                        else
                        {
                            $planppingst = $getplan->ppingst;
                            $planspinget= $getstockgroup->stockpriceeffect;
                        }

                        /*******Comission Calulation*****/
                        if($productstock->producttype == "")
                        {
                            if($productstock->staffbonustype == 'percentage_profitmargin')
                            {
                                $ppexgst = $productstock->ppexgst;
                                $spexgst = $productstock->spexgst;

                                $diffprofit = $spexgst - $ppexgst;

                                $profitmargin = $diffprofit * $productstock->staffbonus / 100;
                                $comission = $profitmargin * 1;
                            }
                            else if($productstock->staffbonustype == 'percentage_saleprice')
                            {
                                $spingst = $productstock->spingst;
                                $profitmargin = $spingst * $productstock->staffbonus / 100;
                                $comission = $profitmargin * 1;
                            }
                            else if($productstock->staffbonustype == 'percentage_dealermargin')
                            {
                                $dealermargin = $productstock->dealermargin;
                                $profitmargin = $dealermargin * $productstock->staffbonus / 100;
                                $comission = $profitmargin * 1;
                            }
                            else if($productstock->staffbonustype == 'fixed')
                            {
                                $staffbonus = $productstock->staffbonus;
                                $comission = $staffbonus * 1;
                            }
                            else
                            {
                                $comission = 0.00;
                            }
                        }
                        else
                        {
                            if($getplan->planbonustype == 'percentage')
                            {
                                $comission = $getplan->plancomission * $getplan->planbonus / 100;
                            }
                            else if($getplan->planbonustype == 'fixed')
                            {
                                $comission = $getplan->planbonus;
                            }
                            else
                            {
                                $comission = 0.00;
                            }
                        }
                        /*******Comission Calulation*****/

                        $plandetails = "Item: ".$productstock->productname.", ".$productstock->productimei."-".$productstock->stockgroupname.", Order Num: ".$ordernumber.", Ful. Order: ".$fulfilmentorder.", Number: ".$mobilenumber.", H.T: ".$getplan->planhandsettermduration.", P.T: ".$getplan->plantermname.", ".$insurancename;

                        $insertorderplanitem = new orderitem;
                        $insertorderplanitem->orderID = $orderid;
                        $insertorderplanitem->productID = $productstock->productID;
                        $insertorderplanitem->stockID = $productstock->psID;
                        
                        $insertorderplanitem->planID = $planid;
                        $insertorderplanitem->planOrderID = $ordernumber;
                        $insertorderplanitem->plandetails = $plandetails;
                        $insertorderplanitem->planInsurance = $insuranceid;
                        $insertorderplanitem->planFullfillmentOrderid = $fulfilmentorder;
                        $insertorderplanitem->planMobilenumber= $mobilenumber;
                        $insertorderplanitem->quantity = '1';
                        $insertorderplanitem->stockgroup = $stockgroup;
                        $insertorderplanitem->ppingst = $planppingst;
                        $insertorderplanitem->spingst = $planspinget;
                        $insertorderplanitem->salePrice = $planspinget;
                        $insertorderplanitem->subTotal = $planspinget;
                        $insertorderplanitem->Comission = $comission;
                        $insertorderplanitem->save();

                        if($insertorderplanitem->save())
                        {
                             $updateproductstock = productstock::where('psID', $request->input('postpaiddevice'))->first();
                             $updateproductstock->productquantity = $updateproductstock->productquantity - 1;
                             $updateproductstock->save();

                            return redirect()->back()->with('success', 'Plan added to invoice');
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Something went wrong. Please try again');
                        }
                    }
                }
                else
                {
                    $checkorder = orderitem::where('orderID', $orderid)->where('planOrderID', $ordernumber)->count();

                    if($checkorder == 0)
                    {
                        return redirect()->back()->with('error', 'Plan order number already associated with another invoice. Please enter a unique plan order number');
                    }
                    else
                    {
                        $checkproductstock = productstock::where('psID', $request->input('postpaiddevice'))
                        ->where('productquantity', '>', '0')
                        ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                        ->count();

                        if($checkproductstock == 0)
                        {
                            return redirect()->back()->with('error', 'Plan consist device already out of stock');
                        }
                        else
                        {
                            $productstock = productstock::where('psID', $request->input('postpaiddevice'))
                            ->where('productquantity', '>', '0')
                            ->leftJoin('products', 'products.productID', '=', 'productstock.productID')
                            ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                            ->where('masterstockgroup.stockpriceeffect', '0.00')
                            ->where('masterstockgroup.productqtyeffect', '1')
                            ->first();

                            if($planinsurance != "")
                            {
                                $insurance = product::leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                                ->where('masterstockgroup.stockpriceeffect', '0.00')
                                ->where('masterstockgroup.productqtyeffect', '0')
                                ->where('products.productID', $planinsurance)
                                ->first();

                                $insurancename= $insurance->productname;

                                if($insurance->staffbonustype == 'percentage_profitmargin')
                                {
                                    $ppexgst = $insurance->ppexgst;
                                    $spexgst = $insurance->spexgst;

                                    $diffprofit = $spexgst - $ppexgst;

                                    $profitmargin = $diffprofit * $insurance->staffbonus / 100;
                                    $inscomission = $profitmargin * 1;
                                }
                                else if($insurance->staffbonustype == 'percentage_saleprice')
                                {
                                    $spingst = $insurance->spingst;
                                    $profitmargin = $spingst * $insurance->staffbonus / 100;
                                    $inscomission = $profitmargin * 1;
                                }
                                else if($insurance->staffbonustype == 'percentage_dealermargin')
                                {
                                    $dealermargin = $insurance->dealermargin;
                                    $profitmargin = $dealermargin * $insurance->staffbonus / 100;
                                    $inscomission = $profitmargin * 1;
                                }
                                else if($insurance->staffbonustype == 'fixed')
                                {
                                    $staffbonus = $insurance->staffbonus;
                                    $inscomission = $staffbonus * 1;
                                }
                                else
                                {
                                    $inscomission = 0.00;
                                }

                                $insertinsurance = new orderitem;
                                $insertinsurance->orderID = $orderid;
                                $insertinsurance->productID = $planinsurance;
                                $insertinsurance->planOrderID = $ordernumber;
                                $insertinsurance->planFullfillmentOrderid = $fulfilmentorder;
                                $insertinsurance->planMobilenumber= $mobilenumber;
                                $insertinsurance->quantity = '1';
                                $insertinsurance->stockgroup = $insurance->stockgroupID;
                                $insertinsurance->ppingst = $insurance->ppingst;
                                $insertinsurance->spingst = $insurance->spingst;
                                $insertinsurance->salePrice = $insurance->spingst;
                                $insertinsurance->subTotal = $insurance->spingst;
                                $insertinsurance->Comission = $inscomission;
                                $insertinsurance->save();

                                if($insertinsurance->save())
                                {
                                    $insuranceid = $insertinsurance->orderitemID;
                                }
                                else
                                {
                                    $insuranceid = '';   
                                }
                            }
                            else
                            {
                                $insurancename= "";
                                $insuranceid = '';
                            }

                            $getplan = plan::where('planID', $planid)
                            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'planhandsetterm')
                            ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
                            ->first();
                            $planname= $getplan->planname;

                            $getstockgroup = masterstockgroup::find($stockgroup);
                            $stockgroupname = $getstockgroup->stockgroupname;
                            $stockpriceeffect = $getstockgroup->stockpriceeffect;

                            if($stockpriceeffect=='sellingprice')
                            {
                                $planppingst= $getplan->ppingst;
                                $planspinget = $getplan->ppingst;
                            }
                            else
                            {
                                $planppingst = $getplan->ppingst;
                                $planspinget= $getstockgroup->stockpriceeffect;
                            }

                            /*******Comission Calulation*****/
                            if($productstock->producttype == "")
                            {
                                if($productstock->staffbonustype == 'percentage_profitmargin')
                                {
                                    $ppexgst = $productstock->ppexgst;
                                    $spexgst = $productstock->spexgst;

                                    $diffprofit = $spexgst - $ppexgst;

                                    $profitmargin = $diffprofit * $productstock->staffbonus / 100;
                                    $comission = $profitmargin * 1;
                                }
                                else if($productstock->staffbonustype == 'percentage_saleprice')
                                {
                                    $spingst = $productstock->spingst;
                                    $profitmargin = $spingst * $productstock->staffbonus / 100;
                                    $comission = $profitmargin * 1;
                                }
                                else if($productstock->staffbonustype == 'percentage_dealermargin')
                                {
                                    $dealermargin = $productstock->dealermargin;
                                    $profitmargin = $dealermargin * $productstock->staffbonus / 100;
                                    $comission = $profitmargin * 1;
                                }
                                else if($productstock->staffbonustype == 'fixed')
                                {
                                    $staffbonus = $productstock->staffbonus;
                                    $comission = $staffbonus * 1;
                                }
                                else
                                {
                                    $comission = 0.00;
                                }
                            }
                            else
                            {
                                if($getplan->planbonustype == 'percentage')
                                {
                                    $comission = $getplan->plancomission * $getplan->planbonus / 100;
                                }
                                else if($getplan->planbonustype == 'fixed')
                                {
                                    $comission = $getplan->planbonus;
                                }
                                else
                                {
                                    $comission = 0.00;
                                }
                            }
                            /*******Comission Calulation*****/

                            $plandetails = "Item: ".$productstock->productname.", ".$productstock->productimei."-".$productstock->stockgroupname.", Order Num: ".$ordernumber.", Ful. Order: ".$fulfilmentorder.", Number: ".$mobilenumber.", H.T: ".$getplan->planhandsettermduration.", P.T: ".$getplan->plantermname.", ".$insurancename;

                            $insertorderplanitem = new orderitem;
                            $insertorderplanitem->orderID = $orderid;
                            $insertorderplanitem->productID = $productstock->productID;
                            $insertorderplanitem->stockID = $productstock->psID;
                            
                            $insertorderplanitem->planID = $planid;
                            $insertorderplanitem->planOrderID = $ordernumber;
                            $insertorderplanitem->planFullfillmentOrderid = $fulfilmentorder;
                            $insertorderplanitem->planInsurance = $insuranceid;
                            $insertorderplanitem->planMobilenumber = $mobilenumber;
                            $insertorderplanitem->plandetails = $plandetails;
                            $insertorderplanitem->quantity = '1';
                            $insertorderplanitem->stockgroup = $stockgroup;
                            $insertorderplanitem->ppingst = $planppingst;
                            $insertorderplanitem->spingst = $planspinget;
                            $insertorderplanitem->salePrice = $planspinget;
                            $insertorderplanitem->subTotal = $planspinget;
                            $insertorderplanitem->Comission = $comission;

                            $insertorderplanitem->save();

                            if($insertorderplanitem->save())
                            {
                                 $updateproductstock = productstock::where('psID', $request->input('postpaiddevice'))->first();
                                 $updateproductstock->productquantity = $updateproductstock->productquantity - 1;
                                 $updateproductstock->save();

                                return redirect()->back()->with('success', 'Plan added to invoice');
                            }
                            else
                            {
                                return redirect()->back()->with('error', 'Something went wrong. Please try again');
                            }
                        }
                    }
                }
            }
            else if($request->input('deferreddevice') != '')
            {
                $checkplanorder = orderitem::where('planOrderID', $ordernumber)->count();

                if($checkplanorder == 0)
                {
                    $product = product::leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                    ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                    ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                    ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                    ->where('products.productID', $request->input('deferreddevice'))
                    ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                    ->where('masterstockgroup.stockpriceeffect', '0.00')
                    ->where('masterstockgroup.productqtyeffect', '0')
                    ->first();

                    if($planinsurance != "")
                    {
                        $insurance = product::leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                        ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                        ->where('masterstockgroup.stockpriceeffect', '0.00')
                        ->where('masterstockgroup.productqtyeffect', '0')
                        ->where('products.productID', $planinsurance)
                        ->first();

                        $insurancename= $insurance->productname;

                        if($insurance->staffbonustype == 'percentage_profitmargin')
                        {
                            $ppexgst = $insurance->ppexgst;
                            $spexgst = $insurance->spexgst;

                            $diffprofit = $spexgst - $ppexgst;

                            $profitmargin = $diffprofit * $insurance->staffbonus / 100;
                            $inscomission = $profitmargin * 1;
                        }
                        else if($insurance->staffbonustype == 'percentage_saleprice')
                        {
                            $spingst = $insurance->spingst;
                            $profitmargin = $spingst * $insurance->staffbonus / 100;
                            $inscomission = $profitmargin * 1;
                        }
                        else if($insurance->staffbonustype == 'percentage_dealermargin')
                        {
                            $dealermargin = $insurance->dealermargin;
                            $profitmargin = $dealermargin * $insurance->staffbonus / 100;
                            $inscomission = $profitmargin * 1;
                        }
                        else if($insurance->staffbonustype == 'fixed')
                        {
                            $staffbonus = $insurance->staffbonus;
                            $inscomission = $staffbonus * 1;
                        }
                        else
                        {
                            $inscomission = 0.00;
                        }

                        $insertinsurance = new orderitem;
                        $insertinsurance->orderID = $orderid;
                        $insertinsurance->productID = $planinsurance;
                        $insertinsurance->planOrderID = $ordernumber;
                        $insertinsurance->planFullfillmentOrderid = $fulfilmentorder;
                        $insertinsurance->planMobilenumber= $mobilenumber;
                        $insertinsurance->quantity = '1';
                        $insertinsurance->stockgroup = $insurance->stockgroupID;
                        $insertinsurance->ppingst = $insurance->ppingst;
                        $insertinsurance->spingst = $insurance->spingst;
                        $insertinsurance->salePrice = $insurance->spingst;
                        $insertinsurance->subTotal = $insurance->spingst;
                        $insertinsurance->Comission = $inscomission;
                        $insertinsurance->save();

                        if($insertinsurance->save())
                        {
                            $insuranceid = $insertinsurance->orderitemID;
                        }
                        else
                        {
                            $insuranceid = '';   
                        }
                    }
                    else
                    {
                        $insurancename= "";
                        $insuranceid = '';
                    }

                    $getplan = plan::where('planID', $planid)
                    ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'planhandsetterm')
                    ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
                    ->first();
                    $planname= $getplan->planname;

                    $getstockgroup = masterstockgroup::find($stockgroup);
                    $stockgroupname = $getstockgroup->stockgroupname;
                    $stockpriceeffect = $getstockgroup->stockpriceeffect;

                    if($stockpriceeffect=='sellingprice')
                    {
                        $planppingst= $getplan->ppingst;
                        $planspinget = $getplan->ppingst;
                    }
                    else
                    {
                        $planppingst = $getplan->ppingst;
                        $planspinget= $getstockgroup->stockpriceeffect;
                    }

                    /*******Comission Calulation*****/
                    if($product->producttype == "")
                    {
                        if($product->staffbonustype == 'percentage_profitmargin')
                        {
                            $ppexgst = $product->ppexgst;
                            $spexgst = $product->spexgst;

                            $diffprofit = $spexgst - $ppexgst;

                            $profitmargin = $diffprofit * $product->staffbonus / 100;
                            $comission = $profitmargin * 1;
                        }
                        else if($product->staffbonustype == 'percentage_saleprice')
                        {
                            $spingst = $product->spingst;
                            $profitmargin = $spingst * $product->staffbonus / 100;
                            $comission = $profitmargin * 1;
                        }
                        else if($product->staffbonustype == 'percentage_dealermargin')
                        {
                            $dealermargin = $product->dealermargin;
                            $profitmargin = $dealermargin * $product->staffbonus / 100;
                            $comission = $profitmargin * 1;
                        }
                        else if($product->staffbonustype == 'fixed')
                        {
                            $staffbonus = $product->staffbonus;
                            $comission = $staffbonus * 1;
                        }
                        else
                        {
                            $comission = 0.00;
                        }
                    }
                    else
                    {
                        if($getplan->planbonustype == 'percentage')
                        {
                            $comission = $getplan->plancomission * $getplan->planbonus / 100;
                        }
                        else if($getplan->planbonustype == 'fixed')
                        {
                            $comission = $getplan->planbonus;
                        }
                        else
                        {
                            $comission = 0.00;
                        }
                    }
                    /*******Comission Calulation*****/

                    $plandetails = "Item: ".$product->productname." - ".$product->brandname." - ".$product->colourname." - ".$product->modelname." - ".$product->stockgroupname." , Order Num: ".$ordernumber.", Ful. Order: ".$fulfilmentorder.", Number: ".$mobilenumber.", H.T: ".$getplan->planhandsettermduration.", P.T: ".$getplan->plantermname.", ".$insurancename;

                    $insertorderplanitem = new orderitem;
                    $insertorderplanitem->orderID = $orderid;
                    $insertorderplanitem->productID = $product->productID;
                    
                    $insertorderplanitem->planID = $planid;
                    $insertorderplanitem->planOrderID = $ordernumber;
                    $insertorderplanitem->planFullfillmentOrderid = $fulfilmentorder;
                    $insertorderplanitem->planMobilenumber = $mobilenumber;
                    $insertorderplanitem->plandetails = $plandetails;
                    $insertorderplanitem->planInsurance = $insuranceid;
                    $insertorderplanitem->quantity = '1';
                    $insertorderplanitem->stockgroup = $stockgroup;
                    $insertorderplanitem->ppingst = $planppingst;
                    $insertorderplanitem->spingst = $planspinget;
                    $insertorderplanitem->salePrice = $planspinget;
                    $insertorderplanitem->subTotal = $planspinget;
                    $insertorderplanitem->Comission = $comission;

                    $insertorderplanitem->save();

                    if($insertorderplanitem->save())
                    {
                        return redirect()->back()->with('success', 'Plan added to invoice');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Something went wrong. Please try again');
                    }
                }
                else
                {
                    $checkorder = orderitem::where('orderID', $orderid)->where('planOrderID', $ordernumber)->count();

                    if($checkorder == 0)
                    {
                        return redirect()->back()->with('error', 'Plan order number already associated with another invoice. Please enter a unique plan order number');
                    }
                    else
                    {
                        $product = product::leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                        ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                        ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                        ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                        ->where('products.productID', $request->input('deferreddevice'))
                        ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                        ->where('masterstockgroup.stockpriceeffect', '0.00')
                        ->where('masterstockgroup.productqtyeffect', '0')
                        ->first();

                        if($planinsurance != "")
                        {
                            $insurance = product::leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                            ->where('masterstockgroup.stockpriceeffect', '0.00')
                            ->where('masterstockgroup.productqtyeffect', '0')
                            ->where('products.productID', $planinsurance)
                            ->first();

                            $insurancename= $insurance->productname;

                            if($insurance->staffbonustype == 'percentage_profitmargin')
                            {
                                $ppexgst = $insurance->ppexgst;
                                $spexgst = $insurance->spexgst;

                                $diffprofit = $spexgst - $ppexgst;

                                $profitmargin = $diffprofit * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'percentage_saleprice')
                            {
                                $spingst = $insurance->spingst;
                                $profitmargin = $spingst * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'percentage_dealermargin')
                            {
                                $dealermargin = $insurance->dealermargin;
                                $profitmargin = $dealermargin * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'fixed')
                            {
                                $staffbonus = $insurance->staffbonus;
                                $inscomission = $staffbonus * 1;
                            }
                            else
                            {
                                $inscomission = 0.00;
                            }

                            $insertinsurance = new orderitem;
                            $insertinsurance->orderID = $orderid;
                            $insertinsurance->productID = $planinsurance;
                            $insertinsurance->planOrderID = $ordernumber;
                            $insertinsurance->planFullfillmentOrderid = $fulfilmentorder;
                            $insertinsurance->planMobilenumber= $mobilenumber;
                            $insertinsurance->quantity = '1';
                            $insertinsurance->stockgroup = $insurance->stockgroupID;
                            $insertinsurance->ppingst = $insurance->ppingst;
                            $insertinsurance->spingst = $insurance->spingst;
                            $insertinsurance->salePrice = $insurance->spingst;
                            $insertinsurance->subTotal = $insurance->spingst;
                            $insertinsurance->Comission = $inscomission;
                            $insertinsurance->save();

                            if($insertinsurance->save())
                            {
                                $insuranceid = $insertinsurance->orderitemID;
                            }
                            else
                            {
                                $insuranceid = '';   
                            }
                        }
                        else
                        {
                            $insurancename= "";
                            $insuranceid = '';
                        }

                        $getplan = plan::where('planID', $planid)
                        ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'planhandsetterm')
                        ->leftJoin('masterplanterm', 'masterplanterm.plantermID', '=', 'plan.planterm')
                        ->first();
                        $planname= $getplan->planname;

                        $getstockgroup = masterstockgroup::find($stockgroup);
                        $stockgroupname = $getstockgroup->stockgroupname;
                        $stockpriceeffect = $getstockgroup->stockpriceeffect;

                        if($stockpriceeffect=='sellingprice')
                        {
                            $planppingst= $getplan->ppingst;
                            $planspinget = $getplan->ppingst;
                        }
                        else
                        {
                            $planppingst = $getplan->ppingst;
                            $planspinget= $getstockgroup->stockpriceeffect;
                        }

                        /*******Comission Calulation*****/
                        if($product->producttype == "")
                        {
                            if($product->staffbonustype == 'percentage_profitmargin')
                            {
                                $ppexgst = $product->ppexgst;
                                $spexgst = $product->spexgst;

                                $diffprofit = $spexgst - $ppexgst;

                                $profitmargin = $diffprofit * $product->staffbonus / 100;
                                $comission = $profitmargin * 1;
                            }
                            else if($product->staffbonustype == 'percentage_saleprice')
                            {
                                $spingst = $product->spingst;
                                $profitmargin = $spingst * $product->staffbonus / 100;
                                $comission = $profitmargin * 1;
                            }
                            else if($product->staffbonustype == 'percentage_dealermargin')
                            {
                                $dealermargin = $product->dealermargin;
                                $profitmargin = $dealermargin * $product->staffbonus / 100;
                                $comission = $profitmargin * 1;
                            }
                            else if($product->staffbonustype == 'fixed')
                            {
                                $staffbonus = $product->staffbonus;
                                $comission = $staffbonus * 1;
                            }
                            else
                            {
                                $comission = 0.00;
                            }
                        }
                        else
                        {
                            if($getplan->planbonustype == 'percentage')
                            {
                                $comission = $getplan->plancomission * $getplan->planbonus / 100;
                            }
                            else if($getplan->planbonustype == 'fixed')
                            {
                                $comission = $getplan->planbonus;
                            }
                            else
                            {
                                $comission = 0.00;
                            }
                        }
                        /*******Comission Calulation*****/

                        $plandetails = "Device: ".$product->productname.", Deferred".", Order Num: ".$ordernumber.", Ful. Order: ".$fulfilmentorder.", Number: ".$mobilenumber.", H.T: ".$getplan->planhandsettermduration.", P.T: ".$getplan->plantermname.", ".$insurancename;

                        $insertorderplanitem = new orderitem;
                        $insertorderplanitem->orderID = $orderid;
                        $insertorderplanitem->productID = $product->productID;
                        
                        $insertorderplanitem->planID = $planid;
                        $insertorderplanitem->planOrderID = $ordernumber;
                        $insertorderplanitem->planFullfillmentOrderid = $fulfilmentorder;
                        $insertorderplanitem->planMobilenumber = $mobilenumber;
                        $insertorderplanitem->plandetails = $plandetails;
                        $insertorderplanitem->planInsurance = $insuranceid;
                        $insertorderplanitem->quantity = '1';
                        $insertorderplanitem->stockgroup = $stockgroup;
                        $insertorderplanitem->ppingst = $planppingst;
                        $insertorderplanitem->spingst = $planspinget;
                        $insertorderplanitem->salePrice = $planspinget;
                        $insertorderplanitem->subTotal = $planspinget;
                        $insertorderplanitem->Comission = $comission;

                        $insertorderplanitem->save();

                        if($insertorderplanitem->save())
                        {
                            return redirect()->back()->with('success', 'Plan added to invoice');
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Something went wrong. Please try again');
                        }

                    }
                }
            }
            else
            {
                $checkplanorder = orderitem::where('planOrderID', $ordernumber)->count();

                if($checkplanorder == 0)
                {
                    $getplan = plan::find($planid);
                    $planname= $getplan->planname;

                    if($planinsurance != "")
                    {
                        $insurance = product::leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                        ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                        ->where('masterstockgroup.stockpriceeffect', '0.00')
                        ->where('masterstockgroup.productqtyeffect', '0')
                        ->where('products.productID', $planinsurance)
                        ->first();

                        $insurancename= $insurance->productname;

                        if($insurance->staffbonustype == 'percentage_profitmargin')
                        {
                            $ppexgst = $insurance->ppexgst;
                            $spexgst = $insurance->spexgst;

                            $diffprofit = $spexgst - $ppexgst;

                            $profitmargin = $diffprofit * $insurance->staffbonus / 100;
                            $inscomission = $profitmargin * 1;
                        }
                        else if($insurance->staffbonustype == 'percentage_saleprice')
                        {
                            $spingst = $insurance->spingst;
                            $profitmargin = $spingst * $insurance->staffbonus / 100;
                            $inscomission = $profitmargin * 1;
                        }
                        else if($insurance->staffbonustype == 'percentage_dealermargin')
                        {
                            $dealermargin = $insurance->dealermargin;
                            $profitmargin = $dealermargin * $insurance->staffbonus / 100;
                            $inscomission = $profitmargin * 1;
                        }
                        else if($insurance->staffbonustype == 'fixed')
                        {
                            $staffbonus = $insurance->staffbonus;
                            $inscomission = $staffbonus * 1;
                        }
                        else
                        {
                            $inscomission = 0.00;
                        }

                        $insertinsurance = new orderitem;
                        $insertinsurance->orderID = $orderid;
                        $insertinsurance->productID = $planinsurance;
                        $insertinsurance->planOrderID = $ordernumber;
                        $insertinsurance->planFullfillmentOrderid = $fulfilmentorder;
                        $insertinsurance->planMobilenumber= $mobilenumber;
                        $insertinsurance->quantity = '1';
                        $insertinsurance->stockgroup = $insurance->stockgroupID;
                        $insertinsurance->ppingst = $insurance->ppingst;
                        $insertinsurance->spingst = $insurance->spingst;
                        $insertinsurance->salePrice = $insurance->spingst;
                        $insertinsurance->subTotal = $insurance->spingst;
                        $insertinsurance->Comission = $inscomission;
                        $insertinsurance->save();

                        if($insertinsurance->save())
                        {
                            $insuranceid = $insertinsurance->orderitemID;
                        }
                        else
                        {
                            $insuranceid = '';   
                        }
                    }
                    else
                    {
                        $insurancename= "";
                        $insuranceid = '';
                    }

                    $getstockgroup = masterstockgroup::find($stockgroup);
                    $stockgroupname = $getstockgroup->stockgroupname;
                    $stockpriceeffect = $getstockgroup->stockpriceeffect;

                    if($stockpriceeffect=='sellingprice')
                    {
                        $planppingst= $getplan->ppingst;
                        $planspinget = $getplan->ppingst;
                    }
                    else
                    {
                        $planppingst = $getplan->ppingst;
                        $planspinget= $getstockgroup->stockpriceeffect;
                    }

                    /*******Comission Calulation*****/
                    if($getplan->planbonustype == 'percentage')
                    {
                        $comission = $getplan->plancomission * $getplan->planbonus / 100;
                    }
                    else if($getplan->planbonustype == 'fixed')
                    {
                        $comission = $getplan->planbonus;
                    }
                    else
                    {
                        $comission = 0.00;
                    }
                    /*******Comission Calulation*****/

                    $plandetails = "Order Num: ".$ordernumber.", Ful. Order: ".$fulfilmentorder.", Number: ".$mobilenumber.", ".$insurancename;

                    $insertorderplanitem = new orderitem;
                    $insertorderplanitem->orderID = $orderid;
                    $insertorderplanitem->planID = $planid;
                    $insertorderplanitem->planOrderID = $ordernumber;
                    $insertorderplanitem->planFullfillmentOrderid = $fulfilmentorder;
                    $insertorderplanitem->planMobilenumber = $mobilenumber;
                    $insertorderplanitem->plandetails = $plandetails;
                    $insertorderplanitem->planInsurance = $insuranceid;
                    $insertorderplanitem->quantity = '1';
                    $insertorderplanitem->stockgroup = $stockgroup;
                    $insertorderplanitem->ppingst = $planppingst;
                    $insertorderplanitem->spingst = $planspinget;
                    $insertorderplanitem->salePrice = $planspinget;
                    $insertorderplanitem->subTotal = $planspinget;
                    $insertorderplanitem->Comission = $comission;

                    $insertorderplanitem->save();

                    if($insertorderplanitem->save())
                    {
                        return redirect()->back()->with('success', 'Plan Added To Order');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Something Went Wrong. Please re-try again.');
                    }
                }
                else
                {
                    $checkorder = orderitem::where('orderID', $orderid)->where('planOrderID', $ordernumber)->count();

                    if($checkorder == 0)
                    {
                        return redirect()->back()->with('error', 'Plan order number already associated with another invoice. Please enter a unique plan order number');
                    }
                    else
                    {
                        $getplan = plan::find($planid);
                        $planname= $getplan->planname;

                        if($planinsurance != "")
                        {
                            $insurance = product::leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                            ->where('masterstockgroup.stockpriceeffect', '0.00')
                            ->where('masterstockgroup.productqtyeffect', '0')
                            ->where('products.productID', $planinsurance)
                            ->first();

                            $insurancename= $insurance->productname;

                            if($insurance->staffbonustype == 'percentage_profitmargin')
                            {
                                $ppexgst = $insurance->ppexgst;
                                $spexgst = $insurance->spexgst;

                                $diffprofit = $spexgst - $ppexgst;

                                $profitmargin = $diffprofit * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'percentage_saleprice')
                            {
                                $spingst = $insurance->spingst;
                                $profitmargin = $spingst * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'percentage_dealermargin')
                            {
                                $dealermargin = $insurance->dealermargin;
                                $profitmargin = $dealermargin * $insurance->staffbonus / 100;
                                $inscomission = $profitmargin * 1;
                            }
                            else if($insurance->staffbonustype == 'fixed')
                            {
                                $staffbonus = $insurance->staffbonus;
                                $inscomission = $staffbonus * 1;
                            }
                            else
                            {
                                $inscomission = 0.00;
                            }

                            $insertinsurance = new orderitem;
                            $insertinsurance->orderID = $orderid;
                            $insertinsurance->productID = $planinsurance;
                            $insertinsurance->planOrderID = $ordernumber;
                            $insertinsurance->planFullfillmentOrderid = $fulfilmentorder;
                            $insertinsurance->planMobilenumber= $mobilenumber;
                            $insertinsurance->quantity = '1';
                            $insertinsurance->stockgroup = $insurance->stockgroupID;
                            $insertinsurance->ppingst = $insurance->ppingst;
                            $insertinsurance->spingst = $insurance->spingst;
                            $insertinsurance->salePrice = $insurance->spingst;
                            $insertinsurance->subTotal = $insurance->spingst;
                            $insertinsurance->Comission = $inscomission;
                            $insertinsurance->save();

                            if($insertinsurance->save())
                            {
                                $insuranceid = $insertinsurance->orderitemID;
                            }
                            else
                            {
                                $insuranceid = '';   
                            }
                        }
                        else
                        {
                            $insurancename= "";
                            $insuranceid = '';
                        }

                        $getstockgroup = masterstockgroup::find($stockgroup);
                        $stockgroupname = $getstockgroup->stockgroupname;
                        $stockpriceeffect = $getstockgroup->stockpriceeffect;

                        if($stockpriceeffect=='sellingprice')
                        {
                            $planppingst= $getplan->ppingst;
                            $planspinget = $getplan->ppingst;
                        }
                        else
                        {
                            $planppingst = $getplan->ppingst;
                            $planspinget= $getstockgroup->stockpriceeffect;
                        }

                        /*******Comission Calulation*****/
                        if($getplan->planbonustype == 'percentage')
                        {
                            $comission = $getplan->plancomission * $getplan->planbonus / 100;
                        }
                        else if($getplan->planbonustype == 'fixed')
                        {
                            $comission = $getplan->planbonus;
                        }
                        else
                        {
                            $comission = 0.00;
                        }
                        /*******Comission Calulation*****/

                        $plandetails = "Order Num: ".$ordernumber.", Ful. Order: ".$fulfilmentorder.", Number: ".$mobilenumber.", ".$insurancename;

                        $insertorderplanitem = new orderitem;
                        $insertorderplanitem->orderID = $orderid;
                        $insertorderplanitem->planID = $planid;
                        $insertorderplanitem->planOrderID = $ordernumber;
                        $insertorderplanitem->planFullfillmentOrderid = $fulfilmentorder;
                        $insertorderplanitem->planMobilenumber = $mobilenumber;
                        $insertorderplanitem->plandetails = $plandetails;
                        $insertorderplanitem->planInsurance = $insuranceid;
                        $insertorderplanitem->quantity = '1';
                        $insertorderplanitem->stockgroup = $stockgroup;
                        $insertorderplanitem->ppingst = $planppingst;
                        $insertorderplanitem->spingst = $planspinget;
                        $insertorderplanitem->salePrice = $planspinget;
                        $insertorderplanitem->subTotal = $planspinget;
                        $insertorderplanitem->Comission = $comission;

                        $insertorderplanitem->save();

                        if($insertorderplanitem->save())
                        {
                            return redirect()->back()->with('success', 'Plan Added To Order');
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Something Went Wrong. Please re-try again.');
                        }
                    }
                }
            }
        }
    }

    public function addimeinumber(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
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
                    $invoicenumber = $request->input('invoicenumber');

                    $getproduct= productstock::where('productimei', $number)
                    ->where('productquantity', '!=', '0')
                    ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
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
                        ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                        ->leftJoin('products', 'products.productID', '=', 'productstock.productID')
                        ->where('products.producttype', $producttype)
                        ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                        ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
                        ->where('stockpriceeffect', 'sellingprice')
                        ->where('productqtyeffect', '1')
                        ->leftJoin('masterproducttype', 'masterproducttype.producttypeID', '=', 'products.producttype')
                        ->first();

                        /******GST Calculation******/
                        $gstpercent = $getproduct->spgst;
                        $spingst = $getproduct->spingst;

                        $gstamount = $spingst * $gstpercent / 100;
                        $gstamountotal = $gstamount;
                        /******GST Calculation******/

                        /******Commission Calculation******/
                        if($getproduct->staffbonustype == 'percentage_profitmargin')
                        {
                            $ppexgst = $getproduct->ppexgst;
                            $gstsaleprice = $salePrice;
                            $salepricetax = $getproduct->spgst;

                            $salepriceexgst = $gstsaleprice - $gstsaleprice * $salepricetax / 100;

                            $diffprofit = $salepriceexgst - $ppexgst;

                            $profitmargin = $diffprofit * $getproduct->staffbonus / 100;
                            $comission = $profitmargin * 1;
                        }
                        else if($getproduct->staffbonustype == 'percentage_saleprice')
                        {
                            $spingst = $salePrice;
                            $profitmargin = $spingst * $getproduct->staffbonus / 100;
                            $comission = $profitmargin * 1;
                        }
                        else if($getproduct->staffbonustype == 'percentage_dealermargin')
                        {
                            $dealermargin = $getproduct->dealermargin;
                            $profitmargin = $dealermargin * $getproduct->staffbonus / 100;
                            $comission = $profitmargin * 1;
                        }
                        else if($getproduct->staffbonustype == 'fixed')
                        {
                            $staffbonus = $getproduct->staffbonus;
                            $comission = $staffbonus * 1;
                        }
                        else
                        {
                            $comission = 0.00;
                        }

                        /******Comission Calculation******/

                        $insertorderitem = new orderitem;
                        $insertorderitem->orderID = $invoicenumber;
                        $insertorderitem->productID = $getproduct->productID;
                        $insertorderitem->stockID = $getproduct->psID;
                        $insertorderitem->quantity = '1';
                        $insertorderitem->stockgroup = $getproduct->stockgroupID;
                        $insertorderitem->ppingst = $getproduct->ppingst;
                        $insertorderitem->spingst = $getproduct->spingst;
                        $insertorderitem->salePrice = $getproduct->spingst;
                        $insertorderitem->gstamount = $gstamountotal;
                        $insertorderitem->subTotal = $getproduct->spingst;
                        $insertorderitem->Comission = $comission;
                        $insertorderitem->save();

                        if($insertorderitem->save())
                        {
                            $getproduct->productquantity = '0';
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
    }

    public function orderpayment(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'payingamount'=>'required',
                'invoiceid'=>'required',
                'totalitemamount'=>'required',
                'paymenttype'=>'required',
                'saletype'=>'required'
                ],[
                    'payingamount.required'=>'Paying Amount cannot be empty',
                    'invoiceid.required'=>'Failed to get invoice number',
                    'totalitemamount.required'=>'Failed to get total items amount',
                    'paymenttype.required'=>'Payment type cannot be empty',
                    'saletype.required'=>'Please select sale type'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    $payingamount = $request->input('payingamount');
                    $invoiceid = $request->input('invoiceid');
                    $totalitemamount = $request->input('totalitemamount');
                    $paymenttype= $request->input('paymenttype');
                    $saletype = $request->input('saletype');

                    $checkcustomer = orderdetail::where('orderID', $invoiceid)->first();

                    if($checkcustomer->customerID != "")
                    {
                        $checkpayment = orderpayments::where('orderID', $invoiceid)->count();

                        $orderpayment = orderpayments::where('orderID', $invoiceid)->sum('paidAmount');

                        if($checkpayment > 0)
                        {
                            if($orderpayment == $totalitemamount)
                            {
                                return redirect()->back()->with('error', 'You have processed complete amount of invoice. You cannot take higher amount than invoice amount. (PaymentProcess-ErrorCode - 01)');
                            }
                            else
                            {
                                $actualremainingamount = $totalitemamount - $orderpayment;
                                //return $actualremainingamount;
                                if($actualremainingamount >= $payingamount)
                                { 
                                    $updatesaletype = orderdetail::where('orderID', $invoiceid)->first();
                                    $updatesaletype->orderType = $saletype;
                                    $updatesaletype->save();

                                    $insertorderpayment = new orderpayments;
                                    $insertorderpayment->orderID = $invoiceid;
                                    $insertorderpayment->paymentType = $paymenttype;
                                    $insertorderpayment->paidAmount = $payingamount;
                                    $insertorderpayment->save();

                                    if($insertorderpayment->save())
                                    {
                                        if($actualremainingamount == $payingamount)
                                        {
                                            //return $actualremainingamount.":".$payingamount;
                                            $updatesaletype->orderstatus = '1';
                                            $updatesaletype->save();
                                            return redirect()->route('sale', ['id'=> $invoiceid]);
                                        }

                                        return redirect()->back()->with('success', 'Payment regarding the invoice is accepted. Split payment is detected. Process remaining amount (PaymentProcess-SuccessCode - 01)');
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('error', 'Payment regarding the invoice cannot be accepted. (PaymentProcess-ErrorCode - 02)');
                                    }
                                }
                                else
                                {
                                    return redirect()->back()->with('error', 'You cannot process more than remaining amount (PaymentProcess-ErrorCode - 05)');
                                }
                            }
                        }
                        else
                        {
                            if($payingamount == $totalitemamount)
                            {
                               $openconfirmpopup = '1';

                               $paymentdata = ['openconfirmpopup'=>$openconfirmpopup, 'payingamount'=>$payingamount, 'invoiceid'=>$invoiceid, 'totalitemamount'=>$totalitemamount, 'paymenttype'=>$paymenttype, 'saletype'=>$saletype];

                                return redirect()->back()->with('paymentdata', $paymentdata);
                            }
                            else
                            {
                                if($totalitemamount <= $payingamount)
                                {
                                    return redirect()->back()->with('error', 'Cannot process higher amount than invoice amount. (PaymentProcess-ErrorCode - 03)');
                                }
                                else
                                {
                                    $updatesaletype = orderdetail::where('orderID', $invoiceid)->first();
                                    $updatesaletype->orderType = $saletype;
                                    $updatesaletype->save();

                                    $insertorderpayment = new orderpayments;
                                    $insertorderpayment->orderID = $invoiceid;
                                    $insertorderpayment->paymentType = $paymenttype;
                                    $insertorderpayment->paidAmount = $payingamount;
                                    $insertorderpayment->save();

                                    if($insertorderpayment->save())
                                    {
                                        return redirect()->back()->with('success', 'Payment regarding the invoice is accepted. Split payment is detected. Process remaining amount (PaymentProcess-SuccessCode - 02)');
                                    }
                                    else
                                    {
                                        return redirect()->back()->with('error', 'Payment regarding the invoice cannot be accepted. (PaymentProcess-ErrorCode - 04)');
                                    }
                                } 
                            }
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Please select a customer. (PaymentProcess-ErrorCode - 036)');
                    }
                } 
            }
        }
    }

    public function confirmfullpayment(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'payingamount'=>'required',
                'invoiceid'=>'required',
                'totalitemamount'=>'required',
                'paymenttype'=>'required',
                'saletype'=>'required'
                ],[
                    'payingamount.required'=>'Paying Amount cannot be empty',
                    'invoiceid.required'=>'Failed to get invoice number',
                    'totalitemamount.required'=>'Failed to get total items amount',
                    'paymenttype.required'=>'Payment type cannot be empty',
                    'saletype.required'=>'Please select sale type'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    $payingamount = $request->input('payingamount');
                    $invoiceid = $request->input('invoiceid');
                    $totalitemamount = $request->input('totalitemamount');
                    $paymenttype= $request->input('paymenttype');
                    $saletype = $request->input('saletype');

                    $checkcustomer = orderdetail::where('orderID', $invoiceid)->first();

                    if($checkcustomer->customerID != "")
                    {
                        $checkpayment = orderpayments::where('orderID', $invoiceid)->count();

                        $orderpayment = orderpayments::where('orderID', $invoiceid)->sum('paidAmount');

                        if($checkpayment > 0)
                        {
                            return redirect()->back()->with('error', 'Payment regarding the invoice cannot be accepted. (PaymentProcess-ErrorCode - 06)');
                        }
                        else
                        {
                            if($payingamount == $totalitemamount)
                            {
                               $updatesaletype = orderdetail::where('orderID', $invoiceid)->first();
                                $updatesaletype->orderType = $saletype;
                                $updatesaletype->orderstatus = '1';
                                $updatesaletype->save();

                                $insertorderpayment = new orderpayments;
                                $insertorderpayment->orderID = $invoiceid;
                                $insertorderpayment->paymentType = $paymenttype;
                                $insertorderpayment->paidAmount = $payingamount;
                                $insertorderpayment->save();

                                if($insertorderpayment->save())
                                {
                                    return redirect()->route('sale', ['id'=> $invoiceid]);
                                }
                                else
                                {
                                    return redirect()->back()->with('error', 'Payment regarding the invoice cannot be accepted. (PaymentProcess-ErrorCode - 04)');
                                }
                            }
                            else
                            {
                                return redirect()->back()->with('error', 'Payment must be same as invoice amount. (PaymentProcess-ErrorCode - 06)');
                            }
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Please select a customer. (PaymentProcess-ErrorCode - 037)');
                    }
                } 
            }
        }
    }

    public function ajaxupdatesalenote(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            
            if($request->get('invoiceid'))
            {
              $invoiceid = $request->get('invoiceid');
              $username = $request->get('username');
              $data = orderdetail::where('orderID', $invoiceid)->first();
              $data->salenote = $username;
              $data->save();
            }
        }
    }

    public function calculatediscount(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'discount'=>'required'
                ],[
                    'discount.required'=>'Please enter discounted percentage'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    $discount = $request->input('discount');

                    $orderitem = orderitem::where('orderitemID', $request->input('itemid'))
                    ->whereNull('discountedType')
                    ->first();

                    if($orderitem != "")
                    {
                        $product = product::where('productID', $orderitem->productID)->first();

                        $getproductstockgroup= productstockgroup::where('productID', $product->productID)
                        ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', 'productstockgroup.stockgroupID')
                        ->where('stockpriceeffect', 'sellingprice')
                        ->where('productqtyeffect', '1')
                        ->where('stockgroupstatus', '1')
                        ->first();

                        $productstock = productstock::where('productID', $product->productID)->first();

                        if($request->input('discounttype') == 'Percentage')
                        {
                            $spingst = $orderitem->spingst;

                            $salePrice = $spingst - $spingst * $discount / 100;

                            $subtotal = $salePrice * $orderitem->quantity;

                            $discountAmount = ($spingst * $discount / 100) * $orderitem->quantity;

                            $gstamount = $subtotal * $product->spgst / 100;

                            $orderitem->discountedType = $request->input('discounttype');

                            /******Commission Calculation******/
                            if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                            {
                                $ppexgst = $productstock->ppexgst;
                                $gstsaleprice = $salePrice;
                                $salepricetax = $product->spgst;

                                $salepriceexgst = $gstsaleprice - $gstsaleprice * $salepricetax / 100;

                                $diffprofit = $salepriceexgst - $ppexgst;

                                $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                $comission = $profitmargin * $orderitem->quantity;
                            }
                            else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                            {
                                $spingst = $salePrice;
                                $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                $comission = $profitmargin * $orderitem->quantity;
                            }
                            else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                            {
                                $dealermargin = $getproductstockgroup->dealermargin;
                                $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                $comission = $profitmargin * $orderitem->quantity;
                            }
                            else if($getproductstockgroup->staffbonustype == 'fixed')
                            {
                                $staffbonus = $getproductstockgroup->staffbonus;
                                $comission = $staffbonus * $orderitem->quantity;
                            }
                            else
                            {
                                $comission = 0.00;
                            }

                            /******Comission Calculation******/

                            $orderitem->discount = $discount;
                            $orderitem->discountedAmount = $discountAmount;
                            $orderitem->salePrice = $salePrice;
                            $orderitem->gstamount = $gstamount;
                            $orderitem->subTotal = $subtotal;
                            $orderitem->Comission = $comission;
                            $orderitem->save();

                            return redirect()->back();
                        }
                        elseif($request->input('discounttype') == 'Amount')
                        {
                            $spingst = $orderitem->spingst;

                            $salePrice = $spingst - $discount;

                            $subtotal = $salePrice * $orderitem->quantity;

                            $discountAmount = $discount * $orderitem->quantity;

                            $gstamount = $subtotal * $product->spgst / 100;

                            $orderitem->discountedType = $request->input('discounttype');

                            /******Commission Calculation******/
                            if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                            {
                                $ppexgst = $productstock->ppexgst;
                                $gstsaleprice = $salePrice;
                                $salepricetax = $product->spgst;

                                $salepriceexgst = $gstsaleprice - $gstsaleprice * $salepricetax / 100;

                                $diffprofit = $salepriceexgst - $ppexgst;

                                $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                $comission = $profitmargin * $orderitem->quantity;
                            }
                            else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                            {
                                $spingst = $salePrice;
                                $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                $comission = $profitmargin * $orderitem->quantity;
                            }
                            else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                            {
                                $dealermargin = $getproductstockgroup->dealermargin;
                                $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                $comission = $profitmargin * $orderitem->quantity;
                            }
                            else if($getproductstockgroup->staffbonustype == 'fixed')
                            {
                                $staffbonus = $getproductstockgroup->staffbonus;
                                $comission = $staffbonus * $orderitem->quantity;
                            }
                            else
                            {
                                $comission = 0.00;
                            }

                            /******Comission Calculation******/

                            $orderitem->discount = $discount;
                            $orderitem->discountedAmount = $discountAmount;
                            $orderitem->salePrice = $salePrice;
                            $orderitem->gstamount = $gstamount;
                            $orderitem->subTotal = $subtotal;
                            $orderitem->Comission = $comission;
                            $orderitem->save();

                            return redirect()->back();
                        }
                    }
                    else
                    {
                        $orderitem = orderitem::where('orderitemID', $request->input('itemid'))
                        ->where('discountedType', $request->input('discounttype'))
                        ->first();

                        if($orderitem != "")
                        {
                            $product = product::where('productID', $orderitem->productID)->first();

                            $getproductstockgroup= productstockgroup::where('productID', $product->productID)
                            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', 'productstockgroup.stockgroupID')
                            ->where('stockpriceeffect', 'sellingprice')
                            ->where('productqtyeffect', '1')
                            ->where('stockgroupstatus', '1')
                            ->first();

                            $productstock = productstock::where('productID', $product->productID)->first();

                            if($request->input('discounttype') == 'Percentage')
                            {
                                $spingst = $orderitem->spingst;

                                $salePrice = $spingst - $spingst * $discount / 100;

                                $subtotal = $salePrice * $orderitem->quantity;

                                $discountAmount = ($spingst * $discount / 100) * $orderitem->quantity;

                                $gstamount = $subtotal * $product->spgst / 100;

                                $orderitem->discountedType = $request->input('discounttype');

                                /******Commission Calculation******/
                                if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                {
                                    $ppexgst = $productstock->ppexgst;
                                    $gstsaleprice = $salePrice;
                                    $salepricetax = $product->spgst;

                                    $salepriceexgst = $gstsaleprice - $gstsaleprice * $salepricetax / 100;

                                    $diffprofit = $salepriceexgst - $ppexgst;

                                    $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $orderitem->quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                {
                                    $spingst = $salePrice;
                                    $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $orderitem->quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                {
                                    $dealermargin = $getproductstockgroup->dealermargin;
                                    $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $orderitem->quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'fixed')
                                {
                                    $staffbonus = $getproductstockgroup->staffbonus;
                                    $comission = $staffbonus * $orderitem->quantity;
                                }
                                else
                                {
                                    $comission = 0.00;
                                }

                                /******Comission Calculation******/
                                $orderitem->discount = $discount;
                                $orderitem->discountedAmount = $discountAmount;
                                $orderitem->salePrice = $salePrice;
                                $orderitem->gstamount = $gstamount;
                                $orderitem->subTotal = $subtotal;
                                $orderitem->Comission = $comission;
                                $orderitem->save();

                                return redirect()->back();
                            }
                            elseif($request->input('discounttype') == 'Amount')
                            {
                                $spingst = $orderitem->spingst;

                                $salePrice = $spingst - $discount;

                                $subtotal = $salePrice * $orderitem->quantity;

                                $discountAmount = $discount * $orderitem->quantity;

                                $gstamount = $subtotal * $product->spgst / 100;

                                $orderitem->discountedType = $request->input('discounttype');

                                /******Commission Calculation******/
                                if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                {
                                    $ppexgst = $productstock->ppexgst;
                                    $gstsaleprice = $salePrice;
                                    $salepricetax = $product->spgst;

                                    $salepriceexgst = $gstsaleprice - $gstsaleprice * $salepricetax / 100;

                                    $diffprofit = $salepriceexgst - $ppexgst;

                                    $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $orderitem->quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                {
                                    $spingst = $salePrice;
                                    $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $orderitem->quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                {
                                    $dealermargin = $getproductstockgroup->dealermargin;
                                    $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $orderitem->quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'fixed')
                                {
                                    $staffbonus = $getproductstockgroup->staffbonus;
                                    $comission = $staffbonus * $orderitem->quantity;
                                }
                                else
                                {
                                    $comission = 0.00;
                                }

                                /******Comission Calculation******/
                                $orderitem->discount = $discount;
                                $orderitem->discountedAmount = $discountAmount;
                                $orderitem->salePrice = $salePrice;
                                $orderitem->gstamount = $gstamount;
                                $orderitem->subTotal = $subtotal;
                                $orderitem->Comission = $comission;
                                $orderitem->save();

                                return redirect()->back();
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Sorry you cant change discount mode. (ErrorCode - 30)');
                        }
                    }
                } 
            }
        }
    }

    public function updatequantity(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'quantity'=>'required'
                ],[
                    'quantity.required'=>'Please enter quantity'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    $orderitem = orderitem::where('orderitemID', $request->input('itemid'))->first();

                    $product = product::where('productID', $orderitem->productID)->first();

                    $productstock = productstock::where('psID', $orderitem->stockID)->first();

                    /****for differed and Recharge quantity update*****/

                    if($orderitem != "")
                    {
                        if($productstock == "")
                        {
                            if($orderitem->quantity > $request->input('quantity'))
                            {   
                                $diffquantity = $orderitem->quantity - $request->input('quantity');

                                $itemquantity = $orderitem->quantity - $diffquantity;

                                $subtotal = $orderitem->salePrice * $itemquantity;

                                if($orderitem->discountedType == 'Percentage')
                                {
                                    $discountAmount = ($orderitem->spingst * $orderitem->discount / 100) * $itemquantity;
                                }
                                else
                                {
                                    $discountAmount = $orderitem->discount * $itemquantity;
                                }

                                $gstamount = $subtotal * $product->spgst / 100;

                                $comission = $orderitem->Comission * $itemquantity;

                                $orderitem->discountedAmount = $discountAmount;
                                $orderitem->quantity = $itemquantity;
                                $orderitem->gstamount = $gstamount;
                                $orderitem->subTotal = $subtotal;
                                $orderitem->Comission = $comission;
                                $orderitem->save();

                                if($orderitem->save())
                                {
                                   return redirect()->back();
                                }
                            }
                            else
                            {
                                $diffquantity = $request->input('quantity') - $orderitem->quantity;
                                
                                $itemquantity = $orderitem->quantity + $diffquantity;

                                $subtotal = $orderitem->salePrice * $itemquantity;

                                if($orderitem->discountedType == 'Percentage')
                                {
                                    $discountAmount = ($orderitem->spingst * $orderitem->discount / 100) * $itemquantity;
                                }
                                else
                                {
                                    $discountAmount = $orderitem->discount * $itemquantity;
                                }

                                $gstamount = $subtotal * $product->spgst / 100;

                                $comission = $orderitem->Comission * $itemquantity; 

                                $orderitem->discountedAmount = $discountAmount;
                                $orderitem->quantity = $itemquantity;
                                $orderitem->gstamount = $gstamount;
                                $orderitem->subTotal = $subtotal;
                                $orderitem->Comission = $comission;
                                $orderitem->save();

                                if($orderitem->save())
                                {
                                   return redirect()->back();
                                }
                            }
                        }
                        else
                        {
                            if($orderitem->quantity > $request->input('quantity'))
                            {   
                                $diffquantity = $orderitem->quantity - $request->input('quantity');

                                $itemquantity = $orderitem->quantity - $diffquantity;

                                $stockquantity = $productstock->productquantity + $diffquantity;

                                $subtotal = $orderitem->salePrice * $itemquantity;

                                if($orderitem->discountedType == 'Percentage')
                                {
                                    $discountAmount = ($orderitem->spingst * $orderitem->discount / 100) * $itemquantity;
                                }
                                else
                                {
                                    $discountAmount = $orderitem->discount * $itemquantity;
                                }

                                $gstamount = $subtotal * $product->spgst / 100;

                                $comission = $orderitem->Comission * $itemquantity;

                                $orderitem->discountedAmount = $discountAmount;
                                $orderitem->quantity = $itemquantity;
                                $orderitem->gstamount = $gstamount;
                                $orderitem->subTotal = $subtotal;
                                $orderitem->Comission = $comission;
                                $orderitem->save();

                                if($orderitem->save())
                                {
                                   $productstock->productquantity = $stockquantity;
                                   $productstock->save(); 

                                   return redirect()->back();
                                }
                            }
                            else
                            {
                                $diffquantity = $request->input('quantity') - $orderitem->quantity;

                                if($productstock->productquantity >= $diffquantity)
                                {
                                    $itemquantity = $orderitem->quantity + $diffquantity;

                                    $stockquantity = $productstock->productquantity - $diffquantity;

                                    $subtotal = $orderitem->salePrice * $itemquantity;

                                    if($orderitem->discountedType == 'Percentage')
                                    {
                                        $discountAmount = ($orderitem->spingst * $orderitem->discount / 100) * $itemquantity;
                                    }
                                    else
                                    {
                                        $discountAmount = $orderitem->discount * $itemquantity;
                                    }

                                    $gstamount = $subtotal * $product->spgst / 100;

                                    $comission = $orderitem->Comission * $itemquantity; 

                                    $orderitem->discountedAmount = $discountAmount;
                                    $orderitem->quantity = $itemquantity;
                                    $orderitem->gstamount = $gstamount;
                                    $orderitem->subTotal = $subtotal;
                                    $orderitem->Comission = $comission;
                                    $orderitem->save();

                                    if($orderitem->save())
                                    {
                                       $productstock->productquantity = $stockquantity;
                                       $productstock->save(); 

                                       return redirect()->back();
                                    }
                                }
                                else
                                {
                                    return redirect()->back()->with('error', 'Not sufficient quantity (ErrorCode - 031)');
                                }
                            }
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'No item found (ErrorCode - 029)');
                    }
                } 
            }
        }
    }

    public function invoicedeleteitem(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'itemid'=>'required'
                ],[
                    'itemid.required'=>'Failed to fetch item id'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                   $orderitem = orderitem::where('orderitemID', $request->input('itemid'))->first();

                   if($orderitem != "")
                   {
                       if($orderitem->stockID != "")
                       {
                          $productstock = productstock::where('psID', $orderitem->stockID)->first();

                          $productstock->productquantity = $productstock->productquantity + $orderitem->quantity;
                          $productstock->save();

                          if($productstock->save())
                          {
                            $orderitem = orderitem::where('orderitemID', $request->input('itemid'))->delete();
                            return redirect()->back();
                          }
                          else
                          {
                            return redirect()->back()->with('error', 'Failed to remove item (ErrorCode - 33)');
                          }
                       }
                       else
                       {
                            $orderitem = orderitem::where('orderitemID', $request->input('itemid'))->delete();
                            return redirect()->back();
                       }
                   } 
                   else
                   {
                        return redirect()->back()->with('error', 'Failed to remove item (ErrorCode - 32)');
                   }
                } 
            }
        }
    }

    public function invoicedeleteplan(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
            }
            else
            {
               $validator = validator::make($request->all(),[
                'itemid'=>'required'
                ],[
                    'itemid.required'=>'Failed to fetch item id'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                   $orderitem = orderitem::where('orderitemID', $request->input('itemid'))->first();

                   if($orderitem != "")
                   {
                       if($orderitem->stockID != "")
                       {
                          $productstock = productstock::where('psID', $orderitem->stockID)->first();

                          $productstock->productquantity = $productstock->productquantity + $orderitem->quantity;
                          $productstock->save();

                          if($productstock->save())
                          {
                            if($orderitem->planInsurance != "")
                            {
                                $insurance = orderitem::where('orderitemID', $orderitem->planInsurance)->delete();
                            }

                            $orderitem = orderitem::where('orderitemID', $request->input('itemid'))->delete();
                            return redirect()->back();
                          }
                          else
                          {
                            return redirect()->back()->with('error', 'Failed to remove item (ErrorCode - 33)');
                          }
                       }
                       else
                       {
                            if($orderitem->planInsurance != "")
                            {
                                $insurance = orderitem::where('orderitemID', $orderitem->planInsurance)->delete();
                            }
                            
                            $orderitem = orderitem::where('orderitemID', $request->input('itemid'))->delete();
                            return redirect()->back();
                       }
                   } 
                   else
                   {
                        return redirect()->back()->with('error', 'Failed to remove item (ErrorCode - 32)');
                   }
                } 
            }
        }
    }

    public function addrecharge(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                return redirect('newsalestorechange');
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
                    $orderid = $request->input('orderid');
                    $quantity = $request->input('quantity');

                    $getproduct = product::where('productID', $productid)
                    ->where('productstatus', '1')
                    ->first();

                    $getproductstockgroup= productstockgroup::where('productID', $getproduct->productID)
                    ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', 'productstockgroup.stockgroupID')
                    ->where('stockpriceeffect', '0.00')
                    ->where('productqtyeffect', '0')
                    ->where('stockgroupstatus', '1')
                    ->first();

                    if($getproductstockgroup != '')
                    {
                        $orderdetail = orderdetail::where('orderID', $orderid)
                        ->where('storeID', session::get('loggindata.loggeduserstore.store_id'))
                        ->count();

                        if($orderdetail > 0)
                        {
                            $orderitem = orderitem::where('orderID', $orderid)
                            ->where('productID', $getproduct->productID)
                            ->first();

                            if($orderitem == "")
                            {
                                /******Subtotal Calculation******/
                                $subtotal = $getproduct->spingst * $quantity;
                                /******Subtotal Calculation******/

                                /******GST Calculation******/
                                $gstpercent = $getproduct->spgst;
                                $spingst = $getproduct->spingst;

                                $gstamount = $spingst * $gstpercent / 100;
                                $gstamountotal = $gstamount * $quantity;
                                /******GST Calculation******/

                                /******Commission Calculation******/
                                if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                {
                                    $ppexgst = $getproduct->ppexgst;
                                    $spexgst = $getproduct->spexgst;

                                    $diffprofit = $spexgst - $ppexgst;

                                    $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                {
                                    $spingst = $getproduct->spingst;
                                    $profitmargin = $spingst * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                {
                                    $dealermargin = $getproductstockgroup->dealermargin;
                                    $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'fixed')
                                {
                                    $staffbonus = $getproductstockgroup->staffbonus;
                                    $comission = $staffbonus * $quantity;
                                }
                                else
                                {
                                    $comission = 0.00;
                                }

                                /******Comission Calculation******/

                                $insertorderitem = new orderitem;
                                $insertorderitem->orderID = $orderid;
                                $insertorderitem->productID = $getproduct->productID;
                                $insertorderitem->quantity = $quantity;
                                $insertorderitem->stockgroup = $getproductstockgroup->stockgroupID;
                                $insertorderitem->ppingst = $getproduct->ppingst;
                                $insertorderitem->spingst = $getproduct->spingst;
                                $insertorderitem->salePrice = $getproduct->spingst;
                                $insertorderitem->gstamount = $gstamountotal;
                                $insertorderitem->subTotal = $subtotal;
                                $insertorderitem->Comission = $comission;
                                $insertorderitem->save();

                                if($insertorderitem->save())
                                {
                                    return redirect()->back()->with('success','Product added to invoice. (SuccessCode - 008)');
                                }
                                else
                                {
                                    return redirect()->back()->with('error','Failed to add product to invoice. (ErrorCode - 028)');
                                }
                            }
                            else
                            {
                                /******Subtotal Calculation******/
                                $subtotal = $orderitem->salePrice * $quantity;
                                /******Subtotal Calculation******/

                                /******GST Calculation******/
                                $gstpercent = $getproduct->spgst;
                                $salePrice = $orderitem->salePrice;

                                $gstamount = $salePrice * $gstpercent / 100;
                                $gstamountotal = $gstamount * $quantity;
                                /******GST Calculation******/

                                /******Commission Calculation******/
                                if($getproductstockgroup->staffbonustype == 'percentage_profitmargin')
                                {
                                    $CCppexgst = $getproduct->ppexgst;
                                    $CCspingst = $orderitem->salePrice;
                                    $CCsptax = $getproduct->spgst;

                                    $CCspexgst = $CCspingst - $CCspingst * $CCsptax / 100;

                                    $diffprofit = $CCspexgst - $CCppexgst;

                                    $profitmargin = $diffprofit * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'percentage_saleprice')
                                {
                                    $salePrice = $orderitem->salePrice;
                                    $profitmargin = $salePrice * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'percentage_dealermargin')
                                {
                                    $dealermargin = $getproductstockgroup->dealermargin;
                                    $profitmargin = $dealermargin * $getproductstockgroup->staffbonus / 100;
                                    $comission = $profitmargin * $quantity;
                                }
                                else if($getproductstockgroup->staffbonustype == 'fixed')
                                {
                                    $staffbonus = $getproductstockgroup->staffbonus;
                                    $comission = $staffbonus * $quantity;
                                }
                                else
                                {
                                    $comission = 0.00;
                                }

                                /******Comission Calculation******/

                                
                                $orderitem->quantity = $orderitem->quantity + $quantity;
                                $orderitem->gstamount = $orderitem->gstamount + $gstamountotal;
                                $orderitem->subTotal = $orderitem->subTotal + $subtotal;
                                $orderitem->Comission = $orderitem->Comission + $comission;
                                $orderitem->save();

                                if($orderitem->save())
                                {
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
                        return redirect()->back()->with('error','Something thing went wrong. (ErrorCode - 038)');
                    }
                } 
            }
        }
    } 
}
