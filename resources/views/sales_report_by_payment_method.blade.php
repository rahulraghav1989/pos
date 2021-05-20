@extends('main')

@section('content')
<div id="wrapper">
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    
    <link href="{{ asset('posview') }}/multipleselector/styles/multiselect.css" rel="stylesheet" />
    <script src="{{ asset('posview') }}/multipleselector/scripts/multiselect.min.js"></script>
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
                                                    <div class="text-right">
                                                        <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                            Filters
                                                        </a>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="collapse" id="collapseExample">
                                                            <div class="card card-body mt-3 mb-0">
                                                                <form method="post" action="{{route('salebypaymentmethod')}}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        @if(session('loggindata')['loggeduserpermission']->viewreportsalespaymentmethodfilter=='Y')
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Store</label>
                                                                                <select id='testSelect1' name="store[]" multiple>
                                                                                    @foreach($paymentmethoddata['allstore'] as $allstores)
                                                                                        @if(!empty($paymentmethoddata['storeID']))
                                                                                            <option value="{{$allstores->store_id}}" @foreach($paymentmethoddata['storeID'] as $selectedstoreid) @if($allstores->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstores->store_name}}</option>
                                                                                        @else
                                                                                            <option value="{{$allstores->store_id}}">{{$allstores->store_name}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect1')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select User</label>
                                                                                <select id='testSelect2' name="user[]" multiple>
                                                                                    @foreach($paymentmethoddata['allusers'] as $allusers)
                                                                                        @if(!empty($paymentmethoddata['userID']))
                                                                                            <option value="{{$allusers->id}}" @foreach($paymentmethoddata['userID'] as $selectedstoreid) @if($allusers->id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allusers->name}}</option>
                                                                                        @else
                                                                                            <option value="{{$allusers->id}}">{{$allusers->name}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect2')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Sale Type</label>
                                                                                <select id='testSelect3' name="saletype[]" multiple>
                                                                                    @if(!empty($paymentmethoddata['saletype']))
                                                                                        <option value="InStore" @foreach($paymentmethoddata['saletype'] as $saletypename) @if($saletypename=='InStore') SELECTED='SELECTED'  @endif @endforeach>In-Store</option>
                                                                                        <option value="layby" @foreach($paymentmethoddata['saletype'] as $saletypename) @if($saletypename=='layby') SELECTED='SELECTED'  @endif @endforeach>Layby</option>
                                                                                    @else
                                                                                        <option value="InStore" @if($paymentmethoddata['saletype']=='InStore') SELECTED='SELECTED'  @endif>In-Store</option>
                                                                                        <option value="layby" @if($paymentmethoddata['saletype']=='layby') SELECTED='SELECTED'  @endif>Layby</option>
                                                                                    @endif
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect3')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Payment Option</label>
                                                                                <select id='testSelect4' name="payoptions[]" multiple>
                                                                                    @foreach($paymentmethoddata['allpayoption'] as $allpayoption)
                                                                                        @if(!empty($paymentmethoddata['paymenttype']))
                                                                                            <option value="{{$allpayoption->paymentname}}" @foreach($paymentmethoddata['paymenttype'] as $selectedstoreid) @if($allpayoption->paymentname == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allpayoption->paymentname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allpayoption->paymentname}}">{{$allpayoption->paymentname}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect4')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        <div class="col-md-8">
                                                                            <div class="form-group">
                                                                                <div>
                                                                                    <div class="input-daterange input-group" id="date-range">
                                                                                        <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($paymentmethoddata['firstday'])) @endphp" />
                                                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($paymentmethoddata['lastday'])) @endphp" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 text-right">
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
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
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
                                                <th data-priority="6">Sale Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                            $totalsaletotal = 0;
                                            $totalrefundtotal = 0;
                                            @endphp
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
                                                <td>
                                                    {{$sales->paidAmount}}
                                                    @php
                                                    $totalsaletotal += $sales->paidAmount;
                                                    @endphp
                                                </td>
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
                                                <td>
                                                    -{{$refund->paidAmount}}
                                                    @php
                                                    $totalrefundtotal += $refund->paidAmount;
                                                    @endphp
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <th></th>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">{{$totalsaletotal - $totalrefundtotal}}</td>
                                                </tr>
                                            </tfoot>
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
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'SalesByPaymentReport',
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