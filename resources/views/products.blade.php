@extends('main')

@section('content')
	@include('includes.topbar')

    @include('includes.sidebar')
    <!-- <script src="//code.jquery.com/jquery-3.3.1.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <!-- <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script> -->
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
var SITEURL = '{{URL::to('')}}';
 $(document).ready( function () {
   $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $('#products').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
          url: SITEURL + "/products",
          type: 'GET',
         },
         columns: [
                  {data: 'barcode', name: 'barcode'},
                  {data: 'stockcode', name: 'stockcode'},
                  {data: 'productcategory.categoryname', name: 'productcategory.categoryname'},
                  {data: 'productname', name: 'productname', orderable: false,searchable: false},
                  {data: 'description', name: 'description' },
                  {data: 'productaddedby.name', name: 'productaddedby.name'},
                  {
                    data: 'created_at', name: 'created_at',
                    "render": function (data) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return (month.toString().length > 1 ? month : "0" + month) + "-" + date.getDate() + "-" + date.getFullYear();
                    }
                  },
                  {data: 'action', name: 'action', orderable: false},
               ],
        order: [[0, 'desc']]
      });   
   });
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
		                            	<div class="col-md-12 text-right">
                                    @if(session('loggindata')['loggeduserpermission']->viewproductfilters=='Y')
                                    <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        Filters
                                    </a>
                                    @endif
                                    @if(session('loggindata')['loggeduserpermission']->addproducts=='Y')
                                    <a href="productaddpage" class="btn btn-outline-success waves-effect waves-light">Add Products</a>
                                    @endif
                                  </div>
                                  @if(session('loggindata')['loggeduserpermission']->viewproductfilters=='Y')
                                  <div class="col-md-12">
                                    <div class="collapse" id="collapseExample">
                                        <div class="card card-body mt-3 mb-0">
                                            <form action="{{route('productsfilters')}}" method="post">
                                              @csrf
                                              <div class="row">
                                                <div class="col-md-2">
                                                  <select name="supplier" class="form-control">
                                                    <option value="">SELECT SUPPLIER</option>
                                                    @foreach(session('filtersdata')['allsuppliers'] as $allsuppliers)
                                                    <option value="{{$allsuppliers->supplierID}}" @if(session('productsfilters')['supplier'] == $allsuppliers->supplierID) SELECTED='SELECTED' @endif>{{$allsuppliers->suppliername}}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                                <div class="col-md-2">
                                                  <select name="category" class="form-control">
                                                    <option value="">SELECT CATEGORY</option>
                                                    @foreach(session('filtersdata')['allcategories'] as $allcategories)
                                                    <option value="{{$allcategories->categoryID}}" @if(session('productsfilters')['category'] == $allcategories->categoryID) SELECTED='SELECTED' @endif>{{$allcategories->categoryname}}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                                <div class="col-md-2">
                                                  <select name="brand" class="form-control">
                                                    <option value="">SELECT BRAND</option>
                                                    @foreach(session('filtersdata')['allbrands'] as $allbrands)
                                                    <option value="{{$allbrands->brandID}}" @if(session('productsfilters')['brand'] == $allbrands->brandID) SELECTED='SELECTED' @endif>{{$allbrands->brandname}}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                                <div class="col-md-2">
                                                  <select name="model" class="form-control">
                                                    <option value="">SELECT MODEL</option>
                                                    @foreach(session('filtersdata')['allmodels'] as $allmodels)
                                                    <option value="{{$allmodels->modelID}}" @if(session('productsfilters')['model'] == $allmodels->modelID) SELECTED='SELECTED' @endif>{{$allmodels->modelname}}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                                <div class="col-md-2">
                                                  <select name="colour" class="form-control">
                                                    <option value="">SELECT COLOURS</option>
                                                    @foreach(session('filtersdata')['allcolours'] as $allcolours)
                                                    <option value="{{$allcolours->colourID}}" @if(session('productsfilters')['colour'] == $allcolours->colourID) SELECTED='SELECTED' @endif>{{$allcolours->colourname}}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                                <div class="col-md-2">
                                                  <button type="submit" class="btn btn-success">Apply Filters</button>
                                                </div>
                                              </div>
                                            </form>
                                        </div>
                                    </div>
                                  </div>
                                  @endif
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
                                    <table id="products" class="table table-bordered table-striped" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                          <tr>
                                          	<th>Barcode</th>
                                          	<th>Stock Code</th>
                                          	<th>Product<br> Category</th>
                                            <th>Product<br> Name/entity</th>
                                            <th>Product<br> Description</th>
                                            <th>Added<br> By/On</th>
                                            <th>Created<br> On</th>
                                            <th>Action</th>
                                          </tr>
                                        </thead>
                                        <tbody>
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
@endsection
        