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
use App\customer;
use App\orderdetail;

class customerController extends Controller
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

    public function customerview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewcustomer')=='N' ||session::get('loggindata.loggeduserpermission.viewcustomer')=='')
        {
            return redirect('404');
        } 
        else
        {
        	
        	$getcustomer = customer::leftJoin('users', 'users.id', '=', 'userID')
        	->leftJoin('store', 'store.store_id', '=', 'customer.storeID')
        	->get(
        		array(
            			'customer.customerID', 
            			'customer.customertype', 
            			'customer.customertitle', 
            			'customer.customerfirstname', 
            			'customer.customerlastname', 
            			'customer.customermobilenumber', 
            			'customer.customerhomenumber', 
            			'customer.customeraltcontactnumber', 
            			'customer.customerdob', 
            			'customer.customeremail',
            			'customer.customerbusinessname',
            			'customer.customeracnabn',
            			'customer.customerbusinessemail',
            			'customer.customerbusinesswebsite',
            			'customer.customeraddress',
            			'customer.customerpostcode',
            			'customer.customersuburbname',
            			'customer.customerstate',
            			'customer.customernote',
            			'customer.onAccountPayment',
            			'customer.userID',
            			'customer.storeID',
            			'customer.created_at',
            			'users.id',
            			'users.name',
            			'store.store_id',
            			'store.store_name'
            		)
            	);

            $stores = store::where('storestatus', '1')->get();

            $customerdata = ['getcustomer'=>$getcustomer, 'stores'=>$stores];

            return view('customer')->with('customerdata', $customerdata);
        }
    }

    public function addcustomer(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addcustomer')=='N' ||session::get('loggindata.loggeduserpermission.addcustomer')=='')
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
                'title'=>'required',
                'firstname'=>'required',
                'lastname'=>'required',
                'mobilenumber'=>'required'
                ],[
                    'customertype.required'=>'Customer Type is required',
                    'title.required'=>'Customer Title is required',
                    'firstname.required'=>'Customer First Name is required',
                    'lastname.required'=>'Customer Last Name is required',
                    'mobilenumber.required'=>'Customer Mobile Number is required'
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
                        $insertcustomer->storeID= $request->input('store');
                        
                        $insertcustomer->save();

                        if($insertcustomer->save())
                        {
                           return redirect()->back()->with('success','Customer Added Successfully'); 
                        }
                        else
                        {
                            return redirect()->back()->with('error','Fail to add customer');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error','Mobile number already exists. Couldnot add customer.');
                    }
                } 
            }
        }
    }

    public function customerdetailview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.viewcustomer')=='N' ||session::get('loggindata.loggeduserpermission.viewcustomer')=='')
        {
            return redirect('404');
        } 
        else
        {
        	
        	$getcustomer = customer::leftJoin('users', 'users.id', '=', 'userID')
        	->leftJoin('store', 'store.store_id', '=', 'customer.storeID')
        	->where('customer.customerID', $id)
        	->first(
        		array(
            			'customer.customerID', 
            			'customer.customertype', 
            			'customer.customertitle', 
            			'customer.customerfirstname', 
            			'customer.customerlastname', 
            			'customer.customermobilenumber', 
            			'customer.customerhomenumber', 
            			'customer.customeraltcontactnumber', 
            			'customer.customerdob', 
            			'customer.customeremail',
            			'customer.customerbusinessname',
            			'customer.customeracnabn',
            			'customer.customerbusinessemail',
            			'customer.customerbusinesswebsite',
            			'customer.customeraddress',
            			'customer.customerpostcode',
            			'customer.customersuburbname',
            			'customer.customerstate',
            			'customer.customernote',
            			'customer.onAccountPayment',
            			'customer.userID',
            			'customer.storeID',
            			'customer.created_at',
            			'users.id',
            			'users.name',
            			'store.store_id',
            			'store.store_name'
            		)
            	);

        	$customersale = orderdetail::where('customerID', $getcustomer->customerID)
        	->where('orderstatus', '1')
        	->leftJoin('store', 'store.store_id', '=', 'orderdetail.storeID')
        	->leftJoin('users', 'users.id', '=', 'orderdetail.userID')
        	->get();

            $stores = store::where('storestatus', '1')->get();

            $customerdetail = ['getcustomer'=>$getcustomer, 'stores'=>$stores, 'customersale'=>$customersale];

            return view('customerdetail')->with('customerdetail', $customerdetail);
        }
    }

    public function editcustomer(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.addcustomer')=='N' ||session::get('loggindata.loggeduserpermission.addcustomer')=='')
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
                'title'=>'required',
                'firstname'=>'required',
                'lastname'=>'required',
                'mobilenumber'=>'required'
                ],[
                    'customertype.required'=>'Customer Type is required',
                    'title.required'=>'Customer Title is required',
                    'firstname.required'=>'Customer First Name is required',
                    'lastname.required'=>'Customer Last Name is required',
                    'mobilenumber.required'=>'Customer Mobile Number is required'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    if(empty($request->input('onaccount')))
                    {
                        $onaccount = 0;
                    }
                    else
                    {
                        $onaccount = 1;
                    }

                    $updatecustomer= customer::where('customerID', $request->input('customerid'))->first();
                    $updatecustomer->customertype= $request->input('customertype');
                    $updatecustomer->customertitle= $request->input('title');
                    $updatecustomer->customerfirstname= $request->input('firstname');
                    $updatecustomer->customerlastname= $request->input('lastname');
                    $updatecustomer->customermobilenumber= $request->input('mobilenumber');
                    $updatecustomer->customerhomenumber= $request->input('homenumber');
                    $updatecustomer->customeraltcontactnumber= $request->input('altcontactnumber');
                    $updatecustomer->customerdob= $request->input('dob');
                    $updatecustomer->customeremail= $request->input('email');
                    $updatecustomer->customerbusinessname= $request->input('businessname');
                    $updatecustomer->customeracnabn= $request->input('acnabn');
                    $updatecustomer->customerbusinessemail= $request->input('businessemail');
                    $updatecustomer->customerbusinesswebsite= $request->input('businesswebsite');
                    $updatecustomer->customeraddress= $request->input('address');
                    $updatecustomer->customerpostcode= $request->input('postcode');
                    $updatecustomer->customersuburbname= $request->input('suburbname');
                    $updatecustomer->customerstate= $request->input('state');
                    $updatecustomer->customernote= $request->input('note');
                    $updatecustomer->onAccountPayment= $onaccount;
                    $updatecustomer->userID= session::get('loggindata.loggedinuser.id');
                    $updatecustomer->storeID= $request->input('store');
                    $updatecustomer->save();

                    if($updatecustomer->save())
                    {
                       return redirect()->back()->with('success','Customer Updated Successfully'); 
                    }
                    else
                    {
                        return redirect()->back()->with('error','Fail to Update customer');
                    }
                } 
            }
        }
    }
}
