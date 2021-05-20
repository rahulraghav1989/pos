@extends('main')

@section('content')
    <div id="wrapper">
        <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
        <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
        <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    	@include('includes.topbar')

    	@include('includes.sidebar')
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">Outgoing Transfer</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Transfer</a></li>
                                    <li class="breadcrumb-item active">Outgoing Transfer</li>
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
                                            @if(session('loggindata')['loggeduserpermission']->viewstocktransferfilters=='Y')
                                            <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                Filters
                                            </a>
                                            @endif
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
	                                        	@if(count(session('loggindata')['loggeduserstore']) == 1)
	                                        	<a href="startstocktransfer" class="btn btn-outline-primary waves-effect waves-light">New Transfer</a>
	                                    		@else
	                                    		<a class="btn btn-outline-light waves-effect waves-light">New Transfer</a>
	                                    		@endif
	                                    	@endif
	                                    </div>
                                        @if(session('loggindata')['loggeduserpermission']->viewstocktransferfilters=='Y')
                                        <div class="col-md-12">
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form action="{{route('stocktransferoutfilters')}}" method="post">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <select class="form-control" name="transferto">
                                                                    <option value="">SELECT TRANSFER TO</option>
                                                                    @foreach($stocktransferdata['allstore'] as $gettostore)
                                                                    <option value="{{$gettostore->store_id}}" @if($gettostore->store_id == $stocktransferdata['transferto']) SELECTED='SELECTED' @endif>{{$gettostore->store_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($stocktransferdata['firstday'])) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($stocktransferdata['lastday'])) @endphp" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 ">
                                                                <button type="submit" class="btn btn-success">Apply</button>
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
                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Transfer ID</th>
                                            <th>Transfer Date</th>
                                            <th>Date Received</th>
                                            <th>Transfer By</th>
                                            <th>Transfer From</th>
                                            <th>Transfer To</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
    										@foreach($stocktransferdata['getstocktransfer'] as $stockdata)
    										<tr>	
    											<td>
                                                    @if($stockdata->stocktransferStatus == 0)
                                                    <a href="createstocktransfer/{{$stockdata->stocktransferID}}" style="color: #03a9f4;">{{$stockdata->stocktransferID}}</a>
                                                    @else
                                                    <a href="stocktransferinvoice/{{$stockdata->stocktransferID}}" style="color: #03a9f4;">{{$stockdata->stocktransferID}}</a>
                                                    @endif
                                                </td>
    											<td>
                                                    @php echo date('d-m-Y', strtotime($stockdata->stocktransferDate)) @endphp
                                                </td>
    											<td>
    												@if($stockdata->receivetrasnsferDate == "")
    												<span style="color: red; font-weight: 600;">Not Yet Received</span>
    												@else
                                                    @php echo date('d-m-Y', strtotime($stockdata->receivetrasnsferDate)) @endphp
    												@endif
    											</td>
    											<td>{{$stockdata->name}}</td>
                                                <td>{{$stockdata['fromstore']->store_name}}</td>
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
            <script src="{{ asset('posview') }}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
            <script src="{{ asset('posview') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
            <script src="{{ asset('posview') }}/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
            <script src="{{ asset('posview') }}/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>
            <script src="{{ asset('posview') }}/assets/pages/form-advanced.js"></script>
        </div>
    </div>
@endsection
        