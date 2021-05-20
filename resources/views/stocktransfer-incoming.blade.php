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
                                <h4 class="page-title">Incoming Transfer</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Transfer</a></li>
                                    <li class="breadcrumb-item active">Incoming Transfer</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                            	<div class="card-body">
                            		<div class="col-12">
	                                    <div class="text-right">

	                                        @if(session('loggindata')['loggeduserpermission']->changestore=='Y')
	                                        <!----Store Change Model--->
	                                        <div class="modal fade changestore" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	                                            <div class="modal-dialog modal-dialog-centered">
	                                                <div class="modal-content">
	                                                    <div class="modal-header">
	                                                        <h5 class="modal-title mt-0">Change Store</h5>
	                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                                            <span aria-hidden="true">&times;</span>
	                                                        </button>
	                                                    </div>
	                                                    <div class="modal-body">
	                                                        <form action="{{route('changestore')}}" method="post">
	                                                            @csrf
	                                                            <select name="store" class="form-control">
	                                                                <option value="">SELECT STORE</option>
	                                                                @foreach(session('allstore') as $storename)
	                                                                <option value="{{$storename->store_id}}" @if(session('storeid')==$storename->store_id) SELECTED=='SELECTED' @endif>{{$storename->store_name}}</option>
	                                                                @endforeach           
	                                                            </select>
	                                                            <br>
	                                                            <button type="submit" class="btn btn-primary">Select</button>
	                                                        </form>
	                                                    </div>
	                                                </div><!-- /.modal-content -->
	                                            </div><!-- /.modal-dialog -->
	                                        </div>
	                                        <!----Store Change Model--->
	                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore">Change Store</button>
	                                        @endif
	                                        @if(session('loggindata')['loggeduserpermission']->addstocktransfer=='Y')
	                                        	@if(count(session('loggindata')['loggeduserstore']) == '1')
	                                        	<a href="startstocktransfer" class="btn btn-outline-primary waves-effect waves-light">New Transfer</a>
	                                    		@else
	                                    		<a class="btn btn-outline-light waves-effect waves-light">New Transfer</a>
	                                    		@endif
	                                    	@endif
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
                                	@if(count(session('loggindata')['loggeduserstore']) > 1 || count(session('loggindata')['loggeduserstore']) == 0)
	                                    <p style="color: #fd0d0d;">Please select a store as you not logged in any store.</p>
	                                @else
	                                	<p>Store: 
                                            <span style="font-size: 1.2em; color: #fd0d0d; font-weight: 600;"> 
                                                @if(count(session('loggindata')['loggeduserstore']) > 0)
                                                    @foreach(session('loggindata')['loggeduserstore'] as $store)
                                                    {{$store->store_name}}
                                                    @endforeach
                                                @endif
                                            </span>
                                        </p>
	                                @endif

                                    <table id="datatable3" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Transfer ID</th>
                                            <th>Transfer Date</th>
                                            <th>Date Received</th>
                                            <th>Transfer By</th>
                                            <th>Transfer From</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                            @foreach($stocktransferdata['getincomeingtransfer'] as $stockdata)
                                            <tr>    
                                                <td>
                                                    <a href="stocktransferreceive/{{$stockdata->stocktransferID}}" style="color: #03a9f4;">{{$stockdata->stocktransferID}}</a>
                                                </td>
                                                <td>{{$stockdata->stocktransferDate}}</td>
                                                <td>
                                                    @if($stockdata->receivetrasnsferDate == "")
                                                    <span style="color: red; font-weight: 600;">Not Yet Received</span>
                                                    @else
                                                    {{$stockdata->receivetrasnsferDate}}
                                                    @endif
                                                </td>
                                                <td>{{$stockdata->name}}</td>
                                                <td>{{$stockdata->store_name}}</td>
                                                <td>
                                                    @if($stockdata->stocktransferStatus == 0)
                                                    <span class="badge badge-danger">Not Transfered</span>
                                                    @elseif($stockdata->stocktransferStatus == 1)
                                                    <span class="badge badge-warning">Pending Receive</span>
                                                    @elseif($stockdata->stocktransferStatus == 2)
                                                    <span class="badge badge-success">Completed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
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
            <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
        </div>
    </div>
@endsection
        