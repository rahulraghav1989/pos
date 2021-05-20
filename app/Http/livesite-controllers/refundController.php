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
use App\orderitem;
use App\orderpayments;
use App\productstock;
use App\paymentoptions;
use App\refundorderdetail;
use App\refundorderitem;
use App\refundorderpayments;
use App\product;

class refundController extends Controller
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

    public function refunditem(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.refund')=='N' || session::get('loggindata.loggeduserpermission.refund')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'itemid'=>'required'
            ],[
                'itemid.required'=>'Please select a item to refund'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
            	if(!empty(session::get('loggindata.loggeduserstore')))
            	{
            		$storeID = session::get('loggindata.loggeduserstore.store_id');
            	}
            	else
            	{
            		$storeID = $request->input('storeid');
            	}

            	$checkorder = orderdetail::where('orderID', $request->input('invoiceid'))
                ->where('orderstatus', '1')
                ->where('storeID', $storeID)
                ->count();

                if($checkorder > 0)
                {
                	$checkorderitem = orderitem::where('orderID', $request->input('invoiceid'))->whereIn('orderitemID', $request->input('itemid'))->count();

                	if($checkorderitem > 0)
                	{
                        /*****REFUND INVOICE ID CREATION*****/
                        $dateforrefundinvoiceid = Carbon::now()->toDateTimeString();
                        $refundinvoiceidstoreid = store::where('store_id', $storeID)->first();
                        $refundinvoiceiduserid = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

                        $refundinvoiceidtobe = $dateforrefundinvoiceid.$refundinvoiceidstoreid->store_id.$refundinvoiceiduserid->id;
                        
                        $refundinvoiceid = 'R'.preg_replace("/[^A-Za-z0-9]/","",$refundinvoiceidtobe);
                        /*****REFUND INVOICE ID CREATION*****/

                        $orderdetail = orderdetail::where('orderID', $request->input('invoiceid'))
                        ->where('orderstatus', '1')
                        ->where('storeID', $storeID)
                        ->first();

                        $insertorderdetail = new refundorderdetail;
                        $insertorderdetail->refundInvoiceID = $refundinvoiceid;
                        $insertorderdetail->orderID = $orderdetail->orderID;
                        $insertorderdetail->orderType = $orderdetail->orderType;
                        $insertorderdetail->salenote = $orderdetail->salenote;
                        $insertorderdetail->customerID = $orderdetail->customerID;
                        $insertorderdetail->storeID = $orderdetail->storeID;
                        $insertorderdetail->userID = $orderdetail->userID;
                        $insertorderdetail->refundBy = session::get('loggindata.loggedinuser.id');
                        $insertorderdetail->orderDate = $orderdetail->orderDate;
                        $insertorderdetail->orderCreated_at = $orderdetail->created_at;
                        $insertorderdetail->refundMonth = date('m');
                        $insertorderdetail->refundYear = date('Y');
                        $insertorderdetail->refundDate = date('Y-m-d');
                        $insertorderdetail->refundStatus = '0';
                        $insertorderdetail->save();

                        if($insertorderdetail->save())
                        {
                            foreach($request->input('itemid') as $k => $v)
                            {
                                $invoiceitemID = $v;

                                $getorderitem = orderitem::where('orderID', $request->input('invoiceid'))
                                ->where('orderitemID', $invoiceitemID)
                                ->first();

                                $refundquantity = $request->input('refundquantity_'.$v);

                                if($getorderitem->discountedType == 'Percentage')
                                {
                                    $salePrice = $getorderitem->salePrice;

                                    $subtotal = $salePrice * $refundquantity;

                                    $discountAmount = ($getorderitem->spingst * $getorderitem->discount / 100) * $refundquantity;

                                    $gstamount = $getorderitem->gstamount / $request->input('soldquantity_'.$v);

                                    $totalgst = $gstamount * $refundquantity;
                                }
                                elseif($getorderitem->discountedType == 'Amount')
                                {
                                    $salePrice = $getorderitem->salePrice;

                                    //$salePrice = $spingst - $discount;

                                    $subtotal = $salePrice * $refundquantity;

                                    $discountAmount = $getorderitem->discount * $refundquantity;

                                    $gstamount = $getorderitem->gstamount / $request->input('soldquantity_'.$v);
                                    $totalgst = $gstamount * $refundquantity;
                                }
                                else
                                {
                                    $salePrice = $getorderitem->salePrice;
                                    $discountAmount = '';
                                    $subtotal = $salePrice * $refundquantity;
                                    $gstamount = $getorderitem->gstamount / $request->input('soldquantity_'.$v);
                                    $totalgst = $gstamount * $refundquantity;
                                }

                                if($getorderitem->quantity == $refundquantity)
                                {
                                    //$comission = 0.00;
                                    $comission1 = $getorderitem->Comission / $request->input('soldquantity_'.$v);
                                    $comission = $comission1 * $refundquantity;
                                }
                                else
                                {
                                    $comission1 = $getorderitem->Comission / $request->input('soldquantity_'.$v);
                                    $comission = $comission1 * $refundquantity;
                                }

                                $insertrefund = refundorderitem::insert(
                                [
                                    'refundInvoiceID'=> $refundinvoiceid, 
                                    'orderID'=>$getorderitem->orderID, 
                                    'productID'=>$getorderitem->productID, 
                                    'stockID'=>$getorderitem->stockID, 
                                    'planID'=>$getorderitem->planID,
                                    'planOrderID'=> $getorderitem->planOrderID,
                                    'planFullfillmentOrderid'=> $getorderitem->planFullfillmentOrderid,
                                    'planMobilenumber'=> $getorderitem->planMobilenumber,
                                    'planInsurance'=> $getorderitem->planInsurance,
                                    'plandetails'=> $getorderitem->plandetails,
                                    'discountedType'=> $getorderitem->discountedType,
                                    'discount'=> $getorderitem->discount,
                                    'discountedAmount'=> $discountAmount,
                                    'quantity'=> $refundquantity,
                                    'stockgroup'=> $getorderitem->stockgroup,
                                    'ppingst'=> $getorderitem->ppingst,
                                    'spingst'=> $getorderitem->spingst,
                                    'salePrice'=> $getorderitem->salePrice,
                                    'gstamount'=> $totalgst,
                                    'subTotal'=> $subtotal,
                                    'Comission'=>$comission,
                                    'refundReason'=>$request->input('reason_'.$v)
                                ]);

                                if($getorderitem->stockID != "")
                                {
                                    $updatestock = productstock::where('productID', $getorderitem->productID)
                                    ->where('psID', $getorderitem->stockID)
                                    ->first();
                                    $updatestock->productquantity = $updatestock->productquantity + $refundquantity;
                                    $updatestock->save();
                                }
                            }
                            return redirect()->route('refund', ['id'=> $refundinvoiceid]);
                        }
                        else
                        {
                            return redirect()->back()->with('error', 'Failed to create refund invoice. (ErrorCode - 003)');
                        }
                	}
                	else
                	{
                		return redirect()->back()->with('error', 'Invoice item not found. (ErrorCode - 002)');
                	}
                }
                else
                {
                	return redirect()->back()->with('error', 'Invoice detail not found. (ErrorCode - 001)');
                }
            }
        }
    }

    public function refundview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.refund')=='N' ||session::get('loggindata.loggeduserpermission.refund')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')=='')
            {
                $getorderdetails = refundorderdetail::where('refundInvoiceID', $id)
                ->leftJoin('customer', 'customer.customerID', '=', 'refundorderdetail.customerID')
                ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
                ->first();

                $getorderitems = refundorderitem::where('refundInvoiceID', $id)
                ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
                ->with('productstock')
                ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'refundorderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->get(array('refundorderitem.refunditemID', 'refundorderitem.refundInvoiceID', 'refundorderitem.orderID', 'refundorderitem.productID', 'refundorderitem.stockID', 'refundorderitem.planID', 'refundorderitem.planOrderID', 'refundorderitem.plandetails', 'refundorderitem.discountedType', 'refundorderitem.discount', 'refundorderitem.quantity', 'refundorderitem.ppingst', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.subTotal', 'products.stockcode', 'products.productname', 'products.barcode', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname'));

                $getorderitemssum = refundorderitem::where('refundInvoiceID', $id)->sum('subTotal');

                $getorderdiscountsum = refundorderitem::where('refundInvoiceID', $id)->sum('discountedAmount');

                $orderpaidamount = refundorderpayments::where('refundInvoiceID', $id)->sum('paidAmount');

                $paymentoptions = paymentoptions::where('paymentstatus', '1')
                ->whereIn('paymenttype', ['Offline', 'Online'])
                ->get();
                $paymentoptionaccount = paymentoptions::where('paymentstatus', '1')
                ->whereIn('paymenttype', ['Account'])
                ->get();

                $invoicedata = ['getorderdetails'=>$getorderdetails,'getorderitems'=>$getorderitems, 'paymentoptions'=>$paymentoptions, 'getorderitemssum'=>$getorderitemssum, 'orderpaidamount'=>$orderpaidamount, 'paymentoptionaccount'=>$paymentoptionaccount, 'getorderdiscountsum'=>$getorderdiscountsum];
                return view('refund')->with('invoicedata', $invoicedata);
            }
            else
            {
                $getorderdetails = refundorderdetail::where('refundInvoiceID', $id)
                ->leftJoin('customer', 'customer.customerID', '=', 'refundorderdetail.customerID')
                ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
                ->where('refundorderdetail.storeID', session::get('loggindata.loggeduserstore.store_id'))
                ->first();

                $getorderitems = refundorderitem::where('refundInvoiceID', $id)
                ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
                ->with('productstock')
                ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'refundorderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->get(array('refundorderitem.refunditemID', 'refundorderitem.refundInvoiceID', 'refundorderitem.orderID', 'refundorderitem.productID', 'refundorderitem.stockID', 'refundorderitem.planID', 'refundorderitem.planOrderID', 'refundorderitem.plandetails', 'refundorderitem.discountedType', 'refundorderitem.discount', 'refundorderitem.quantity', 'refundorderitem.ppingst', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.subTotal', 'products.stockcode', 'products.productname', 'products.barcode', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname'));

                //return $getorderitems;

                $getorderitemssum = refundorderitem::where('refundInvoiceID', $id)->sum('subTotal');

                $getorderdiscountsum = refundorderitem::where('refundInvoiceID', $id)->sum('discountedAmount');

                $orderpaidamount = refundorderpayments::where('refundInvoiceID', $id)->sum('paidAmount');

                
                $paymentoptions = paymentoptions::where('paymentstatus', '1')
                ->whereIn('paymenttype', ['Offline', 'Online'])
                ->get();
                $paymentoptionaccount = paymentoptions::where('paymentstatus', '1')
                ->whereIn('paymenttype', ['Account'])
                ->get();

                //return $productcategory;

                $invoicedata = ['getorderdetails'=>$getorderdetails,'getorderitems'=>$getorderitems, 'paymentoptions'=>$paymentoptions, 'getorderitemssum'=>$getorderitemssum, 'orderpaidamount'=>$orderpaidamount, 'paymentoptionaccount'=>$paymentoptionaccount, 'getorderdiscountsum'=>$getorderdiscountsum];
                return view('refund')->with('invoicedata', $invoicedata);
            }
        }
    }

    public function ajaxupdaterefundnote(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.refund')=='N' ||session::get('loggindata.loggeduserpermission.refund')=='')
        {
            return redirect('404');
        } 
        else
        {
            
            if($request->get('invoiceid'))
            {
              $invoiceid = $request->get('invoiceid');
              $username = $request->get('username');
              $data = refundorderdetail::where('refundInvoiceID', $invoiceid)->first();
              $data->refundnote = $username;
              $data->save();
            }
        }
    }

    public function updaterefundquantity(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.refund')=='N' ||session::get('loggindata.loggeduserpermission.refund')=='')
        {
            return redirect('404');
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
                $orderitem = refundorderitem::where('refunditemID', $request->input('itemid'))->first();

                $soldorderitem = orderitem::where('orderID', $orderitem->orderID)
                ->where('productID', $orderitem->productID)
                ->where('stockID', $orderitem->stockID)
                ->first();

                if($soldorderitem->quantity >= $request->input('quantity') && $request->input('quantity') > '0')
                {
                    $product = product::where('productID', $orderitem->productID)->first();

                    $productstock = productstock::where('psID', $orderitem->stockID)->first();

                    if($orderitem != "")
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

                            $orderitem->discountedAmount = $discountAmount;
                            $orderitem->quantity = $itemquantity;
                            $orderitem->gstamount = $gstamount;
                            $orderitem->subTotal = $subtotal;
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

                                $orderitem->discountedAmount = $discountAmount;
                                $orderitem->quantity = $itemquantity;
                                $orderitem->gstamount = $gstamount;
                                $orderitem->subTotal = $subtotal;
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
                                return redirect()->back()->with('error', 'Not sufficient quantity (ErrorCode - 004)');
                            }
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'No item found (ErrorCode - 005)');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Refund quantity cannot be larger than sold quantity Or must be greater than 0 (ErrorCode - 006)');
                }
            }
        }
    }

    public function refundinvoiceitemdelete(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.refund')=='N' ||session::get('loggindata.loggeduserpermission.refund')=='')
        {
            return redirect('404');
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
               $orderitem = refundorderitem::where('refunditemID', $request->input('itemid'))->first();

               if($orderitem != "")
               {
                   $orderitem = refundorderitem::where('refunditemID', $request->input('itemid'))->first();

                   if($orderitem->stockID != "")
                   {
                    $updatestock = productstock::where('productID', $orderitem->productID)->where('psID', $orderitem->stockID)->first();
                    $updatestock->productquantity = $updatestock->productquantity - $request->input('quantity');
                    $updatestock->save();
                   }

                   $deleteorderitem = refundorderitem::where('refunditemID', $request->input('itemid'))->delete();

                   if($deleteorderitem == 1)
                   {
                        return redirect()->back();
                   }
                   else
                   {
                        return redirect()->back()->with('error', 'Failed to remove item (ErrorCode - 010)');
                   }
               } 
               else
               {
                    return redirect()->back()->with('error', 'Failed to remove item (ErrorCode - 007)');
               }
            }
        }
    }

    public function cancelrefund(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.refund')=='N' ||session::get('loggindata.loggeduserpermission.refund')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'refundinvoiceid'=>'required'
            ],[
                'refundinvoiceid.required'=>'Failed to fetch item id'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
               $refundorderdetail = refundorderdetail::where('refundInvoiceID', $request->input('refundinvoiceid'))->first();

               if($refundorderdetail != "")
               {
                $refundorderitem = refundorderitem::where('refundInvoiceID', $refundorderdetail->refundInvoiceID)->count();

                if($refundorderitem == 0)
                {
                    $refundorderdetail = refundorderdetail::where('refundInvoiceID', $request->input('refundinvoiceid'))->delete();

                    return redirect()->route('salehistory');
                }
                else
                {
                    return redirect()->back()->with('error', 'Please remove all items then try to cancel refund (ErrorCode - 009)');
                }
               }
               else
               {
                return redirect()->back()->with('error', 'No refund order found (ErrorCode - 008)');
               }
            }
        }
    }

    public function refundorderpayment(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'payingamount'=>'required',
            'refundinvoiceid'=>'required',
            'totalitemamount'=>'required',
            'paymenttype'=>'required',
            ],[
                'payingamount.required'=>'Paying Amount cannot be empty',
                'refundinvoiceid.required'=>'Failed to get invoice number',
                'totalitemamount.required'=>'Failed to get total items amount',
                'paymenttype.required'=>'Payment type cannot be empty'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $payingamount = $request->input('payingamount');
                $refundinvoiceid = $request->input('refundinvoiceid');
                $totalitemamount = $request->input('totalitemamount');
                $paymenttype= $request->input('paymenttype');

                $checkorder = refundorderdetail::where('refundInvoiceID', $refundinvoiceid)->first();

                if($checkorder->customerID != "")
                {
                    $checkpayment = refundorderpayments::where('refundInvoiceID', $refundinvoiceid)->count();

                    if($checkpayment > 0)
                    {
                        $orderpayment = refundorderpayments::where('refundInvoiceID', $refundinvoiceid)->sum('paidAmount');

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
                                $insertorderpayment = new refundorderpayments;
                                $insertorderpayment->refundInvoiceID = $refundinvoiceid;
                                $insertorderpayment->paymentType = $paymenttype;
                                $insertorderpayment->paidAmount = $payingamount;
                                $insertorderpayment->save();

                                if($insertorderpayment->save())
                                {
                                    if($actualremainingamount == $payingamount)
                                    {
                                        $updaterefundstatus = refundorderdetail::where('refundInvoiceID', $refundinvoiceid)->first();
                                        $updaterefundstatus->refundStatus = '1';
                                        $updaterefundstatus->save();
                                        //return redirect()->back();
                                        return redirect()->route('refundinvoice', ['id'=> $refundinvoiceid]);
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

                           $paymentdata = ['openconfirmpopup'=>$openconfirmpopup, 'payingamount'=>$payingamount, 'refundinvoiceid'=>$refundinvoiceid, 'totalitemamount'=>$totalitemamount, 'paymenttype'=>$paymenttype];

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
                                $insertorderpayment = new refundorderpayments;
                                $insertorderpayment->refundInvoiceID = $refundinvoiceid;
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

    public function refundconfirmfullpayment(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.newsale')=='N' ||session::get('loggindata.loggeduserpermission.newsale')=='')
        {
            return redirect('404');
        } 
        else
        {
            $validator = validator::make($request->all(),[
            'payingamount'=>'required',
            'refundinvoiceid'=>'required',
            'totalitemamount'=>'required',
            'paymenttype'=>'required',
            ],[
                'payingamount.required'=>'Paying Amount cannot be empty',
                'refundinvoiceid.required'=>'Failed to get invoice number',
                'totalitemamount.required'=>'Failed to get total items amount',
                'paymenttype.required'=>'Payment type cannot be empty'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $payingamount = $request->input('payingamount');
                $refundinvoiceid = $request->input('refundinvoiceid');
                $totalitemamount = $request->input('totalitemamount');
                $paymenttype= $request->input('paymenttype');

                $checkcustomer = refundorderdetail::where('refundInvoiceID', $refundinvoiceid)->first();

                if($checkcustomer->customerID != "")
                {
                    $checkpayment = refundorderpayments::where('refundInvoiceID', $refundinvoiceid)->count();

                    $orderpayment = refundorderpayments::where('refundInvoiceID', $refundinvoiceid)->sum('paidAmount');

                    if($checkpayment > 0)
                    {
                        return redirect()->back()->with('error', 'Payment regarding the invoice cannot be accepted. (PaymentProcess-ErrorCode - 06)');
                    }
                    else
                    {
                        if($payingamount == $totalitemamount)
                        {
                           $updaterefundstatus = refundorderdetail::where('refundInvoiceID', $refundinvoiceid)->first();
                            $updaterefundstatus->refundStatus = '1';
                            $updaterefundstatus->save();

                            $insertorderpayment = new refundorderpayments;
                            $insertorderpayment->refundInvoiceID = $refundinvoiceid;
                            $insertorderpayment->paymentType = $paymenttype;
                            $insertorderpayment->paidAmount = $payingamount;
                            $insertorderpayment->save();

                            if($insertorderpayment->save())
                            {
                                return redirect()->route('refundinvoice', ['id'=> $refundinvoiceid]);
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

    public function refundinvoiceview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.refund')=='N' ||session::get('loggindata.loggeduserpermission.refund')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')!='')
            {
                $saledetail = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
                ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
                ->leftJoin('productstock', 'productstock.psID', '=', 'refundorderitem.stockID')
                ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'refundorderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->with('customer')
                ->with('refundbyuser')
                ->with('orderpayment')
                ->leftJoin('users', 'users.id', '=', 'refundorderdetail.userID')
                ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
                ->where('refundorderdetail.refundInvoiceID', $id)
                ->where('store.store_id', session::get('loggindata.loggeduserstore.store_id'))
                ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderID', 'refundorderdetail.orderType', 'refundorderdetail.refundStatus', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.refundBy', 'refundorderdetail.created_at', 'refundorderitem.refunditemID', 'refundorderitem.productID', 'refundorderitem.stockID', 'refundorderitem.planID', 'refundorderitem.planOrderID', 'refundorderitem.plandetails', 'refundorderitem.discountedType', 'refundorderitem.discount', 'refundorderitem.quantity', 'refundorderitem.stockgroup', 'refundorderitem.ppingst', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.subTotal', 'products.productname', 'products.barcode', 'products.stockcode', 'products.colour', 'products.model', 'products.brand', 'plan.plantypeID', 'plan.planpropositionID', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'users.name', 'store.store_name', 'store.store_address', 'productstock.productimei', 'store.store_contact', 'store.store_email', 'users.username'));

                //return $saledetail;

                return view('refund-invoice')->with('saledetail', $saledetail);
            }
            else
            {
                $saledetail = refundorderdetail::leftJoin('refundorderitem', 'refundorderitem.refundInvoiceID', '=', 'refundorderdetail.refundInvoiceID')
                ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
                ->leftJoin('productstock', 'productstock.psID', '=', 'refundorderitem.stockID')
                ->leftJoin('plan', 'plan.planID', '=', 'refundorderitem.planID')
                ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'refundorderitem.stockgroup')
                ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
                ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
                ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
                ->with('customer')
                ->with('refundbyuser')
                ->with('orderpayment')
                ->leftJoin('users', 'users.id', '=', 'refundorderdetail.userID')
                ->leftJoin('store', 'store.store_id', '=', 'refundorderdetail.storeID')
                ->where('refundorderdetail.refundInvoiceID', $id)
                ->where('refundorderdetail.refundStatus', '1')
                ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.orderID', 'refundorderdetail.orderType', 'refundorderdetail.refundstatus', 'refundorderdetail.customerID', 'refundorderdetail.storeID', 'refundorderdetail.userID', 'refundorderdetail.refundBy', 'refundorderdetail.created_at', 'refundorderitem.refunditemID', 'refundorderitem.refundInvoiceID', 'refundorderitem.productID', 'refundorderitem.stockID', 'refundorderitem.planID', 'refundorderitem.planOrderID', 'refundorderitem.plandetails', 'refundorderitem.discountedType', 'refundorderitem.discount', 'refundorderitem.quantity', 'refundorderitem.stockgroup', 'refundorderitem.ppingst', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.subTotal', 'products.productname', 'products.barcode', 'products.stockcode', 'products.colour', 'products.model', 'products.brand', 'plan.plantypeID', 'plan.planpropositionID', 'plan.plancode', 'plan.planname', 'masterstockgroup.stockgroupname', 'masterbrand.brandname', 'mastercolour.colourname', 'mastermodel.modelname', 'users.name', 'store.store_name', 'store.store_address', 'productstock.productimei'));

                //return $saledetail;

                return view('refund-invoice')->with('saledetail', $saledetail);
            }
        }
    }
}
