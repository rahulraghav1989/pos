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
                            <h4 class="page-title">Sales Connection</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales Report</a></li>
                                <li class="breadcrumb-item active">Sales Connection</li>
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
                                                    <form method="post" action="{{route('salesconnectionfilter')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportsalesconnectionfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="store">
                                                                        <option value="">SELECT STORE</option>
                                                                        @foreach($connectiondata['allstore'] as $allstore)
                                                                        <option value="{{$allstore->store_id}}" @if($allstore->store_id == $connectiondata['storeID']) SELECTED='SELECTED' @endif>{{$allstore->store_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="user">
                                                                        <option value="">SELECT USER</option>
                                                                        @foreach($connectiondata['allusers'] as $allusers)
                                                                        <option value="{{$allusers->id}}" @if($allusers->id == $connectiondata['userID']) SELECTED='SELECTED' @endif>{{$allusers->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($connectiondata['firstday'])) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($connectiondata['lastday'])) @endphp" />
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
                                <!-- <style type="text/css">
                                    .btn-group{display: none;}
                                </style> -->
                                <p class="alert alert-warning">Showing Result: <span style="font-weight: 600; color: #000;">{{$connectiondata['userdetail']->name}} @if($connectiondata['storedetail'] != ''), {{$connectiondata['storedetail']->store_name}} @endif, (@php echo date('d-m-Y', strtotime($connectiondata['firstday'])) @endphp TO @php echo date('d-m-Y', strtotime($connectiondata['lastday'])) @endphp)</span></p>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Sale Invoice</th>
                                                <th>Store</th>
                                                <th data-priority="1">Customer</th>
                                                <th data-priority="1">Sale Rep.</th>
                                                <th>Order ID</th>
                                                <th>Plan Type</th>
                                                <th>Plan Code</th>
                                                <th>Plan</th>
                                                <th>Plan Proposition</th>
                                                <th>Plan Category</th>
                                                <th>Plan Term</th>
                                                <th>Plan Handset Term</th>
                                                <th data-priority="1">Product Name</th>
                                                <th data-priority="1">Device</th>
                                                <th data-priority="1">Quantity</th>
                                                <th data-priority="1">PP (Inc. GST)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($connectiondata['getconnection'] as $sales)
                                            <tr>
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                <th>
                                                    <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                </th>
                                                <td>{{$sales->store_name}}</td>
                                                <td>{{$sales->customerfirstname}} {{$sales->customerlastname}}</td>
                                                <td>{{$sales->name}}</td>
                                                <td>{{$sales->planOrderID}}</td>
                                                <td>{{$sales->plantypename}}</td>
                                                <td>{{$sales->plancode}}</td>
                                                <td>{{$sales->planname}}</td>
                                                <td>{{$sales->planpropositionname}}</td>
                                                <td>{{$sales->pcname}}</td>
                                                <td>{{$sales->plantermname}}</td>
                                                <td>{{$sales->planhandsettermname}}</td>
                                                <td>{{$sales->productname}}</td>
                                                <td>{{$sales->productimei}}</td>
                                                <td>{{$sales->quantity}}</td>
                                                <td>${{$sales->ppingst}}</td>
                                            </tr>
                                            @endforeach
                                            @foreach($connectiondata['getrefundconnection'] as $refund)
                                            <tr style="background-color: #f1525247;">
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                <th>
                                                    <a href="refundinvoice/{{$refund->refundInvoiceID}}" style="color: #007bff;">{{$refund->refundInvoiceID}}</a>
                                                </th>
                                                <td>{{$refund->store_name}}</td>
                                                <td>{{$refund->customerfirstname}} {{$refund->customerlastname}}</td>
                                                <td>{{$refund->name}}</td>
                                                <td>{{$refund->planOrderID}}</td>
                                                <td>{{$refund->plantypename}}</td>
                                                <td>{{$refund->plancode}}</td>
                                                <td>{{$refund->planname}}</td>
                                                <td>{{$refund->planpropositionname}}</td>
                                                <td>{{$refund->pcname}}</td>
                                                <td>{{$refund->plantermname}}</td>
                                                <td>{{$refund->planhandsettermname}}</td>
                                                <td>{{$refund->productname}}</td>
                                                <td>{{$refund->productimei}}</td>
                                                <td>{{$refund->quantity}}</td>
                                                <td>${{$refund->ppingst}}</td>
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
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'SaleConn-@php echo $connectiondata['userdetail']->name."-".date('d-m-Y', strtotime($connectiondata['firstday']))."to".date('d-m-Y', strtotime($connectiondata['lastday'])) @endphp',
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