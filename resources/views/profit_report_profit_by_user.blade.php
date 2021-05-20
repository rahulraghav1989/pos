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
                            <h4 class="page-title">Profit By User</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Profit Report</a></li>
                                <li class="breadcrumb-item active">Profit By User</li>
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
                                            <div class="text-right">
                                                <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                    Filters
                                                </a>
                                            </div>
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form method="post" action="{{route('profitbyuser')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportprofitbyuserfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Select Store</label>
                                                                    <select id="testSelect1" name="store[]" multiple>
                                                                        @foreach($userprofitdata['allstore'] as $allstore)
                                                                            @if($userprofitdata['storeID']!="")
                                                                                <option value="{{$allstore->store_id}}" @foreach($userprofitdata['storeID'] as $selectedstoreid) @if($allstore->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstore->store_name}}</option>
                                                                            @else
                                                                                <option value="{{$allstore->store_id}}">{{$allstore->store_name}}</option>
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
                                                                    <select id="testSelect2" name="user[]" multiple>
                                                                        @foreach($userprofitdata['allusers'] as $allusers)
                                                                            @if($userprofitdata['userID']!='')
                                                                                <option value="{{$allusers->id}}" @foreach($userprofitdata['userID'] as $selecteduserid) @if($allusers->id == $selecteduserid) SELECTED='SELECTED' @endif @endforeach>{{$allusers->name}}</option>
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
                                                            @endif
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Date Range</label>
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($userprofitdata['firstday'])) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($userprofitdata['lastday'])) @endphp" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success">Apply Filter</button>
                                                                
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
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Sale Invoice</th>
                                                <th>Store</th>
                                                <th>Sale Rep.</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Supplier</th>
                                                <th>Category</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Colour</th>
                                                <th>PP (Inc. GST)</th>
                                                <th>SP (Inc. GST)</th>
                                                <th>Discount</th>
                                                <th>Sold Price (Inc. GST)</th>
                                                <th>Quantity</th>
                                                <th>Profit (inc. GST)</th>
                                                <th>Total Price (Inc. GST)</th>
                                                <th>Total Profit (inc. GST)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                            $salepp = 0;
                                            $salesp = 0;
                                            $salediscount = 0;
                                            $salesoldprice = 0;
                                            $saleprofit = 0;
                                            $saletotalprice = 0;
                                            $saletotalprofit = 0;
                                            $refundpp= 0;
                                            $refundsp = 0;
                                            $refunddiscount = 0;
                                            $refundsoldprice = 0;
                                            $refundprofit = 0;
                                            $refundtotalprice = 0;
                                            $refundtotalprofit = 0;
                                            @endphp
                                            @foreach($userprofitdata['userprofit'] as $sales)
                                            <tr>
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                <th>
                                                    <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                </th>
                                                <td>{{$sales->store_name}}</td>
                                                <td>{{$sales->name}}</td>
                                                <td>{{$sales->barcode}}</td>
                                                <td>{{$sales->productname}}</td>
                                                <td>{{$sales->suppliername}}</td>
                                                <td>
                                                    @if($sales->subcategoryname != '')
                                                    {{$sales->subcategoryname}}
                                                    @else
                                                    {{$sales->categoryname}}
                                                    @endif
                                                </td>
                                                <td>{{$sales->brandname}}</td>
                                                <td>{{$sales->modelname}}</td>
                                                <td>{{$sales->colourname}}</td>
                                                <td>
                                                    {{$sales->ppingst}}
                                                    @php
                                                    $salepp += $sales->ppingst;
                                                    @endphp
                                                </td>
                                                <td>
                                                    {{$sales->spingst}}
                                                    @php
                                                    $salesp += $sales->spingst;
                                                    @endphp
                                                </td>
                                                <td>
                                                    @if($sales->discountedAmount == '')
                                                    0.00
                                                    @else
                                                    {{$sales->discountedAmount}}
                                                        @php
                                                        $salediscount += $sales->discountedAmount;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$sales->salePrice}}
                                                    @php
                                                    $salesoldprice += $sales->salePrice;
                                                    @endphp
                                                </td>
                                                <td>{{$sales->quantity}}</td>
                                                <td>
                                                    {{$sales->salePrice - $sales->ppingst}}
                                                    @php
                                                    $saleprofit += ($sales->salePrice - $sales->ppingst);
                                                    @endphp
                                                </td>
                                                <td>
                                                    {{$sales->subTotal}}
                                                    @php
                                                    $saletotalprice += $sales->subTotal;
                                                    @endphp
                                                </td>
                                                <td>
                                                    {{($sales->salePrice - $sales->ppingst) * $sales->quantity}}
                                                    @php
                                                    $saletotalprofit += ($sales->salePrice - $sales->ppingst) * $sales->quantity;
                                                    @endphp
                                                </td>
                                            </tr>
                                            @endforeach
                                            @foreach($userprofitdata['userrefund'] as $refund)
                                            <tr style="background-color: #f1525247;">
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                <th>
                                                    <a href="refundsale/{{$refund->refundInvoiceID}}" style="color: #007bff;">{{$refund->refundInvoiceID}}</a>
                                                </th>
                                                <td>{{$refund->store_name}}</td>
                                                <td>{{$refund->name}}</td>
                                                <td>{{$refund->barcode}}</td>
                                                <td>{{$refund->productname}}</td>
                                                <td>{{$refund->suppliername}}</td>
                                                <td>
                                                    @if($refund->subcategoryname != '')
                                                    {{$refund->subcategoryname}}
                                                    @else
                                                    {{$refund->categoryname}}
                                                    @endif
                                                </td>
                                                <td>{{$refund->brandname}}</td>
                                                <td>{{$refund->modelname}}</td>
                                                <td>{{$refund->colourname}}</td>
                                                <td>
                                                    -{{$refund->ppingst}}
                                                    @php
                                                    $refundpp += $refund->ppingst;
                                                    @endphp
                                                </td>
                                                <td>
                                                    -{{$refund->spingst}}
                                                    @php
                                                    $refundsp += $refund->spingst;
                                                    @endphp
                                                </td>
                                                <td>
                                                    @if($refund->discountedAmount == '')
                                                    -0.00
                                                    @else
                                                    -{{$refund->discountedAmount}}
                                                        @php
                                                        $refunddiscount += $refund->discountedAmount;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td>
                                                    -{{$refund->salePrice}}
                                                    @php
                                                    $refundsoldprice += $refund->salePrice;
                                                    @endphp
                                                </td>
                                                <td>{{$refund->quantity}}</td>
                                                <td>
                                                    -{{$refund->salePrice - $refund->ppingst}}
                                                    @php
                                                    $refundprofit += ($refund->salePrice - $refund->ppingst);
                                                    @endphp
                                                </td>
                                                <td>
                                                    -{{$refund->subTotal}}
                                                    @php
                                                    $refundtotalprice += $refund->subTotal;
                                                    @endphp
                                                </td>
                                                <td>
                                                    -{{($refund->salePrice - $refund->ppingst) * $refund->quantity}}
                                                    @php
                                                    $refundtotalprofit += ($refund->salePrice - $refund->ppingst) * $refund->quantity;
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
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$salepp - $refundpp}}
                                                    </td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$salesp - $refundsp}}
                                                    </td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$salediscount - $refunddiscount}}
                                                    </td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$salesoldprice - $refundsoldprice}}
                                                    </td>
                                                    <td></td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$saleprofit - $refundprofit}}
                                                    </td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$saletotalprice - $refundtotalprice}}
                                                    </td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$saletotalprofit - $refundtotalprofit}}
                                                    </td>
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
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'UserProfitReport',
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