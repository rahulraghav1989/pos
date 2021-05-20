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
                            <h4 class="page-title">Sales By Payment Method</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales Report</a></li>
                                <li class="breadcrumb-item active">Sales by payment Method</li>
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
                                                    <form method="post" action="{{route('salesbypaymentmethodfilter')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportsalespaymentmethodfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="store">
                                                                        <option value="">SELECT STORE</option>
                                                                        @foreach($paymentmethoddata['allstore'] as $allstore)
                                                                        <option value="{{$allstore->store_id}}" @if($allstore->store_id == $paymentmethoddata['storeID']) SELECTED='SELECTED' @endif>{{$allstore->store_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="user">
                                                                        <option value="">SELECT USER</option>
                                                                        @foreach($paymentmethoddata['allusers'] as $allusers)
                                                                        <option value="{{$allusers->id}}" @if($allusers->id == $paymentmethoddata['userID']) SELECTED='SELECTED' @endif>{{$allusers->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="payoptions">
                                                                        <option value="">SELECT OPTIONS</option>
                                                                        @foreach($paymentmethoddata['allpayoption'] as $allpayoption)
                                                                        <option value="{{$allpayoption->paymentname}}" @if($allpayoption->paymentname == $paymentmethoddata['paymenttype']) SELECTED='SELECTED' @endif>{{$allpayoption->paymentname}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($paymentmethoddata['firstday'])) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($paymentmethoddata['lastday'])) @endphp" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 text-right">
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
                                <p class="alert alert-warning">Showing Result: <span style="font-weight: 600; color: #000;">{{$paymentmethoddata['userdetail']->name}} @if($paymentmethoddata['storedetail'] != ''), {{$paymentmethoddata['storedetail']->store_name}} @endif @if($paymentmethoddata['paymenttype'] != ''), {{$paymentmethoddata['paymenttype']}} @endif, (@php echo date('d-m-Y', strtotime($paymentmethoddata['firstday'])) @endphp TO @php echo date('d-m-Y', strtotime($paymentmethoddata['lastday'])) @endphp)</span></p>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th data-priority="1">Sale Date</th>
                                                <th>Sale Invoice</th>
                                                <th>Store</th>
                                                <th data-priority="1">Customer</th>
                                                <th data-priority="3">Sales Rep.</th>
                                                <th data-priority="3">Sale Type</th>
                                                <th data-priority="3">Payment Method</th>
                                                <th data-priority="6">Total Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($paymentmethoddata['getsales'] as $sales)
                                            <tr>
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                <th>
                                                    <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                </th>
                                                <td>{{$sales->store_name}}</td>
                                                <td>{{$sales->customerfirstname}} {{$sales->customerlastname}}</td>
                                                <td>{{$sales->name}}</td>
                                                <td>{{$sales->orderType}}</td>
                                                <td>{{$sales->paymentType}}</td>
                                                <td>${{$sales->paidAmount}}</td>
                                            </tr>
                                            @endforeach
                                            @foreach($paymentmethoddata['getrefund'] as $refund)
                                            <tr style="background-color: #f1525247;">
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                <th>
                                                    <a href="refundinvoice/{{$refund->refundInvoiceID}}" style="color: #007bff;">{{$refund->refundInvoiceID}}</a>
                                                </th>
                                                <td>{{$refund->store_name}}</td>
                                                <td>{{$refund->customerfirstname}} {{$refund->customerlastname}}</td>
                                                <td>{{$refund->name}}</td>
                                                <td>{{$refund->orderType}}</td>
                                                <td>{{$refund->paymentType}}</td>
                                                <td>-${{$refund->paidAmount}}</td>
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
                    "scrollY": "100%",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'Paymentmethod-@php echo $paymentmethoddata['userdetail']->name."-".date('d-m-Y', strtotime($paymentmethoddata['firstday']))."to".date('d-m-Y', strtotime($paymentmethoddata['lastday'])) @endphp',
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