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
                            <h4 class="page-title">Stock Return Report</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Report</a></li>
                                <li class="breadcrumb-item active">Stock Return Report</li>
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
                                                    <form method="post" action="{{route('stockreturnreport')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportstockreturnfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Select Store</label>
                                                                    <select id="testSelect1" name="store[]" multiple>
                                                                        @foreach($allstore as $allstores)
                                                                            @if($storeID!='')
                                                                                <option value="{{$allstores->store_id}}" @foreach($storeID as $selectedstoreid) @if($allstores->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstores->store_name}}</option>
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
                                                                    <select id="testSelect2" name="user[]" multiple>
                                                                        @foreach($allusers as $alluserss)
                                                                            @if($userID!='')
                                                                                <option value="{{$alluserss->id}}" @foreach($userID as $selecteduserid) @if($alluserss->id == $selecteduserid) SELECTED='SELECTED' @endif @endforeach>{{$alluserss->name}}</option>
                                                                            @else
                                                                                <option value="{{$alluserss->id}}">{{$alluserss->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        <script>
                                                                            document.multiselect('#testSelect2')
                                                                                .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                    console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                })
                                                                                .setCheckBoxClick("1", function(target, args) {
                                                                                    console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                });
                                                                        </script>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Select Supplier</label>
                                                                    <select id="testSelect3" name="supplier[]" multiple>
                                                                        @foreach($allsupplier as $supplier)
                                                                            @if($supplierID!='')
                                                                                <option value="{{$supplier->supplierID}}" @foreach($supplierID as $selectedsupplierid) @if($supplier->supplierID == $selectedsupplierid) SELECTED='SELECTED' @endif @endforeach>{{$supplier->suppliername}}</option>
                                                                            @else
                                                                                <option value="{{$supplier->supplierID}}">{{$supplier->suppliername}}</option>
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
                                                                <label>Select Status</label>
                                                                <select id="testSelect4" name="returnstatus[]" multiple>
                                                                    @if($returnstatus!='')
                                                                    <option value="0" @foreach($returnstatus as $retstatus) @if($retstatus == '0') SELECTED='SELECTED' @endif @endforeach>Stock Not Returned</option>
                                                                    <option value="1" @foreach($returnstatus as $retstatus) @if($retstatus == '1') SELECTED='SELECTED' @endif @endforeach>Stock Returned</option>
                                                                    @else
                                                                    <option value="0">Stock Not Returned</option>
                                                                    <option value="1">Stock Returned</option>
                                                                    @endif
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
                                                            <div class="col-md-3 text-right">
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
                                                <th>Return Invoice</th>
                                                <th data-priority="1">RA Number</th>
                                                <th data-priority="1">RA Note</th>
                                                <th data-priority="1">Supplier</th>
                                                <th data-priority="1">Barcode</th>
                                                <th data-priority="1">Product Name</th>
                                                <th data-priority="1">IMEI</th>
                                                <th data-priority="1">Returned Quantity</th>
                                                <th data-priority="1">Expected Credits</th>
                                                <th data-priority="1">Amount Credited</th>
                                                <th data-priority="1">Credit Status</th>
                                                <th data-priority="1">Return Status</th>
                                                <th>Store</th>
                                                <th>Returned By</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getstockreturn as $stockreturn)
                                            <tr>
                                                <td>
                                                    @php echo date('d-m-Y', strtotime($stockreturn->stockreturnDate)) @endphp
                                                </td>
                                                <td>{{$stockreturn->stockreturnID}}</td>
                                                <td>{{$stockreturn->raNumber}}</td>
                                                <td>{{$stockreturn->returnNote}}</td>
                                                <td>{{$stockreturn->suppliername}}</td>
                                                <td>{{$stockreturn->barcode}}</td>
                                                <td>{{$stockreturn->productname}}</td>
                                                <td>
                                                    {{$stockreturn->productimei}}
                                                    @if($stockreturn->demostockID != "")
                                                    {{App\demostock::where('demostockID', $stockreturn->demostockID)->pluck('productimei')->first()}}
                                                    @endif
                                                </td>
                                                <td>{{$stockreturn->quantity}}</td>
                                                <td>{{$stockreturn->total}}</td>
                                                <td>
                                                    {{App\stockreturnpayments::where('stockreturnID', $stockreturn->stockreturnID)->sum('returnamount')}}
                                                </td>
                                                <td>
                                                    @if(App\stockreturnitems::where('stockreturnID', $stockreturn->stockreturnID)->sum('total') == App\stockreturnpayments::where('stockreturnID', $stockreturn->stockreturnID)->sum('returnamount'))
                                                        @if($stockreturn->stockreturnStatus == 0)
                                                        <span class="badge badge-pill badge-danger">Pending</span>
                                                        @else
                                                        <span class="badge badge-pill badge-success">Credited</span>
                                                        @endif
                                                    @else
                                                    <span class="badge badge-pill badge-danger">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($stockreturn->stockreturnStatus == 0)
                                                    <span class="badge badge-pill badge-danger">Not Returned</span>
                                                    @else
                                                    <span class="badge badge-pill badge-success">Stock Returned</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$stockreturn->store_name}}
                                                </td>
                                                <td>{{$stockreturn->name}}</td>
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
                                title: 'StockReturnReport',
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