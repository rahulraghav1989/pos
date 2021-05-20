<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Cookie;
use Tracker;
use Session;
use Validator;

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

class HomeController extends Controller
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

        //return $loggeduserstore;

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

    public function changestore(Request $request)
    {    
        $storeid = $request->input('store');
        session::put('storeid', $storeid);

        return redirect()->back()->with('changestoresuccess','Store Changed Success');
    }

    public function index()
    { 
        return view('home');
    }

    public function addbrandview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' || session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $brands= masterbrand::
            join('users', 'users.id', '=', 'masterbrand.userID')
            ->get();
            return view('brands')->with('brands', $brands);
        }
    }

    public function page404view()
    {  
        return view('page404');
    }

    public function addbrandvalue(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' || session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkbrand= masterbrand::where('brandname', '=', $request->input('brandname'))->first();
            //return $checkbrand;
            if($checkbrand == '')
            {
                $this->validate($request, [
                    'brandname' => 'required'
                ]);

                $insertbrand= new masterbrand;

                $insertbrand->brandname= $request->input('brandname');
                $insertbrand->userid= session::get('loggindata.loggedinuser.id');
                $insertbrand->brandstatus= '1';
                $insertbrand->save();

                return redirect()->back()->with('brandsuccess','Brand added successfully');
            }
            else
            {
               return redirect()->back()->with('branderror','Brand already exist'); 
            }
        }
    }

    public function editbrand(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $this->validate($request, [
                    'brandname' => 'required'
                ]);

            $updatebrand= masterbrand::find($request->input('brandid'));
            $updatebrand->brandname = $request->brandname;
            $updatebrand->save();
            if($updatebrand->save())
            {
               return redirect()->back()->with('editbrandsuccess','Brand edited successfully'); 
            }
            else
            {
                return redirect()->back()->with('editbranderror','Brand not edited');
            }     
        }
    }

    public function editbrandstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $updatebrandstatus= masterbrand::find($request->input('brandid'));
            $updatebrandstatus->brandstatus = $request->brandstatus;
            $updatebrandstatus->save();
            if($updatebrandstatus->save())
            {
               return redirect()->back()->with('statusbrandsuccess','Brand status changed successfully'); 
            }
            else
            {
                return redirect()->back()->with('statusbranderror','Brand not updated');
            }     
        }
    }

    public function colourmasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $colour= mastercolour::
            join('users', 'users.id', '=', 'mastercolour.userID')
            ->get();
            return view('colour')->with('colour', $colour);
        }
    }

    public function addcolour(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkcolour= mastercolour::where('colourname', '=', $request->input('colourname'))->first();
            //return $checkbrand;
            if($checkcolour == '')
            {
                $this->validate($request, [
                    'colourname' => 'required'
                ]);

                $insertcolour= new mastercolour;

                $insertcolour->colourname= $request->input('colourname');
                $insertcolour->userID= session::get('loggindata.loggedinuser.id');
                $insertcolour->colourstatus= '1';
                $insertcolour->save();

                return redirect()->back()->with('coloursuccess','Colour added successfully');
            }
            else
            {
               return redirect()->back()->with('colourerror','Colour already exist'); 
            }
        }
    }

    public function editcolour(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $this->validate($request, [
                    'colourname' => 'required'
                ]);

            $updatecolour= mastercolour::find($request->input('colourid'));
            $updatecolour->colourname = $request->colourname;
            $updatecolour->save();
            if($updatecolour->save())
            {
               return redirect()->back()->with('editcoloursuccess','Colour edited successfully'); 
            }
            else
            {
                return redirect()->back()->with('editcolourerror','Colour not edited');
            }     
        }
    }

    public function editcolourstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $updatecolourstatus= mastercolour::find($request->input('colourid'));
            $updatecolourstatus->colourstatus = $request->colourstatus;
            $updatecolourstatus->save();
            if($updatecolourstatus->save())
            {
               return redirect()->back()->with('statuscoloursuccess','Colour status changed successfully'); 
            }
            else
            {
                return redirect()->back()->with('statuscolourerror','Colour status not updated');
            }     
        }
    }

    public function modelmasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $model= mastermodel::
            join('users', 'users.id', '=', 'mastermodel.userID')
            ->join('masterbrand', 'masterbrand.brandID', '=', 'mastermodel.brandID')
            ->get();
            $brandall= masterbrand::get();
            $modelviewdata= ['model'=>$model, 'brandall'=>$brandall];
            return view('model')->with('modelviewdata',$modelviewdata);
        }
    }

    public function addmodel(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkmodel= mastermodel::where('modelname', '=', $request->input('modelname'))->first();
            //return $checkbrand;
            if($checkmodel == '')
            {
                $this->validate($request, [
                    'modelname' => 'required'
                ]);

                $insertmodel= new mastermodel;

                $insertmodel->modelname= $request->input('modelname');
                $insertmodel->brandID= $request->input('brandid');
                $insertmodel->userID= session::get('loggindata.loggedinuser.id');
                $insertmodel->modelstatus= '1';
                $insertmodel->save();

                return redirect()->back()->with('modelsuccess','Model added successfully');
            }
            else
            {
               return redirect()->back()->with('modelerror','Model already exist'); 
            }
        }
    }

    public function editmodel(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $this->validate($request, [
                    'modelname' => 'required',
                    'brandid' => 'required'
                ]);

            $updatemodel= mastermodel::find($request->input('modelid'));
            $updatemodel->modelname = $request->modelname;
            $updatemodel->brandID = $request->brandid;
            $updatemodel->save();
            if($updatemodel->save())
            {
               return redirect()->back()->with('editmodelsuccess','Model edited successfully'); 
            }
            else
            {
                return redirect()->back()->with('editmodelerror','Model not edited');
            }     
        }
    }

    public function editmodelstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $updatemodelstatus= mastermodel::find($request->input('modelid'));
            $updatemodelstatus->modelstatus = $request->modelstatus;
            $updatemodelstatus->save();
            if($updatemodelstatus->save())
            {
               return redirect()->back()->with('statusmodelsuccess','Model status changed successfully'); 
            }
            else
            {
                return redirect()->back()->with('statusmodelerror','Model status not updated');
            }     
        }
    }

    public function suppliermasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $supplier= mastersupplier::
            join('users', 'users.id', '=', 'mastersupplier.userID')
            ->get();
            return view('supplier')->with('supplier',$supplier);
        }
    }

    public function addsupplier(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checksupplier= mastersupplier::where('suppliername', '=', $request->input('suppliername'))->first();
            //return $checkbrand;
            if($checksupplier == '')
            {
                $validator = validator::make($request->all(),[
                'suppliername'=>'required',
                'contactnumber'=>'required',
                ],[
                    'suppliername.required'=>'Supplier name is required',
                    'contactnumber.required'=>'Number is required and digits allowed'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {

                    $insertsupplier= new mastersupplier;

                    $insertsupplier->suppliername= $request->input('suppliername');
                    $insertsupplier->supplierdescription= $request->input('supplierdescription');
                    $insertsupplier->suppliercontactnumber= $request->input('contactnumber');
                    $insertsupplier->supplieraltercontactnumber= $request->input('altcontactnumber');
                    $insertsupplier->supplieracnabn= $request->input('acbabn');
                    $insertsupplier->supplieremail= $request->input('email');
                    $insertsupplier->supplierwebsite= $request->input('website');
                    $insertsupplier->supplierContactperson= $request->input('personname');
                    $insertsupplier->supplierContactpersonnumber= $request->input('personnumber');
                    $insertsupplier->supplierContactpersonemail= $request->input('personemail');
                    $insertsupplier->supplierAddressunit= $request->input('unitnumber');
                    $insertsupplier->supplierAddressstreet= $request->input('streetnumber');
                    $insertsupplier->supplierAddressstreetname= $request->input('streetname');
                    $insertsupplier->SupplierAddresssuburb= $request->input('suburbname');
                    $insertsupplier->supplierAddresspostcode= $request->input('postcode');
                    $insertsupplier->supplierAddressstate= $request->input('state');
                    $insertsupplier->supplierAddresscountry= $request->input('country');
                    $insertsupplier->suppliercreatingnote= $request->input('note');
                    $insertsupplier->userID= session::get('loggindata.loggedinuser.id');
                    $insertsupplier->supplierstatus= '1';
                    $insertsupplier->save();

                    return redirect()->back()->with('suppliersuccess','Supplier add successfully');
                }
            }
            else
            {
               return redirect()->back()->with('suppliererror','Supplier already exist'); 
            }
        }
    }

    public function editsupplier(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
                'suppliername'=>'required',
                'supplierdescription'=>'required',
                'contactnumber'=>'required',
                'acbabn'=>'required',
                'email'=>'required|email',
                'personname'=>'required',
                'personnumber'=>'required',
                'personemail'=>'required|email',
                'streetnumber'=>'required',
                'streetname'=>'required',
                'suburbname'=>'required',
                'postcode'=>'required',
                'state'=>'required',
                'country'=>'required',
                ],[
                    'suppliername.required'=>'Supplier name is required',
                    'supplierdescription.required'=>'Description is required',
                    'contactnumber.required'=>'Number is required and digits allowed',
                    'acbabn.required'=>'Acb Abn is required',
                    'email.required'=>'Email is required',
                    'email.email'=>'must be email formate',
                    'personname.required'=>'Contact person name is required',
                    'personnumber.required'=>'Contact person number is required and digits allowed',
                    'personemail.required'=>'Contact person email is required and must be email formate',
                    'streetnumber.required'=>'Street number is required',
                    'streetname.required'=>'Street name is required',
                    'suburbname.required'=>'suburb name is required',
                    'postcode.required'=>'Post code is required and digits allowed',
                    'state.required'=>'State is required',
                    'country.required'=>'Country is required'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    $updatesupplier= mastersupplier::find($request->input('supplierid'));
                    $updatesupplier->suppliername= $request->suppliername;
                    $updatesupplier->supplierdescription= $request->supplierdescription;
                    $updatesupplier->suppliercontactnumber= $request->contactnumber;
                    $updatesupplier->supplieraltercontactnumber= $request->altcontactnumber;
                    $updatesupplier->supplieracnabn= $request->acbabn;
                    $updatesupplier->supplieremail= $request->email;
                    $updatesupplier->supplierwebsite= $request->website;
                    $updatesupplier->supplierContactperson= $request->personname;
                    $updatesupplier->supplierContactpersonnumber= $request->personnumber;
                    $updatesupplier->supplierContactpersonemail= $request->personemail;
                    $updatesupplier->supplierAddressunit= $request->unitnumber;
                    $updatesupplier->supplierAddressstreet= $request->streetnumber;
                    $updatesupplier->supplierAddressstreetname= $request->streetname;
                    $updatesupplier->SupplierAddresssuburb= $request->suburbname;
                    $updatesupplier->supplierAddresspostcode= $request->postcode;
                    $updatesupplier->supplierAddressstate= $request->state;
                    $updatesupplier->supplierAddresscountry= $request->country;
                    $updatesupplier->suppliercreatingnote= $request->note;
                    $updatesupplier->save();
                    if($updatesupplier->save())
                    {
                       return redirect()->back()->with('editsuppliersuccess','Supplier edited successfully'); 
                    }
                    else
                    {
                        return redirect()->back()->with('editsuppliererror','Supplier not edited');
                    }
                }     
        }
    }

    public function editsupplierstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $updatesupplierstatus= mastersupplier::find($request->input('supplierid'));
            $updatesupplierstatus->supplierstatus = $request->supplierstatus;
            $updatesupplierstatus->save();
            if($updatesupplierstatus->save())
            {
               return redirect()->back()->with('statussuppliersuccess','Supplier status changed successfully'); 
            }
            else
            {
                return redirect()->back()->with('statussuppliererror','Supplier status not updated');
            }     
        }
    }

    public function storemasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $store= store::
            join('store_type', 'store_type.storeTypeID','=','store.storeTypeID')
            ->get();

            $storetype= storetype::get();
            $storedata= ['store'=>$store, 'storetype'=>$storetype];
            return view('store')->with('storedata',$storedata);
        }
    }

    public function addstore(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkstore= store::where('store_name', '=', $request->input('storename'))->first();
            $checkcode = store::where('store_code', '=', $request->input('storecode'))->first();
            
            //return $checkbrand;
            if($checkstore == '' && $checkcode == '')
            {
                $validator = validator::make($request->all(),[
                'storecode'=>'required',
                'storename'=>'required',
                'storeaddress'=>'required',
                'storepincode'=>'required',
                'storecontact'=>'required',
                'storetype'=>'required',
                'storeip'=>'required',
                'eodamount'=>'required'
                ],[
                    'storecode.required'=>'Store code is required',
                    'storename.required'=>'Store name is required',
                    'storeaddress.required'=>'Store address is required',
                    'storepincode.required'=>'Store pincode - Number is required and digits allowed',
                    'storecontact.required'=>'Store contact is required',
                    'storetype.required'=>'Store type is required',
                    'storeip.required'=>'Store ip is required',
                    'eodamount.required'=>'Please enter Reconciliation Amount'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {

                    $insertstore= new store;

                    $insertstore->store_code= $request->input('storecode');
                    $insertstore->store_name= $request->input('storename');
                    $insertstore->store_address= $request->input('storeaddress');
                    $insertstore->store_pincode= $request->input('storepincode');
                    $insertstore->store_contact= $request->input('storecontact');
                    $insertstore->store_email= $request->input('storeemail');
                    $insertstore->storeTypeID= $request->input('storetype');
                    $insertstore->storeIP= $request->input('storeip');
                    $insertstore->storeEODAmount= $request->input('eodamount');
                    $insertstore->storestatus= '1';
                    $insertstore->save();

                    return redirect()->back()->with('storesuccess','Store add successfully');
                }
            }
            else
            {
               return redirect()->back()->with('storeerror','Store already exist'); 
            }
        }
    }

    public function editstore(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'storename'=>'required',
            'storeaddress'=>'required',
            'storepincode'=>'required',
            'storecontact'=>'required',
            'storetype'=>'required',
            'storeip'=>'required',
            'eodamount'=>'required'
            ],[
                'storename.required'=>'Store name is required',
                'storeaddress.required'=>'Store address is required',
                'storepincode.required'=>'Store pincode - Number is required and digits allowed',
                'storecontact.required'=>'Store contact is required',
                'storetype.required'=>'Store type is required',
                'storeip.required'=>'Store ip is required',
                'eodamount.required'=>'Please enter Reconciliation Amount'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatestore= store::find($request->input('storeid'));
                $updatestore->store_name= $request->input('storename');
                $updatestore->store_address= $request->input('storeaddress');
                $updatestore->store_pincode= $request->input('storepincode');
                $updatestore->store_contact= $request->input('storecontact');
                $updatestore->store_email= $request->input('storeemail');
                $updatestore->storeTypeID= $request->input('storetype');
                $updatestore->storeIP= $request->input('storeip');
                $updatestore->storeEODAmount= $request->input('eodamount');
                $updatestore->save();
                if($updatestore->save())
                {
                   return redirect()->back()->with('editstoresuccess','Store edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('editstoreerror','Store not edited');
                }
            }     
        }
    }

    public function editstorestatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $updatestorestatus= store::find($request->input('storeid'));
            $updatestorestatus->storestatus = $request->storestatus;
            $updatestorestatus->save();
            if($updatestorestatus->save())
            {
               return redirect()->back()->with('statusstoresuccess','Store status changed successfully'); 
            }
            else
            {
                return redirect()->back()->with('statusstoreerror','Store status not updated');
            }     
        }
    }

    public function stockgroupmasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $stockgroup= masterstockgroup::
            join('users', 'users.id','=','masterstockgroup.userID')
            ->get();

            return view('stockgroup')->with('stockgroup',$stockgroup);
        }
    }

    public function addstockgroup(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkstockgroup= masterstockgroup::where('stockgroupname', '=', $request->input('stockgroupname'))->first();
            //return $checkbrand;
            if($checkstockgroup == '')
            {
                $validator = validator::make($request->all(),[
                'stockgroupname'=>'required',
                'stockgrouppriceeffect'=>'required'
                ],[
                    'stockgroupname.required'=>'Stock group name is required',
                    'stockgrouppriceeffect.required'=>'Stock group Price effect is required'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {

                    $insertstockgroup= new masterstockgroup;

                    $insertstockgroup->stockgroupname= $request->input('stockgroupname');
                    $insertstockgroup->stockpriceeffect= $request->input('stockgrouppriceeffect');
                    $insertstockgroup->stockgroupstatus= '1';
                    $insertstockgroup->productqtyeffect= $request->input('stockgroupquantityeffect');
                    $insertstockgroup->userID= session::get('loggindata.loggedinuser.id');
                    $insertstockgroup->save();

                    return redirect()->back()->with('stockgroupsuccess','Stock group add successfully');
                }
            }
            else
            {
               return redirect()->back()->with('stockgrouperror','Stock group already exist'); 
            }
        }
    }

    public function editstockgroup(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'stockgroupname'=>'required',
            'stockgrouppriceeffect'=>'required'
            ],[
                'stockgroupname.required'=>'Stock Group name is required',
                'stockgrouppriceeffect.required'=>'Stock Group Price effect is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatestockgroup= masterstockgroup::find($request->input('stockgroupID'));
                $updatestockgroup->stockgroupname= $request->input('stockgroupname');
                $updatestockgroup->stockpriceeffect= $request->input('stockgrouppriceeffect');
                $updatestockgroup->productqtyeffect =$request->input('stockgroupquantityeffect');
                $updatestockgroup->save();
                if($updatestockgroup->save())
                {
                   return redirect()->back()->with('editstockgroupsuccess','Stock Group edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('editstockgrouperror','Stock group not edited');
                }
            }     
        }
    }

    public function editstockgroupstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'stockgroupstatus'=>'required'
            ],[
                'stockgroupstatus.required'=>'Stock group status is null'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatestockgroupstatus= masterstockgroup::find($request->input('stockgroupid'));
                $updatestockgroupstatus->stockgroupstatus= $request->input('stockgroupstatus');
                $updatestockgroupstatus->save();
                if($updatestockgroupstatus->save())
                {
                   return redirect()->back()->with('editstockgroupstatussuccess','Stock Group status edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('editstockgroupstatuserror','Stock group status not edited');
                }
            }     
        }
    }

    public function categorymasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $category= mastercategory::
            join('users', 'users.id','=','mastercategory.userID')
            ->get();

            $subcategory= mastersubcategory::
            join('mastercategory', 'mastercategory.categoryID','=','mastersubcategory.categoryID')
            ->join('users', 'users.id','=','mastersubcategory.userID')
            ->get();

            //$receivetype= masterprreceivetype::get();

            $categorydata= ['category'=>$category, 'subcategory'=>$subcategory];
            return view('category')->with('categorydata',$categorydata);
        }
    }

    public function addcategory(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            if($request->input('parentcategory')=='')
            {
                $checkcategory= mastercategory::where('categoryname', '=', $request->input('categoryname'))->first();

                if($checkcategory == '')
                {
                    $validator = validator::make($request->all(),[
                    'categoryname'=>'required'
                    ],[
                        'categoryname.required'=>'Category name is required'
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    else
                    {

                        $insertcategory= new mastercategory;

                        $insertcategory->categoryname= $request->input('categoryname');
                        $insertcategory->categorystatus= '1';
                        $insertcategory->userID= session::get('loggindata.loggedinuser.id');
                        $insertcategory->save();

                        return redirect()->back()->with('categorysuccess','Category add successfully');
                    }
                }
                else
                {
                    return redirect()->back()->with('categoryerror','Category already exist');
                }
            }
            else
            {
                $checksubcategory= mastersubcategory::where('subcategoryname', '=', $request->input('categoryname'))->where('categoryID', '=', 'parentcategory')->first();

                if($checksubcategory == '')
                {
                    $insertsubcategory= new mastersubcategory;

                    $insertsubcategory->categoryID= $request->input('parentcategory');
                    $insertsubcategory->subcategoryname= $request->input('categoryname');
                    $insertsubcategory->subcategorystatus= '1';
                    $insertsubcategory->userID= session::get('loggindata.loggedinuser.id');
                    $insertsubcategory->save();

                    return redirect()->back()->with('categorysuccess','Sub Category add successfully');
                }
                else
                {
                    return redirect()->back()->with('categoryerror','Sub Category add successfully');
                }
            }
        }
    }

    public function editcategory(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'categoryname'=>'required'
            ],[
                'categoryname.required'=>'Category name is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatecategory= mastercategory::find($request->input('categoryID'));
                $updatecategory->categoryname= $request->input('categoryname');
                $updatecategory->save();
                if($updatecategory->save())
                {
                   return redirect()->back()->with('editcategorysuccess','Category edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('editcategoryerror','Cateogry not edited');
                }
            }     
        }
    }

    public function editcategorystatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'categorystatus'=>'required'
            ],[
                'categorystatus.required'=>'Category status is null'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatecategorystatus= mastercategory::find($request->input('categoryid'));
                $updatecategorystatus->categorystatus= $request->input('categorystatus');
                $updatecategorystatus->save();
                if($updatecategorystatus->save())
                {
                   return redirect()->back()->with('editcategorystatussuccess','Category status edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('editcategorystatuserror','Category status not edited');
                }
            }     
        }
    }

    public function editsubcategory(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'subcategoryname'=>'required'
            ],[
                'subcategoryname.required'=>'Sub Category name is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatesubcategory= mastersubcategory::find($request->input('subcategoryID'));
                $updatesubcategory->subcategoryname= $request->input('subcategoryname');
                $updatesubcategory->save();
                if($updatesubcategory->save())
                {
                   return redirect()->back()->with('editsubcategorysuccess','Sub Category edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('editsubcategoryerror','Sub Cateogry not edited');
                }
            }     
        }
    }

    public function editsubcategorystatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'subcategorystatus'=>'required'
            ],[
                'subcategorystatus.required'=>'Sub Category status is null'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatesubcategorystatus= mastersubcategory::find($request->input('subcategoryid'));
                $updatesubcategorystatus->subcategorystatus= $request->input('subcategorystatus');
                $updatesubcategorystatus->save();
                if($updatesubcategorystatus->save())
                {
                   return redirect()->back()->with('editsubcategorystatussuccess','Sub Category status edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('editsubcategorystatuserror','Sub Category status not edited');
                }
            }     
        }
    }

    public function plancategorymasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $plancategory= masterplancategory::
            join('users', 'users.id','=','masterplancategory.userID')
            ->get();

            return view('plancategory')->with('plancategory',$plancategory);
        }
    }

    public function addplancategory(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plancategoryname'=>'required'
            ],[
                'plancategoryname.required'=>'Plan Category name is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkplancategory= masterplancategory::where('pcname', '=', $request->input('plancategoryname'))->count();
                
                if($checkplancategory == 0)
                {
                    $insertplancategory= new masterplancategory;

                    $insertplancategory->pcname= $request->input('plancategoryname');
                    $insertplancategory->pcstatus= '1';
                    $insertplancategory->userID= session::get('loggindata.loggedinuser.id');
                    $insertplancategory->save();

                    if($insertplancategory->save())
                    {
                        return redirect()->back()->with('plancategorysuccess','Plan Category Added Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('plancategoryerror','Failed To Add Plan Category');
                    }
                }
                else
                {
                    return redirect()->back()->with('plancategoryerror','Plan Category Already Exist');
                }
            }
        }
    }

    public function editplancategory(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plancategoryname'=>'required'
            ],[
                'plancategoryname.required'=>'Plan Category name is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatesubcategory= masterplancategory::find($request->input('plancategoryid'));
                $updatesubcategory->pcname= $request->input('plancategoryname');
                $updatesubcategory->save();
                if($updatesubcategory->save())
                {
                   return redirect()->back()->with('plancategorysuccess','Plan Category edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('plancategoryerror','Plan Category Not Edited');
                }
            }     
        }
    }

    public function editplancategorystatus(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plancategorystatus'=>'required'
            ],[
                'plancategorystatus.required'=>'Plan Category status is null'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplancategorystatus= masterplancategory::find($request->input('plancategoryid'));
                $updateplancategorystatus->pcstatus= $request->input('plancategorystatus');
                $updateplancategorystatus->save();
                if($updateplancategorystatus->save())
                {
                   return redirect()->back()->with('plancategorysuccess','Plan Category Status Updated Successfully'); 
                }
                else
                {
                    return redirect()->back()->with('plancategoryerror','Plan Category Status Not Updated.');
                }
            }     
        }
    }

    public function plantypemasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $plantype= masterplantype::
            join('users', 'users.id','=','masterplantype.userID')
            ->get();

            return view('plantype')->with('plantype',$plantype);
        }
    }

    public function addplantype(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plantype'=>'required'
            ],[
                'plantype.required'=>'Plan Type name is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkplantype= masterplantype::where('plantypename', '=', $request->input('plantype'))->count();
                
                if($checkplantype == 0)
                {
                    $insertplantype= new masterplantype;

                    $insertplantype->plantypename= $request->input('plantype');
                    $insertplantype->plantypestatus= '1';
                    $insertplantype->userID= session::get('loggindata.loggedinuser.id');
                    $insertplantype->save();

                    if($insertplantype->save())
                    {
                        return redirect()->back()->with('plantypesuccess','Plan Type Added Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('plantypeerror','Failed To Add Plan Type');
                    }
                }
                else
                {
                    return redirect()->back()->with('plantypeerror','Plan Type Already Exist');
                }
            }
        }
    }

    public function editplantype(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plantype'=>'required'
            ],[
                'plantype.required'=>'Plan Type name is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplantype= masterplantype::find($request->input('plantypeid'));
                $updateplantype->plantypename= $request->input('plantype');
                $updateplantype->save();
                if($updateplantype->save())
                {
                   return redirect()->back()->with('plantypesuccess','Plan Type edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('plantypeerror','Plan Type Not Edited');
                }
            }     
        }
    }

    public function editplantypestatus(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plantypestatus'=>'required'
            ],[
                'plantypestatus.required'=>'Plan Type status is null'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplantypestatus= masterplantype::find($request->input('plantypeid'));
                $updateplantypestatus->plantypestatus= $request->input('plantypestatus');
                $updateplantypestatus->save();
                if($updateplantypestatus->save())
                {
                   return redirect()->back()->with('plantypesuccess','Plan Type Status Updated Successfully'); 
                }
                else
                {
                    return redirect()->back()->with('plantypeerror','Plan Type Status Not Updated.');
                }
            }     
        }
    }

    public function plantermmasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $planterm= masterplanterm::
            join('users', 'users.id','=','masterplanterm.userID')
            ->get();

            return view('planterm')->with('planterm',$planterm);
        }
    }

    public function addplanterm(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plantermname'=>'required',
            'plantermduration'=>'required'
            ],[
                'plantermname.required'=>'Plan Term name is required',
                'plantermduration.required'=>'Plan Term Duration is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkplanterm= masterplanterm::where('plantermname', '=', $request->input('plantermname'))->count();
                
                if($checkplanterm == 0)
                {
                    $insertplanterm= new masterplanterm;

                    $insertplanterm->plantermname= $request->input('plantermname');
                    $insertplanterm->plantermduration= $request->input('plantermduration');
                    $insertplanterm->plantermstatus= '1';
                    $insertplanterm->userID= session::get('loggindata.loggedinuser.id');
                    $insertplanterm->save();

                    if($insertplanterm->save())
                    {
                        return redirect()->back()->with('plantermsuccess','Plan Term Added Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('plantermerror','Failed To Add Plan Term');
                    }
                }
                else
                {
                    return redirect()->back()->with('plantermerror','Plan Term Already Exist');
                }
            }
        }
    }

    public function editplanterm(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' || session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plantermname'=>'required',
            'plantermduration'=>'required'
            ],[
                'plantype.required'=>'Plan Term name is required',
                'plantermduration.required'=>'Plan Term Duration is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplanterm= masterplanterm::find($request->input('plantermid'));
                $updateplanterm->plantermname= $request->input('plantermname');
                $updateplanterm->plantermduration= $request->input('plantermduration');
                $updateplanterm->save();
                if($updateplanterm->save())
                {
                   return redirect()->back()->with('plantermsuccess','Plan Term edited successfully'); 
                }
                else
                {
                    return redirect()->back()->with('plantermerror','Plan Term Not Edited');
                }
            }     
        }
    }

    public function editplantermstatus(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' || session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'plantermstatus'=>'required'
            ],[
                'plantermstatus.required'=>'Plan Term status is null'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplantermstatus= masterplanterm::find($request->input('plantermid'));
                $updateplantermstatus->plantermstatus= $request->input('plantermstatus');
                $updateplantermstatus->save();
                if($updateplantermstatus->save())
                {
                   return redirect()->back()->with('plantermsuccess','Plan Term Status Updated Successfully'); 
                }
                else
                {
                    return redirect()->back()->with('plantermerror','Plan Term Status Not Updated.');
                }
            }     
        }
    }

    public function producttypemasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $allproducttype = masterproducttype::
            join('users', 'users.id', '=', 'userID')
            ->get(array('masterproducttype.producttypeID', 'masterproducttype.producttypename', 'masterproducttype.producttype', 'masterproducttype.productrestrictiontype', 'masterproducttype.productrestrictionword', 'masterproducttype.producttypestatus', 'masterproducttype.created_at', 'users.name', 'masterproducttype.addtionalproducttype', 'masterproducttype.add_producttypename', 'masterproducttype.add_producttype', 'masterproducttype.add_productrestrictiontype', 'masterproducttype.add_productrestrictionword'));

            //return $allproducttype;
            return view('producttype')->with('allproducttype', $allproducttype);
        }
    }

    public function addproducttype(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'producttypename'=>'required',
            'producttype'=>'required',
            'restrictiontype'=>'required'
            ],[
                'producttypename.required'=>'Product Type name is required',
                'producttype.required'=>'Product Type is required',
                'restrictiontype.required'=>'Restriction Type is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkproducttype= masterproducttype::where('producttypename', '=', $request->input('producttypename'))->count();
                
                if($checkproducttype == 0)
                {
                    if($request->input('addtionalunique')=="")
                    {
                        $insertproducttype= new masterproducttype;

                        $insertproducttype->producttypename= $request->input('producttypename');
                        $insertproducttype->producttype= $request->input('producttype');
                        $insertproducttype->productrestrictiontype= $request->input('restrictiontype');
                        $insertproducttype->productrestrictionword= $request->input('restrictionword');
                        $insertproducttype->producttypestatus= '1';
                        $insertproducttype->addtionalproducttype = '0';
                        $insertproducttype->userID= session::get('loggindata.loggedinuser.id');
                        $insertproducttype->save();

                        if($insertproducttype->save())
                        {
                            return redirect()->back()->with('success','Product Type Added Successfully');
                        }
                        else
                        {
                            return redirect()->back()->with('error','Failed To Add Product Type');
                        }
                    }
                    else
                    {
                        $insertproducttype= new masterproducttype;

                        $insertproducttype->producttypename= $request->input('producttypename');
                        $insertproducttype->producttype= $request->input('producttype');
                        $insertproducttype->productrestrictiontype= $request->input('restrictiontype');
                        $insertproducttype->productrestrictionword= $request->input('restrictionword');
                        $insertproducttype->producttypestatus= '1';
                        $insertproducttype->userID= session::get('loggindata.loggedinuser.id');
                        $insertproducttype->addtionalproducttype = '1';
                        $insertproducttype->add_producttypename = $request->input('add_producttypename');
                        $insertproducttype->add_producttype = $request->input('add_producttype');
                        $insertproducttype->add_productrestrictiontype = $request->input('add_restrictiontype');
                        $insertproducttype->add_productrestrictionword = $request->input('add_restrictionword');
                        $insertproducttype->save();

                        if($insertproducttype->save())
                        {
                            return redirect()->back()->with('success','Product Type Added Successfully');
                        }
                        else
                        {
                            return redirect()->back()->with('error','Failed To Add Product Type');
                        }
                    }
                }
                else
                {
                    return redirect()->back()->with('error','Product Type Already Exist');
                }
            }
        }
    }

    public function editproducttype(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' ||session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'producttypename'=>'required',
            'producttype'=>'required',
            'restrictiontype'=>'required'
            ],[
                'producttypename.required'=>'Product Type name is required',
                'producttype.required'=>'Product Type is required',
                'restrictiontype.required'=>'Restriction Type is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateproducttype= masterproducttype::find($request->input('producttypeid'));

                if($request->input('addtionalunique')=="")
                {

                    $updateproducttype->producttypename= $request->input('producttypename');
                    $updateproducttype->producttype= $request->input('producttype');
                    $updateproducttype->productrestrictiontype= $request->input('restrictiontype');
                    $updateproducttype->productrestrictionword= $request->input('restrictionword');
                    $updateproducttype->addtionalproducttype = '0';

                    $updateproducttype->save();

                    if($updateproducttype->save())
                    {
                        return redirect()->back()->with('success','Product Type Edited Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error','Failed To Edit Product Type');
                    }
                }
                else
                {
                    $updateproducttype->producttypename= $request->input('producttypename');
                    $updateproducttype->producttype= $request->input('producttype');
                    $updateproducttype->productrestrictiontype= $request->input('restrictiontype');
                    $updateproducttype->productrestrictionword= $request->input('restrictionword');
                    $updateproducttype->addtionalproducttype = '1';
                    $updateproducttype->add_producttypename = $request->input('add_producttypename');
                    $updateproducttype->add_producttype = $request->input('add_producttype');
                    $updateproducttype->add_productrestrictiontype = $request->input('add_restrictiontype');
                    $updateproducttype->add_productrestrictionword = $request->input('add_restrictionword');
                    $updateproducttype->save();

                    if($updateproducttype->save())
                    {
                        return redirect()->back()->with('success','Product Type Edited Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error','Failed To Edit Product Type');
                    }
                }
            }
        }
    }

    public function editproducttypestatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' ||session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'producttypestatus'=>'required'
            ],[
                'producttypestatus.required'=>'Product Type Status is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateproducttypestatus= masterproducttype::find($request->input('producttypeid'));

                $updateproducttypestatus->producttypestatus= $request->input('producttypestatus');
                $updateproducttypestatus->save();

                if($updateproducttypestatus->save())
                {
                    return redirect()->back()->with('success','Product Type status updated successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Failed To update Product Type status');
                }
            }
        }
    }

    public function planpropositiontypeview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getplanpropositiontype = masterplanpropositiontype::
            join('users', 'users.id', '=', 'userID')
            ->get();
            return view('planpropositiontype')->with('getplanpropositiontype', $getplanpropositiontype);
        }
    }

    public function addplanpropositiontype(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'planpropositiontype'=>'required'
            ],[
                'planpropositiontype.required'=>'Plan Proposition Type is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkproducttype= masterplanpropositiontype::where('planpropositionname', '=', $request->input('planpropositiontype'))->count();
                
                if($checkproducttype == 0)
                {
                    $insertplanpropositiontype= new masterplanpropositiontype;

                    $insertplanpropositiontype->planpropositionname= $request->input('planpropositiontype');
                    $insertplanpropositiontype->planpropositionstatus= '1';
                    $insertplanpropositiontype->userID= session::get('loggindata.loggedinuser.id');
                    $insertplanpropositiontype->save();

                    if($insertplanpropositiontype->save())
                    {
                        return redirect()->back()->with('success','Plan Proposition Type Added Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error','Failed To Add Plan Proposition Type');
                    }
                }
                else
                {
                    return redirect()->back()->with('error','Product Type Already Exist');
                }
            }
        }
    }

    public function editplanpropositiontype(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' ||session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'planpropositiontype'=>'required'
            ],[
                'planpropositiontype.required'=>'Plan Proposition Type is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplanpropositiontype= masterplanpropositiontype::find($request->input('planpropositionid'));

                $updateplanpropositiontype->planpropositionname= $request->input('planpropositiontype');
                $updateplanpropositiontype->save();

                if($updateplanpropositiontype->save())
                {
                    return redirect()->back()->with('success','Plan Proposition Type Updated Successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Failed To Update Plan Proposition Type');
                }
            }
        }
    }

    public function editplanpropositionstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' ||session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'planpropositionstatus'=>'required'
            ],[
                'planpropositionstatus.required'=>'Plan Proposition Type Status is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplanpropositiontypestatus= masterplanpropositiontype::find($request->input('planpropositiontypeid'));

                $updateplanpropositiontypestatus->planpropositionstatus= $request->input('planpropositionstatus');
                $updateplanpropositiontypestatus->save();

                if($updateplanpropositiontypestatus->save())
                {
                    return redirect()->back()->with('success','Plan Proposition Type status updated successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Failed To update Plan Proposition Type status');
                }
            }
        }
    }

    public function planhandsettermview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $getplanhandsetterm = masterplanhandsetterm::
            join('users', 'users.id', '=', 'userID')
            ->get();
            return view('planhandset')->with('getplanhandsetterm', $getplanhandsetterm);
        }
    }

    public function addplanhandsetterm(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'planhandsettermname'=>'required',
            'planhandsettermduration'=>'required'
            ],[
                'planhandsettermname.required'=>'Plan Handset Term Name is required',
                'planhandsettermduration.required'=>'Plan Handset Term Duration is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkhandsetterm= masterplanhandsetterm::where('planhandsettermname', '=', $request->input('planhandsettermname'))->count();
                
                if($checkhandsetterm == 0)
                {
                    $inserthandsetterm= new masterplanhandsetterm;

                    $inserthandsetterm->planhandsettermname= $request->input('planhandsettermname');
                    $inserthandsetterm->planhandsettermduration= $request->input('planhandsettermduration');
                    $inserthandsetterm->planhandsettermstatus = '1';
                    $inserthandsetterm->userID= session::get('loggindata.loggedinuser.id');
                    $inserthandsetterm->save();

                    if($inserthandsetterm->save())
                    {
                        return redirect()->back()->with('success','Plan Handset Term Type Added Successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error','Failed To Add Plan Handset Term');
                    }
                }
                else
                {
                    return redirect()->back()->with('error','Product Type Already Exist');
                }
            }
        }
    }

    public function editplanhandsetterm(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' ||session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'planhandsettermname'=>'required',
            'planhandsettermduration'=>'required'
            ],[
                'planhandsettermname.required'=>'Plan Handset Term Name is required',
                'planhandsettermduration.required'=>'Plan Handset Term Duration is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplanhandsetterm= masterplanhandsetterm::find($request->input('planhandsettermid'));

                $updateplanhandsetterm->planhandsettermname= $request->input('planhandsettermname');
                $updateplanhandsetterm->planhandsettermduration= $request->input('planhandsettermduration');
                $updateplanhandsetterm->save();

                if($updateplanhandsetterm->save())
                {
                    return redirect()->back()->with('success','Plan Handset Term Updated Successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Failed To Update Plan Handset Term');
                }
            }
        }
    }

    public function editplanhandsettermstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' ||session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'planhandsettermstatus'=>'required'
            ],[
                'planhandsettermstatus.required'=>'Plan Proposition Type Status is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplanhandsettermstatus= masterplanhandsetterm::find($request->input('planhandsettermid'));

                $updateplanhandsettermstatus->planhandsettermstatus= $request->input('planhandsettermstatus');
                $updateplanhandsettermstatus->save();

                if($updateplanhandsettermstatus->save())
                {
                    return redirect()->back()->with('success','Plan Handset Term status updated successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Failed To update Plan Handset Term status');
                }
            }
        }
    }

    public function paymentmasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $paymentoptions = paymentoptions::join('users', 'users.id', '=', 'userID')->get();

            return view('payment')->with('paymentoptions', $paymentoptions);
        }
    }

    public function addpaymentoption(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'optionname'=>'required',
            'optiontype'=>'required'
            ],[
                'optionname.required'=>'Payment option name is required',
                'optiontype.required'=>'Payment option type is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $checkpaymentoption= paymentoptions::where('paymentname', '=', $request->input('optionname'))->count();
                
                if($checkpaymentoption == 0)
                {
                    $insertpaymentoption= new paymentoptions;

                    $insertpaymentoption->paymentname= $request->input('optionname');
                    $insertpaymentoption->paymenttype= $request->input('optiontype');
                    $insertpaymentoption->paymentstatus = '1';
                    $insertpaymentoption->userID= session::get('loggindata.loggedinuser.id');
                    $insertpaymentoption->save();

                    if($insertpaymentoption->save())
                    {
                        return redirect()->back()->with('success','Payment option added successfully');
                    }
                    else
                    {
                        return redirect()->back()->with('error','Failed To Add payment option');
                    }
                }
                else
                {
                    return redirect()->back()->with('error','Payment option Already Exist');
                }
            }
        }
    }

    public function editpaymentoption(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editmasters')=='N' ||session::get('loggindata.loggeduserpermission.editmasters')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'optionname'=>'required',
            'optiontype'=>'required'
            ],[
                'optionname.required'=>'Payment option name is required',
                'optiontype.required'=>'Payment option type is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatepaymentoption= paymentoptions::find($request->input('optionid'));

                $updatepaymentoption->paymentname= $request->input('optionname');
                $updatepaymentoption->paymenttype= $request->input('optiontype');
                $updatepaymentoption->save();

                if($updatepaymentoption->save())
                {
                    return redirect()->back()->with('success','Payment option Updated Successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Failed To Update Payment option');
                }
            }
        }
    }

    public function editpaymentoptionstatus(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.deletemaster')=='N' ||session::get('loggindata.loggeduserpermission.deletemaster')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'postatus'=>'required'
            ],[
                'postatus.required'=>'Payment option Status is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updatepostatus= paymentoptions::find($request->input('poid'));

                $updatepostatus->paymentstatus= $request->input('postatus');
                $updatepostatus->save();

                if($updatepostatus->save())
                {
                    return redirect()->back()->with('success','Payment option status updated successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Failed To update payment option status');
                }
            }
        }
    }

    public function comissionmasterview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $planproposition = masterplanpropositiontype::where('planpropositionstatus', '1')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'masterplancategory.pcID')
            ->where('masterplancategory.pcstatus', '1')
            ->get();
            $productcategory = mastercategory::where('categorystatus', '1')->get();
            $mastercomission = mastercomission::get();

            $with = array(
                'planproposition'=>$planproposition,
                'productcategory'=>$productcategory,
                'mastercomission'=>$mastercomission
            );    

            return view('comissionmaster')->with($with);
        }
    }

    public function addcomissionmaster(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        {
            foreach($request->input('categoryid') as $k => $v)
            {
                $category = $v;
                $categoryname = $request->input('category_'.$category);

                $checkcategory = mastercomission::where('comossioncategory', $categoryname)->count();

                if($checkcategory > 0 )
                {
                    //update
                    $update = mastercomission::where('comossioncategory', $categoryname)->first();
                    $update->comissioncategoryview = $request->input('show_'.$category);
                    $update->comissioncounton = $request->input('counton_'.$category);
                    $update->save();
                }
                else
                {
                    //insert
                    $insert = mastercomission::insert(
                    [
                        'comossioncategory'=> $categoryname, 
                        'comissioncategoryview'=>$request->input('show_'.$category), 
                        'comissioncounton'=>$request->input('counton_'.$category)
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Comission changes is saved successfully.');
        }
    }

    public function plancomission()
    {   
        if(session::get('loggindata.loggeduserpermission.viewmasters')=='N' ||session::get('loggindata.loggeduserpermission.viewmasters')=='')
        {
            return redirect('404');
        } 
        else
        {
            $plancategory = masterplancategory::where('pcstatus', '1')->get();
            $planterm = masterplanhandsetterm::where('planhandsettermstatus', '1')->get(); 
            $comissionrange = plancomission::get();
            $comissiontax = plancomissiontax::first();

            $with = array(
                'plancategory'=>$plancategory,
                'comissionrange'=>$comissionrange,
                'comissiontax'=>$comissiontax,
                'planterm'=>$planterm
            );
            return view('plancomissionmaster')->with($with);
        }
    }

    public function plancomissionadd(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        {
            $rangeid = $request->input('updaterangeid');
            $updatefromrange = $request->input('updatefromrange');
            $updatetorange = $request->input('updatetorange');
            $updatemultipler = $request->input('updatemultipler');
            $updatecategory = $request->input('updatecategory');
            $updateterm = $request->input('updateterm');

            $fromrange = $request->input('fromrange');
            $torange = $request->input('torange');
            $multipler = $request->input('multipler');
            $category = $request->input('category');
            $term = $request->input('term');
                
            $size = count($fromrange);

            foreach($rangeid as $key=>$id)
            {
                $updatestockgroup = plancomission::where('plancomissionID', $id)->update(
                [
                    'plancomissionFrom'=>$updatefromrange[$key], 
                    'plancomissionTo'=>$updatetorange[$key], 
                    'plancomissionMultiplier'=>$updatemultipler[$key],
                    'plancomissionTerm'=>$updateterm[$key], 
                    'plancomissionCategory'=>$updatecategory[$key]
                ]);
            }

            for($i = 0 ; $i < $size ; $i++){
                if($multipler[$i]!=0)
                {
                  $items[] = 
                  [
                     "plancomissionFrom" => $fromrange[$i],
                     "plancomissionTo" => $torange[$i],
                     "plancomissionMultiplier" => $multipler[$i],
                     "plancomissionTerm"=>$term[$i],
                     "plancomissionCategory"=> $category[$i]
                  ];
                }
                else
                {
                    $items = "";
                }
            }
            if($items != "")
            {
                plancomission::insert($items);
            }
            

            return redirect()->back()->with('success', 'Comission changes is saved successfully.');
        }
    }

    public function addplancomtax(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addmasters')=='N' ||session::get('loggindata.loggeduserpermission.addmasters')=='')
        {
            return redirect('404');
        }
        else
        {
            $gst = $request->input('gst');

            $checkgst = plancomissiontax::first();

            if($checkgst == "")
            {
                $insert = new plancomissiontax;
                $insert->plancomtaxValue = $gst;
                $insert->save();
            }
            else
            {
                $checkgst->plancomtaxValue = $gst;
                $checkgst->save();
            }

            return redirect()->back()->with('success', 'Comission changes is saved successfully.');
        }
    }

    public function ajaxdeleteplancomission(Request $request)
    {  
        if(isset($request->id))
        {
          $todo = plancomission::findOrFail($request->id);
          $todo->delete();
          return redirect()->back();
        }
    }
}
