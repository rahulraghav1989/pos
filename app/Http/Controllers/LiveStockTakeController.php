<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
use App\usergroup;
use App\store;
use App\storetype;
use App\masterstockgroup;
use App\mastercategory;
use App\mastersubcategory;
use App\masterplancategory;
use App\masterplantype;
use App\masterplanterm;
use App\masterproducttype;
use App\masterplanpropositiontype;
use App\masterplanhandsetterm;
use App\paymentoptions;
use App\mastercomission;
use App\plancomission;
use App\plancomissiontax;
use App\livestocktake;
use App\livestocktakeitems;
use App\product;
use App\productstock;

class LiveStockTakeController extends Controller
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

    public function livestocktake(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.livestocktake')=='N' ||session::get('loggindata.loggeduserpermission.livestocktake')=='')
        {
            return redirect('404');
        }
        else
        {
            if(count(session::get('loggindata.loggeduserstore')) == 0 || count(session::get('loggindata.loggeduserstore')) >= '2')
            {
                return view('livestocktake-store');
            }
            else
            {
                foreach(session::get('loggindata.loggeduserstore') as $storeid)
                {
                    $storeid = $storeid->store_id;
                }

                $datefororderid = Carbon::now()->toDateTimeString();
                $orderidstoreid = store::where('store_id', $storeid)->first();
                $orderiduserid = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

                $orderidtobe = $datefororderid.$orderidstoreid->store_id.$orderiduserid->id;
                
                $orderid = preg_replace("/[^A-Za-z0-9]/","",$orderidtobe);

                $insertlivestock = new livestocktake;
                $insertlivestock->istID = $orderid;
                $insertlivestock->storeID = $orderidstoreid->store_id;
                $insertlivestock->userID = session::get('loggindata.loggedinuser.id');
                $insertlivestock->save();

                if($insertlivestock->save())
                {
                    return redirect()->route('livestocktakecreate', ['id'=> $insertlivestock->istID]);
                }
                else
                {
                    return redirect()->back()->with('error', 'something went wrong');
                }
            }
        }
    }

    public function startlivestocktake(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.livestocktake')=='N' ||session::get('loggindata.loggeduserpermission.livestocktake')=='')
        {
            return redirect('404');
        }
        else
        {
            $storeid = $request->input('store');

            $datefororderid = Carbon::now()->toDateTimeString();
            $orderidstoreid = store::where('store_id', $storeid)->first();
            $orderiduserid = loggeduser::where('id', session::get('loggindata.loggedinuser.id'))->first();

            $orderidtobe = $datefororderid.$orderidstoreid->store_id.$orderiduserid->id;
            
            $orderid = preg_replace("/[^A-Za-z0-9]/","",$orderidtobe);

            $insertlivestock = new livestocktake;
            $insertlivestock->istID = $orderid;
            $insertlivestock->storeID = $orderidstoreid->store_id;
            $insertlivestock->userID = session::get('loggindata.loggedinuser.id');
            $insertlivestock->save();

            if($insertlivestock->save())
            {
                return redirect()->route('livestocktakecreate', ['id'=> $insertlivestock->istID]);
            }
            else
            {
                return redirect()->back()->with('error', 'something went wrong');
            }
        }
    }

    public function livestocktakecreate($id)
    {
        if(session::get('loggindata.loggeduserpermission.livestocktake')=='N' ||session::get('loggindata.loggeduserpermission.livestocktake')=='')
        {
            return redirect('404');
        }
        else
        {
            $getdata = livestocktake::where('istID', $id)
            ->leftJoin('store', 'store.store_id', '=', 'livestocktake.storeID')
            ->first();

            $getlivestockitems = livestocktakeitems::where('lstiID', $id)
            ->leftJoin('products', 'products.productID', '=', 'livestocktakeItems.lstiproductID')
            ->get();

            $with = array(
                'getdata'=>$getdata,
                'getlivestockitems'=>$getlivestockitems
            );

            return view('livestocktake-create')->with($with);
        }
    }

    public function ajaxfindbarcode(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.livestocktake')=='N' ||session::get('loggindata.loggeduserpermission.livestocktake')=='')
        {
            return redirect('404');
        }
        else
        {
            $findbarcode = product::where('barcode', $request->input('barcode'))->count();

            if($findbarcode > 1)
            {
                $getbarcode = product::where('barcode', $request->input('barcode'))->get();
                $openpopup = '1';

                $with = array(
                    'getbarcode'=>$getbarcode,
                    'openpopup'=>$openpopup
                );

                return redirect()->back()->with();
            }
            else
            {
                $getbarcode = product::where('barcode', $request->input('barcode'))->first();

                if($getbarcode!='')
                {
                    $getstock = productstock::where('productID', $getbarcode->productID)->where('storeID', $request->input('storeID'))->first();

                    if($getstock!='')
                    {
                        $checkstockitem = livestocktakeitems::where('lstiproductID', $getbarcode->productID)->where('lstiID', $request->input('id'))->first();

                        if($checkstockitem == '')
                        {
                            $insertlivestockitem = new livestocktakeitems;
                            $insertlivestockitem->lstiID = $request->input('id');
                            $insertlivestockitem->lstiproductID = $getbarcode->productID;
                            $insertlivestockitem->lstiquantity = '1';
                            $insertlivestockitem->lstiavailableQuantity = $getstock->productquantity;
                            $insertlivestockitem->save();

                            if($insertlivestockitem->save())
                            {
                                echo 'unique';
                            }
                            else
                            {
                                echo 'not_unique';
                            }
                        }
                        else
                        {
                            $checkstockitem->lstiquantity = $checkstockitem->lstiquantity+1;
                            $checkstockitem->save();

                            if($checkstockitem->save())
                            {
                                echo 'unique';
                            }
                            else
                            {
                                echo 'not_unique';
                            }
                        }
                    }
                    else
                    {
                        $checkstockitem = livestocktakeitems::where('lstiproductID', $getbarcode->productID)->where('lstiID', $request->input('id'))->first();

                        if($checkstockitem == '')
                        {
                            $insertlivestockitem = new livestocktakeitems;
                            $insertlivestockitem->lstiID = $request->input('id');
                            $insertlivestockitem->lstiproductID = $getbarcode->productID;
                            $insertlivestockitem->lstiquantity = '1';
                            $insertlivestockitem->lstiavailableQuantity = '0';
                            $insertlivestockitem->save();

                            if($insertlivestockitem->save())
                            {
                                echo 'unique';
                            }
                            else
                            {
                                echo 'not_unique';
                            }
                        }
                        else
                        {
                            $checkstockitem->lstiquantity = $checkstockitem->lstiquantity+1;
                            $checkstockitem->save();

                            if($checkstockitem->save())
                            {
                                echo 'unique';
                            }
                            else
                            {
                                echo 'not_unique';
                            }
                        }
                    }
                }
                else
                {
                    echo 'not_unique';
                }
            }
        }
    }

    public function ajaxfindimei(Request $request)
    {
        if(session::get('loggindata.loggeduserpermission.livestocktake')=='N' ||session::get('loggindata.loggeduserpermission.livestocktake')=='')
        {
            return redirect('404');
        }
        else
        {
            $findimei = productstock::where('productimei', $request->input('imei'))->where('storeID', $request->input('storeID'))->count();

            if($findimei!='')
            {
                $getstock = productstock::where('productimei', $request->input('imei'))->where('storeID', $request->input('storeID'))->first();

                if($getstock!='')
                {
                    $insertlivestockitem = new livestocktakeitems;
                    $insertlivestockitem->lstiID = $request->input('id');
                    $insertlivestockitem->lstiproductID = $getstock->productID;
                    $insertlivestockitem->lstiimei = $getstock->productimei;
                    $insertlivestockitem->lstiquantity = '1';
                    $insertlivestockitem->lstiavailableQuantity = $getstock->productquantity;
                    $insertlivestockitem->save();

                    if($insertlivestockitem->save())
                    {
                        echo 'unique';
                    }
                    else
                    {
                        echo 'not_unique';
                    }
                }
                else
                {
                    $insertlivestockitem = new livestocktakeitems;
                    $insertlivestockitem->lstiID = $request->input('id');
                    $insertlivestockitem->lstiproductID = $getstock->productID;
                    $insertlivestockitem->lstiimei = $getstock->productimei;
                    $insertlivestockitem->lstiquantity = '1';
                    $insertlivestockitem->lstiavailableQuantity = '0';
                    $insertlivestockitem->save();

                    if($insertlivestockitem->save())
                    {
                        echo 'unique';
                    }
                    else
                    {
                        echo 'not_unique';
                    }
                }
            }
            else
            {
                echo 'not_unique';
            }
        }
    }
}
