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
                                <h4 class="page-title">Demo</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Purchase Order</a></li>
                                    <li class="breadcrumb-item active">Demo Receive</li>
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
                                            @if(session('loggindata')['loggeduserstore']!='')
                                                @if(session('loggindata')['loggeduserpermission']->addpurchaseorder=='Y')
                                                <form action="{{route('adddemoreceive')}}" method="post" style="float: right; margin-left: 10px;">
                                                    @csrf
                                                    <input type="hidden" name="store" value="@if(session('storeid') == '')@foreach(session('loggindata')['loggeduserstore'] as $postore) {{$postore->store_id}} @endforeach @else {{session('storeid')}} @endif">
                                                    <button type="submit" class="btn btn-outline-primary waves-effect waves-light">Receive Demo</button>
                                                </form>
                                                @endif
                                            @else
                                                @if(session('loggindata')['loggeduserpermission']->addpurchaseorder=='Y')
                                                    <button type="button" class="btn btn-light waves-effect waves-light">Receive Demo</button>
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
                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                        	<th>Demo Rec. <br>Number</th>
                                        	<th>Demo Ref. Number</th>
                                        	<th>Store</th>
                                            <th>Supplier</th>
                                            <th>Items</th>
                                            <th>Status</th>
                                            <th>Added By/On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        	@foreach($alldrreceiveorder->groupBy('receiveInvoiceID') as $alldr)
                                        	<tr>
                                        		<td>{{$alldr[0]->receiveInvoiceID}}</td>
                                        		<td>{{$alldr[0]->referenceNumber}}</td>
                                        		<td>{{$alldr[0]->store_name}}</td>
                                        		<td>{{$alldr[0]->demosupplier['suppliername']}}</td>
                                        		<td>{{count($alldr[0]['demoitem'])}}</td>
                                                <td>
                                                	@if($alldr[0]['receiveorderstatus'] == 0)
                                                	<span class="badge badge-danger">In-Complete</span>
                                                    @elseif($alldr[0]['receiveorderstatus'] == 1)
                                                    <span class="badge badge-info">Pending</span>
                                                    @elseif($alldr[0]['receiveorderstatus'] == 2)
                                                    <span class="badge badge-success">Completed</span>
                                                    @elseif($alldr[0]['receiveorderstatus'] == 3)
                                                    <span class="badge badge-warning">In-Active</span>
                                                    @elseif($alldr[0]['receiveorderstatus'] == 4)
                                                    <span class="badge badge-info">Partital</span>
                                                    @endif
                                                </td>
                                        		<td>{{$alldr[0]->name}}<br>{{$alldr[0]->created_at}}</td>
                                        		<td>
                                        			@if($alldr[0]['receiveorderstatus'] == 0)
                                        			<a href="demoordercreation/{{$alldr[0]->receiveInvoiceID}}" class="btn btn-outline-success waves-effect waves-light"><i class="icon-eye"></i></a>
                                                	@else
                                                	<a href="demoreceiveitem/{{$alldr[0]->receiveInvoiceID}}" class="btn btn-outline-success waves-effect waves-light"><i class="icon-eye"></i></a>
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
            <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
        </div>
    </div>
@endsection
        