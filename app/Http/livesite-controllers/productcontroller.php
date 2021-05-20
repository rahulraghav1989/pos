<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Cookie;
use Tracker;
use Session;
use Validator;
use DB;
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
use App\stockreturn;

class productcontroller extends Controller
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

        session::put('allstore', $allstore);
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function productsview()
    {   
        if(session::get('loggindata.loggeduserpermission.viewproducts')=='N' ||session::get('loggindata.loggeduserpermission.viewproducts')=='')
        {
            return redirect('404');
        } 
        else
        {
            /******Filters*****/
            $supplier = session::get('productsfilters.supplier');
            $category = session::get('productsfilters.category');
            $brand = session::get('productsfilters.brand');
            $model = session::get('productsfilters.model');
            $colour = session::get('productsfilters.colour');
            /*$stockgroup = session::get('productsfilters.stockgroup');*/
            /******Filters*****/

        	/*$allproducts= product::with('productbrand')
            ->with('productcolour')
            ->with('productmodel')
            ->with('productcategory')
            ->with('productsubcategory')
            ->with('productstock')
            ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
        	->join('users', 'users.id', '=', 'products.userID')
            ->where('products.supplierID', 'LIKE', '%'.$supplier.'%')
            ->where('products.categories', 'LIKE', '%'.$category.'%')
            ->where('products.brand', 'LIKE', '%'.$brand.'%')
            ->where('products.model', 'LIKE', '%'.$model.'%')
            ->where('products.colour', 'LIKE', '%'.$colour.'%')
            ->where('masterstockgroup.stockgroupID', 'LIKE', '%'.$stockgroup.'%')
        	->get();*/

            /*$allproducts = product::leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->leftJoin('productstockgroup', 'productstockgroup.productID', '=', 'products.productID')
            ->leftJoin('masterstockgroup', 'masterstockgroup.stockgroupID', '=', 'productstockgroup.stockgroupID')
            ->leftJoin('users', 'users.id', '=', 'products.userID')
            ->where('products.supplierID', 'LIKE', '%'.$supplier.'%')
            ->where('products.categories', 'LIKE', '%'.$category.'%')
            ->where('products.brand', 'LIKE', '%'.$brand.'%')
            ->where('products.model', 'LIKE', '%'.$model.'%')
            ->where('products.colour', 'LIKE', '%'.$colour.'%')
            ->where('masterstockgroup.stockgroupID', 'LIKE', '%'.$stockgroup.'%')
            ->groupBy('products.productID')
            ->get(array(
                'products.productID',
                'products.productname',
                'products.barcode',
                'products.stockcode',
                'products.description',
                'products.categories',
                'products.colour',
                'products.model',
                'products.brand',
                'products.supplierID',
                'products.userID',
                'products.created_at',
                'products.productstatus',
                'mastercategory.categoryname',
                'masterstockgroup.stockgroupname',
                'users.name'
            ));*/
            $allproducts = product::select('productID', 'productname', 'barcode', 'stockcode', 'description', 'productstatus', 'created_at', 'categories', 'supplierID', 'userID')->with('productcategory')
            ->with('productsupplier')
            ->with('productaddedby')
            ->where('supplierID', 'LIKE', '%'.$supplier.'%')
            ->where('categories', 'LIKE', '%'.$category.'%')
            ->where('colour', 'LIKE', '%'.$colour.'%')
            ->where('model', 'LIKE', '%'.$model.'%')
            ->where('brand', 'LIKE', '%'.$brand.'%')
            ->where('products.productstatus', '1')
            ->get();
        	//return $allproducts;
            if(request()->ajax()) {
                return datatables()->of($allproducts)
                /*->addColumn('action', 'action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()*/
                ->addIndexColumn()
                ->addColumn('action', function($allproducts){
                if(session::get('loggindata.loggeduserpermission.editproducts')=='Y')
                {
                    $btn = '<a href="changeproduct/'.$allproducts->productID.'" class="btn btn-outline-success waves-effect waves-light btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a>';
                }
                else
                {
                    $btn = '<a class="btn btn-light waves-effect btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a>';
                }
                    
                if($allproducts->productstatus == 0)
                {
                    if(session::get('loggindata.loggeduserpermission.deleteproducts')=='Y')
                    {
                        $btn1 = '
                        <div class="modal fade bs-example-modal-center inactivestatusmodel'.$allproducts->productID.'" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title mt-0">'.$allproducts->productname.' Status</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="" action="editproductstatus" method="post" novalidate="">
                                            <input type="hidden" name="_token" value="'.csrf_token().'">
                                            <div class="form-group">
                                                <h4>'.$allproducts->productname.' is in <span class="badge badge-primary">Inactive Status</span></h4>
                                                <h4>Do you want to make it <span class="badge badge-primary">Active Status</span></h4>
                                                <p>Click on yes to continue or cancle it.</p>
                                                <input type="hidden" name="productid" class="form-control" value="'.$allproducts->productID.'">
                                                <input type="hidden" name="productstatus" class="form-control" value="1">
                                            </div>
                                            <div class="form-group text-right">
                                                <div>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                        Yes
                                                    </button>
                                                    <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div>
                        </div>
                        <span data-toggle="tooltip" data-placement="top" title="Active">
                        <a class="btn btn-outline-danger waves-effect waves-light btn-sm" data-toggle="modal" data-target=".inactivestatusmodel'.$allproducts->productID.'"><i class="icon-music-pause"></i></a></span>';
                    }
                    else
                    {
                        $btn1 = '<a class="btn btn-light waves-effect btn-sm" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-pause"></i></a>';
                    }
                }
                else
                {
                    if(session::get('loggindata.loggeduserpermission.deleteproducts')=='Y')
                    {
                        $btn1 = '
                        <div class="modal fade bs-example-modal-center activestatusmodel'.$allproducts->productID.'" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title mt-0">'.$allproducts->productname.' Status</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="" action="editproductstatus" method="post" novalidate="">
                                            <input type="hidden" name="_token" value="'.csrf_token().'">
                                            <div class="form-group">
                                                <h4>'.$allproducts->productname.' is in <span class="badge badge-primary">Active Status</span></h4>
                                                <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
                                                <p>Click on yes to continue or cancle it.</p>
                                                <input type="hidden" name="productid" class="form-control" value="'.$allproducts->productID.'">
                                                <input type="hidden" name="productstatus" class="form-control" value="0">
                                            </div>
                                            <div class="form-group text-right">
                                                <div>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                        Yes
                                                    </button>
                                                    <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div>
                        </div>
                        <span data-toggle="tooltip" data-placement="top" title="In-Active">
                        <a href="" class="btn btn-outline-success waves-effect waves-light btn-sm" data-toggle="modal" data-target=".activestatusmodel'.$allproducts->productID.'"><i class="icon-music-play"></i></a></span>';
                    }
                    else
                    {
                        $btn1 = '<a class="btn btn-light waves-effect btn-sm" data-toggle="tooltip" data-placement="top" title="In-Active"><i class="icon-music-play"></i></a>';
                    }
                }
                
                return $btn." ".$btn1;
                })->rawColumns(['action'])
                ->make(true);
            }

        	/*$productsdata= ['allbrands'=>$allbrands, 'allcolours'=>$allcolours, 'allmodels'=>$allmodels, 'allstockgroup'=>$allstockgroup, 'allsuppliers'=>$allsuppliers, 'alltaxs'=>$alltaxs, 'allcategories'=>$allcategories, 'allproducts'=>$allproducts, 'allproducttype'=>$allproducttype, 'supplier'=>$supplier, 'category'=>$category, 'brand'=>$brand, 'model'=>$model, 'colour'=>$colour, 'stockgroup'=>$stockgroup];*/
            return view('products');
        }
    }

    public function productaddpageview()
    {   
        if(session::get('loggindata.loggeduserpermission.addproducts')=='N' ||session::get('loggindata.loggeduserpermission.addproducts')=='')
        {
            return redirect('404');
        } 
        else
        {
            $allbrands= masterbrand::where('brandstatus', '1')->get();
            $allcolours= mastercolour::where('colourstatus', '1')->get();
            $allmodels= mastermodel::where('modelstatus', '1')->get();
            $allstockgroup= masterstockgroup::where('stockgroupstatus', '1')->get();
            $allsuppliers= mastersupplier::where('supplierstatus', '1')->get();
            $alltaxs= mastertax::where('taxstatus', '1')->get();
            $allcategories= mastercategory::with('subcategory')->where('categorystatus', '1')->get();
            $allproducttype= masterproducttype::where('producttypestatus', '1')->get();
            
            $productsdata= ['allbrands'=>$allbrands, 'allcolours'=>$allcolours, 'allmodels'=>$allmodels, 'allstockgroup'=>$allstockgroup, 'allsuppliers'=>$allsuppliers, 'alltaxs'=>$alltaxs, 'allcategories'=>$allcategories, 'allproducttype'=>$allproducttype];
            return view('productsaddpage')->with('productsdata', $productsdata);
        }
    }

    public function productadd(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addproducts')=='N' ||session::get('loggindata.loggeduserpermission.addproducts')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkproduct= product::where('productname', '=', $request->input('productname'))->first();
            //return $checkbrand;
            if($checkproduct == '')
            {
                $validator = validator::make($request->all(),[
                'productname'=>'required',
                'stockcode'=>'required',
                'ppexgst'=>'required',
                'ppgst'=>'required',
                'ppingst'=>'required',
                'spexgst'=>'required',
                'spgst'=>'required',
                'spingst'=>'required',
                'categories'=>'required',
                'supplier'=>'required',
                'stockgroup'=>'required'
                ],[
                    'productname.required'=>'Product name is required',
                    'stockcode.required'=>'Stock code is required',
                    'ppexgst.required'=>'Purchase price Ex. Gst is required',
                    'ppgst.required'=>'Purchase gst is required',
                    'ppingst.required'=>'Purchase price In. Gst is required',
                    'spexgst.required'=>'Selling price Ex. Gst is required',
                    'spgst.required'=>'Selling gst is required',
                    'spingst.required'=>'Selling price In. Gst is required',
                    'categories.required'=>'Categories is required',
                    'supplier.required'=>'Supplier is required',
                    'stockgroup.required'=>'Stockgroup is required'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    if(empty($request->input('markup')))
                    {
                        $markup = '0';
                    }
                    else
                    {
                        $markup = $request->input('markup');
                    }
                    
                    
                    if($markup != '' || $markup == 0 || $markup == '')
                    {
                        $ppingst = $request->input('ppingst');
                        $totalppingst = $ppingst + $markup;
                    }
                    
                    if($totalppingst <= $request->input('spingst'))
                    {
                        $insertproduct= new product;

                        $subcategory = strpos($request->input('categories'), '-');

                        $subcat= preg_replace('/[^0-9]/','',$request->input('categories'));
                        $getsubcat = mastersubcategory::where('subcategoryID', $subcat)->first();

                        if($subcategory===0)
                        {
                            $insertproduct->subcategory= preg_replace('/[^0-9]/','',$request->input('categories'));
                            $insertproduct->categories = $getsubcat->categoryID;
                        }
                        else
                        {
                            $insertproduct->categories= $request->input('categories');
                            $insertproduct->subcategory= '0';
                        }

                        $insertproduct->productname= $request->input('productname');
                        $insertproduct->barcode= $request->input('barcode');
                        $insertproduct->stockcode= $request->input('stockcode');
                        $insertproduct->description= $request->input('description');
                        $insertproduct->ppexgst= $request->input('ppexgst');
                        $insertproduct->ppgst= $request->input('ppgst');
                        $insertproduct->ppingst= $request->input('ppingst');
                        $insertproduct->spexgst= $request->input('spexgst');
                        $insertproduct->spgst= $request->input('spgst');
                        $insertproduct->spingst= $request->input('spingst');
                        $insertproduct->colour= $request->input('colour');
                        $insertproduct->model= $request->input('model');
                        $insertproduct->brand= $request->input('brand');
                        $insertproduct->producttype = $request->input('producttype');
                        $insertproduct->supplierID= $request->input('supplier');
                        $insertproduct->productstatus= '1';
                        //$insertproduct->categories= $request->input('categories');

                        $stockgroup= $request->input('stockgroup');
                        $supplier= $request->input('supplier');
                        $minqty= $request->input('min-qty');
                        $maxqty= $request->input('max-qty');

                        $insertproduct->userID= session::get('loggindata.loggedinuser.id');
                        $insertproduct->save();

                        if($insertproduct->save())
                        {
                            if($stockgroup != '')
                            {
                                $stockgroupid = $request->input('stockgroup');
                                $dealermargin = $request->input('dealermargin');
                                $stockgroupsbt = $request->input('stockgroupbonustype');
                                $stockgroupsbv = $request->input('stockgroupbonusvalue');
                                    
                                $size = count($stockgroupid);
                                
                                for($i = 0 ; $i < $size ; $i++){
                                  $items[] = [
                                     "productID" => $insertproduct->productID,
                                     "stockgroupID" => $stockgroupid[$i],
                                     "staffbonustype" => $stockgroupsbt[$i],
                                     "dealermargin"=> $dealermargin[$i], 
                                     "staffbonus" => $stockgroupsbv[$i]
                                  ];
                                }
                                productstockgroup::insert($items);
                            }

                            if($supplier != '')
                            {
                                $insertsupplier= new productsupplier;
                                $insertsupplier->productID= $insertproduct->productID;

                                $insertsupplier->product_name= $request->input('s-productname');
                                $insertsupplier->product_description= $request->input('s-description');
                                $insertsupplier->productsku= $request->input('s-sku');
                                $insertsupplier->productsupplier= $supplier;

                                $insertsupplier->save();
                            }

                            if($minqty != '' && $maxqty != '')
                            {
                                $insertqty= new productqtyalert;
                                $insertqty->productID= $insertproduct->productID;
                                $insertqty->supplierID= $supplier;
                                $insertqty->minimumqty= $minqty;
                                $insertqty->maximumqty= $maxqty;

                                $insertqty->save();
                            }
                            else
                            {
                                $insertqty= new productqtyalert;
                                $insertqty->productID= $insertproduct->productID;
                                $insertqty->supplierID= $supplier;
                                $insertqty->minimumqty= 0;
                                $insertqty->maximumqty= 0;

                                $insertqty->save();
                            }

                            return redirect()->back()->with('productaddsuccess','Product add successfully');
                        }
                        else
                        {
                            return redirect()->back()->with('productadderror','Product failed to add');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('productadderror','Selling Price must be greater than or equal');
                    }
                }
            }
            else
            {
               return redirect()->back()->with('productadderror','Product already exist'); 
            }
        }
    }

    public function editproductstatus(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.deleteproducts')=='N' || session::get('loggindata.loggeduserpermission.deleteproducts')=='')
        {
            return redirect('404');
        }
        else
        { 
            $validator = validator::make($request->all(),[
            'productstatus'=>'required'
            ],[
                'productstatus.required'=>'Product status is null'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $updateproductstatus= product::find($request->input('productid'));
                $updateproductstatus->productstatus= $request->input('productstatus');
                $updateproductstatus->save();
                if($updateproductstatus->save())
                {
                   return redirect()->back()->with('productaddsuccess','Product Status Updated Successfully'); 
                }
                else
                {
                    return redirect()->back()->with('productadderror','Product Status Not Updated.');
                }
            }     
        }
    }

    public function changeproductview($id)
    {   
        if(session::get('loggindata.loggeduserpermission.editproducts')=='N' ||session::get('loggindata.loggeduserpermission.editproducts')=='')
        {
            return redirect('404');
        } 
        else
        {
            $allbrands= masterbrand::where('brandstatus', '1')->get();
            $allcolours= mastercolour::where('colourstatus', '1')->get();
            $allmodels= mastermodel::where('modelstatus', '1')->get();
            $allstockgroup= masterstockgroup::where('stockgroupstatus', '1')->get();
            $allsuppliers= mastersupplier::where('supplierstatus', '1')->get();
            $alltaxs= mastertax::where('taxstatus', '1')->get();
            $allcategories= mastercategory::with('subcategory')->where('categorystatus', '1')->get();
            $allproducttype= masterproducttype::where('producttypestatus', '1')->get();
            $products= product::with('productbrand')
            ->with('productcolour')
            ->with('productmodel')
            ->with('productcategory')
            ->with('productsubcategory')
            ->with('productstockgroup')
            ->leftJoin('productqtyalert', 'productqtyalert.productID', '=', 'products.productID')
            ->leftJoin('productsupplierdetail', 'productsupplierdetail.productID', '=', 'products.productID')
            ->join('users', 'users.id', '=', 'products.userID')
            ->where('products.productID', $id)
            ->first(array('products.productID', 'products.stockcode', 'products.productname', 'products.barcode', 'products.description', 'products.ppexgst', 'products.ppgst', 'products.ppingst', 'products.spexgst', 'products.spgst', 'products.spingst', 'products.colour', 'products.model', 'products.brand', 'products.categories', 'products.subcategory', 'products.producttype', 'productqtyalert.minimumqty', 'productqtyalert.maximumqty', 'productqtyalert.productqtyID', 'productsupplierdetail.product_name', 'productsupplierdetail.product_description', 'productsupplierdetail.productsku', 'productsupplierdetail.productsupplier', 'productsupplierdetail.psdID'));
            //return $products;
            $editproductsdata= ['allbrands'=>$allbrands, 'allcolours'=>$allcolours, 'allmodels'=>$allmodels, 'allstockgroup'=>$allstockgroup, 'allsuppliers'=>$allsuppliers, 'alltaxs'=>$alltaxs, 'allcategories'=>$allcategories, 'products'=>$products, 'allproducttype'=>$allproducttype];
            return view('products-edit')->with('editproductsdata', $editproductsdata);
        }
    }

    public function editproduct(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.editproducts')=='N' ||session::get('loggindata.loggeduserpermission.editproducts')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkproduct= product::where('productID', '=', $request->input('productid'))->first();
            //return $checkbrand;
            if($checkproduct == '')
            {
                return redirect()->back()->with('error','Couldnot found product.(ErrorCode - 101)');  
            }
            else
            {
               $validator = validator::make($request->all(),[
                'productname'=>'required',
                'stockcode'=>'required',
                'ppexgst'=>'required',
                'ppgst'=>'required',
                'ppingst'=>'required',
                'spexgst'=>'required',
                'spgst'=>'required',
                'spingst'=>'required',
                'categories'=>'required',
                'supplier'=>'required',
                'stockgroup'=>'required',
                'productid'=>'required'
                ],[
                    'productname.required'=>'Product name is required',
                    'stockcode.required'=>'Stock code is required',
                    'ppexgst.required'=>'Purchase price Ex. Gst is required',
                    'ppgst.required'=>'Purchase gst is required',
                    'ppingst.required'=>'Purchase price In. Gst is required',
                    'spexgst.required'=>'Selling price Ex. Gst is required',
                    'spgst.required'=>'Selling gst is required',
                    'spingst.required'=>'Selling price In. Gst is required',
                    'categories.required'=>'Categories is required',
                    'supplier.required'=>'Supplier is required',
                    'stockgroup.required'=>'Stockgroup is required',
                    'productid.required'=>'productid is required'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                else
                {
                    if(empty($request->input('markup')))
                    {
                        $markup = $request->input('markup');
                    }
                    else
                    {
                        $markup = '';
                    }
                    
                    
                    if($markup != '' || $markup == 0 || $markup == '')
                    {
                        $ppingst = $request->input('ppingst');
                        $totalppingst = $ppingst + $markup;
                    }
                    
                    if($totalppingst <= $request->input('spingst'))
                    {
                        $updateproduct= product::find($request->input('productid'));

                        $subcategory = strpos($request->input('categories'), '-');

                        $subcat= preg_replace('/[^0-9]/','',$request->input('categories'));
                        $getsubcat = mastersubcategory::where('subcategoryID', $subcat)->first();

                        if($subcategory===0)
                        {
                            $updateproduct->subcategory= preg_replace('/[^0-9]/','',$request->input('categories'));
                            $updateproduct->categories = $getsubcat->categoryID;
                        }
                        else
                        {
                            $updateproduct->categories= $request->input('categories');
                            $updateproduct->subcategory= '0';
                        }

                        $updateproduct->productname= $request->input('productname');
                        $updateproduct->barcode= $request->input('barcode');
                        $updateproduct->stockcode= $request->input('stockcode');
                        $updateproduct->description= $request->input('description');
                        $updateproduct->ppexgst= $request->input('ppexgst');
                        $updateproduct->ppgst= $request->input('ppgst');
                        $updateproduct->ppingst= $request->input('ppingst');
                        $updateproduct->spexgst= $request->input('spexgst');
                        $updateproduct->spgst= $request->input('spgst');
                        $updateproduct->spingst= $request->input('spingst');
                        $updateproduct->colour= $request->input('colour');
                        $updateproduct->model= $request->input('model');
                        $updateproduct->brand= $request->input('brand');
                        $updateproduct->producttype = $request->input('producttype');
                        $updateproduct->supplierID= $request->input('supplier');
                        $updateproduct->productstatus= '1';
                        //$updateproduct->categories= $request->input('categories');

                        $stockgroup= $request->input('stockgroup');
                        $supplier= $request->input('supplier');
                        $minqty= $request->input('min-qty');
                        $maxqty= $request->input('max-qty');

                        $updateproduct->userID= session::get('loggindata.loggedinuser.id');
                        $updateproduct->save();

                        if($updateproduct->save())
                        {
                            if($request->input('editstockgroupid') != '')
                            {
                                foreach($request->input('editstockgroupid') as $key=>$id)
                                {
                                    $updatestockgroup = productstockgroup::where('productSGID', $id)->update(
                                    [
                                        'stockgroupID'=>$request->input('editstockgroup')[$key], 
                                        'staffbonustype'=>$request->input('editstockgroupbonustype')[$key], 
                                        'dealermargin'=>$request->input('editdealermargin')[$key], 
                                        'staffbonus'=>$request->input('editstockgroupbonusvalue')[$key]
                                    ]);
                                }
                            }
                            
                            $stockgroupid = $request->input('stockgroup');
                            $dealermargin = $request->input('dealermargin');
                            $stockgroupsbt = $request->input('stockgroupbonustype');
                            $stockgroupsbv = $request->input('stockgroupbonusvalue');
                            
                            if($stockgroupid[0] != '')
                            {
                                $size = count($stockgroupid);
                            
                                for($i = 0 ; $i < $size ; $i++){
                                  $items[] = [
                                     "productID" => $request->input('productid'),
                                     "stockgroupID" => $stockgroupid[$i],
                                     "staffbonustype" => $stockgroupsbt[$i],
                                     "dealermargin"=> $dealermargin[$i], 
                                     "staffbonus" => $stockgroupsbv[$i]
                                  ];
                                }
                                productstockgroup::insert($items);
                            }   

                            if($supplier != '')
                            {
                                $updatesupplier= productsupplier::find($request->input('s-psdid'));

                                $updatesupplier->product_name= $request->input('s-productname');
                                $updatesupplier->product_description= $request->input('s-description');
                                $updatesupplier->productsku= $request->input('s-sku');
                                $updatesupplier->productsupplier= $supplier;

                                $updatesupplier->save();
                            }

                            if($minqty != '' && $maxqty != '')
                            {
                                $updateqty= productqtyalert::find($request->input('productqtyid'));
                                $updateqty->supplierID= $supplier;
                                $updateqty->minimumqty= $minqty;
                                $updateqty->maximumqty= $maxqty;

                                $updateqty->save();
                            }
                            else
                            {
                                $updateqty= productqtyalert::find($request->input('productqtyid'));
                                $updateqty->supplierID= $supplier;
                                $updateqty->minimumqty= 0;
                                $updateqty->maximumqty= 0;

                                $updateqty->save();
                            }

                            return redirect()->back()->with('success','Product updated successfully. (SuccessCode - 101)');
                        }
                        else
                        {
                            return redirect()->back()->with('error','Product failed to update. (ErrorCode - 103)');
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('productadderror','Selling Price must be greater than or equal to porchase price. (ErrorCode - 102)');
                    }
                } 
            }
        }
    }

    public function ajaxdeletestockgroup(Request $request)
    { 
        if(session::get('loggindata.loggeduserpermission.editproducts')=='N' ||session::get('loggindata.loggeduserpermission.editproducts')=='')
        {
            return redirect('404');
        }
        else
        { 
            if(isset($request->id))
            {
              $todo = productstockgroup::findOrFail($request->id);
              $todo->delete();
              return redirect()->back();
            }
        }
    }

    public function ajaxcheckbarcode(Request $request)
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
              $data = product::where('barcode', $username)->count();
              
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

    public function ajaxaddcolour(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addproducts')=='N' ||session::get('loggindata.loggeduserpermission.addproducts')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkcolour= mastercolour::where('colourname', '=', $request->get('colourname'))->first();
            //return $checkbrand;
            if($checkcolour == '')
            {
                $this->validate($request, [
                    'colourname' => 'required'
                ]);

                $insertcolour= new mastercolour;

                $insertcolour->colourname= $request->get('colourname');
                $insertcolour->userID= session::get('loggindata.loggedinuser.id');
                $insertcolour->colourstatus= '1';
                $insertcolour->save();
                $colourid = $insertcolour->colourID;
                $colourname = $request->get('colourname');

                /*return redirect()->back()->with('coloursuccess','Colour added successfully');*/
                //echo 'unique';
                $with = array(
                    'success'=>'unique',
                    'colourID'=>$colourid,
                    'colourname'=>$colourname
                );
                return with($with);
            }
            else
            {
               /*return redirect()->back()->with('colourerror','Colour already exist'); */
               echo 'not_unique';
            }
        }
    }

    public function ajaxaddmodel(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addproducts')=='N' ||session::get('loggindata.loggeduserpermission.addproducts')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkmodel= mastermodel::where('modelname', '=', $request->get('modelname'))->first();
            //return $checkbrand;
            if($checkmodel == '')
            {
                $this->validate($request, [
                    'modelname' => 'required'
                ]);

                $insertmodel= new mastermodel;

                $insertmodel->modelname= $request->get('modelname');
                $insertmodel->brandID= $request->get('brandid');
                $insertmodel->userID= session::get('loggindata.loggedinuser.id');
                $insertmodel->modelstatus= '1';
                $insertmodel->save();
                $modelid = $insertmodel->modelID;
                $modelname = $request->get('modelname');

                /*return redirect()->back()->with('modelsuccess','Model added successfully');*/
                $with = array(
                    'success'=>'unique',
                    'modelid'=>$modelid,
                    'modelname'=>$modelname
                );
                return with($with);
            }
            else
            {
               /*return redirect()->back()->with('modelerror','Model already exist');*/
               echo 'not_unique'; 
            }
        }
    }

    public function ajaxaddbrand(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addproducts')=='N' || session::get('loggindata.loggeduserpermission.addproducts')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checkbrand= masterbrand::where('brandname', '=', $request->get('brandname'))->first();
            //return $checkbrand;
            if($checkbrand == '')
            {
                $this->validate($request, [
                    'brandname' => 'required'
                ]);

                $insertbrand= new masterbrand;

                $insertbrand->brandname= $request->get('brandname');
                $insertbrand->userid= session::get('loggindata.loggedinuser.id');
                $insertbrand->brandstatus= '1';
                $insertbrand->save();
                $brandid = $insertbrand->brandID;
                $brandname = $request->get('brandname');

                /*return redirect()->back()->with('brandsuccess','Brand added successfully');*/
                $with = array(
                    'success'=>'unique',
                    'brandid'=>$brandid,
                    'brandname'=>$brandname
                );
                return with($with);
            }
            else
            {
               /*return redirect()->back()->with('branderror','Brand already exist');*/
               echo 'not_unique'; 
            }
        }
    }

    public function ajaxaddsupplier(Request $request)
    { 

        if(session::get('loggindata.loggeduserpermission.addproducts')=='N' ||session::get('loggindata.loggeduserpermission.addproducts')=='')
        {
            return redirect('404');
        }
        else
        { 
            $checksupplier= mastersupplier::where('suppliername', '=', $request->get('suppliername'))->first();
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

                    $insertsupplier->suppliername= $request->get('suppliername');
                    $insertsupplier->supplierdescription= $request->get('supplierdescription');
                    $insertsupplier->suppliercontactnumber= $request->get('contactnumber');
                    $insertsupplier->supplieraltercontactnumber= $request->get('altcontactnumber');
                    $insertsupplier->supplieracnabn= $request->get('acbabn');
                    $insertsupplier->supplieremail= $request->get('email');
                    $insertsupplier->supplierwebsite= $request->get('website');
                    $insertsupplier->supplierContactperson= $request->get('personname');
                    $insertsupplier->supplierContactpersonnumber= $request->get('personnumber');
                    $insertsupplier->supplierContactpersonemail= $request->get('personemail');
                    $insertsupplier->supplierAddressunit= $request->get('unitnumber');
                    $insertsupplier->supplierAddressstreet= $request->get('streetnumber');
                    $insertsupplier->supplierAddressstreetname= $request->get('streetname');
                    $insertsupplier->SupplierAddresssuburb= $request->get('suburbname');
                    $insertsupplier->supplierAddresspostcode= $request->get('postcode');
                    $insertsupplier->supplierAddressstate= $request->get('state');
                    $insertsupplier->supplierAddresscountry= $request->get('country');
                    $insertsupplier->suppliercreatingnote= $request->get('note');
                    $insertsupplier->userID= session::get('loggindata.loggedinuser.id');
                    $insertsupplier->supplierstatus= '1';
                    $insertsupplier->save();
                    $supplierid = $insertsupplier->supplierID;
                    $suppliername = $request->get('suppliername');

                    /*return redirect()->back()->with('suppliersuccess','Supplier add successfully');*/

                    $with = array(
                    'success'=>'unique',
                    'supplierid'=>$supplierid,
                    'suppliername'=>$suppliername
                    );
                    return with($with);
                }
            }
            else
            {
               /*return redirect()->back()->with('suppliererror','Supplier already exist');*/

               echo "not_unique"; 
            }
        }
    }
}
