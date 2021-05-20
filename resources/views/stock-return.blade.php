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
                            <h4 class="page-title">Supplier Stock Return</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Products/Plans</a></li>
                                <li class="breadcrumb-item active">Stock Return</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <form action="{{route('stockreturnfilter')}}" method="post">
                                                @csrf
                                                <div class="row"> 
                                                @if(session('loggindata')['loggeduserpermission']->viewstockreturnfilter=='Y')
                                                    
                                                    <div class="col-md-3">
                                                        <div style="display: none;">
                                                            @php
                                                                $storeid = '';
                                                            @endphp
                                                            @foreach($storeID as $storeid)
                                                                $storeid = $storeid;
                                                            @endforeach
                                                        </div>
                                                        <select name="store" class="form-control">
                                                            <option value="">SELECT STORE</option>
                                                            @foreach($allstore as $stores)
                                                            <option value="{{$stores->store_id}}" @if($storeid == $stores->store_id) SELECTED='SELECTED' @endif>{{$stores->store_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="supplier" class="form-control">
                                                            <option value="">SELECT SUPPLIER</option>
                                                            @foreach($allsupplier as $suppliers)
                                                            <option value="{{$suppliers->supplierID}}" @if($supplierID == $suppliers->supplierID) SELECTED='SELECTED' @endif>{{$suppliers->suppliername}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="returnstatus" class="form-control">
                                                            <option value="" @if($returnstatus == '') SELECTED='SELECTED' @endif>SELECT RETURN STATUS</option>
                                                            <option value="0" @if($returnstatus == '0') SELECTED='SELECTED' @endif>Stock Not Returned</option>
                                                            <option value="1" @if($returnstatus == '1') SELECTED='SELECTED' @endif>Stock Returned</option>
                                                        </select>
                                                    </div>
                                                @endif
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div>
                                                                <div class="input-daterange input-group" id="date-range">
                                                                    <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($firstday)) @endphp" />
                                                                    <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($lastday)) @endphp" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class="col-md-2 text-right">
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
                                            <button type="button" class="btn btn-primary" style="margin-bottom: 10px;" data-toggle="modal" data-target=".changestore">Change Store</button>
                                            @endif

                                            @if(count(session('loggindata')['loggeduserstore']) == 1)
                                                @if(session('loggindata')['loggeduserpermission']->addstockreturn=='Y')
                                                    <form action="{{route('createstockreturn')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="storeid" value="@if(session('storeid') == '')@foreach(session('loggindata')['loggeduserstore'] as $postore) {{$postore->store_id}} @endforeach @else {{session('storeid')}} @endif">
                                                        <button class="btn btn-primary" type="submit">Return Products</button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-light" type="submit">Return Products</button>
                                                @endif
                                            @else
                                                <button class="btn btn-light" type="submit">Return Products</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Return Invoice</th>
                                                <th>Store</th>
                                                <th data-priority="1">RA Number</th>
                                                <th data-priority="1">Supplier</th>
                                                <th data-priority="1">Amount Credited</th>
                                                <th data-priority="1">Expected Credits</th>
                                                <th data-priority="1">Credit Status</th>
                                                <th data-priority="1">Return Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($getstockreturn as $returns)
                                                <tr>
                                                    <td>
                                                        @php echo date('d-m-Y', strtotime($returns->stockreturnDate)) @endphp
                                                    </td>
                                                    <td>
                                                        <a href="stockreturncreation/{{$returns->stockreturnID}}" style="color: #007bff;">{{$returns->stockreturnID}}</a>
                                                    </td>
                                                    <td>
                                                        {{$returns->store_name}}
                                                    </td>
                                                    <td>
                                                        {{$returns->raNumber}}
                                                    </td>
                                                    <td>
                                                        {{$returns->suppliername}}
                                                    </td>
                                                    <td>
                                                        ${{App\stockreturnpayments::where('stockreturnID', $returns->stockreturnID)->sum('returnamount')}}
                                                    </td>
                                                    <td>
                                                        ${{App\stockreturnitems::where('stockreturnID', $returns->stockreturnID)->sum('total')}}
                                                    </td>
                                                    <td>
                                                        @if(App\stockreturnitems::where('stockreturnID', $returns->stockreturnID)->sum('total') == App\stockreturnpayments::where('stockreturnID', $returns->stockreturnID)->sum('returnamount'))
                                                            @if($returns->stockreturnStatus == 0)
                                                            <span class="badge badge-pill badge-danger">Pending</span>
                                                            @else
                                                            <span class="badge badge-pill badge-success">Credited</span>
                                                            @endif
                                                        @else
                                                        <span class="badge badge-pill badge-danger">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($returns->returnAdminApproval == 0)
                                                            <form action="{{route('adminapproval')}}" method="post" style="float: left;">
                                                                @csrf
                                                                <input type="hidden" name="stockreturnid" value="{{$returns->stockreturnID}}">
                                                                <input type="hidden" name="adminstatus" value="2">
                                                                <button type="submit" class="btn btn-sm btn-danger" style="font-size: 10px;"><i class="fas fa-times"></i></button>
                                                            </form>
                                                            <form action="{{route('adminapproval')}}" method="post" style="float: left; margin-left: 2px;">
                                                                @csrf
                                                                <input type="hidden" name="stockreturnid" value="{{$returns->stockreturnID}}">
                                                                <input type="hidden" name="adminstatus" value="1">
                                                                <button type="submit" class="btn btn-sm btn-success" style="font-size: 10px;"><i class="fas fa-check"></i></button>
                                                            </form>
                                                        @elseif($returns->returnAdminApproval == 2)
                                                        <span class="badge badge-pill badge-danger">Disapproved By Admin</span>
                                                        @else
                                                            @if($returns->stockreturnStatus == 0)
                                                            <span class="badge badge-pill badge-danger">Not Returned</span>
                                                            @else
                                                            <span class="badge badge-pill badge-success">Stock Returned</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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

        <script src="{{ asset('posview') }}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="{{ asset('posview') }}/assets/pages/form-advanced.js"></script>
        <!-- Responsive-table-->
        <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]]
                    /*buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: '',
                            }
                        ]*/
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            } );
        </script>
    </div>
</div>
@endsection