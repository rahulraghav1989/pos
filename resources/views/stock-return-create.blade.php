@extends('main')

@section('content')
<div id="wrapper">
    @include('includes.topbar')

    @include('includes.sidebar')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Stock Return Invoice</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Products/Plans</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Return</a></li>
                                <li class="breadcrumb-item active">Stock Return Invoice</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30" style="border: 1px solid #CCC;">
                            <div class="card-body">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                                @if(session()->has('success'))
                                    <div class="alert alert-success alert-dismissible" role="alert" style="margin-top: 10px;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                @if(session()->has('error'))
                                <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 10px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    {{ session()->get('error') }}
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title">Stock Return Details</h4>
                                    </div>
                                    @if($getstockreturn->returnAdminApproval == 0 || $getstockreturn->returnAdminApproval == 2)
                                    <div class="col-md-6 text-right">
                                        <form action="{{route('cancelstockreturn')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="stockreturnid" value="{{$stockreturnid}}">
                                            <button type="submit" class="btn btn-danger">Cancel Return</button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                <hr>
                                
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>RA Number (*)</label>
                                            <input type="text" name="ranumber" id="ranumber" class="form-control" value="{{$getstockreturn->raNumber}}" @if($getstockreturn->returnAdminApproval == 1) readonly @endif>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Supplier (*)</label>
                                            <select name="supplier" id="supplier" class="form-control"  @if($getstockreturn->returnAdminApproval == 1) readonly @endif>
                                                <option value="">SELECT SUPPLIER</option>
                                                @foreach($supplier as $suppliers)
                                                <option value="{{$suppliers->supplierID}}" @if($getstockreturn->supplierID == $suppliers->supplierID) SELECTED='SELECTED' @endif>{{$suppliers->suppliername}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Note/Consignment Number</label>
                                            <input type="text" name="note" id="note" class="form-control" value="{{$getstockreturn->returnNote}}" @if($getstockreturn->returnAdminApproval == 1) readonly @endif>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Date Returned (*)</label>
                                            <input type="date" name="date" id="date" class="form-control" value="{{$getstockreturn->stockreturnDate}}" @if($getstockreturn->returnAdminApproval == 1) readonly @endif>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-md-9" style="border-right: 1px solid #CCC;">
                                        <h4 class="mt-0 header-title">Return Items</h4>
                                        @if($getstockreturn->returnAdminApproval == 0 || $getstockreturn->returnAdminApproval == 2)
                                        <hr>
                                        <div class="col-md-12 text-center">
                                            <form action="{{route('stockreturnbybarcode')}}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" name="stockreturnid" id="stockreturnid" value="{{$stockreturnid}}">
                                                    <input type="hidden" name="storeid" value="{{$getstockreturn->storeID}}">
                                                    <div class="col-md-2">
                                                        <label>Quantity</label>
                                                        <input type="number" name="quantity" class="form-control" min="1" value="1" required="">
                                                    </div>
                                                    <div class="col-md-10">
                                                        <label>Add Product By Barcode</label>
                                                        <div class="input-group">
                                                            <input type="text" name="barcode" placeholder="Barcode" class="form-control" required="">
                                                            <div class="input-group-append add-on">
                                                                <button class="btn btn-success" type="submit">ADD</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 10px;">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <!---All Product Search--->
                                                    <div class="modal fade allsearchproduct" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0" id="myLargeModalLabel">All Products</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="card m-b-30">
                                                                        <div class="card-body">
                                                                            <ul class="nav nav-pills nav-justified" role="tablist">
                                                                                @foreach($productcategory->groupBy('categoryID') as $pc)
                                                                                    
                                                                                <li class="nav-item waves-effect waves-light">
                                                                                    <a class="nav-link @php
                                                                                    if($productcategory[0]->categoryID == $pc[0]->categoryID)
                                                                                    {
                                                                                        echo $tabclass = 'active';
                                                                                    }
                                                                                    @endphp" data-toggle="tab" href="#home-1{{$pc[0]->categoryID}}" role="tab">
                                                                                        <span class="d-none d-md-block">{{$pc[0]->categoryname}}</span>
                                                                                        <span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span> 
                                                                                    </a>
                                                                                </li>
                                                                                @endforeach
                                                                            </ul>
                                            
                                                                            <!-- Tab panes -->
                                                                            <div class="tab-content">
                                                                                @foreach($productcategory->groupBy('categoryID') as $pc)
                                                                                <div class="tab-pane p-3 @php
                                                                                    if($productcategory[0]->categoryID == $pc[0]->categoryID)
                                                                                    {
                                                                                        echo $tabclass = 'active';
                                                                                    }
                                                                                    @endphp" id="home-1{{$pc[0]->categoryID}}" role="tabpanel">
                                                                                    <table id="datatable{{$pc[0]->categoryID}}" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>Barcode</th>
                                                                                            <th>Stock Code</th>
                                                                                            <th>Product Name</th>
                                                                                            <th>Quantity</th>
                                                                                            <th>Select</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @foreach($pc as $pcp)
                                                                                            <tr>
                                                                                                <td>{{$pcp->barcode}}</td>
                                                                                                <td>{{$pcp->stockcode}}</td>
                                                                                                <td>{{$pcp->productname}}</td>
                                                                                                <td>
                                                                                                    @if($pcp->productquantity == "")
                                                                                                    0
                                                                                                    @else
                                                                                                    {{$pcp->productquantity}}
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    <form action="{{route('stockreturnaddallbyproductid')}}" method="post">
                                                                                                        @csrf
                                                                                                        <input type="hidden" name="productid" value="{{$pcp->productID}}">
                                                                                                        <input type="hidden" name="stockreturnid" value="{{$stockreturnid}}">
                                                                                                        <input type="hidden" name="quantity" value="1">
                                                                                                        <input type="hidden" name="storeid" value="{{$getstockreturn->storeID}}">
                                                                                                        <button type="submit" class="btn btn-primary">SELECT</button>
                                                                                                    </form>
                                                                                                </td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                @endforeach
                                                                            </div>
                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
                                                    <!---All Product Search--->
                                                    <button  class="btn btn-primary btn-block waves-effect waves-light" data-toggle="modal" data-target=".allsearchproduct" data-backdrop="static" data-keyboard="false">All Products</button>
                                                </div>
                                                @foreach($producttype as $producttype)
                                                <div class="modal fade producttype{{$producttype->producttypeID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0" id="myLargeModalLabel">Enter {{$producttype->producttypename}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{route('stockreturnaddimeinumber')}}" method="post">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label>{{$producttype->producttypename}}</label>
                                                                                @if($producttype->productrestrictiontype == 1)
                                                                                <input type="text" name="number" class="form-control" placeholder="Type Here" required="" minlength="{{$producttype->productrestrictionword}}" maxlength="{{$producttype->productrestrictionword}}" onkeypress="return isNumber(event)">
                                                                                @else
                                                                                <input type="text" name="number" class="form-control" placeholder="Type Here" required="" minlength="{{$producttype->productrestrictionword}}" maxlength="{{$producttype->productrestrictionword}}">
                                                                                @endif
                                                                                <input type="hidden" name="producttype" class="form-control" value="{{$producttype->producttypeID}}">
                                                                                <input type="hidden" name="stockreturnid" class="form-control" value="{{$stockreturnid}}">
                                                                                <input type="hidden" name="storeid" class="form-control" value="{{$getstockreturn->storeID}}">
                                                                            </div>
                                                                            <div class="col-md-12 text-right" style="margin-top: 20px;">
                                                                                <button type="submit" class="btn btn-primary">Search</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-primary btn-block waves-effect waves-light" data-toggle="modal" data-target=".producttype{{$producttype->producttypeID}}">Add A {{$producttype->producttypename}}</button>
                                                </div>
                                                @endforeach
                                                <div class="col-md-3">
                                                    <div class="modal fade faultyproducts" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0" id="myLargeModalLabel">Faulty Product</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table id="datatable21" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Refund Invoice</th>
                                                                            <th>Barcode</th>
                                                                            <th>Product Name</th>
                                                                            <th>Quantity</th>
                                                                            <th>Select</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($faultyitems as $faultyitem)
                                                                            <tr>
                                                                                <td>{{$faultyitem->refundInvoiceID}}</td>
                                                                                <td>{{$faultyitem->barcode}}</td>
                                                                                <td>{{$faultyitem->productname}}</td>
                                                                                <td>
                                                                                    {{$faultyitem->quantity}}
                                                                                </td>
                                                                                <td>
                                                                                    <form action="{{route('stockreturnaddfaultyproduct')}}" method="post">
                                                                                        @csrf
                                                                                        <input type="hidden" name="productid" value="{{$faultyitem->productID}}">
                                                                                        <input type="hidden" name="stockreturnid" value="{{$stockreturnid}}">
                                                                                        <input type="hidden" name="quantity" value="{{$faultyitem->quantity}}">
                                                                                        <input type="hidden" name="storeid" value="{{$getstockreturn->storeID}}">
                                                                                        <input type="hidden" name="refunditemid" value="{{$faultyitem->refunditemID}}">
                                                                                        <button type="submit" class="btn btn-primary">SELECT</button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-block waves-effect waves-light" data-toggle="modal" data-target=".faultyproducts">Faulty Products</button>
                                                </div>
                                                <div class="col-md-3" style="margin-top: 10px;">
                                                    <div class="modal fade demoproducts" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0" id="myLargeModalLabel">Demo Product</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table id="datatable20" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Barcode</th>
                                                                            <th>Product Name</th>
                                                                            <th>IMEI/Serial</th>
                                                                            <th>Quantity</th>
                                                                            <th>Select</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($demoproducts as $demo)
                                                                            <tr>
                                                                                <td>{{$demo->productname}}</td>
                                                                                <td>{{$demo->barcode}}</td>
                                                                                <td>{{$demo->productimei}}</td>
                                                                                <td>
                                                                                    {{$demo->productquantity}}
                                                                                </td>
                                                                                <td>
                                                                                    <form action="{{route('stockreturnadddemoproduct')}}" method="post">
                                                                                        @csrf
                                                                                        <input type="hidden" name="demoid" value="{{$demo->demostockID}}">
                                                                                        <input type="hidden" name="productid" value="{{$demo->productID}}">
                                                                                        <input type="hidden" name="stockreturnid" value="{{$stockreturnid}}">
                                                                                        <input type="hidden" name="quantity" value="{{$demo->productquantity}}">
                                                                                        <input type="hidden" name="storeid" value="{{$getstockreturn->storeID}}">
                                                                                        <button type="submit" class="btn btn-primary">SELECT</button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
                                                    <button type="button" class="btn btn-warning btn-block waves-effect waves-light" data-toggle="modal" data-target=".demoproducts">Demo Products</button>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-md-12" style="margin-top: 50px;">
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="tech-companies-1" class="table  table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Barcode</th>
                                                        <th data-priority="3">Product Name</th>
                                                        <th data-priority="1">Quantity</th>
                                                        <th data-priority="3">Return Price <br>(Ex. GST)</th>
                                                        <th data-priority="6">GST (%)</th>
                                                        <th data-priority="6">Total <br>(Inc. GST)</th>
                                                        <th data-priority="6">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($getstockreturnitems as $returnitems)
                                                    <tr @if($returnitems->refundItemID != '') style='background-color:#a3aee469;' @endif @if($returnitems->demostockID != '') style='background-color:#e4a3a369;' @endif>
                                                        <td>{{$returnitems->barcode}}</td>
                                                        <td>
                                                            {{$returnitems->productname}}
                                                            @if($returnitems->productimei != '')
                                                            <br>
                                                            {{$returnitems->productimei}}
                                                            @endif
                                                            @if($returnitems->simnumber != '')
                                                            <br>
                                                            Sim: {{$returnitems->simnumber}}
                                                            @endif
                                                            @if($returnitems->demostockID != '')
                                                            {{App\demostock::where('demostockID', $returnitems->demostockID)->pluck('productimei')->first()}}
                                                            @endif
                                                        </td>
                                                        <td>{{$returnitems->quantity}}</td>
                                                        <td>${{$returnitems->ppexgst}}</td>
                                                        <td>{{$returnitems->gst}}%</td>
                                                        <td>${{$returnitems->total}}</td>
                                                        <td>
                                                            @if(session('loggindata')['loggeduserpermission']->editstockreturnitem=='Y' && $getstockreturn->returnAdminApproval == 0 || $getstockreturn->returnAdminApproval == 2)
                                                            <!---Edit Modal--->
                                                            <div class="modal fade editmodel{{$returnitems->stockreturnitemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{$returnitems->productname}}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="{{route('editstockreturnitem')}}" method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="stockreturnitemid" value="{{$returnitems->stockreturnitemID}}">
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-md-4">
                                                                                            <label>Purchase Price (Ex. GST)</label>
                                                                                            <input type="text" name="ppexgst" value="{{$returnitems->ppexgst}}" class="form-control" required="">
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <label>GST %</label>
                                                                                            <input type="text" name="ppgst" value="{{$returnitems->gst}}" class="form-control" required="">
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <label>Purchase Price (Inc. GST)</label>
                                                                                            <input type="text" name="ppingst" value="{{$returnitems->ppingst}}" class="form-control" required="">
                                                                                        </div>
                                                                                        <div class="col-md-12 text-right" style="margin-top: 20px;">
                                                                                            <button type="submit" class="btn btn-primary">Apply</button>
                                                                                            <button type="button" class="btn btn-light" class="close" data-dismiss="modal">Close</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div><!-- /.modal-content -->
                                                                </div><!-- /.modal-dialog -->
                                                            </div>
                                                            <!---Edit Modal--->
                                                            <a href="" class="btn btn-sm btn-outline-success waves-effect waves-light"  data-toggle="modal" data-target=".editmodel{{$returnitems->stockreturnitemID}}" data-backdrop="static" data-keyboard="false"><i class="icon-pencil"></i></a>
                                                            @else
                                                            <a class="btn btn-sm btn-outline-light waves-effect waves-light"><i class="icon-pencil"></i></a>
                                                            @endif

                                                            @if(session('loggindata')['loggeduserpermission']->deletestockreturnitem=='Y' && $getstockreturn->returnAdminApproval == 0 || $getstockreturn->returnAdminApproval == 2)
                                                            <!---Delete Modal--->
                                                            <div class="modal fade deletemodel{{$returnitems->stockreturnitemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">Remove Item</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <h5>You are about to remove <span class="badge badge-primary">{{$returnitems->productname}}</span> from invoice</h5>
                                                                            <h5>Press <span class="badge badge-primary">Yes</span> to continue OR Cancel it</h5>
                                                                            <form action="{{route('deletestockreturnitem')}}" method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="stockreturnitemid" value="{{$returnitems->stockreturnitemID}}">
                                                                                <div class="form-group">
                                                                                    <div class="col-md-12 text-right" style="margin-top: 20px;">
                                                                                        <button type="submit" class="btn btn-primary">Yes</button>
                                                                                        <button type="button" class="btn btn-light" class="close" data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div><!-- /.modal-content -->
                                                                </div><!-- /.modal-dialog -->
                                                            </div>
                                                            <!---Delete Modal--->
                                                            <a href="" class="btn btn-sm btn-outline-danger waves-effect waves-light"  data-toggle="modal" data-target=".deletemodel{{$returnitems->stockreturnitemID}}" data-backdrop="static" data-keyboard="false"><i class="far fa-trash-alt"></i></a>
                                                            @else
                                                            <a class="btn btn-sm btn-outline-light waves-effect waves-light"><i class="far fa-trash-alt"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @if($getstockreturn->returnAdminApproval != '1')
                                        <hr>
                                        <div class="col-md-12 text-center" style="margin-top: 50px;">
                                            <form action="{{route('stockreturnconfirm')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="stockreturnid" value="{{$stockreturnid}}">
                                                <button type="submit" class="btn btn-success">Return Stock</button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <form action="{{route('stockreturncreditamount')}}" method="post">
                                            @csrf
                                            <h4 class="mt-0 header-title">Returns Credit Details</h4>
                                            <hr>
                                            @if($getstockreturnitems->sum('total') != $getstockreturnpayments->sum('returnamount'))
                                                @if($getstockreturn->returnAdminApproval == 1)
                                                <div class="col-md-12">
                                                    <label>Amount Credited</label>
                                                    <input type="text" name="creditamount" class="form-control">
                                                    <input type="hidden" name="stockreturnid" value="{{$stockreturnid}}">
                                                    <input type="hidden" name="totalamount" value="{{$getstockreturnitems->sum('total')}}">
                                                    <input type="hidden" name="creditedamount" value="{{$getstockreturnpayments->sum('returnamount')}}">
                                                </div>
                                                @endif
                                            @endif
                                            <div class="col-md-12">
                                                <table width="100%">
                                                    <thead>
                                                        <th>&nbsp;</th>
                                                        <th>&nbsp;</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-bold">Expected Total<br> Credit inc Gst</td>
                                                            <td class="text-bold">
                                                                ${{$getstockreturnitems->sum('total')}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-bold">Amount Credited</td>
                                                            <td class="text-bold">
                                                                ${{$getstockreturnpayments->sum('returnamount')}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-bold">Credits Pending</td>
                                                            <td class="text-bold">
                                                                ${{$getstockreturnitems->sum('total') - $getstockreturnpayments->sum('returnamount')}}
                                                            </td>
                                                        </tr>
                                                        @if($getstockreturn->stockreturnStatus != '0')
                                                            @if($getstockreturnitems->sum('total') != $getstockreturnpayments->sum('returnamount'))
                                                                @if($getstockreturn->returnAdminApproval == 1)
                                                                    <tr>
                                                                        <td colspan="2" style="text-align: center;">
                                                                            <button type="submit" class="btn btn-success">Credit</button>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- content -->

        @include('includes.footer')
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
        <script src="{{ asset('posview') }}/assets/js/calculation.js"></script>
        <script>
        $(document).ready(function(){

         $('#ranumber').blur(function(){
          var error_email = '';
          var ranumber = $('#ranumber').val();
          var stockreturnid = $('#stockreturnid').val();
          var _token = $('input[name="_token"]').val();
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          $.ajax({
            url:"{{ route('ajaxupdateranumber') }}",
            method:"POST",
            data:{ranumber:ranumber, stockreturnid:stockreturnid, _token:_token},
            success:function(result)
            {
             
            }
           })
         });
         
        });
        </script>

        <script>
        $(document).ready(function(){

         $('#note').blur(function(){
          var error_email = '';
          var note = $('#note').val();
          var stockreturnid = $('#stockreturnid').val();
          var _token = $('input[name="_token"]').val();
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          $.ajax({
            url:"{{ route('ajaxupdatenote') }}",
            method:"POST",
            data:{note:note, stockreturnid:stockreturnid, _token:_token},
            success:function(result)
            {
             
            }
           })
         });
         
        });
        </script>

        <script>
        $(document).ready(function(){

         $('#date').blur(function(){
          var error_email = '';
          var date = $('#date').val();
          var stockreturnid = $('#stockreturnid').val();
          var _token = $('input[name="_token"]').val();
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          $.ajax({
            url:"{{ route('ajaxupdatedate') }}",
            method:"POST",
            data:{date:date, stockreturnid:stockreturnid, _token:_token},
            success:function(result)
            {
             
            }
           })
         });
         
        });
        </script>

        <script>
        $(document).ready(function(){

         $('#supplier').on('change', function(){
          var error_email = '';
          var supplier = $('#supplier').val();
          var stockreturnid = $('#stockreturnid').val();
          var _token = $('input[name="_token"]').val();
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          $.ajax({
            url:"{{ route('ajaxupdatesupplier') }}",
            method:"POST",
            data:{supplier:supplier, stockreturnid:stockreturnid, _token:_token},
            success:function(result)
            {
             
            }
           })
         });
         
        });
        </script>
    </div>
</div>
@endsection