@extends('main')

@section('content')
	@include('includes.topbar')

    @include('includes.sidebar')
    <script>
    	function isNumber(evt) {
		    evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;
		    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		}
    </script>

    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">Products</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                    <li class="breadcrumb-item active">Products</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                            	<div class="card-body">
                            		<div class="row"> 
		                            	<div class="col-md-12">
		                            		<form action="{{route('productsfilters')}}" method="post">
		                            			@csrf
		                            			<div class="row">
		                            				<div class="col-md-2">
		                            					<select class="form-control" name="supplier">
		                            						<option value="">SELECT SUPPLIER</option>
		                            						@foreach($productsdata['allsuppliers'] as $suppliers)
		                            						<option value="{{$suppliers->supplierID}}" @if($suppliers->supplierID == $productsdata['supplier'])  SELECTED='SELECTED' @endif>{{$suppliers->suppliername}}</option>
		                            						@endforeach
		                            					</select>
		                            				</div>

		                            				<div class="col-md-2">
		                            					<select class="form-control" name="category">
		                            						<option value="">SELECT CATEGORY</option>
		                            						@foreach($productsdata['allcategories'] as $categorys)
		                            						<option value="{{$categorys->categoryID}}" @if($categorys->categoryID == $productsdata['category'])  SELECTED='SELECTED' @endif>{{$categorys->categoryname}}</option>
		                            						@endforeach
		                            					</select>
		                            				</div>

		                            				<div class="col-md-2">
		                            					<select class="form-control" name="brand">
		                            						<option value="">SELECT BRAND</option>
		                            						@foreach($productsdata['allbrands'] as $brands)
		                            						<option value="{{$brands->brandID}}" @if($brands->brandID == $productsdata['brand'])  SELECTED='SELECTED' @endif>{{$brands->brandname}}</option>
		                            						@endforeach
		                            					</select>
		                            				</div>

		                            				<div class="col-md-2">
		                            					<select class="form-control" name="model">
		                            						<option value="">SELECT MODEL</option>
		                            						@foreach($productsdata['allmodels'] as $models)
		                            						<option value="{{$models->modelID}}" @if($models->modelID == $productsdata['model'])  SELECTED='SELECTED' @endif>{{$models->modelname}}</option>
		                            						@endforeach
		                            					</select>
		                            				</div>

		                            				<div class="col-md-2">
		                            					<select class="form-control" name="colour">
		                            						<option value="">SELECT COLOUR</option>
		                            						@foreach($productsdata['allcolours'] as $colours)
		                            						<option value="{{$colours->colourID}}" @if($colours->colourID == $productsdata['colour'])  SELECTED='SELECTED' @endif>{{$colours->colourname}}</option>
		                            						@endforeach
		                            					</select>
		                            				</div>

		                            				<div class="col-md-2">
		                            					<select class="form-control" name="stockgroup">
		                            						<option value="">SELECT GROUP</option>
		                            						@foreach($productsdata['allstockgroup'] as $stockgroups)
		                            						<option value="{{$stockgroups->stockgroupID}}" @if($stockgroups->stockgroupID == $productsdata['stockgroup'])  SELECTED='SELECTED' @endif>{{$stockgroups->stockgroupname}}</option>
		                            						@endforeach
		                            					</select>
		                            				</div>

		                            			</div>
		                            			<div class="row" style="margin-top: 10px;">
		                            				<div class="col-md-12 text-right">
		                            					@if(session('loggindata')['loggeduserpermission']->addproducts=='Y')
		                            					<a href="productaddpage" class="btn btn-outline-primary waves-effect waves-light">Add Products</a>
		                            					@endif
		                            					<button type="submit" class="btn btn-success">Apply</button>
		                            				</div>
		                            			</div>	
		                            		</form>	
		                            	</div>
		                            </div>
                            	</div>
                            	@if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                            	@if(session()->has('productaddsuccess'))
									<div class="card-body">	                                
									    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
									        {{ session()->get('productaddsuccess') }}
									    </div>
									</div>
								@endif
								@if(session()->has('productadderror'))
								<div class="card-body">
								    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
								        {{ session()->get('productadderror') }}
								    </div>
								</div>
								@endif
                                <div class="card-body">
                                    <table id="datatable" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                        	<th>Barcode</th>
                                        	<th>Stock Code</th>
                                        	<th>Product<br> Category</th>
                                            <th>Product<br> Name/entity</th>
                                            <th>Product<br> Description</th>
                                            <th>Quantity<br> Available</th>
                                            <th>Product<br> Stock Group</th>
                                            <th>Added<br> By/On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        	@foreach($productsdata['allproducts']->groupBy('productID') as $products)
                                        	<tr>
                                        		<td>
                                        			@if($products[0]->barcode !='')
                                        			{{$products[0]->barcode}}
                                        			@else
                                        			#
                                        			@endif
                                        		</td>
                                        		<td>
                                        			@if($products[0]->stockcode !='')
                                        			{{$products[0]->stockcode}}
                                        			@else
                                        			#
                                        			@endif
                                        		</td>
                                        		<td>{{$products[0]->productcategory['categoryname']}} {{$products[0]->productsubcategory['subcategoryname']}}</td>
                                        		<td>
                                        			{{$products[0]->productname}} 
                                        		</td>
                                        		<td>{{$products[0]->description}}</td>
                                        		<td>

                                        			@if(count($products[0]->productstock) == "")
                                        			0.00
                                        			@else
	                                        			@foreach(\App\store::get() as $store)
	                                        			{{$store->store_code}}: {{$products[0]->productstock->where('storeID', $store->store_id)->where('productID', $products[0]->productID)->sum('productquantity')}}<br>
                                        				@endforeach
                                        			@endif
                                        		</td>
                                        		<td>
                                        			@foreach($products as $stockgroup)
                                        			{{$stockgroup->stockgroupname}}<br>
                                        			@endforeach
                                        		</td>
                                        		<td>{{$products[0]->name}}<br>{{$products[0]->created_at}}</td>
                                        		<td>
                                        			@if(session('loggindata')['loggeduserpermission']->editproducts=='Y')
                                        			<a href="changeproduct/{{$products[0]->productID}}" class="btn btn-outline-success waves-effect waves-light"><i class="icon-pencil"></i></a>
                                        			@else
                                        			<a class="btn btn-light waves-effect" title="Active"><i class="icon-pencil"></i></a>
                                        			@endif
                                        			@if($products[0]->productstatus == 1)
                                        				@if(session('loggindata')['loggeduserpermission']->deleteproducts=='Y')
                                        				<!--Active Model-->
                                        				<div class="modal fade bs-example-modal-center activestatusmodel{{$products[0]->productID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
									                        <div class="modal-dialog modal-dialog-centered">
									                            <div class="modal-content">
									                                <div class="modal-header">
									                                    <h5 class="modal-title mt-0">{{$products[0]->productname}} Status</h5>
									                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									                                        <span aria-hidden="true">&times;</span>
									                                    </button>
									                                </div>
									                                <div class="modal-body">
									                                    <form class="" action="{{route('editproductstatus')}}" method="post" novalidate="">
									                                    	@csrf
									                                        <div class="form-group">
									                                            <h4>{{$products[0]->productname}} is in <span class="badge badge-primary">Active Status</span></h4>
									                                            <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
									                                            <p>Click on yes to continue or cancle it.</p>
									                                            <input type="hidden" name="productid" class="form-control" value="{{$products[0]->productID}}">
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
									                        </div><!-- /.modal-dialog -->
									                    </div>
									                    <!--Active Model-->
                                        				<a class="btn btn-outline-success waves-effect waves-light" title="Active" data-toggle="modal" data-target=".activestatusmodel{{$products[0]->productID}}"><i class="icon-music-play"></i></a>
                                        				@else
                                        				<a class="btn btn-light waves-effect" title="Active"><i class="icon-music-play"></i></a>
                                        				@endif
                                        			@else
                                        				@if(session('loggindata')['loggeduserpermission']->deleteproducts=='Y')
                                        				<!--Inactive model-->
		                                            	<div class="modal fade bs-example-modal-center inactivestatusmodel{{$products[0]->productID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
									                        <div class="modal-dialog modal-dialog-centered">
									                            <div class="modal-content">
									                                <div class="modal-header">
									                                    <h5 class="modal-title mt-0">{{$products[0]->productname}} Status</h5>
									                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									                                        <span aria-hidden="true">&times;</span>
									                                    </button>
									                                </div>
									                                <div class="modal-body">
									                                    <form class="" action="{{route('editproductstatus')}}" method="post" novalidate="">
									                                    	@csrf
									                                        <div class="form-group">
									                                            <h4>{{$products[0]->productname}} is in <span class="badge badge-primary">Inactive Status</span></h4>
									                                            <h4>Do you want to make it <span class="badge badge-primary">Active Status</span></h4>
									                                            <p>Click on yes to continue or cancle it.</p>
									                                            <input type="hidden" name="productid" class="form-control" value="{{$products[0]->productID}}">
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
									                        </div><!-- /.modal-dialog -->
									                    </div>
		                                            	<!--Inactive model-->
                                        				<a class="btn btn-outline-danger waves-effect waves-light" data-toggle="modal" data-target=".inactivestatusmodel{{$products[0]->productID}}" title="Inactive"><i class="icon-music-pause"></i></a>
                                        				@else
                                        				<a class="btn btn-light waves-effect" title="Inactive"><i class="icon-music-pause"></i></a>
                                        				@endif
                                        			@endif
                                        		</td>
                                        	</tr>
                                        	@endforeach
                                        </tbody>
                                    </table>
    
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->

            @include('includes.footer')

        </div>
    </div>
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="{{ asset('posview') }}/assets/js/calculation.js"></script>
    <script>
    $(document).ready(function(){

     $('#barcode').blur(function(){
      var error_email = '';
      var username = $('#barcode').val();
      var _token = $('input[name="_token"]').val();
      var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      $.ajax({
        url:"{{ route('ajaxcheckbarcode') }}",
        method:"POST",
        data:{username:username, _token:_token},
        success:function(result)
        {
         if(result == 'unique')
         {
          $('#error_email').html('<label class="text-success">Barcode Not Exists</label>');
          $('#barcode').removeClass('has-error');
          $('#submit').attr('disabled', false);
         }
         else
         {
          $('#error_email').html('<label class="text-danger">Barcode already in use</label>');
          $('#barcode').addClass('has-error');
          $('#submit').attr('disabled', 'disabled');
         }
        }
       })
     });
     
    });
    </script>
@endsection
        