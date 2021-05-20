<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Cookie;
use Tracker;
use Session;
use Validator;

use App\loggeduser;
use App\mainmenu;
use App\storeuser;
use App\submenu;
use App\userpermission;
use App\mastersupplier;
use App\usergroup;
use App\store;
use App\storetype;
use App\masterplantype;
use App\masterplanterm;
use App\masterplancategory;
use App\masterstockgroup;
use App\plan;
use App\planstockgroup;
use App\planhandsetterm;
use App\masterplanpropositiontype;
use App\masterplanhandsetterm;

class planscontroller extends Controller
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
    public function plansview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewplans')=='N' ||session::get('loggindata.loggeduserpermission.viewplans')=='')
        {
            return redirect('404');
        } 
        else
        {
        	$plantype= masterplantype::where('plantypestatus', '1')->get();
        	$planterm= masterplanterm::where('plantermstatus', '1')->get();
        	$plancategory= masterplancategory::where('pcstatus', '1')->get();
        	$planstockgroup= masterstockgroup::where('stockgroupstatus', '1')->get();
            $planpropositiontype = masterplanpropositiontype::where('planpropositionstatus', '1')->get();
            $planhandsetterm = masterplanhandsetterm::where('planhandsettermstatus', '1')->get();

            /*****For Filters*****/
            $planamount = plan::groupBy('ppingst')->get();
            /*****For Filters*****/

            /******Filters*****/
            $pprice = session::get('planfilters.planprice');
            $ptype = session::get('planfilters.plantype');
            $propositiontype = session::get('planfilters.propositiontype');
            $pterm = session::get('planfilters.planterm');
            $phandsetterm = session::get('planfilters.planhandsetterm');
            $stockgroup = session::get('planfilters.planstockgroup');
            /******Filters*****/

        	/*$allplans= plan::with('plantypere')
        	->with('plantermre')
            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'plan.planhandsetterm')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
        	->leftJoin('users', 'users.id', '=', 'plan.userID')
        	->get();*/

            $allplans= plan::with('plantypere')
            ->with('plantermre')
            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'plan.planhandsetterm')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
            ->leftJoin('users', 'users.id', '=', 'plan.userID')
            ->where('plan.ppingst', 'LIKE', '%'.$pprice.'%')
            ->where('plan.plantypeID', 'LIKE', '%'.$ptype.'%')
            ->where('plan.planpropositionID', 'LIKE', '%'.$propositiontype.'%')
            ->where('plan.planterm', 'LIKE', '%'.$pterm.'%')
            ->where('plan.planhandsetterm', 'LIKE', '%'.$phandsetterm.'%')
            ->where('plan.planstockgroup', 'LIKE', '%'.$stockgroup.'%')
            ->get();

        	$plandata = ['plantype'=>$plantype, 'planterm'=>$planterm, 'plancategory'=>$plancategory, 'planstockgroup'=>$planstockgroup, 'allplans'=>$allplans, 'planpropositiontype'=>$planpropositiontype, 'planhandsetterm'=>$planhandsetterm, 'planamount'=>$planamount, 'pprice'=>$pprice, 'ptype'=>$ptype, 'propositiontype'=>$propositiontype, 'pterm'=>$pterm, 'phandsetterm'=>$phandsetterm, 'stockgroup'=>$stockgroup];

            return view('plans')->with('plandata', $plandata);
        }
    }

    public function planadd(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addplans')=='N' ||session::get('loggindata.loggeduserpermission.addplans')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'planname'=>'required',
            'plancode'=>'required',
            'ppingst'=>'required',
            'plantype'=>'required',
            'planpropositiontype'=>'required',
            'planterm'=>'required',
            'plancategory'=>'required',
            'planstockgroup'=>'required'
            ],[
                'planname.required'=>'Plan name is required',
                'plancode.required'=>'Plan code is required',
                'ppingst.required'=>'Plan price Inc. Gst is required',
                'plantype.required'=>'Plan Type is required',
                'planpropositiontype.required'=>'Plan Proposition is required',
                'planterm.required'=>'Plan Term is required',
                'plancategory.required'=>'Plan category is required',
                'planstockgroup.required'=>'Plan stock group is required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $insertplan=0;
                $items1 = [];
                $planname= $request->input('planname');
                $plancode= $request->input('plancode');
                $description= $request->input('description');
                $ppingst= $request->input('ppingst');
                $plantype= $request->input('plantype');
                $planterm= $request->input('planterm');
                $planstatus= '1';
                $planpropositiontype= $request->input('planpropositiontype');

                $planhandsetterm = $request->input('planhandsetterm');
                $plancategoryID = $request->input('plancategory');
                $plancomission = $request->input('plancomission');
                $planbonustype = $request->input('planstaffbonustype');
                $planbonus = $request->input('planstaffbonusvalue');
                
                if($request->input('planadditionalcom') == 1)
                {
                    $planaddcomission = 1;
                }
                else
                {
                    $planaddcomission = 0;
                }

                $planstockgroup= $request->input('planstockgroup');

                if($planstockgroup == '')
                {
                    $planstockgroup = array();
                }

                $size = count($planhandsetterm);
                    
                for($i = 0 ; $i < $size ; $i++)
                {
                  $items[] = [
                     "plantypeID" => $plantype,
                     "planpropositionID" => $planpropositiontype, 
                     "plancode" => $plancode, 
                     "planname" => $planname,
                     "description" => $description,
                     "ppingst" => $ppingst,
                     "planterm" => $planterm,
                     "planhandsetterm" => $planhandsetterm[$i],
                     "plancategoryID" => $plancategoryID[$i],
                     "plancomission" => $plancomission[$i],
                     "planbonustype" => $planbonustype[$i],
                     "planbonus" => $planbonus[$i],
                     "planaddtionalcomission"=> $planaddcomission,
                     "planstatus" => $planstatus,
                     "planstockgroup" => implode(',', $planstockgroup),
                     "userID" => session::get('loggindata.loggedinuser.id')
                  ];
                }
                $insertplan = plan::insert($items);

                return redirect()->back()->with('plansuccess','Plan Added Successfully');
            }
        }
    }

    public function editplanstatus(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.deleteplans')=='N' || session::get('loggindata.loggeduserpermission.deleteplans')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'planstatus'=>'required'
            ],[
                'planstatus.required'=>'Plan status is null'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateplanstatus= plan::find($request->input('planid'));
                $updateplanstatus->planstatus= $request->input('planstatus');
                $updateplanstatus->save();
                if($updateplanstatus->save())
                {
                   return redirect()->back()->with('plansuccess','Plan Status Updated Successfully'); 
                }
                else
                {
                    return redirect()->back()->with('planerror','Plan Status Not Updated');
                }
            }     
        }
    }

    public function changeplanview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.editplans')=='N' ||session::get('loggindata.loggeduserpermission.editplans')=='')
        {
            return redirect('404');
        } 
        else
        {
            $plantype= masterplantype::where('plantypestatus', '1')->get();
            $planterm= masterplanterm::where('plantermstatus', '1')->get();
            $plancategory= masterplancategory::where('pcstatus', '1')->get();
            $planstockgroup= masterstockgroup::where('stockgroupstatus', '1')->get();
            $planpropositiontype = masterplanpropositiontype::where('planpropositionstatus', '1')->get();
            $planhandsetterm = masterplanhandsetterm::where('planhandsettermstatus', '1')->get();

            $allplans= plan::with('plantypere')
            ->with('plantermre')
            ->leftJoin('masterplanhandsetterm', 'masterplanhandsetterm.planhandsettermID', '=', 'plan.planhandsetterm')
            ->leftJoin('masterplancategory', 'masterplancategory.pcID', '=', 'plan.plancategoryID')
            ->leftJoin('users', 'users.id', '=', 'plan.userID')
            ->where('plan.planID', $id)
            ->first();

            //return $allplans;


            $plandata = ['plantype'=>$plantype, 'planterm'=>$planterm, 'plancategory'=>$plancategory, 'planstockgroup'=>$planstockgroup, 'allplans'=>$allplans, 'planpropositiontype'=>$planpropositiontype, 'planhandsetterm'=>$planhandsetterm];
            return view('plans-edit')->with('plandata', $plandata);
        }
    }

    public function planedit(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addplans')=='N' ||session::get('loggindata.loggeduserpermission.addplans')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkplan= plan::where('planID', '=', $request->input('planid'))->count();
            //return $checkbrand;
            if($checkplan == 1)
            {
                $validator = validator::make($request->all(),[
                'planname'=>'required',
                'plancode'=>'required',
                'ppingst'=>'required',
                'plantype'=>'required',
                'planpropositiontype'=>'required',
                'planterm'=>'required',
                'plancategory'=>'required',
                'planstockgroup'=>'required'
                ],[
                    'planname.required'=>'Plan name is required',
                    'plancode.required'=>'Plan code is required',
                    'ppingst.required'=>'Plan price Inc. Gst is required',
                    'plantype.required'=>'Plan Type is required',
                    'planpropositiontype.required'=>'Plan Proposition is required',
                    'planterm.required'=>'Plan Term is required',
                    'plancategory.required'=>'Plan category is required',
                    'planstockgroup.required'=>'Plan stock group is required'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    if($request->input('planadditionalcom') == 1)
                    {
                        $planadditionalcom = '1';
                    }
                    else
                    {
                        $planadditionalcom = '0';
                    }

                    $updateplan= plan::where('planID', '=', $request->input('planid'))->first();
                    $updateplan->planname= $request->input('planname');
                    $updateplan->plancode= $request->input('plancode');
                    $updateplan->description= $request->input('description');
                    $updateplan->ppingst= $request->input('ppingst');
                    $updateplan->plantypeID= $request->input('plantype');
                    $updateplan->planterm= $request->input('planterm');
                    $updateplan->planpropositionID= $request->input('planpropositiontype');
                    $updateplan->planhandsetterm = $request->input('planhandsetterm');
                    $updateplan->plancategoryID = $request->input('plancategory');
                    $updateplan->plancomission = $request->input('plancomission');
                    $updateplan->planbonustype = $request->input('planstaffbonustype');
                    $updateplan->planbonus = $request->input('planstaffbonusvalue');
                    $updateplan->planaddtionalcomission = $planadditionalcom;
                    $updateplan->planstockgroup= implode(',', $request->input('planstockgroup'));
                    $updateplan->save();

                    if($updateplan->save())
                    {
                        return redirect()->back()->with('success','Plan updated successfully.');
                    }
                    else
                    {
                        return redirect()->back()->with('error','Failed to update plan.');
                    }
                }
            }
            else
            {
               return redirect()->back()->with('error','Couldnot found plan details to update. Failed to update.'); 
            }
        }
    }
}
