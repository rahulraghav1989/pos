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
                                                    <div class="text-right">
                                                        <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                            Filters
                                                        </a>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="collapse" id="collapseExample">
                                                            <div class="card card-body mt-3 mb-0">
                                                                <form method="post" action="{{route('salesconnection')}}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        @if(session('loggindata')['loggeduserpermission']->viewreportsalesconnectionfilter=='Y')
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Store</label>
                                                                                <select id="testSelect1" name="store[]" multiple>
                                                                                    @foreach($connectiondata['allstore'] as $allstore)
                                                                                        @if($connectiondata['storeID']!='')
                                                                                            <option value="{{$allstore->store_id}}" @foreach($connectiondata['storeID'] as $selectedstoreid) @if($allstore->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstore->store_name}}</option>
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
                                                                                    @foreach($connectiondata['allusers'] as $allusers)
                                                                                        @if($connectiondata['userID']!='')
                                                                                            <option value="{{$allusers->id}}" @foreach($connectiondata['userID'] as $selecteduserid) @if($allusers->id == $selecteduserid) SELECTED='SELECTED' @endif @endforeach>{{$allusers->name}}</option>
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
                                                                                <label>Select Plan Type</label>
                                                                                <select id="testSelect3" name="plantype[]" multiple>
                                                                                    @foreach($connectiondata['allplantype'] as $allplantype)
                                                                                        @if($connectiondata['plantype']!='')
                                                                                            <option value="{{$allplantype->plantypeID}}" @foreach($connectiondata['plantype'] as $selectedplantype) @if($allplantype->plantypeID == $selectedplantype) SELECTED='SELECTED' @endif @endforeach>{{$allplantype->plantypename}}</option>
                                                                                        @else
                                                                                            <option value="{{$allplantype->plantypeID}}">{{$allplantype->plantypename}}</option>
                                                                                        @endif
                                                                                    @endforeach
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
                                                                                <label>Select Plan Proposition</label>
                                                                                <select id="testSelect4" name="planproposition[]" multiple>
                                                                                    @foreach($connectiondata['allplanproposition'] as $allplanproposition)
                                                                                        @if($connectiondata['planproposition']!='')
                                                                                            <option value="{{$allplanproposition->planpropositionID}}" @foreach($connectiondata['planproposition'] as $selectedplanproposition) @if($allplanproposition->planpropositionID == $selectedplanproposition) SELECTED='SELECTED' @endif @endforeach>{{$allplanproposition->planpropositionname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allplanproposition->planpropositionID}}">{{$allplanproposition->planpropositionname}}</option>
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
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Plan Category</label>
                                                                                <select id="testSelect5" name="plancategory[]" multiple>\
                                                                                    @foreach($connectiondata['allplancategory'] as $allplancategory)
                                                                                        @if($connectiondata['plancategory']!='')
                                                                                            <option value="{{$allplancategory->pcID}}" @foreach($connectiondata['plancategory'] as $selectedplancategory) @if($allplancategory->pcID == $selectedplancategory) SELECTED='SELECTED' @endif @endforeach>{{$allplancategory->pcname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allplancategory->pcID}}">{{$allplancategory->pcname}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect5')
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
                                                                                <label>Select Plan Term</label>
                                                                                <select id="testSelect6" name="planterm[]" multiple>
                                                                                    @foreach($connectiondata['allplanterm'] as $allplanterm)
                                                                                        @if($connectiondata['planterm']!='')
                                                                                            <option value="{{$allplanterm->plantermID}}" @foreach($connectiondata['planterm'] as $selectedplanterm) @if($allplanterm->plantermID == $selectedplanterm) SELECTED='SELECTED' @endif @endforeach>{{$allplanterm->plantermname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allplanterm->plantermID}}">{{$allplanterm->plantermname}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect6')
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
                                                                                <label>Select Plan Handset Term</label>
                                                                                <select id="testSelect7" name="planhandsetterm[]" multiple>
                                                                                    @foreach($connectiondata['allplanhandsetterm'] as $allplanhandsetterm)
                                                                                        @if($connectiondata['planhandsetterm']!='')
                                                                                            <option value="{{$allplanhandsetterm->planhandsettermID}}" @foreach($connectiondata['planhandsetterm'] as $selectedhandsetterm) @if($allplanhandsetterm->planhandsettermID == $selectedhandsetterm) SELECTED='SELECTED' @endif @endforeach>{{$allplanhandsetterm->planhandsettermname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allplanhandsetterm->planhandsettermID}}">{{$allplanhandsetterm->planhandsettermname}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect7')
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
                                                                                        <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($connectiondata['firstday'])) @endphp" />
                                                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($connectiondata['lastday'])) @endphp" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 text-right">
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
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- <style type="text/css">
                                    .btn-group{display: none;}
                                </style> -->
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Sale Invoice</th>
                                                <th>Store</th>
                                                <th data-priority="1">Customer</th>
                                                <th data-priority="1">Customer Mob</th>
                                                <th data-priority="1">Sale Rep.</th>
                                                <th>Order ID</th>
                                                <th>Plan Type</th>
                                                <th>Plan Code</th>
                                                <th>Plan</th>
                                                <th>Plan Proposition</th>
                                                <th>Plan Category</th>
                                                <th>Plan Term</th>
                                                <th>Plan Handset Term</th>
                                                <th data-priority="1">Barcode</th>
                                                <th data-priority="1">Stock Code</th>
                                                <th data-priority="1">Product Name</th>
                                                <th>Product Supplier</th>
                                                <th data-priority="1">Device</th>
                                                <th data-priority="1">Quantity</th>
                                                <th data-priority="1">PP (Inc. GST)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                            $salessp = 0;
                                            $refundsp = 0;
                                            @endphp
                                            @foreach($connectiondata['getconnection'] as $sales)
                                            <tr>
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                <th>
                                                    <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                </th>
                                                <td>{{$sales->store_name}}</td>
                                                <td>
                                                    {{$sales->customerfirstname}} {{$sales->customerlastname}}
                                                </td>
                                                <td>
                                                    {{$sales->customermobilenumber}}
                                                </td>
                                                <td>{{$sales->name}}</td>
                                                <td>{{$sales->planOrderID}}</td>
                                                <td>{{$sales->plantypename}}</td>
                                                <td>{{$sales->plancode}}</td>
                                                <td>{{$sales->planname}}</td>
                                                <td>{{$sales->planpropositionname}}</td>
                                                <td>{{$sales->pcname}}</td>
                                                <td>{{$sales->plantermname}}</td>
                                                <td>{{$sales->planhandsettermname}}</td>
                                                <td>{{$sales->barcode}}</td>
                                                <td>{{$sales->stockcode}}</td>
                                                <td>{{$sales->productname}}</td>
                                                <td>{{$sales->suppliername}}</td>
                                                <td>{{$sales->productimei}}</td>
                                                <td>{{$sales->quantity}}</td>
                                                <td>
                                                    {{$sales->ppingst}}
                                                    @php
                                                    $salessp += $sales->ppingst;
                                                    @endphp
                                                </td>
                                            </tr>
                                            @endforeach
                                            @foreach($connectiondata['getrefundconnection'] as $refund)
                                            <tr style="background-color: #f1525247;">
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                <th>
                                                    <a href="refundinvoice/{{$refund->refundInvoiceID}}" style="color: #007bff;">{{$refund->refundInvoiceID}}</a>
                                                </th>
                                                <td>{{$refund->store_name}}</td>
                                                <td>
                                                    {{$refund->customerfirstname}} {{$refund->customerlastname}}
                                                    
                                                </td>
                                                <td>{{$refund->customermobilenumber}}</td>
                                                <td>{{$refund->name}}</td>
                                                <td>{{$refund->planOrderID}}</td>
                                                <td>{{$refund->plantypename}}</td>
                                                <td>{{$refund->plancode}}</td>
                                                <td>{{$refund->planname}}</td>
                                                <td>{{$refund->planpropositionname}}</td>
                                                <td>{{$refund->pcname}}</td>
                                                <td>{{$refund->plantermname}}</td>
                                                <td>{{$refund->planhandsettermname}}</td>
                                                <td>{{$refund->barcode}}</td>
                                                <td>{{$refund->stockcode}}</td>
                                                <td>
                                                    {{$refund->productname}}
                                                </td>
                                                <td>{{$refund->suppliername}}</td>
                                                <td>{{$refund->productimei}}</td>
                                                <td>-{{$refund->quantity}}</td>
                                                <td>
                                                    {{$refund->ppingst}}
                                                    @php
                                                    $refundsp += $refund->ppingst;
                                                    @endphp
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$salessp - $refundsp}}
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
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'SalesConnectionReport',
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