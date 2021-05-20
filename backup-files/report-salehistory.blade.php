@extends('main')

@section('content')
<div id="wrapper">
	@include('includes.topbar')

    @include('includes.sidebar')
    <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable( {
                "order": [[ 0, "desc" ]]
            } );
        } );
    </script>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Sale History</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales</a></li>
                                <li class="breadcrumb-item active">Sale History</li>
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
                                    <div class="col-6">
                                        @if(session('loggindata')['loggeduserpermission']->viewsalehistoryfilters=='Y')
                                        <form action="{{route('salehistoryfilter')}}" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select class="form-control" name="users">
                                                        <option value="">SELECT USER</option>
                                                        @foreach($allusers as $users)
                                                        <option value="{{$users->id}}" @if($users->id == $userID) SELECTED='SELECTED' @endif>{{$users->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div>
                                                            <div class="input-daterange input-group" id="date-range">
                                                                <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($firstday)) @endphp" />
                                                                <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($lastday)) @endphp" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-success">Apply</button>
                                                </div>
                                            </div>
                                        </form>
                                        @endif
                                    </div>
                            		<div class="col-6">
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
                                                                    <option value="{{$storename->store_id}}" @if(session('loggindata')['loggeduserstore']['store_id']==$storename->store_id) SELECTED=='SELECTED' @endif>{{$storename->store_name}}</option>
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
                                        </div>
                                    </div>
                                </div>
                        	</div>
                            <div class="card-body">
                                <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Sale Date</th>
                                        <th>INV. ID</th>
                                        <th>Customer</th>
                                        <th>Sale Rep.</th>
                                        <th>Sale Type</th>
                                        <th>Sale Total</th>
                                        <th>Store</th>
                                        <th>Sale Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($allsale as $salehistory)
                                        <tr>
                                            <td>
                                                @php
                                                echo date('d-m-Y H:i:s', strtotime($salehistory->created_at))
                                                @endphp
                                            </td>
                                            <td><a href="sale/{{$salehistory->orderID}}" style="color: #007bff;"> {{$salehistory->orderID}} </a></td>
                                            <td>{{$salehistory->customer['customerfirstname']}} {{$salehistory->customer['customerlastname']}}</td>
                                            <td>{{$salehistory->name}}</td>
                                            <td>{{$salehistory->orderType}}</td>
                                            <td>
                                                {{$salehistory->orderpayment()->sum('paidAmount')}}
                                            </td>
                                            <td>{{$salehistory->store_name}}</td>
                                            <td>
                                                @if($salehistory->orderstatus == 0)
                                                <span class="badge badge-danger">In-complete</span>
                                                @else
                                                <span class="badge badge-success">Complete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach($refundsale as $refundhistory)
                                        <tr style="background-color: #f1525247;">
                                            <td>
                                                @php
                                                echo date('d-m-Y H:i:s', strtotime($refundhistory->created_at))
                                                @endphp
                                            </td>
                                            <td><a href="refundinvoice/{{$refundhistory->refundInvoiceID}}" style="color: #007bff;">{{$refundhistory->refundInvoiceID}}</a></td>
                                            <td>{{$refundhistory->customer['customerfirstname']}} {{$refundhistory->customer['customerlastname']}}</td>
                                            <td>{{$refundhistory->name}}</td>
                                            <td>{{$refundhistory->orderType}}</td>
                                            <td>
                                                -{{$refundhistory->orderpayment()->sum('paidAmount')}}
                                            </td>
                                            <td>{{$refundhistory->store_name}}</td>
                                            <td>
                                                @if($refundhistory->refundStatus == 0)
                                                <span class="badge badge-danger">In-complete</span>
                                                @else
                                                <span class="badge badge-success">Complete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
            </div>
            <!-- container-fluid -->

        </div>
        <!-- content -->

        @include('includes.footer')
        <!-- <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script> -->
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>

        <script src="{{ asset('posview') }}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="{{ asset('posview') }}/assets/pages/form-advanced.js"></script>
        <!-- Responsive-table-->
        <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>
    </div>
</div>
@endsection