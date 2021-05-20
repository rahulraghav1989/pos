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

    public function usermasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.vieweuser')=='N' ||session::get('loggindata.loggeduserpermission.vieweuser')=='')
        {
            return redirect('404');
        } 
        else
        {
            if(session::get('loggindata.loggeduserstore')!='')
            {
            	$users= loggeduser::
	            join('userlogintype', 'userlogintype.userID', '=', 'id')
	            ->join('usertype', 'usertype.usertypeID', '=', 'userlogintype.usertypeID')
	            ->leftJoin('storeuser', 'storeuser.userID', '=', 'users.id')
	            ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
	            ->where('storeuser.store_id', session::get('loggindata.loggeduserstore.store_id'))
	            ->get();
            }
            else
            {
            	$users= loggeduser::
	            join('userlogintype', 'userlogintype.userID', '=', 'id')
	            ->join('usertype', 'usertype.usertypeID', '=', 'userlogintype.usertypeID')
	            ->leftJoin('storeuser', 'storeuser.userID', '=', 'users.id')
	            ->leftJoin('store', 'store.store_id', '=', 'storeuser.store_id')
	            ->get();
            }

            $allusergroup = usergroup::get();
            $allstore = store::get();

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

                        if($request->input('usertype') == 1)
                        {
                            $userpermission = new userpermission;
                            $userpermission->userID = $lastinsertedid;
                            $userpermission->changestore = 'Y';
                            $userpermission->newsale = 'Y';
                            $userpermission->refund = 'Y';
                            $userpermission->viewmasters = 'Y';
                            $userpermission->addmasters = 'Y';
                            $userpermission->editmasters = 'Y';
                            $userpermission->deletemaster = 'Y';
                            $userpermission->vieweuser = 'Y';
                            $userpermission->adduser = 'Y';
                            $userpermission->edituser = 'Y';
                            $userpermission->deleteuser = 'Y';
                            $userpermission->editpermission = 'Y';
                            $userpermission->viewusergroup = 'Y';
                            $userpermission->viewproducts = 'Y';
                            $userpermission->addproducts = 'Y';
                            $userpermission->editproducts = 'Y';
                            $userpermission->deleteproducts = 'Y';
                            $userpermission->viewproductfilters = 'Y';
                            $userpermission->searchproducts = 'Y';
                            $userpermission->searchproductsbystore = 'Y';
                            $userpermission->viewpurchaseorder = 'Y';
                            $userpermission->addpurchaseorder = 'Y';
                            $userpermission->editpurchaseorder = 'Y';
                            $userpermission->deletepurchaseorder = 'Y';
                            $userpermission->editpurchaseorderitem = 'Y';
                            $userpermission->deletepurchaseorderitem = 'Y';
                            $userpermission->viewpurchaseorderprice = 'Y';
                            $userpermission->receivepurchaseorder = 'Y';
                            $userpermission->viewpurchaseorderfilters = 'Y';
                            $userpermission->viewpurchaseorderreceivefilters = 'Y';
                            $userpermission->viewplans = 'Y';
                            $userpermission->addplans = 'Y';
                            $userpermission->editplans = 'Y';
                            $userpermission->deleteplans = 'Y';
                            $userpermission->viewplansfilters = 'Y';
                            $userpermission->viewinstock = 'Y';
                            $userpermission->viewdemostock = 'Y';
                            $userpermission->viewsalehistory = 'Y';
                            $userpermission->viewsalehistoryfilters = 'Y';
                            $userpermission->viewtimesheet = 'Y';
                            $userpermission->addtimesheet = 'Y';
                            $userpermission->viewrostermanager = 'Y';
                            $userpermission->rostermanagerpay = 'Y';
                            $userpermission->reportEOD = 'Y';
                            $userpermission->reportEODtill = 'Y';
                            $userpermission->reportEODfilter = 'Y';
                            $userpermission->viewstocktransferout = 'Y';
                            $userpermission->viewstocktransferin = 'Y';
                            $userpermission->addstocktransfer = 'Y';
                            $userpermission->viewstocktransferfilters = 'Y';
                            $userpermission->viewcustomer = 'Y';
                            $userpermission->addcustomer = 'Y';
                            $userpermission->editcustomer = 'Y';
                            $userpermission->viewtracker = 'Y';
                            $userpermission->viewtrackerfilter = 'Y';
                            $userpermission->viewstoretracker = 'Y';
                            $userpermission->viewstoretrackerfilter = 'Y';
                            $userpermission->addpersonaltarget = 'Y';
                            $userpermission->addstoretarget = 'Y';
                            $userpermission->viewtrackerbonus = 'Y';
                            $userpermission->viewstoretrackerbonus = 'Y';
                            $userpermission->viewreportsalesbyuser = 'Y';
                            $userpermission->viewreportsalesbyuserfilter = 'Y';
                            $userpermission->viewreportsalesbyuserexport = 'Y';
                            $userpermission->viewreportsalespaymentmethod = 'Y';
                            $userpermission->viewreportsalespaymentmethodfilter = 'Y';
                            $userpermission->viewreportsalespaymentmethodexport = 'Y';
                            $userpermission->viewreportsalesmaster = 'Y';
                            $userpermission->viewreportsalesmasterfilter = 'Y';
                            $userpermission->viewreportsalesmasterexport = 'Y';
                            $userpermission->viewreportsalesconnection = 'Y';
                            $userpermission->viewreportsalesconnectionfilter = 'Y';
                            $userpermission->viewreportsalesconnectionexport = 'Y';
                            $userpermission->viewreportprofitbyuser = 'Y';
                            $userpermission->viewreportprofitbyuserfilter = 'Y';
                            $userpermission->viewreportprofitbycategory = 'Y';
                            $userpermission->viewreportprofitbycategoryfilter = 'Y';
                            $userpermission->viewreportprofitbyconnection = 'Y';
                            $userpermission->viewreportprofitbyconnectionfilter = 'Y';
                            $userpermission->viewreportstockhistory = 'Y';
                            $userpermission->viewreportstockhistoryfilter = 'Y';
                            $userpermission->viewreportstocktransfer = 'Y';
                            $userpermission->viewreportstocktransferfilter = 'Y';
                            $userpermission->viewreportstockreturn = 'Y';
                            $userpermission->viewreportstockreturnfilter = 'Y';
                            $userpermission->viewreportstockholding = 'Y';
                            $userpermission->viewreportstockholdingfilter = 'Y';
                            $userpermission->viewreportproductreceive = 'Y';
                            $userpermission->viewreportproductreceivefilters = 'Y';
                            $userpermission->viewdemoreceive = 'Y';
                            $userpermission->adddemoreceive = 'Y';
                            $userpermission->viewstockreturn = 'Y';
                            $userpermission->addstockreturn = 'Y';
                            $userpermission->viewstockreturnfilter = 'Y';
                            $userpermission->editstockreturnitem = 'Y';
                            $userpermission->deletestockreturnitem = 'Y';
                            $userpermission->stockreturnAdminAprroval = 'Y';
                            $userpermission->bulk_appacccomission = 'Y';
                            $userpermission->save();
                        }
                        else if($request->input('usertype') == 2) 
                        {
                            $userpermission = new userpermission;
                            $userpermission->userID = $lastinsertedid;
                            $userpermission->changestore = 'Y';
                            $userpermission->newsale = 'N';
                            $userpermission->refund = 'N';
                            $userpermission->viewmasters = 'N';
                            $userpermission->addmasters = 'N';
                            $userpermission->editmasters = 'N';
                            $userpermission->deletemaster = 'N';
                            $userpermission->vieweuser = 'Y';
                            $userpermission->adduser = 'N';
                            $userpermission->edituser = 'N';
                            $userpermission->deleteuser = 'N';
                            $userpermission->editpermission = 'N';
                            $userpermission->viewusergroup = 'N';
                            $userpermission->viewproducts = 'N';
                            $userpermission->addproducts = 'N';
                            $userpermission->editproducts = 'N';
                            $userpermission->deleteproducts = 'N';
                            $userpermission->viewproductfilters = 'N';
                            $userpermission->searchproducts = 'N';
                            $userpermission->searchproductsbystore = 'N';
                            $userpermission->viewpurchaseorder = 'N';
                            $userpermission->addpurchaseorder = 'N';
                            $userpermission->editpurchaseorder = 'N';
                            $userpermission->deletepurchaseorder = 'N';
                            $userpermission->editpurchaseorderitem = 'N';
                            $userpermission->deletepurchaseorderitem = 'N';
                            $userpermission->viewpurchaseorderprice = 'N';
                            $userpermission->receivepurchaseorder = 'N';
                            $userpermission->viewpurchaseorderfilters = 'N';
                            $userpermission->viewpurchaseorderreceivefilters = 'N';
                            $userpermission->viewplans = 'N';
                            $userpermission->addplans = 'N';
                            $userpermission->editplans = 'N';
                            $userpermission->deleteplans = 'N';
                            $userpermission->viewplansfilters = 'N';
                            $userpermission->viewinstock = 'N';
                            $userpermission->viewdemostock = 'N';
                            $userpermission->viewsalehistory = 'N';
                            $userpermission->viewsalehistoryfilters = 'N';
                            $userpermission->viewtimesheet = 'N';
                            $userpermission->addtimesheet = 'N';
                            $userpermission->viewrostermanager = 'N';
                            $userpermission->rostermanagerpay = 'N';
                            $userpermission->reportEOD = 'N';
                            $userpermission->reportEODtill = 'N';
                            $userpermission->reportEODfilter = 'N';
                            $userpermission->viewstocktransferout = 'N';
                            $userpermission->viewstocktransferin = 'N';
                            $userpermission->addstocktransfer = 'N';
                            $userpermission->viewstocktransferfilters = 'N';
                            $userpermission->viewcustomer = 'N';
                            $userpermission->addcustomer = 'N';
                            $userpermission->editcustomer = 'N';
                            $userpermission->viewtracker = 'N';
                            $userpermission->viewtrackerfilter = 'N';
                            $userpermission->viewstoretracker = 'N';
                            $userpermission->viewstoretrackerfilter = 'N';
                            $userpermission->addpersonaltarget = 'N';
                            $userpermission->addstoretarget = 'N';
                            $userpermission->viewtrackerbonus = 'N';
                            $userpermission->viewstoretrackerbonus = 'N';
                            $userpermission->viewreportsalesbyuser = 'Y';
                            $userpermission->viewreportsalesbyuserfilter = 'Y';
                            $userpermission->viewreportsalesbyuserexport = 'Y';
                            $userpermission->viewreportsalespaymentmethod = 'Y';
                            $userpermission->viewreportsalespaymentmethodfilter = 'Y';
                            $userpermission->viewreportsalespaymentmethodexport = 'Y';
                            $userpermission->viewreportsalesmaster = 'Y';
                            $userpermission->viewreportsalesmasterfilter = 'Y';
                            $userpermission->viewreportsalesmasterexport = 'Y';
                            $userpermission->viewreportsalesconnection = 'Y';
                            $userpermission->viewreportsalesconnectionfilter = 'Y';
                            $userpermission->viewreportsalesconnectionexport = 'Y';
                            $userpermission->viewreportprofitbyuser = 'Y';
                            $userpermission->viewreportprofitbyuserfilter = 'Y';
                            $userpermission->viewreportprofitbycategory = 'Y';
                            $userpermission->viewreportprofitbycategoryfilter = 'Y';
                            $userpermission->viewreportprofitbyconnection = 'Y';
                            $userpermission->viewreportprofitbyconnectionfilter = 'Y';
                            $userpermission->viewreportstockhistory = 'Y';
                            $userpermission->viewreportstockhistoryfilter = 'Y';
                            $userpermission->viewreportstocktransfer = 'Y';
                            $userpermission->viewreportstocktransferfilter = 'Y';
                            $userpermission->viewreportstockreturn = 'Y';
                            $userpermission->viewreportstockreturnfilter = 'Y';
                            $userpermission->viewreportstockholding = 'Y';
                            $userpermission->viewreportstockholdingfilter = 'Y';
                            $userpermission->viewreportproductreceive = 'Y';
                            $userpermission->viewreportproductreceivefilters = 'Y';
                            $userpermission->viewdemoreceive = 'N';
                            $userpermission->adddemoreceive = 'N';
                            $userpermission->viewstockreturn = 'N';
                            $userpermission->addstockreturn = 'N';
                            $userpermission->viewstockreturnfilter = 'N';
                            $userpermission->editstockreturnitem = 'N';
                            $userpermission->deletestockreturnitem = 'N';
                            $userpermission->stockreturnAdminAprroval = 'N';
                            $userpermission->bulk_appacccomission = 'N';
                            $userpermission->save();
                        }
                        else if($request->input('usertype') == 3) 
                        {
                            $userpermission = new userpermission;
                            $userpermission->userID = $lastinsertedid;
                            $userpermission->changestore = 'N';
                            $userpermission->newsale = 'Y';
                            $userpermission->refund = 'Y';
                            $userpermission->viewmasters = 'N';
                            $userpermission->addmasters = 'N';
                            $userpermission->editmasters = 'N';
                            $userpermission->deletemaster = 'N';
                            $userpermission->vieweuser = 'Y';
                            $userpermission->adduser = 'N';
                            $userpermission->edituser = 'N';
                            $userpermission->deleteuser = 'N';
                            $userpermission->editpermission = 'N';
                            $userpermission->viewusergroup = 'N';
                            $userpermission->viewproducts = 'Y';
                            $userpermission->addproducts = 'N';
                            $userpermission->editproducts = 'N';
                            $userpermission->deleteproducts = 'N';
                            $userpermission->viewproductfilters = 'N';
                            $userpermission->searchproducts = 'Y';
                            $userpermission->searchproductsbystore = 'N';
                            $userpermission->viewpurchaseorder = 'Y';
                            $userpermission->addpurchaseorder = 'Y';
                            $userpermission->editpurchaseorder = 'Y';
                            $userpermission->deletepurchaseorder = 'Y';
                            $userpermission->editpurchaseorderitem = 'Y';
                            $userpermission->deletepurchaseorderitem = 'Y';
                            $userpermission->viewpurchaseorderprice = 'N';
                            $userpermission->receivepurchaseorder = 'Y';
                            $userpermission->viewpurchaseorderfilters = 'N';
                            $userpermission->viewpurchaseorderreceivefilters = 'N';
                            $userpermission->viewplans = 'Y';
                            $userpermission->addplans = 'N';
                            $userpermission->editplans = 'N';
                            $userpermission->deleteplans = 'N';
                            $userpermission->viewplansfilters = 'N';
                            $userpermission->viewinstock = 'Y';
                            $userpermission->viewdemostock = 'Y';
                            $userpermission->viewsalehistory = 'Y';
                            $userpermission->viewsalehistoryfilters = 'N';
                            $userpermission->viewtimesheet = 'Y';
                            $userpermission->addtimesheet = 'Y';
                            $userpermission->viewrostermanager = 'N';
                            $userpermission->rostermanagerpay = 'N';
                            $userpermission->reportEOD = 'Y';
                            $userpermission->reportEODtill = 'Y';
                            $userpermission->reportEODfilter = 'N';
                            $userpermission->viewstocktransferout = 'Y';
                            $userpermission->viewstocktransferin = 'Y';
                            $userpermission->addstocktransfer = 'Y';
                            $userpermission->viewstocktransferfilters = 'N';
                            $userpermission->viewcustomer = 'Y';
                            $userpermission->addcustomer = 'Y';
                            $userpermission->editcustomer = 'N';
                            $userpermission->viewtracker = 'Y';
                            $userpermission->viewtrackerfilter = 'N';
                            $userpermission->viewstoretracker = 'Y';
                            $userpermission->viewstoretrackerfilter = 'N';
                            $userpermission->addpersonaltarget = 'N';
                            $userpermission->addstoretarget = 'N';
                            $userpermission->viewtrackerbonus = 'Y';
                            $userpermission->viewstoretrackerbonus = 'Y';
                            $userpermission->viewreportsalesbyuser = 'Y';
                            $userpermission->viewreportsalesbyuserfilter = 'N';
                            $userpermission->viewreportsalesbyuserexport = 'N';
                            $userpermission->viewreportsalespaymentmethod = 'Y';
                            $userpermission->viewreportsalespaymentmethodfilter = 'N';
                            $userpermission->viewreportsalespaymentmethodexport = 'N';
                            $userpermission->viewreportsalesmaster = 'N';
                            $userpermission->viewreportsalesmasterfilter = 'N';
                            $userpermission->viewreportsalesmasterexport = 'N';
                            $userpermission->viewreportsalesconnection = 'Y';
                            $userpermission->viewreportsalesconnectionfilter = 'N';
                            $userpermission->viewreportsalesconnectionexport = 'N';
                            $userpermission->viewreportprofitbyuser = 'N';
                            $userpermission->viewreportprofitbyuserfilter = 'N';
                            $userpermission->viewreportprofitbycategory = 'N';
                            $userpermission->viewreportprofitbycategoryfilter = 'N';
                            $userpermission->viewreportprofitbyconnection = 'N';
                            $userpermission->viewreportprofitbyconnectionfilter = 'N';
                            $userpermission->viewreportstockhistory = 'Y';
                            $userpermission->viewreportstockhistoryfilter = 'N';
                            $userpermission->viewreportstocktransfer = 'Y';
                            $userpermission->viewreportstocktransferfilter = 'N';
                            $userpermission->viewreportstockreturn = 'Y';
                            $userpermission->viewreportstockreturnfilter = 'N';
                            $userpermission->viewreportstockholding = 'Y';
                            $userpermission->viewreportstockholdingfilter = 'N';
                            $userpermission->viewreportproductreceive = 'Y';
                            $userpermission->viewreportproductreceivefilters = 'N';
                            $userpermission->viewdemoreceive = 'Y';
                            $userpermission->adddemoreceive = 'Y';
                            $userpermission->viewstockreturn = 'Y';
                            $userpermission->addstockreturn = 'Y';
                            $userpermission->viewstockreturnfilter = 'N';
                            $userpermission->editstockreturnitem = 'Y';
                            $userpermission->deletestockreturnitem = 'Y';
                            $userpermission->stockreturnAdminAprroval = 'N';
                            $userpermission->bulk_appacccomission = 'N';
                            $userpermission->save();
                        }
                        else if($request->input('usertype') == 4) 
                        {
                            $userpermission = new userpermission;
                            $userpermission->userID = $lastinsertedid;
                            $userpermission->changestore = 'N';
                            $userpermission->newsale = 'Y';
                            $userpermission->refund = 'Y';
                            $userpermission->viewmasters = 'N';
                            $userpermission->addmasters = 'N';
                            $userpermission->editmasters = 'N';
                            $userpermission->deletemaster = 'N';
                            $userpermission->vieweuser = 'Y';
                            $userpermission->adduser = 'N';
                            $userpermission->edituser = 'N';
                            $userpermission->deleteuser = 'N';
                            $userpermission->editpermission = 'N';
                            $userpermission->viewusergroup = 'N';
                            $userpermission->viewproducts = 'Y';
                            $userpermission->addproducts = 'N';
                            $userpermission->editproducts = 'N';
                            $userpermission->deleteproducts = 'N';
                            $userpermission->viewproductfilters = 'N';
                            $userpermission->searchproducts = 'Y';
                            $userpermission->searchproductsbystore = 'N';
                            $userpermission->viewpurchaseorder = 'Y';
                            $userpermission->addpurchaseorder = 'Y';
                            $userpermission->editpurchaseorder = 'Y';
                            $userpermission->deletepurchaseorder = 'Y';
                            $userpermission->editpurchaseorderitem = 'Y';
                            $userpermission->deletepurchaseorderitem = 'Y';
                            $userpermission->viewpurchaseorderprice = 'N';
                            $userpermission->receivepurchaseorder = 'Y';
                            $userpermission->viewpurchaseorderfilters = 'N';
                            $userpermission->viewpurchaseorderreceivefilters = 'N';
                            $userpermission->viewplans = 'Y';
                            $userpermission->addplans = 'N';
                            $userpermission->editplans = 'N';
                            $userpermission->deleteplans = 'N';
                            $userpermission->viewplansfilters = 'N';
                            $userpermission->viewinstock = 'Y';
                            $userpermission->viewdemostock = 'Y';
                            $userpermission->viewsalehistory = 'Y';
                            $userpermission->viewsalehistoryfilters = 'N';
                            $userpermission->viewtimesheet = 'Y';
                            $userpermission->addtimesheet = 'Y';
                            $userpermission->viewrostermanager = 'N';
                            $userpermission->rostermanagerpay = 'N';
                            $userpermission->reportEOD = 'Y';
                            $userpermission->reportEODtill = 'Y';
                            $userpermission->reportEODfilter = 'N';
                            $userpermission->viewstocktransferout = 'Y';
                            $userpermission->viewstocktransferin = 'Y';
                            $userpermission->addstocktransfer = 'Y';
                            $userpermission->viewstocktransferfilters = 'N';
                            $userpermission->viewcustomer = 'Y';
                            $userpermission->addcustomer = 'Y';
                            $userpermission->editcustomer = 'Y';
                            $userpermission->viewtracker = 'Y';
                            $userpermission->viewtrackerfilter = 'N';
                            $userpermission->viewstoretracker = 'N';
                            $userpermission->viewstoretrackerfilter = 'N';
                            $userpermission->addpersonaltarget = 'N';
                            $userpermission->addstoretarget = 'N';
                            $userpermission->viewtrackerbonus = 'N';
                            $userpermission->viewstoretrackerbonus = 'N';
                            $userpermission->viewreportsalesbyuser = 'Y';
                            $userpermission->viewreportsalesbyuserfilter = 'N';
                            $userpermission->viewreportsalesbyuserexport = 'N';
                            $userpermission->viewreportsalespaymentmethod = 'Y';
                            $userpermission->viewreportsalespaymentmethodfilter = 'N';
                            $userpermission->viewreportsalespaymentmethodexport = 'N';
                            $userpermission->viewreportsalesmaster = 'N';
                            $userpermission->viewreportsalesmasterfilter = 'N';
                            $userpermission->viewreportsalesmasterexport = 'N';
                            $userpermission->viewreportsalesconnection = 'Y';
                            $userpermission->viewreportsalesconnectionfilter = 'N';
                            $userpermission->viewreportsalesconnectionexport = 'N';
                            $userpermission->viewreportprofitbyuser = 'N';
                            $userpermission->viewreportprofitbyuserfilter = 'N';
                            $userpermission->viewreportprofitbycategory = 'N';
                            $userpermission->viewreportprofitbycategoryfilter = 'N';
                            $userpermission->viewreportprofitbyconnection = 'N';
                            $userpermission->viewreportprofitbyconnectionfilter = 'N';
                            $userpermission->viewreportstockhistory = 'Y';
                            $userpermission->viewreportstockhistoryfilter = 'N';
                            $userpermission->viewreportstocktransfer = 'Y';
                            $userpermission->viewreportstocktransferfilter = 'N';
                            $userpermission->viewreportstockreturn = 'Y';
                            $userpermission->viewreportstockreturnfilter = 'N';
                            $userpermission->viewreportstockholding = 'Y';
                            $userpermission->viewreportstockholdingfilter = 'N';
                            $userpermission->viewreportproductreceive = 'Y';
                            $userpermission->viewreportproductreceivefilters = 'N';
                            $userpermission->viewdemoreceive = 'Y';
                            $userpermission->adddemoreceive = 'Y';
                            $userpermission->viewstockreturn = 'Y';
                            $userpermission->addstockreturn = 'Y';
                            $userpermission->viewstockreturnfilter = 'N';
                            $userpermission->editstockreturnitem = 'Y';
                            $userpermission->deletestockreturnitem = 'Y';
                            $userpermission->stockreturnAdminAprroval = 'N';
                            $userpermission->bulk_appacccomission = 'N';
                            $userpermission->save();
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

                        if($request->input('usertype') == 1)
                        {
                            $checkpermission = userpermission::where('userID', $request->input('userid'))->first();
                            $checkpermission->changestore = 'Y';
                            $checkpermission->newsale = 'Y';
                            $checkpermission->refund = 'Y';
                            $checkpermission->viewmasters = 'Y';
                            $checkpermission->addmasters = 'Y';
                            $checkpermission->editmasters = 'Y';
                            $checkpermission->deletemaster = 'Y';
                            $checkpermission->vieweuser = 'Y';
                            $checkpermission->adduser = 'Y';
                            $checkpermission->edituser = 'Y';
                            $checkpermission->deleteuser = 'Y';
                            $checkpermission->editpermission = 'Y';
                            $checkpermission->viewusergroup = 'Y';
                            $checkpermission->viewproducts = 'Y';
                            $checkpermission->addproducts = 'Y';
                            $checkpermission->editproducts = 'Y';
                            $checkpermission->deleteproducts = 'Y';
                            $checkpermission->viewproductfilters = 'Y';
                            $checkpermission->searchproducts = 'Y';
                            $checkpermission->searchproductsbystore = 'Y';
                            $checkpermission->viewpurchaseorder = 'Y';
                            $checkpermission->addpurchaseorder = 'Y';
                            $checkpermission->editpurchaseorder = 'Y';
                            $checkpermission->deletepurchaseorder = 'Y';
                            $checkpermission->editpurchaseorderitem = 'Y';
                            $checkpermission->deletepurchaseorderitem = 'Y';
                            $checkpermission->viewpurchaseorderprice = 'Y';
                            $checkpermission->receivepurchaseorder = 'Y';
                            $checkpermission->viewpurchaseorderfilters = 'Y';
                            $checkpermission->viewpurchaseorderreceivefilters = 'Y';
                            $checkpermission->viewplans = 'Y';
                            $checkpermission->addplans = 'Y';
                            $checkpermission->editplans = 'Y';
                            $checkpermission->deleteplans = 'Y';
                            $checkpermission->viewplansfilters = 'Y';
                            $checkpermission->viewinstock = 'Y';
                            $checkpermission->viewdemostock = 'Y';
                            $checkpermission->viewsalehistory = 'Y';
                            $checkpermission->viewsalehistoryfilters = 'Y';
                            $checkpermission->viewtimesheet = 'Y';
                            $checkpermission->addtimesheet = 'Y';
                            $checkpermission->viewrostermanager = 'Y';
                            $checkpermission->rostermanagerpay = 'Y';
                            $checkpermission->reportEOD = 'Y';
                            $checkpermission->reportEODtill = 'Y';
                            $checkpermission->reportEODfilter = 'Y';
                            $checkpermission->viewstocktransferout = 'Y';
                            $checkpermission->viewstocktransferin = 'Y';
                            $checkpermission->addstocktransfer = 'Y';
                            $checkpermission->viewstocktransferfilters = 'Y';
                            $checkpermission->viewcustomer = 'Y';
                            $checkpermission->addcustomer = 'Y';
                            $checkpermission->editcustomer = 'Y';
                            $checkpermission->viewtracker = 'Y';
                            $checkpermission->viewtrackerfilter = 'Y';
                            $checkpermission->viewstoretracker = 'Y';
                            $checkpermission->viewstoretrackerfilter = 'Y';
                            $checkpermission->addpersonaltarget = 'Y';
                            $checkpermission->addstoretarget = 'Y';
                            $checkpermission->viewtrackerbonus = 'Y';
                            $checkpermission->viewstoretrackerbonus = 'Y';
                            $checkpermission->viewreportsalesbyuser = 'Y';
                            $checkpermission->viewreportsalesbyuserfilter = 'Y';
                            $checkpermission->viewreportsalesbyuserexport = 'Y';
                            $checkpermission->viewreportsalespaymentmethod = 'Y';
                            $checkpermission->viewreportsalespaymentmethodfilter = 'Y';
                            $checkpermission->viewreportsalespaymentmethodexport = 'Y';
                            $checkpermission->viewreportsalesmaster = 'Y';
                            $checkpermission->viewreportsalesmasterfilter = 'Y';
                            $checkpermission->viewreportsalesmasterexport = 'Y';
                            $checkpermission->viewreportsalesconnection = 'Y';
                            $checkpermission->viewreportsalesconnectionfilter = 'Y';
                            $checkpermission->viewreportsalesconnectionexport = 'Y';
                            $checkpermission->viewreportprofitbyuser = 'Y';
                            $checkpermission->viewreportprofitbyuserfilter = 'Y';
                            $checkpermission->viewreportprofitbycategory = 'Y';
                            $checkpermission->viewreportprofitbycategoryfilter = 'Y';
                            $checkpermission->viewreportprofitbyconnection = 'Y';
                            $checkpermission->viewreportprofitbyconnectionfilter = 'Y';
                            $checkpermission->viewreportstockhistory = 'Y';
                            $checkpermission->viewreportstockhistoryfilter = 'Y';
                            $checkpermission->viewreportstocktransfer = 'Y';
                            $checkpermission->viewreportstocktransferfilter = 'Y';
                            $checkpermission->viewreportstockreturn = 'Y';
                            $checkpermission->viewreportstockreturnfilter = 'Y';
                            $checkpermission->viewreportstockholding = 'Y';
                            $checkpermission->viewreportstockholdingfilter = 'Y';
                            $checkpermission->viewreportproductreceive = 'Y';
                            $checkpermission->viewreportproductreceivefilters = 'Y';
                            $checkpermission->viewdemoreceive = 'Y';
                            $checkpermission->adddemoreceive = 'Y';
                            $checkpermission->viewstockreturn = 'Y';
                            $checkpermission->addstockreturn = 'Y';
                            $checkpermission->viewstockreturnfilter = 'Y';
                            $checkpermission->editstockreturnitem = 'Y';
                            $checkpermission->deletestockreturnitem = 'Y';
                            $checkpermission->stockreturnAdminAprroval = 'Y';
                            $checkpermission->bulk_appacccomission = 'Y';
                            $checkpermission->save();
                        }
                        else if($request->input('usertype') == 2) 
                        {
                            $checkpermission1 = userpermission::where('userID', $request->input('userid'))->first();
                            $checkpermission1->changestore = 'Y';
                            $checkpermission1->newsale = 'N';
                            $checkpermission1->refund = 'N';
                            $checkpermission1->viewmasters = 'N';
                            $checkpermission1->addmasters = 'N';
                            $checkpermission1->editmasters = 'N';
                            $checkpermission1->deletemaster = 'N';
                            $checkpermission1->vieweuser = 'Y';
                            $checkpermission1->adduser = 'N';
                            $checkpermission1->edituser = 'N';
                            $checkpermission1->deleteuser = 'N';
                            $checkpermission1->editpermission = 'N';
                            $checkpermission1->viewusergroup = 'N';
                            $checkpermission1->viewproducts = 'N';
                            $checkpermission1->addproducts = 'N';
                            $checkpermission1->editproducts = 'N';
                            $checkpermission1->deleteproducts = 'N';
                            $checkpermission1->viewproductfilters = 'N';
                            $checkpermission1->searchproducts = 'N';
                            $checkpermission1->searchproductsbystore = 'N';
                            $checkpermission1->viewpurchaseorder = 'N';
                            $checkpermission1->addpurchaseorder = 'N';
                            $checkpermission1->editpurchaseorder = 'N';
                            $checkpermission1->deletepurchaseorder = 'N';
                            $checkpermission1->editpurchaseorderitem = 'N';
                            $checkpermission1->deletepurchaseorderitem = 'N';
                            $checkpermission1->viewpurchaseorderprice = 'N';
                            $checkpermission1->receivepurchaseorder = 'N';
                            $checkpermission1->viewpurchaseorderfilters = 'N';
                            $checkpermission1->viewpurchaseorderreceivefilters = 'N';
                            $checkpermission1->viewplans = 'N';
                            $checkpermission1->addplans = 'N';
                            $checkpermission1->editplans = 'N';
                            $checkpermission1->deleteplans = 'N';
                            $checkpermission1->viewplansfilters = 'N';
                            $checkpermission1->viewinstock = 'N';
                            $checkpermission->viewdemostock = 'N';
                            $checkpermission1->viewsalehistory = 'N';
                            $checkpermission1->viewsalehistoryfilters = 'N';
                            $checkpermission1->viewtimesheet = 'N';
                            $checkpermission1->addtimesheet = 'N';
                            $checkpermission1->viewrostermanager = 'N';
                            $checkpermission1->rostermanagerpay = 'N';
                            $checkpermission1->reportEOD = 'N';
                            $checkpermission1->reportEODtill = 'N';
                            $checkpermission1->reportEODfilter = 'N';
                            $checkpermission1->viewstocktransferout = 'N';
                            $checkpermission1->viewstocktransferin = 'N';
                            $checkpermission1->addstocktransfer = 'N';
                            $checkpermission1->viewstocktransferfilters = 'N';
                            $checkpermission1->viewcustomer = 'N';
                            $checkpermission1->addcustomer = 'N';
                            $checkpermission1->editcustomer = 'N';
                            $checkpermission1->viewtracker = 'N';
                            $checkpermission1->viewtrackerfilter = 'N';
                            $checkpermission1->viewstoretracker = 'N';
                            $checkpermission1->viewstoretrackerfilter = 'N';
                            $checkpermission1->addpersonaltarget = 'N';
                            $checkpermission1->addstoretarget = 'N';
                            $checkpermission->viewtrackerbonus = 'N';
                            $checkpermission->viewstoretrackerbonus = 'N';
                            $checkpermission1->viewreportsalesbyuser = 'Y';
                            $checkpermission1->viewreportsalesbyuserfilter = 'Y';
                            $checkpermission1->viewreportsalesbyuserexport = 'Y';
                            $checkpermission1->viewreportsalespaymentmethod = 'Y';
                            $checkpermission1->viewreportsalespaymentmethodfilter = 'Y';
                            $checkpermission1->viewreportsalespaymentmethodexport = 'Y';
                            $checkpermission1->viewreportsalesmaster = 'Y';
                            $checkpermission1->viewreportsalesmasterfilter = 'Y';
                            $checkpermission1->viewreportsalesmasterexport = 'Y';
                            $checkpermission1->viewreportsalesconnection = 'Y';
                            $checkpermission1->viewreportsalesconnectionfilter = 'Y';
                            $checkpermission1->viewreportsalesconnectionexport = 'Y';
                            $checkpermission1->viewreportprofitbyuser = 'Y';
                            $checkpermission1->viewreportprofitbyuserfilter = 'Y';
                            $checkpermission1->viewreportprofitbycategory = 'Y';
                            $checkpermission1->viewreportprofitbycategoryfilter = 'Y';
                            $checkpermission1->viewreportprofitbyconnection = 'Y';
                            $checkpermission1->viewreportprofitbyconnectionfilter = 'Y';
                            $checkpermission1->viewreportstockhistory = 'Y';
                            $checkpermission1->viewreportstockhistoryfilter = 'Y';
                            $checkpermission1->viewreportstocktransfer = 'Y';
                            $checkpermission1->viewreportstocktransferfilter = 'Y';
                            $checkpermission1->viewreportstockreturn = 'Y';
                            $checkpermission1->viewreportstockreturnfilter = 'Y';
                            $checkpermission1->viewreportstockholding = 'Y';
                            $checkpermission1->viewreportstockholdingfilter = 'Y';
                            $checkpermission->viewreportproductreceive = 'Y';
                            $checkpermission->viewreportproductreceivefilters = 'Y';
                            $checkpermission1->viewdemoreceive = 'N';
                            $checkpermission1->adddemoreceive = 'N';
                            $checkpermission1->viewstockreturn = 'N';
                            $checkpermission1->addstockreturn = 'N';
                            $checkpermission1->viewstockreturnfilter = 'N';
                            $checkpermission1->editstockreturnitem = 'N';
                            $checkpermission1->deletestockreturnitem = 'N';
                            $checkpermission1->stockreturnAdminAprroval = 'N';
                            $checkpermission1->bulk_appacccomission = 'N';
                            $checkpermission1->save();
                        }
                        else if($request->input('usertype') == 3) 
                        {
                            $checkpermission2 = userpermission::where('userID', $request->input('userid'))->first();
                            $checkpermission2->changestore = 'N';
                            $checkpermission2->newsale = 'Y';
                            $checkpermission2->refund = 'Y';
                            $checkpermission2->viewmasters = 'N';
                            $checkpermission2->addmasters = 'N';
                            $checkpermission2->editmasters = 'N';
                            $checkpermission2->deletemaster = 'N';
                            $checkpermission2->vieweuser = 'Y';
                            $checkpermission2->adduser = 'N';
                            $checkpermission2->edituser = 'N';
                            $checkpermission2->deleteuser = 'N';
                            $checkpermission2->editpermission = 'N';
                            $checkpermission2->viewusergroup = 'N';
                            $checkpermission2->viewproducts = 'Y';
                            $checkpermission2->addproducts = 'N';
                            $checkpermission2->editproducts = 'N';
                            $checkpermission2->deleteproducts = 'N';
                            $checkpermission2->viewproductfilters = 'N';
                            $checkpermission2->searchproducts = 'Y';
                            $checkpermission2->searchproductsbystore = 'N';
                            $checkpermission2->viewpurchaseorder = 'Y';
                            $checkpermission2->addpurchaseorder = 'Y';
                            $checkpermission2->editpurchaseorder = 'Y';
                            $checkpermission2->deletepurchaseorder = 'Y';
                            $checkpermission2->editpurchaseorderitem = 'Y';
                            $checkpermission2->deletepurchaseorderitem = 'Y';
                            $checkpermission2->viewpurchaseorderprice = 'N';
                            $checkpermission2->receivepurchaseorder = 'Y';
                            $checkpermission2->viewpurchaseorderfilters = 'N';
                            $checkpermission2->viewpurchaseorderreceivefilters = 'N';
                            $checkpermission2->viewplans = 'Y';
                            $checkpermission2->addplans = 'N';
                            $checkpermission2->editplans = 'N';
                            $checkpermission2->deleteplans = 'N';
                            $checkpermission2->viewplansfilters = 'N';
                            $checkpermission2->viewinstock = 'Y';
                            $checkpermission->viewdemostock = 'Y';
                            $checkpermission2->viewsalehistory = 'Y';
                            $checkpermission2->viewsalehistoryfilters = 'N';
                            $checkpermission2->viewtimesheet = 'Y';
                            $checkpermission2->addtimesheet = 'Y';
                            $checkpermission2->viewrostermanager = 'N';
                            $checkpermission2->rostermanagerpay = 'N';
                            $checkpermission2->reportEOD = 'Y';
                            $checkpermission2->reportEODtill = 'Y';
                            $checkpermission2->reportEODfilter = 'N';
                            $checkpermission2->viewstocktransferout = 'Y';
                            $checkpermission2->viewstocktransferin = 'Y';
                            $checkpermission2->addstocktransfer = 'Y';
                            $checkpermission2->viewstocktransferfilters = 'N';
                            $checkpermission2->viewcustomer = 'Y';
                            $checkpermission2->addcustomer = 'Y';
                            $checkpermission2->editcustomer = 'Y';
                            $checkpermission2->viewtracker = 'Y';
                            $checkpermission2->viewtrackerfilter = 'N';
                            $checkpermission2->viewstoretracker = 'Y';
                            $checkpermission2->viewstoretrackerfilter = 'N';
                            $checkpermission2->addpersonaltarget = 'N';
                            $checkpermission2->addstoretarget = 'N';
                            $checkpermission->viewtrackerbonus = 'Y';
                            $checkpermission->viewstoretrackerbonus = 'Y';
                            $checkpermission2->viewreportsalesbyuser = 'Y';
                            $checkpermission2->viewreportsalesbyuserfilter = 'N';
                            $checkpermission2->viewreportsalesbyuserexport = 'N';
                            $checkpermission2->viewreportsalespaymentmethod = 'Y';
                            $checkpermission2->viewreportsalespaymentmethodfilter = 'N';
                            $checkpermission2->viewreportsalespaymentmethodexport = 'N';
                            $checkpermission2->viewreportsalesmaster = 'N';
                            $checkpermission2->viewreportsalesmasterfilter = 'N';
                            $checkpermission2->viewreportsalesmasterexport = 'N';
                            $checkpermission2->viewreportsalesconnection = 'Y';
                            $checkpermission2->viewreportsalesconnectionfilter = 'N';
                            $checkpermission2->viewreportsalesconnectionexport = 'N';
                            $checkpermission2->viewreportprofitbyuser = 'N';
                            $checkpermission2->viewreportprofitbyuserfilter = 'N';
                            $checkpermission2->viewreportprofitbycategory = 'N';
                            $checkpermission2->viewreportprofitbycategoryfilter = 'N';
                            $checkpermission2->viewreportprofitbyconnection = 'N';
                            $checkpermission2->viewreportprofitbyconnectionfilter = 'N';
                            $checkpermission2->viewreportstockhistory = 'Y';
                            $checkpermission2->viewreportstockhistoryfilter = 'N';
                            $checkpermission2->viewreportstocktransfer = 'Y';
                            $checkpermission2->viewreportstocktransferfilter = 'N';
                            $checkpermission2->viewreportstockreturn = 'Y';
                            $checkpermission2->viewreportstockreturnfilter = 'N';
                            $checkpermission2->viewreportstockholding = 'Y';
                            $checkpermission2->viewreportstockholdingfilter = 'N';
                            $checkpermission->viewreportproductreceive = 'Y';
                            $checkpermission->viewreportproductreceivefilters = 'N';
                            $checkpermission2->viewdemoreceive = 'Y';
                            $checkpermission2->adddemoreceive = 'Y';
                            $checkpermission2->viewstockreturn = 'Y';
                            $checkpermission2->addstockreturn = 'Y';
                            $checkpermission2->viewstockreturnfilter = 'N';
                            $checkpermission2->editstockreturnitem = 'Y';
                            $checkpermission2->deletestockreturnitem = 'Y';
                            $checkpermission2->stockreturnAdminAprroval = 'N';
                            $checkpermission2->bulk_appacccomission = 'N';
                            $checkpermission2->save();
                        }
                        else if($request->input('usertype') == 4) 
                        {
                            $checkpermission3 = userpermission::where('userID', $request->input('userid'))->first();
                            $checkpermission3->changestore = 'N';
                            $checkpermission3->newsale = 'Y';
                            $checkpermission3->refund = 'Y';
                            $checkpermission3->viewmasters = 'N';
                            $checkpermission3->addmasters = 'N';
                            $checkpermission3->editmasters = 'N';
                            $checkpermission3->deletemaster = 'N';
                            $checkpermission3->vieweuser = 'Y';
                            $checkpermission3->adduser = 'N';
                            $checkpermission3->edituser = 'N';
                            $checkpermission3->deleteuser = 'N';
                            $checkpermission3->editpermission = 'N';
                            $checkpermission3->viewusergroup = 'N';
                            $checkpermission3->viewproducts = 'Y';
                            $checkpermission3->addproducts = 'N';
                            $checkpermission3->editproducts = 'N';
                            $checkpermission3->deleteproducts = 'N';
                            $checkpermission3->viewproductfilters = 'N';
                            $checkpermission3->searchproducts = 'Y';
                            $checkpermission3->searchproductsbystore = 'N';
                            $checkpermission3->viewpurchaseorder = 'Y';
                            $checkpermission3->addpurchaseorder = 'Y';
                            $checkpermission3->editpurchaseorder = 'Y';
                            $checkpermission3->deletepurchaseorder = 'Y';
                            $checkpermission3->editpurchaseorderitem = 'Y';
                            $checkpermission3->deletepurchaseorderitem = 'Y';
                            $checkpermission3->viewpurchaseorderprice = 'N';
                            $checkpermission3->receivepurchaseorder = 'Y';
                            $checkpermission3->viewpurchaseorderfilters = 'N';
                            $checkpermission3->viewpurchaseorderreceivefilters = 'N';
                            $checkpermission3->viewplans = 'Y';
                            $checkpermission3->addplans = 'N';
                            $checkpermission3->editplans = 'N';
                            $checkpermission3->deleteplans = 'N';
                            $checkpermission3->viewplansfilters = 'N';
                            $checkpermission3->viewinstock = 'Y';
                            $checkpermission->viewdemostock = 'Y';
                            $checkpermission3->viewsalehistory = 'Y';
                            $checkpermission3->viewsalehistoryfilters = 'N';
                            $checkpermission3->viewtimesheet = 'Y';
                            $checkpermission3->addtimesheet = 'Y';
                            $checkpermission3->viewrostermanager = 'N';
                            $checkpermission3->rostermanagerpay = 'N';
                            $checkpermission3->reportEOD = 'Y';
                            $checkpermission3->reportEODtill = 'Y';
                            $checkpermission3->reportEODfilter = 'N';
                            $checkpermission3->viewstocktransferout = 'Y';
                            $checkpermission3->viewstocktransferin = 'Y';
                            $checkpermission3->addstocktransfer = 'Y';
                            $checkpermission3->viewstocktransferfilters = 'N';
                            $checkpermission3->viewcustomer = 'Y';
                            $checkpermission3->addcustomer = 'Y';
                            $checkpermission3->editcustomer = 'Y';
                            $checkpermission3->viewtracker = 'Y';
                            $checkpermission3->viewtrackerfilter = 'N';
                            $checkpermission3->viewstoretracker = 'N';
                            $checkpermission3->viewstoretrackerfilter = 'N';
                            $checkpermission3->addpersonaltarget = 'N';
                            $checkpermission3->addstoretarget = 'N';
                            $checkpermission->viewtrackerbonus = 'N';
                            $checkpermission->viewstoretrackerbonus = 'N';
                            $checkpermission3->viewreportsalesbyuser = 'Y';
                            $checkpermission3->viewreportsalesbyuserfilter = 'N';
                            $checkpermission3->viewreportsalesbyuserexport = 'N';
                            $checkpermission3->viewreportsalespaymentmethod = 'Y';
                            $checkpermission3->viewreportsalespaymentmethodfilter = 'N';
                            $checkpermission3->viewreportsalespaymentmethodexport = 'N';
                            $checkpermission3->viewreportsalesmaster = 'N';
                            $checkpermission3->viewreportsalesmasterfilter = 'N';
                            $checkpermission3->viewreportsalesmasterexport = 'N';
                            $checkpermission3->viewreportsalesconnection = 'Y';
                            $checkpermission3->viewreportsalesconnectionfilter = 'N';
                            $checkpermission3->viewreportsalesconnectionexport = 'N';
                            $checkpermission3->viewreportprofitbyuser = 'N';
                            $checkpermission3->viewreportprofitbyuserfilter = 'N';
                            $checkpermission3->viewreportprofitbycategory = 'N';
                            $checkpermission3->viewreportprofitbycategoryfilter = 'N';
                            $checkpermission3->viewreportprofitbyconnection = 'N';
                            $checkpermission3->viewreportprofitbyconnectionfilter = 'N';
                            $checkpermission3->viewreportstockhistory = 'Y';
                            $checkpermission3->viewreportstockhistoryfilter = 'N';
                            $checkpermission3->viewreportstocktransfer = 'Y';
                            $checkpermission3->viewreportstocktransferfilter = 'N';
                            $checkpermission3->viewreportstockreturn = 'Y';
                            $checkpermission3->viewreportstockreturnfilter = 'N';
                            $checkpermission3->viewreportstockholding = 'Y';
                            $checkpermission3->viewreportstockholdingfilter = 'N';
                            $checkpermission->viewreportproductreceive = 'Y';
                            $checkpermission->viewreportproductreceivefilters = 'N';
                            $checkpermission3->viewdemoreceive = 'Y';
                            $checkpermission3->adddemoreceive = 'Y';
                            $checkpermission3->viewstockreturn = 'Y';
                            $checkpermission3->addstockreturn = 'Y';
                            $checkpermission3->viewstockreturnfilter = 'N';
                            $checkpermission3->editstockreturnitem = 'Y';
                            $checkpermission3->deletestockreturnitem = 'Y';
                            $checkpermission3->stockreturnAdminAprroval = 'N';
                            $checkpermission3->bulk_appacccomission = 'N';
                            $checkpermission3->save();
                        }
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

            $editallstore = store::where('storestatus', '1')->get();

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
}
