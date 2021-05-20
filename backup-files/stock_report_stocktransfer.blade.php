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
                            <h4 class="page-title">Stock Transfer</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Report</a></li>
                                <li class="breadcrumb-item active">Stock Transfer</li>
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
                                        <div class="col-md-12">
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form method="post" action="{{route('reportstocktransferfilter')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportstocktransferfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="store">
                                                                        <option value="">SELECT STORE</option>
                                                                        @foreach($allstore as $allstores)
                                                                        <option value="{{$allstores->store_id}}" @if($allstores->store_id == $storeID) SELECTED='SELECTED' @endif>{{$allstores->store_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="user">
                                                                        <option value="">SELECT USER</option>
                                                                        @foreach($allusers as $alluserss)
                                                                        <option value="{{$alluserss->id}}" @if($alluserss->id == $userID) SELECTED='SELECTED' @endif>{{$alluserss->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($firstday)) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($lastday)) @endphp" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                                                
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="alert alert-warning">Showing Result: 
                                    <span style="font-weight: 600; color: #000;">
                                        @if($storeID == "")
                                        Store (All Store) | 
                                        @else
                                        Store ( {{$storedetail->store_name}} ) |
                                        @endif
                                        @if($userID == "")
                                        User (All Users) | 
                                        @else
                                        User ( {{$userdetail->name}} ) |
                                        @endif
                                        Date (@php echo date('d-m-Y', strtotime($firstday)) @endphp to @php echo date('d-m-Y', strtotime($lastday)) @endphp)</span></p>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice</th>
                                                <th>Transfer Type</th>
                                                <th>From Store</th>
                                                <th>From User</th>
                                                <th>Receiveing Store</th>
                                                <th>Receiveing User</th>
                                                <th>Consignment Number</th>
                                                <th>Note</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Received On</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getstocktransfer as $stocktransfer)
                                            <tr>
                                                <td>{{$stocktransfer->created_at}}</td>
                                                <td><a href="sale/{{$stocktransfer->stocktransferID}}" style="color: #007bff;"> {{$stocktransfer->stocktransferID}}</a></td>
                                                <td>{{$stocktransfer->stocktransferType}}</td>
                                                <td>{{$stocktransfer->store_name}}</td>
                                                <td>{{$stocktransfer->name}}</td>
                                                <td>{{$stocktransfer->tostore->store_name}}</td>
                                                <td>{{$stocktransfer->touser->name}}</td>
                                                <td>{{$stocktransfer->consignmentnumber}}</td>
                                                <td>{{$stocktransfer->transfernote}}</td>
                                                <td>{{$stocktransfer->barcode}}</td>
                                                <td>{{$stocktransfer->productname}}</td>
                                                <td>{{$stocktransfer->quantity}}</td>
                                                <td>
                                                    @if($stocktransfer->receiveStatus == "" || $stocktransfer->receiveStatus == 0)
                                                    <span class="badge badge-success">Pending</span>
                                                    @else
                                                    <span class="badge badge-success">Received</span>
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
                </div>
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
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'StockTransfer-@php if($storedetail !=""){ echo $storedetail->store_name."-"; } if($userdetail !=""){ echo $userdetail->name."-"; } echo date('d-m-Y', strtotime($firstday))."to".date('d-m-Y', strtotime($lastday)) @endphp',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            } );
        </script>
    </div>
</div>
@endsection