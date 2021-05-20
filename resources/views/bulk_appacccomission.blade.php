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
                                <h4 class="page-title">App/Acc Comission</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item">Bulk Process</li>
                                    <li class="breadcrumb-item active">App/Acc Comission</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                            	@if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                            	@if(session()->has('success'))
									<div class="card-body">	                                
									    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
									        {{ session()->get('success') }}
									    </div>
									</div>
								@endif
								@if(session()->has('error'))
								<div class="card-body">
								    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
								        {{ session()->get('error') }}
								    </div>
								</div>
								@endif
                                <div class="card-body">
                                    <div class="row"> 
                                        <div class="col-md-12 text-right">
                                            <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                Filters
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form action="{{route('bulkcomissionfilter')}}" method="post">
                                                      @csrf
                                                      <div class="row">
                                                        <div class="col-md-3">
                                                          <select name="supplier" class="form-control">
                                                            <option value="">SELECT SUPPLIER</option>
                                                            @foreach(session('filtersdata')['allsuppliers'] as $allsuppliers)
                                                            <option value="{{$allsuppliers->supplierID}}" @if($supplier == $allsuppliers->supplierID) SELECTED='SELECTED' @endif>{{$allsuppliers->suppliername}}</option>
                                                            @endforeach
                                                          </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                          <select name="stockgroup" class="form-control">
                                                            <option value="">SELECT STOCKGROUP</option>
                                                            @foreach(session('filtersdata')['allstockgroup'] as $allstockgroup)
                                                            <option value="{{$allstockgroup->stockgroupID}}" @if($stockgroup == $allstockgroup->stockgroupID) SELECTED='SELECTED' @endif>{{$allstockgroup->stockgroupname}}</option>
                                                            @endforeach
                                                          </select>
                                                        </div>
                                                        <div class="col-md-12 text-right" style="margin-top: 10px;">
                                                          <button type="submit" class="btn btn-success">Apply Filters</button>
                                                        </div>
                                                      </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-6" style="margin: 0px auto;">
                                        <div class="card">
                                            <div class="card-heading p-4">
                                                <div class="mini-stat-icon float-right">
                                                    <i class="mdi mdi-cube-outline bg-primary  text-white"></i>
                                                </div>
                                                <div>
                                                    <h5 class="font-16">Total Products Found</h5>
                                                </div>
                                                <h3 class="mt-4">{{$accproductcount}}</h3>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <form method="post" action="{{route('updatebulkappacccomission')}}">
                                                @csrf
                                                <input type="hidden" name="stockgroupid" value="{{$stockgroup}}">
                                                <input type="hidden" name="productid[]" value="@foreach($accproductid as $uproductid){{$uproductid->productID}} @endforeach">
                                                <div class="col-md-12">
                                                    <label>Bonus Type</label>
                                                    <select name="stockgroupbonustype" class="form-control" style="width: 100%;">
                                                        <option value="">SELECT TYPE</option>
                                                        <option value="percentage_profitmargin">% Of Profit Margin</option>
                                                        <option value="percentage_saleprice">% Of RRP (Sale Price)</option>
                                                        <option value="percentage_dealermargin">% Of Dealer Margin</option>
                                                        <option value="fixed">Fixed</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Dealer Margin</label>
                                                    <input type="text" class="form-control" name="dealermargin" value="0.00" required="">
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Staff Bonus</label>
                                                    <input type="text" name="stockgroupbonusvalue" class="form-control" value="0.00" style="width: 100%;">
                                                </div>
                                                <div class="col-md-12 text-right">
                                                    <button type="submit" class="btn btn-success">SAVE</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->

            @include('includes.footer')
            <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
        </div>
    </div>
@endsection
        