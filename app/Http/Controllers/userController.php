<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
use App\userlogintype;
use App\usergrouppermission;

class userController extends Controller
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

    public function usermasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.vieweuser')=='N' ||session::get('loggindata.loggeduserpermission.vieweuser')=='')
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

            $users1= loggeduser::
                join('userlogintype', 'userlogintype.userID', '=', 'id')
            ->join('usertype', 'usertype.usertypeID', '=', 'userlogintype.usertypeID')
            ->leftJoin('storeuser', 'storeuser.userID', '=', 'users.id')
            ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id');
            if(count($storeID) > 0)
            {
                $users1->whereIn('storeuser.store_id', $storeID);
            }
            $users = $users1->get();
            /*if(session::get('loggindata.loggeduserstore')!='')
            {
            	
            }
            else
            {
            	$users= loggeduser::
	            join('userlogintype', 'userlogintype.userID', '=', 'id')
	            ->join('usertype', 'usertype.usertypeID', '=', 'userlogintype.usertypeID')
	            ->leftJoin('storeuser', 'storeuser.userID', '=', 'users.id')
	            ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
	            ->get();
            }*/

            $allusergroup = usergroup::get();
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

            $userdata = ['users'=>$users, 'allusergroup'=>$allusergroup, 'allstore'=>$allstore];

            return view('users')->with('userdata',$userdata);
        }
    }

    public function usergroupsview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewusergroup')=='N' ||session::get('loggindata.loggeduserpermission.viewusergroup')=='')
        {
            return redirect('404');
        } 
        else
        {
            $usersgroup= usergroup::get();
            return view('usersgroup')->with('usersgroup',$usersgroup);
        }
    }

    /*public function adduser(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addpurchaseorder')=='N' || session::get('loggindata.loggeduserpermission.addpurchaseorder')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'name'=>'required',
            'mobile'=>'required',
            'emergencymobile'=>'required',
            'email'=>'required',
            'paymethod'=>'required',
            'normalrate'=>'required',
            'saturdayrate'=>'required',
            'sundayrate'=>'required',
            'feul'=>'required',
            'employment'=>'required',
            'taxScale'=>'required',
            'username'=>'required',
            'password'=>'required',
            'usertype'=>'required',
            'workingrole'=>'required'
            ],[
                'name.required'=>'name is required',
                'mobile.required'=>'mobile is required',
                'emergencymobile.required'=>'Emergency mobile is required',
                'email.required'=>'email is required',
                'paymethod.required'=>'paymethod is required',
                'normalrate.required'=>'normalrate is required',
                'saturdayrate.required'=>'saturdayrate is required',
                'sundayrate.required'=>'sundayrate is required',
                'feul.required'=>'feul is required',
                'employment.required'=>'employment is required',
                'taxScale.required'=>'taxScale is required',
                'username.required'=>'username is required',
                'password.required'=>'password is required',
                'usertype.required'=>'usertype is required',
                'workingrole.required'=>'workingrole is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
            	$checkuser = loggeduser::where('username', $request->input('username'))->count();

            	if($checkuser == 0)
            	{
            		$newuser = new loggeduser;
            		$newuser->username = $request->input('username');
            		$newuser->name = $request->input('name');
            		$newuser->workingrole = $request->input('workingrole');
            		$newuser->email = $request->input('email');
            		$newuser->mobile = $request->input('mobile');
                    $newuser->emergencymobile = $request->input('emergencymobile');
            		$newuser->password = Hash::make($request->input('password'));
            		$newuser->dateofbirth = $request->input('dob');
            		$newuser->address = $request->input('address');
            		$newuser->siebelid = $request->input('siebelid');
            		$newuser->salesforece = $request->input('salesforece');
            		$newuser->vflearning = $request->input('vflearning');
            		$newuser->paymethod = $request->input('paymethod');
            		$newuser->tfn_abn = $request->input('tfn').','.$request->input('abn');
            		$newuser->normalrate = $request->input('normalrate');
            		$newuser->saturdayrate = $request->input('saturdayrate');
            		$newuser->sundayrate = $request->input('sundayrate');
            		$newuser->feul = $request->input('feul');
            		$newuser->bankdetail1 = $request->input('bankdetail1');
            		$newuser->bankdetail2 = $request->input('bankdetail2');
            		$newuser->employment = $request->input('employment');
            		$newuser->taxScale = $request->input('taxScale');
            		$newuser->userstatus = '1';
            		$newuser->addedby = session::get('loggindata.loggedinuser.id');
            		$newuser->save();

            		if($newuser->save())
            		{
            			$lastinsertedid = $newuser->id;

            			$insertlogintype = new userlogintype;
            			$insertlogintype->usertypeID = $request->input('usertype');
            			$insertlogintype->userID = $lastinsertedid;
            			$insertlogintype->save();

            			if($request->input('store') != '')
            			{
            				$insertstoreuser = new storeuser;
            				$insertstoreuser->store_id = $request->input('store');
            				$insertstoreuser->userID = $lastinsertedid;
            				$insertstoreuser->save();
            			}

            			return redirect()->back()->with('success', 'New user created successfully');
            		}
            		else
            		{
            			return redirect()->back()->with('error', 'Failed to create new user');
            		}
            	}
            	else
            	{
            		return redirect()->back()->with('error', 'Username already exists.');
            	}
            }
        }
    }*/


    public function adduser(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.adduser')=='N' || session::get('loggindata.loggeduserpermission.adduser')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'name'=>'required',
            'mobile'=>'required',
            'emergencymobile'=>'required',
            'email'=>'required',
            'paymethod'=>'required',
            'normalrate'=>'required',
            'saturdayrate'=>'required',
            'sundayrate'=>'required',
            'feul'=>'required',
            'employment'=>'required',
            'taxScale'=>'required',
            'username'=>'required',
            'password'=>'required',
            'usertype'=>'required',
            'workingrole'=>'required'
            ],[
                'name.required'=>'name is required',
                'mobile.required'=>'mobile is required',
                'emergencymobile.required'=>'Emergency mobile is required',
                'email.required'=>'email is required',
                'paymethod.required'=>'paymethod is required',
                'normalrate.required'=>'normalrate is required',
                'saturdayrate.required'=>'saturdayrate is required',
                'sundayrate.required'=>'sundayrate is required',
                'feul.required'=>'feul is required',
                'employment.required'=>'employment is required',
                'taxScale.required'=>'taxScale is required',
                'username.required'=>'username is required',
                'password.required'=>'password is required',
                'usertype.required'=>'usertype is required',
                'workingrole.required'=>'workingrole is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkuser = loggeduser::where('username', $request->input('username'))->count();

                if($checkuser == 0)
                {
                    $newuser = new loggeduser;
                    $newuser->username = $request->input('username');
                    $newuser->name = $request->input('name');
                    $newuser->workingrole = $request->input('workingrole');
                    $newuser->email = $request->input('email');
                    $newuser->mobile = $request->input('mobile');
                    $newuser->emergencymobile = $request->input('emergencymobile');
                    $newuser->password = Hash::make($request->input('password'));
                    $newuser->dateofbirth = $request->input('dob');
                    $newuser->address = $request->input('address');
                    $newuser->siebelid = $request->input('siebelid');
                    $newuser->salesforece = $request->input('salesforece');
                    $newuser->vflearning = $request->input('vflearning');
                    $newuser->paymethod = $request->input('paymethod');
                    $newuser->tfn_abn = $request->input('tfn').','.$request->input('abn');
                    $newuser->normalrate = $request->input('normalrate');
                    $newuser->saturdayrate = $request->input('saturdayrate');
                    $newuser->sundayrate = $request->input('sundayrate');
                    $newuser->feul = $request->input('feul');
                    $newuser->bankdetail1 = $request->input('bankdetail1');
                    $newuser->bankdetail2 = $request->input('bankdetail2');
                    $newuser->employment = $request->input('employment');
                    $newuser->taxScale = $request->input('taxScale');
                    $newuser->userstatus = '1';
                    $newuser->addedby = session::get('loggindata.loggedinuser.id');
                    $newuser->save();

                    if($newuser->save())
                    {
                        $lastinsertedid = $newuser->id;

                        $insertlogintype = new userlogintype;
                        $insertlogintype->usertypeID = $request->input('usertype');
                        $insertlogintype->userID = $lastinsertedid;
                        $insertlogintype->save();

                        if($request->input('store') != '')
                        {
                            $size = count($request->input('store'));

                            for ($i = 0; $i < $size; $i++)
                            {
                                $insertstoreuser = new storeuser;
                                $insertstoreuser->store_id = $request->input('store')[$i];
                                $insertstoreuser->userID = $lastinsertedid;
                                $insertstoreuser->save();
                            }
                        }

                        $getusergroup = usergrouppermission::where('usertypeID', $request->input('usertype'))->first();
                        $userpermission = new userpermission;
                        $userpermission->userID = $lastinsertedid;
                        $userpermission->changestore = $getusergroup->changestore;
                        $userpermission->newsale = $getusergroup->newsale;
                        $userpermission->refund = $getusergroup->refund;
                        $userpermission->viewmasters = $getusergroup->viewmasters;
                        $userpermission->addmasters = $getusergroup->addmasters;
                        $userpermission->editmasters = $getusergroup->editmasters;
                        $userpermission->deletemaster = $getusergroup->deletemaster;
                        $userpermission->vieweuser = $getusergroup->vieweuser;
                        $userpermission->adduser = $getusergroup->adduser;
                        $userpermission->edituser = $getusergroup->edituser;
                        $userpermission->deleteuser = $getusergroup->deleteuser;
                        $userpermission->editpermission = $getusergroup->editpermission;
                        $userpermission->viewusergroup = $getusergroup->viewusergroup;
                        $userpermission->addusergroup = $getusergroup->addusergroup;
                        $userpermission->editusergroup = $getusergroup->editusergroup;
                        $userpermission->editusergrouppermission = $getusergroup->editusergrouppermission;
                        $userpermission->viewproducts = $getusergroup->viewproducts;
                        $userpermission->addproducts = $getusergroup->addproducts;
                        $userpermission->editproducts = $getusergroup->editproducts;
                        $userpermission->deleteproducts = $getusergroup->deleteproducts;
                        $userpermission->viewproductfilters = $getusergroup->viewproductfilters;
                        $userpermission->searchproducts = $getusergroup->searchproducts;
                        $userpermission->searchproductsbystore = $getusergroup->searchproductsbystore;
                        $userpermission->viewpurchaseorder = $getusergroup->viewpurchaseorder;
                        $userpermission->addpurchaseorder = $getusergroup->addpurchaseorder;
                        $userpermission->editpurchaseorder = $getusergroup->editpurchaseorder;
                        $userpermission->deletepurchaseorder = $getusergroup->deletepurchaseorder;
                        $userpermission->editpurchaseorderitem = $getusergroup->editpurchaseorderitem;
                        $userpermission->deletepurchaseorderitem = $getusergroup->deletepurchaseorderitem;
                        $userpermission->viewpurchaseorderprice = $getusergroup->viewpurchaseorderprice;
                        $userpermission->receivepurchaseorder = $getusergroup->receivepurchaseorder;
                        $userpermission->viewpurchaseorderfilters = $getusergroup->viewpurchaseorderfilters;
                        $userpermission->viewpurchaseorderreceivefilters = $getusergroup->viewpurchaseorderreceivefilters;
                        $userpermission->viewplans = $getusergroup->viewplans;
                        $userpermission->addplans = $getusergroup->addplans;
                        $userpermission->editplans = $getusergroup->editplans;
                        $userpermission->deleteplans = $getusergroup->deleteplans;
                        $userpermission->viewplansfilters = $getusergroup->viewplansfilters;
                        $userpermission->viewinstock = $getusergroup->viewinstock;
                        $userpermission->viewdemostock = $getusergroup->viewdemostock;
                        $userpermission->viewsalehistory = $getusergroup->viewsalehistory;
                        $userpermission->viewsalehistoryfilters = $getusergroup->viewsalehistoryfilters;
                        $userpermission->viewtimesheet = $getusergroup->viewtimesheet;
                        $userpermission->addtimesheet = $getusergroup->addtimesheet;
                        $userpermission->viewrostermanager = $getusergroup->viewrostermanager;
                        $userpermission->rostermanagerpay = $getusergroup->rostermanagerpay;
                        $userpermission->reportEOD = $getusergroup->reportEOD;
                        $userpermission->reportEODtill = $getusergroup->reportEODtill;
                        $userpermission->reportEODfilter = $getusergroup->reportEODfilter;
                        $userpermission->viewstocktransferout = $getusergroup->viewstocktransferout;
                        $userpermission->viewstocktransferin = $getusergroup->viewstocktransferin;
                        $userpermission->addstocktransfer = $getusergroup->addstocktransfer;
                        $userpermission->viewstocktransferfilters = $getusergroup->viewstocktransferfilters;
                        $userpermission->viewcustomer = $getusergroup->viewcustomer;
                        $userpermission->addcustomer = $getusergroup->addcustomer;
                        $userpermission->editcustomer = $getusergroup->editcustomer;
                        $userpermission->viewtracker = $getusergroup->viewtracker;
                        $userpermission->viewtrackerfilter = $getusergroup->viewtrackerfilter;
                        $userpermission->viewstoretracker = $getusergroup->viewstoretracker;
                        $userpermission->viewstoretrackerfilter = $getusergroup->viewstoretrackerfilter;
                        $userpermission->addpersonaltarget = $getusergroup->addpersonaltarget;
                        $userpermission->addstoretarget = $getusergroup->addstoretarget;
                        $userpermission->viewtrackerbonus = $getusergroup->viewtrackerbonus;
                        $userpermission->viewstoretrackerbonus = $getusergroup->viewstoretrackerbonus;
                        $userpermission->viewreportsalesbyuser = $getusergroup->viewreportsalesbyuser;
                        $userpermission->viewreportsalesbyuserfilter = $getusergroup->viewreportsalesbyuserfilter;
                        $userpermission->viewreportsalespaymentmethod = $getusergroup->viewreportsalespaymentmethod;
                        $userpermission->viewreportsalespaymentmethodfilter = $getusergroup->viewreportsalespaymentmethodfilter;
                        $userpermission->viewreportsalesmaster = $getusergroup->viewreportsalesmaster;
                        $userpermission->viewreportsalesmasterfilter = $getusergroup->viewreportsalesmasterfilter;
                        $userpermission->viewreportsalesmastercombin = $getusergroup->viewreportsalesmastercombin;
                        $userpermission->viewreportsalesmastercombinefilter = $getusergroup->viewreportsalesmastercombinefilter;
                        $userpermission->viewreportsalesconnection = $getusergroup->viewreportsalesconnection;
                        $userpermission->viewreportsalesconnectionfilter = $getusergroup->viewreportsalesconnectionfilter;
                        $userpermission->viewreportprofitbyuser = $getusergroup->viewreportprofitbyuser;
                        $userpermission->viewreportprofitbyuserfilter = $getusergroup->viewreportprofitbyuserfilter;
                        $userpermission->viewreportprofitbycategory = $getusergroup->viewreportprofitbycategory;
                        $userpermission->viewreportprofitbycategoryfilter = $getusergroup->viewreportprofitbycategoryfilter;
                        $userpermission->viewreportprofitbyconnection = $getusergroup->viewreportprofitbyconnection;
                        $userpermission->viewreportprofitbyconnectionfilter = $getusergroup->viewreportprofitbyconnectionfilter;
                        $userpermission->viewreportstockhistory = $getusergroup->viewreportstockhistory;
                        $userpermission->viewreportstockhistoryfilter = $getusergroup->viewreportstockhistoryfilter;
                        $userpermission->viewreportstocktransfer = $getusergroup->viewreportstocktransfer;
                        $userpermission->viewreportstocktransferfilter = $getusergroup->viewreportstocktransferfilter;
                        $userpermission->viewreportstockreturn = $getusergroup->viewreportstockreturn;
                        $userpermission->viewreportstockreturnfilter = $getusergroup->viewreportstockreturnfilter;
                        $userpermission->viewreportstockholding = $getusergroup->viewreportstockholding;
                        $userpermission->viewreportstockholdingfilter = $getusergroup->viewreportstockholdingfilter;
                        $userpermission->viewreportproductreceive = $getusergroup->viewreportproductreceive;
                        $userpermission->viewreportproductreceivefilters = $getusergroup->viewreportproductreceivefilters;
                        $userpermission->viewdemoreceive = $getusergroup->viewdemoreceive;
                        $userpermission->adddemoreceive = $getusergroup->adddemoreceive;
                        $userpermission->viewstockreturn = $getusergroup->viewstockreturn;
                        $userpermission->addstockreturn = $getusergroup->addstockreturn;
                        $userpermission->viewstockreturnfilter = $getusergroup->viewstockreturnfilter;
                        $userpermission->editstockreturnitem = $getusergroup->editstockreturnitem;
                        $userpermission->deletestockreturnitem = $getusergroup->deletestockreturnitem;
                        $userpermission->stockreturnAdminAprroval = $getusergroup->stockreturnAdminAprroval;
                        $userpermission->bulk_appacccomission = $getusergroup->bulk_appacccomission;
                        $userpermission->reportroster = $getusergroup->reportroster;
                        $userpermission->reportrosterfilter = $getusergroup->reportrosterfilter;
                        $userpermission->livestocktake = $getusergroup->livestocktake;
                        $userpermission->upfrontreport = $getusergroup->upfrontreport;
                        $userpermission->save();

                        return redirect()->back()->with('success', 'New user created successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Failed to create new user');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Username already exists.');
                }
            }
        }
    }

    public function ajaxcheckusername(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.adduser')=='N' || session::get('loggindata.loggeduserpermission.adduser')=='')
        {
            return redirect('404');
        }
        else
        { 
            if($request->get('username'))
		    {
		      $username = $request->get('username');
		      $data = loggeduser::where('username', $username)->count();
		      
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
    }

    public function edituser(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.edituser')=='N' || session::get('loggindata.loggeduserpermission.edituser')=='')
        {
            return redirect('404');
        }
        else
        { 
            //return $checkbrand;
            $validator = validator::make($request->all(),[
            'name'=>'required',
            'mobile'=>'required',
            'emergencymobile'=>'required',
            'email'=>'required',
            'paymethod'=>'required',
            'normalrate'=>'required',
            'saturdayrate'=>'required',
            'sundayrate'=>'required',
            'feul'=>'required',
            'employment'=>'required',
            'taxScale'=>'required',
            'usertype'=>'required',
            'workingrole'=>'required'
            ],[
                'name.required'=>'name is required',
                'mobile.required'=>'mobile is required',
                'emergencymobile.required'=>'Emergency mobile is required',
                'email.required'=>'email is required',
                'paymethod.required'=>'paymethod is required',
                'normalrate.required'=>'normalrate is required',
                'saturdayrate.required'=>'saturdayrate is required',
                'sundayrate.required'=>'sundayrate is required',
                'feul.required'=>'feul is required',
                'employment.required'=>'employment is required',
                'taxScale.required'=>'taxScale is required',
                'usertype.required'=>'usertype is required',
                'workingrole.required'=>'workingrole is required'
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $useredit = loggeduser::where('id', $request->input('userid'))->first();

                $useredit->name = $request->input('name');
                $useredit->workingrole = $request->input('workingrole');
                $useredit->email = $request->input('email');
                $useredit->mobile = $request->input('mobile');
                $useredit->emergencymobile = $request->input('emergencymobile');
                $useredit->dateofbirth = $request->input('dob');
                $useredit->address = $request->input('address');
                $useredit->siebelid = $request->input('siebelid');
                $useredit->salesforece = $request->input('salesforece');
                $useredit->vflearning = $request->input('vflearning');
                $useredit->paymethod = $request->input('paymethod');
                $useredit->tfn_abn = $request->input('tfn_abn');
                $useredit->normalrate = $request->input('normalrate');
                $useredit->saturdayrate = $request->input('saturdayrate');
                $useredit->sundayrate = $request->input('sundayrate');
                $useredit->feul = $request->input('feul');
                $useredit->bankdetail1 = $request->input('bankdetail1');
                $useredit->bankdetail2 = $request->input('bankdetail2');
                $useredit->employment = $request->input('employment');
                $useredit->taxScale = $request->input('taxScale');
                $useredit->save();

                if($useredit->save())
                {
                    $checklogintype = userlogintype::where('userID', $request->input('userid'))->first();

                    if($checklogintype->usertypeID !=  $request->input('usertype'))
                    {
                        $checklogintype->usertypeID = $request->input('usertype');
                        $checklogintype->save();

                        $getusergroup = usergrouppermission::where('usertypeID', $request->input('usertype'))->first();

                        $checkpermission = userpermission::where('userID', $request->input('userid'))->first();

                        $checkpermission->changestore = $getusergroup->changestore;
                        $checkpermission->newsale = $getusergroup->newsale;
                        $checkpermission->refund = $getusergroup->refund;
                        $checkpermission->viewmasters = $getusergroup->viewmasters;
                        $checkpermission->addmasters = $getusergroup->addmasters;
                        $checkpermission->editmasters = $getusergroup->editmasters;
                        $checkpermission->deletemaster = $getusergroup->deletemaster;
                        $checkpermission->vieweuser = $getusergroup->vieweuser;
                        $checkpermission->adduser = $getusergroup->adduser;
                        $checkpermission->edituser = $getusergroup->edituser;
                        $checkpermission->deleteuser = $getusergroup->deleteuser;
                        $checkpermission->editpermission = $getusergroup->editpermission;
                        $checkpermission->viewusergroup = $getusergroup->viewusergroup;
                        $checkpermission->addusergroup = $getusergroup->addusergroup;
                        $checkpermission->editusergroup = $getusergroup->editusergroup;
                        $checkpermission->editusergrouppermission = $getusergroup->editusergrouppermission;
                        $checkpermission->viewproducts = $getusergroup->viewproducts;
                        $checkpermission->addproducts = $getusergroup->addproducts;
                        $checkpermission->editproducts = $getusergroup->editproducts;
                        $checkpermission->deleteproducts = $getusergroup->deleteproducts;
                        $checkpermission->viewproductfilters = $getusergroup->viewproductfilters;
                        $checkpermission->searchproducts = $getusergroup->searchproducts;
                        $checkpermission->searchproductsbystore = $getusergroup->searchproductsbystore;
                        $checkpermission->viewpurchaseorder = $getusergroup->viewpurchaseorder;
                        $checkpermission->addpurchaseorder = $getusergroup->addpurchaseorder;
                        $checkpermission->editpurchaseorder = $getusergroup->editpurchaseorder;
                        $checkpermission->deletepurchaseorder = $getusergroup->deletepurchaseorder;
                        $checkpermission->editpurchaseorderitem = $getusergroup->editpurchaseorderitem;
                        $checkpermission->deletepurchaseorderitem = $getusergroup->deletepurchaseorderitem;
                        $checkpermission->viewpurchaseorderprice = $getusergroup->viewpurchaseorderprice;
                        $checkpermission->receivepurchaseorder = $getusergroup->receivepurchaseorder;
                        $checkpermission->viewpurchaseorderfilters = $getusergroup->viewpurchaseorderfilters;
                        $checkpermission->viewpurchaseorderreceivefilters = $getusergroup->viewpurchaseorderreceivefilters;
                        $checkpermission->viewplans = $getusergroup->viewplans;
                        $checkpermission->addplans = $getusergroup->addplans;
                        $checkpermission->editplans = $getusergroup->editplans;
                        $checkpermission->deleteplans = $getusergroup->deleteplans;
                        $checkpermission->viewplansfilters = $getusergroup->viewplansfilters;
                        $checkpermission->viewinstock = $getusergroup->viewinstock;
                        $checkpermission->viewdemostock = $getusergroup->viewdemostock;
                        $checkpermission->viewsalehistory = $getusergroup->viewsalehistory;
                        $checkpermission->viewsalehistoryfilters = $getusergroup->viewsalehistoryfilters;
                        $checkpermission->viewtimesheet = $getusergroup->viewtimesheet;
                        $checkpermission->addtimesheet = $getusergroup->addtimesheet;
                        $checkpermission->viewrostermanager = $getusergroup->viewrostermanager;
                        $checkpermission->rostermanagerpay = $getusergroup->rostermanagerpay;
                        $checkpermission->reportEOD = $getusergroup->reportEOD;
                        $checkpermission->reportEODtill = $getusergroup->reportEODtill;
                        $checkpermission->reportEODfilter = $getusergroup->reportEODfilter;
                        $checkpermission->viewstocktransferout = $getusergroup->viewstocktransferout;
                        $checkpermission->viewstocktransferin = $getusergroup->viewstocktransferin;
                        $checkpermission->addstocktransfer = $getusergroup->addstocktransfer;
                        $checkpermission->viewstocktransferfilters = $getusergroup->viewstocktransferfilters;
                        $checkpermission->viewcustomer = $getusergroup->viewcustomer;
                        $checkpermission->addcustomer = $getusergroup->addcustomer;
                        $checkpermission->editcustomer = $getusergroup->editcustomer;
                        $checkpermission->viewtracker = $getusergroup->viewtracker;
                        $checkpermission->viewtrackerfilter = $getusergroup->viewtrackerfilter;
                        $checkpermission->viewstoretracker = $getusergroup->viewstoretracker;
                        $checkpermission->viewstoretrackerfilter = $getusergroup->viewstoretrackerfilter;
                        $checkpermission->addpersonaltarget = $getusergroup->addpersonaltarget;
                        $checkpermission->addstoretarget = $getusergroup->addstoretarget;
                        $checkpermission->viewtrackerbonus = $getusergroup->viewtrackerbonus;
                        $checkpermission->viewstoretrackerbonus = $getusergroup->viewstoretrackerbonus;
                        $checkpermission->viewreportsalesbyuser = $getusergroup->viewreportsalesbyuser;
                        $checkpermission->viewreportsalesbyuserfilter = $getusergroup->viewreportsalesbyuserfilter;
                        $checkpermission->viewreportsalespaymentmethod = $getusergroup->viewreportsalespaymentmethod;
                        $checkpermission->viewreportsalespaymentmethodfilter = $getusergroup->viewreportsalespaymentmethodfilter;
                        $checkpermission->viewreportsalesmaster = $getusergroup->viewreportsalesmaster;
                        $checkpermission->viewreportsalesmasterfilter = $getusergroup->viewreportsalesmasterfilter;
                        $checkpermission->viewreportsalesmastercombin = $getusergroup->viewreportsalesmastercombin;
                        $checkpermission->viewreportsalesmastercombinefilter = $getusergroup->viewreportsalesmastercombinefilter;
                        $checkpermission->viewreportsalesconnection = $getusergroup->viewreportsalesconnection;
                        $checkpermission->viewreportsalesconnectionfilter = $getusergroup->viewreportsalesconnectionfilter;
                        $checkpermission->viewreportprofitbyuser = $getusergroup->viewreportprofitbyuser;
                        $checkpermission->viewreportprofitbyuserfilter = $getusergroup->viewreportprofitbyuserfilter;
                        $checkpermission->viewreportprofitbycategory = $getusergroup->viewreportprofitbycategory;
                        $checkpermission->viewreportprofitbycategoryfilter = $getusergroup->viewreportprofitbycategoryfilter;
                        $checkpermission->viewreportprofitbyconnection = $getusergroup->viewreportprofitbyconnection;
                        $checkpermission->viewreportprofitbyconnectionfilter = $getusergroup->viewreportprofitbyconnectionfilter;
                        $checkpermission->viewreportstockhistory = $getusergroup->viewreportstockhistory;
                        $checkpermission->viewreportstockhistoryfilter = $getusergroup->viewreportstockhistoryfilter;
                        $checkpermission->viewreportstocktransfer = $getusergroup->viewreportstocktransfer;
                        $checkpermission->viewreportstocktransferfilter = $getusergroup->viewreportstocktransferfilter;
                        $checkpermission->viewreportstockreturn = $getusergroup->viewreportstockreturn;
                        $checkpermission->viewreportstockreturnfilter = $getusergroup->viewreportstockreturnfilter;
                        $checkpermission->viewreportstockholding = $getusergroup->viewreportstockholding;
                        $checkpermission->viewreportstockholdingfilter = $getusergroup->viewreportstockholdingfilter;
                        $checkpermission->viewreportproductreceive = $getusergroup->viewreportproductreceive;
                        $checkpermission->viewreportproductreceivefilters = $getusergroup->viewreportproductreceivefilters;
                        $checkpermission->viewdemoreceive = $getusergroup->viewdemoreceive;
                        $checkpermission->adddemoreceive = $getusergroup->adddemoreceive;
                        $checkpermission->viewstockreturn = $getusergroup->viewstockreturn;
                        $checkpermission->addstockreturn = $getusergroup->addstockreturn;
                        $checkpermission->viewstockreturnfilter = $getusergroup->viewstockreturnfilter;
                        $checkpermission->editstockreturnitem = $getusergroup->editstockreturnitem;
                        $checkpermission->deletestockreturnitem = $getusergroup->deletestockreturnitem;
                        $checkpermission->stockreturnAdminAprroval = $getusergroup->stockreturnAdminAprroval;
                        $checkpermission->bulk_appacccomission = $getusergroup->bulk_appacccomission;
                        $checkpermission->reportroster = $getusergroup->reportroster;
                        $checkpermission->reportrosterfilter = $getusergroup->reportrosterfilter;
                        $checkpermission->livestocktake = $getusergroup->livestocktake;
                        $checkpermission->upfrontreport = $getusergroup->upfrontreport;
                        $checkpermission->save();

                    
                        return redirect()->back()->with('success', 'User data updated successfully');
                    }
                    return redirect()->back()->with('success', 'User data updated successfully');
                }
                else
                {
                    return redirect()->back()->with('error', 'Failed to update User data');
                }
            }
        }
    }

    public function edituserstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deleteuser')=='N' ||session::get('loggindata.loggeduserpermission.deleteuser')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'userstatus'=>'required'
            ],[
                'userstatus.required'=>'User Status is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateuserstatus= loggeduser::find($request->input('id'));

                $updateuserstatus->userstatus= $request->input('userstatus');
                $updateuserstatus->save();

                if($updateuserstatus->save())
                {
                    return redirect()->back()->with('success','User status updated successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Failed To update User status');
                }
            }
        }
    }

    public function userpermissionview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.editpermission')=='N' ||session::get('loggindata.loggeduserpermission.editpermission')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getuserdata = loggeduser::where('id', $id)
            ->leftJoin('userpermission', 'userpermission.userID', '=', 'users.id')
            ->first();

            //return $getuserdata;

            $with = array(
                'getuserdata'=>$getuserdata
            );

            return view('userpermission')->with($with);
        }
    }

    public function ajaxupdateuserpermission(Request $request)
    { 
        $userid = $request->get('userid');
          $option = $request->get('option');
          $column = $request->get('column');

          $data = userpermission::where('userID', $userid)->first();
          $data->$column = $option;
          $data->save();
    }

    public function usersstoreview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.edituser')=='N' ||session::get('loggindata.loggeduserpermission.edituser')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getuserstore = loggeduser::where('id', $id)
            ->leftJoin('storeuser', 'storeuser.userID', '=', 'users.id')
            ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
            ->get();

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
                        $editallstore= $frencadminstore;
                    }
                    else
                    {
                        $editallstore = store::get();
                    }
                }
                else if(count(session::get('loggindata.loggeduserstore')) > '0')
                {
                    $editallstore= session::get('loggindata.loggeduserstore');
                }
                else
                {
                    $editallstore = store::get();
                }
            }
            else
            {
                $editallstore= store::get();
            }

            //$editallstore = store::where('storestatus', '1')->get();

            $with = array(
                'getuserstore'=>$getuserstore,
                'editallstore'=>$editallstore
            );

            return view('userstore')->with($with);
        }
    }

    public function addstoretouser(Request $request)
    { 
        if($request->input('store') != "")
        {
            $size = count($request->input('store'));
        
            for ($i = 0; $i < $size; $i++)
            {
                $checkstore = storeuser::where('userID', $request->input('userid'))->where('store_id', $request->input('store')[$i])->count();

                if($checkstore == 0)
                {
                    $insertstoreuser = new storeuser;
                    $insertstoreuser->store_id = $request->input('store')[$i];
                    $insertstoreuser->userID = $request->input('userid');
                    $insertstoreuser->save();

                    return redirect()->back()->with('success', 'Store Added');
                }
                else
                {
                    return redirect()->back()->with('error', 'Store already exists');  
                }
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Please select atleast one store.');
        }
    }

    public function removeuserstore(Request $request)
    { 
        $deletestoreofuser = storeuser::where('storeUserID', $request->input('storeuserid'))->delete();

        if($deletestoreofuser)
        {
            return redirect()->back()->with('success', 'Store removed successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Store not removed. try again');
        }
    }

    public function usergrouppermissionview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.editusergrouppermission')=='N' ||session::get('loggindata.loggeduserpermission.editusergrouppermission')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getgrouppermission = usergrouppermission::where('ugpID', $id)
            ->first();

            //return $getuserdata;

            $with = array(
                'getgrouppermission'=>$getgrouppermission
            );

            return view('usergrouppermission')->with($with);
        }
    }

    public function ajaxupdategrouppermission(Request $request)
    { 
        $groupid = $request->get('groupid');
          $option = $request->get('option');
          $column = $request->get('column');

          $data = usergrouppermission::where('usertypeID', $groupid)->first();
          $data->$column = $option;
          $data->save();

          $getgroupuser = userlogintype::where('usertypeID', $groupid)->get();
          foreach($getgroupuser as $userid)
          {
            $updateuserpermission = userpermission::where('userID', $userid->userID)->first();
            $updateuserpermission->$column = $option;
            $updateuserpermission->save();
          }
    }

    public function usergroupcreateview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.addusergroup')=='N' ||session::get('loggindata.loggeduserpermission.addusergroup')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getgrouppermission = usergrouppermission::where('ugpID', $id)
            ->first();

            //return $getuserdata;

            $with = array(
                'getgrouppermission'=>$getgrouppermission
            );

            return view('usergroup-create')->with($with);
        }
    }

    public function creategroup(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addusergroup')=='N' ||session::get('loggindata.loggeduserpermission.addusergroup')=='')
        {
            return redirect('404');
        } 
        else
        {
            $groupname = $request->input('groupname');
            $description = $request->input('description');
            $grouprole = $request->input('grouprole');

            $checkgroupname = usergroup::where('usertypeRole', $groupname)->count();

            if($checkgroupname == 0)
            {
                $insertgroup = new usergroup;
                $insertgroup->usertypeName = $groupname;
                $insertgroup->usertypeRole =$grouprole; 
                $insertgroup->typedescription = $description;
                $insertgroup->save();

                if($insertgroup->save())
                {
                    $insertpermission = new usergrouppermission;
                    $insertpermission->usertypeID = $insertgroup->usertypeID;
                    $insertpermission->changestore = $request->input('changestore');
                    $insertpermission->newsale = $request->input('newsale');
                    $insertpermission->refund = $request->input('refund');
                    $insertpermission->viewmasters = $request->input('viewmasters');
                    $insertpermission->addmasters = $request->input('addmasters');
                    $insertpermission->editmasters = $request->input('editmasters');
                    $insertpermission->deletemaster = $request->input('deletemaster');
                    $insertpermission->vieweuser = $request->input('vieweuser');
                    $insertpermission->adduser = $request->input('adduser');
                    $insertpermission->edituser = $request->input('edituser');
                    $insertpermission->deleteuser = $request->input('deleteuser');
                    $insertpermission->editpermission = $request->input('editpermission');
                    $insertpermission->viewusergroup = $request->input('viewusergroup');
                    $insertpermission->addusergroup = $request->input('addusergroup');
                    $insertpermission->editusergroup = $request->input('editusergroup');
                    $insertpermission->editusergrouppermission = $request->input('editusergrouppermission');
                    $insertpermission->viewproducts = $request->input('viewproducts');
                    $insertpermission->addproducts = $request->input('addproducts');
                    $insertpermission->editproducts = $request->input('editproducts');
                    $insertpermission->deleteproducts = $request->input('deleteproducts');
                    $insertpermission->viewproductfilters = $request->input('viewproductfilters');
                    $insertpermission->searchproducts = $request->input('searchproducts');
                    $insertpermission->searchproductsbystore = $request->input('searchproductsbystore');
                    $insertpermission->viewpurchaseorder = $request->input('viewpurchaseorder');
                    $insertpermission->addpurchaseorder = $request->input('addpurchaseorder');
                    $insertpermission->editpurchaseorder = $request->input('editpurchaseorder');
                    $insertpermission->deletepurchaseorder = $request->input('deletepurchaseorder');
                    $insertpermission->editpurchaseorderitem = $request->input('editpurchaseorderitem');
                    $insertpermission->deletepurchaseorderitem = $request->input('deletepurchaseorderitem');
                    $insertpermission->viewpurchaseorderprice = $request->input('viewpurchaseorderprice');
                    $insertpermission->receivepurchaseorder = $request->input('receivepurchaseorder');
                    $insertpermission->viewpurchaseorderfilters = $request->input('viewpurchaseorderfilters');
                    $insertpermission->viewpurchaseorderreceivefilters = $request->input('viewpurchaseorderreceivefilters');
                    $insertpermission->viewplans = $request->input('viewplans');
                    $insertpermission->addplans = $request->input('addplans');
                    $insertpermission->editplans = $request->input('editplans');
                    $insertpermission->deleteplans = $request->input('deleteplans');
                    $insertpermission->viewplansfilters = $request->input('viewplansfilters');
                    $insertpermission->viewinstock = $request->input('viewinstock');
                    $insertpermission->viewdemostock = $request->input('viewdemostock');
                    $insertpermission->viewsalehistory = $request->input('viewsalehistory');
                    $insertpermission->viewsalehistoryfilters = $request->input('viewsalehistoryfilters');
                    $insertpermission->viewtimesheet = $request->input('viewtimesheet');
                    $insertpermission->addtimesheet = $request->input('addtimesheet');
                    $insertpermission->viewrostermanager = $request->input('viewrostermanager');
                    $insertpermission->rostermanagerpay = $request->input('rostermanagerpay');
                    $insertpermission->reportEOD = $request->input('reportEOD');
                    $insertpermission->reportEODtill = $request->input('reportEODtill');
                    $insertpermission->reportEODfilter = $request->input('reportEODfilter');
                    $insertpermission->viewstocktransferout = $request->input('viewstocktransferout');
                    $insertpermission->viewstocktransferin = $request->input('viewstocktransferin');
                    $insertpermission->addstocktransfer = $request->input('addstocktransfer');
                    $insertpermission->viewstocktransferfilters = $request->input('viewstocktransferfilters');
                    $insertpermission->viewcustomer = $request->input('viewcustomer');
                    $insertpermission->addcustomer = $request->input('addcustomer');
                    $insertpermission->editcustomer = $request->input('editcustomer');
                    $insertpermission->viewtracker = $request->input('viewtracker');
                    $insertpermission->viewtrackerfilter = $request->input('viewtrackerfilter');
                    $insertpermission->viewstoretracker = $request->input('viewstoretracker');
                    $insertpermission->viewstoretrackerfilter = $request->input('viewstoretrackerfilter');
                    $insertpermission->addpersonaltarget = $request->input('addpersonaltarget');
                    $insertpermission->addstoretarget = $request->input('addstoretarget');
                    $insertpermission->viewtrackerbonus = $request->input('viewtrackerbonus');
                    $insertpermission->viewstoretrackerbonus = $request->input('viewstoretrackerbonus');
                    $insertpermission->viewreportsalesbyuser = $request->input('viewreportsalesbyuser');
                    $insertpermission->viewreportsalesbyuserfilter = $request->input('viewreportsalesbyuserfilter');
                    $insertpermission->viewreportsalespaymentmethod = $request->input('viewreportsalespaymentmethod');
                    $insertpermission->viewreportsalespaymentmethodfilter = $request->input('viewreportsalespaymentmethodfilter');
                    $insertpermission->viewreportsalesmaster = $request->input('viewreportsalesmaster');
                    $insertpermission->viewreportsalesmasterfilter = $request->input('viewreportsalesmasterfilter');
                    $insertpermission->viewreportsalesmastercombin = $request->input('viewreportsalesmastercombin');
                    $insertpermission->viewreportsalesmastercombinefilter = $request->input('viewreportsalesmastercombinefilter');
                    $insertpermission->viewreportsalesconnection = $request->input('viewreportsalesconnection');
                    $insertpermission->viewreportsalesconnectionfilter = $request->input('viewreportsalesconnectionfilter');
                    $insertpermission->viewreportprofitbyuser = $request->input('viewreportprofitbyuser');
                    $insertpermission->viewreportprofitbyuserfilter = $request->input('viewreportprofitbyuserfilter');
                    $insertpermission->viewreportprofitbycategory = $request->input('viewreportprofitbycategory');
                    $insertpermission->viewreportprofitbycategoryfilter = $request->input('viewreportprofitbycategoryfilter');
                    $insertpermission->viewreportprofitbyconnection = $request->input('viewreportprofitbyconnection');
                    $insertpermission->viewreportprofitbyconnectionfilter = $request->input('viewreportprofitbyconnectionfilter');
                    $insertpermission->viewreportstockhistory = $request->input('viewreportstockhistory');
                    $insertpermission->viewreportstockhistoryfilter = $request->input('viewreportstockhistoryfilter');
                    $insertpermission->viewreportstocktransfer = $request->input('viewreportstocktransfer');
                    $insertpermission->viewreportstocktransferfilter = $request->input('viewreportstocktransferfilter');
                    $insertpermission->viewreportstockreturn = $request->input('viewreportstockreturn');
                    $insertpermission->viewreportstockreturnfilter = $request->input('viewreportstockreturnfilter');
                    $insertpermission->viewreportstockholding = $request->input('viewreportstockholding');
                    $insertpermission->viewreportstockholdingfilter = $request->input('viewreportstockholdingfilter');
                    $insertpermission->viewreportproductreceive = $request->input('viewreportproductreceive');
                    $insertpermission->viewreportproductreceivefilters = $request->input('viewreportproductreceivefilters');
                    $insertpermission->viewdemoreceive = $request->input('viewdemoreceive');
                    $insertpermission->adddemoreceive = $request->input('adddemoreceive');
                    $insertpermission->viewstockreturn = $request->input('viewstockreturn');
                    $insertpermission->addstockreturn = $request->input('addstockreturn');
                    $insertpermission->viewstockreturnfilter = $request->input('viewstockreturnfilter');
                    $insertpermission->editstockreturnitem = $request->input('editstockreturnitem');
                    $insertpermission->deletestockreturnitem = $request->input('deletestockreturnitem');
                    $insertpermission->stockreturnAdminAprroval = $request->input('stockreturnAdminAprroval');
                    $insertpermission->bulk_appacccomission = $request->input('bulk_appacccomission');
                    $insertpermission->reportroster = $request->input('reportroster');
                    $insertpermission->reportrosterfilter = $request->input('reportrosterfilter');
                    $insertpermission->livestocktake = $request->input('livestocktake');
                    $insertpermission->upfrontreport = $request->input('upfrontreport');
                    $insertpermission->save();

                    if($insertpermission->save())
                    {
                        return redirect('usergroups');
                    }
                    else
                    {
                        return redirect()->back()->with('error', 'Something went wrong. Please try again');
                    }
                }
                else
                {
                    return redirect()->back()->with('error', 'Failed to create group. Please try again');
                }
            }
            else
            {
                return redirect()->back()->with('error', 'Group name already exist. Please choose another.');
            }
        }
    }

    public function editusergroup(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.editusergroup')=='N' ||session::get('loggindata.loggeduserpermission.editusergroup')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getusergroup = usergroup::where('usertypeID', $request->input('usertypeid'))
            ->first();

            if($getusergroup != '')
            {
                $getusergroup->usertypeName = $request->input('usergroupname');
                $getusergroup->typedescription = $request->input('description');
                $getusergroup->save();

                if($getusergroup->save())
                {
                    return redirect()->back()->with('success', 'Group changes updated successfully.');
                }
                else
                {
                    return redirect()->back()->with('error', 'Fail to update group changes.');
                }
            }
            else
            {
                return redirect()->back()->with('error', 'Fail to fetch user group data.');
            }
        }
    }
}
