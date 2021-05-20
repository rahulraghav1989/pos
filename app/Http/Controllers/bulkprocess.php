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
use App\mastertax;

use App\product;
use App\productstockgroup;

class bulkprocess extends Controller
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

        if(session::get('loggindata.loggeduserstore') == "" || count(session::get('loggindata.loggeduserstore')) == 0)
        {
            if(session::get('loggindata.loggedinuser.usertypeRole') != 'Admin')
            {
                Auth::logout();
                return redirect('login')->with('error', 'You are trying to log-in in wrong store');
            }
        }

        $allstore= store::get();

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
        $allsubcategory = mastersubcategory::get();

        $filtersdata = array(
            'allbrands'=>$allbrands,
            'allcolours'=>$allcolours,
            'allmodels'=>$allmodels,
            'allstockgroup'=>$allstockgroup,
            'allsuppliers'=>$allsuppliers,
            'alltaxs'=>$alltaxs,
            'allcategories'=>$allcategories,
            'allproducttype'=>$allproducttype,
            'allstores'=>$allstores,
            'allsubcategory'=>$allsubcategory
        ); 
        session::put('filtersdata', $filtersdata);
        /***Filters****/

        session::put('allstore', $allstore);
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function app_accesscomission()
    {   
        if(session::get('loggindata.loggeduserpermission.bulk_appacccomission')=='N' ||session::get('loggindata.loggeduserpermission.bulk_appacccomission')=='')
        {
            return redirect('404');
        } 
        else
        {
           
           $supplier = session::get('bulkcomissionfilter.supplier');
           /*$subcategory = session::get('bulkcomissionfilter.subcategory');*/
           $stockgroup = session::get('bulkcomissionfilter.stockgroup');
            
           /*$accproductcount= product::leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
           ->where('products.supplierID', 'LIKE', '%'.$supplier.'%')
           ->where('products.categories', 'LIKE', '%'.$category.'%')
           ->where('productstockgroup.stockgroupID', 'LIKE', '%'.$stockgroup.'%')
           ->whereNull('products.producttype')
           ->count();*/
           if(!empty(session::get('bulkcomissionfilter')))
           {
                $accproductcount= product::where('categories', '2')
                ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
                ->where('products.supplierID', 'LIKE', '%'.$supplier.'%')
                ->where('productstockgroup.stockgroupID', $stockgroup)
                ->count();
           }
           else
           {
                $accproductcount= product::where('categories', '2')
                ->count();
           }

           //return $accproductcount;

           $accproductid= product::where('categories', '2')
           ->get(array('products.productID'));

           $with= array(
            'accproductcount'=>$accproductcount,
            'supplier'=>$supplier,
            'stockgroup'=>$stockgroup,
            'accproductid'=>$accproductid
           );

           return view('bulk_appacccomission')->with($with);
        }
    }

    public function update_app_accesscomission(Request $request)
    {   
        if(session::get('loggindata.loggeduserpermission.bulk_appacccomission')=='N' ||session::get('loggindata.loggeduserpermission.bulk_appacccomission')=='')
        {
            return redirect('404');
        } 
        else
        {
             $validator = validator::make($request->all(),[
            'stockgroupid'=>'required'
            ],[
                'stockgroupid.required'=>'Please select one stock group from filters. Its required to change comission'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $productid= preg_replace('/\s+/', ',', $request->input('productid'));
                //return $productid;
                /*$updatecomission = productstockgroup::whereIn('productID', $request->input('productid'))->where('stockgroupID', '=', $request->input('stockgroupid'))->get();
                return $updatecomission;*/

                $updatecomission = productstockgroup::whereIn('productID', $productid)->where('stockgroupID', '=', $request->input('stockgroupid'))->update(
                    [
                        'staffbonustype'=>$request->input('stockgroupbonustype'),
                        'dealermargin'=>$request->input('dealermargin'),
                        'staffbonus'=>$request->input('stockgroupbonusvalue')
                    ]);

                return $updatecomission;
            } 
        }
    }
}
