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
                            <h4 class="page-title">Search Products By IMEI/Serial/Barcode - Get Status<h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Products/Plans</a></li>
                                <li class="breadcrumb-item active">Search Product</li>
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
                                                    @if ($errors->any())
                                                        @foreach ($errors->all() as $error)
                                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                                {{$error}}
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    @if(session()->has('success'))
                                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                                            {{ session()->get('success') }}
                                                        </div>
                                                    @endif
                                                    @if(session()->has('error'))
                                                    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                                        {{ session()->get('error') }}
                                                    </div>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>Search IMEI</h5>
                                                            <form target="_blank" method="post" action="{{route('searchentity')}}">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label>Enter IMEI/SERIAL</label>
                                                                        <input type="text" name="imei" class="form-control" placeholder="Enter IMEI/SERIAL">
                                                                    </div>
                                                                    <!-- <div class="col-md-12" style="margin-top: 20px;">
                                                                        <div class="form-group">
                                                                            <div>
                                                                                <div class="input-daterange input-group" id="date-range">
                                                                                    <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($firstday)) @endphp" />
                                                                                    <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($lastday)) @endphp" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> -->
                                                                    @if(session('loggindata')['loggeduserpermission']->searchproductsbystore=='Y')
                                                                    <div class="col-md-12" style="margin-top: 20px;">
                                                                        <select name="store" class="form-control">
                                                                            <option value="">SELECT STORE</option>
                                                                            @foreach($allstore as $stores)
                                                                            <option value="{{$stores->store_id}}">{{$stores->store_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    @endif
                                                                    <div class="col-md-12 text-center" style="margin-top: 10px;">
                                                                        <button type="submit" class="btn btn-success">SEARCH PRODUCTS</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>Search Barcode</h5>
                                                            <form method="post" action="{{route('searchentity')}}" target="_blank">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label>Enter Barcode</label>
                                                                        <!-- <textarea name="barcode" cols="" rows="2" class="form-control" placeholder="Enter Barcode"></textarea> -->
                                                                        <input type="text" name="barcode" class="form-control" placeholder="Enter Barcode">
                                                                    </div>
                                                                    @if(session('loggindata')['loggeduserpermission']->searchproductsbystore=='Y')
                                                                    <div class="col-md-12" style="margin-top: 20px;">
                                                                        <select name="store" class="form-control">
                                                                            <option value="">SELECT STORE</option>
                                                                            @foreach($allstore as $stores)
                                                                            <option value="{{$stores->store_id}}">{{$stores->store_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    @endif
                                                                    <div class="col-md-12" style="margin-top: 20px;">
                                                                        <div class="form-group">
                                                                            <div>
                                                                                <div class="input-daterange input-group" id="date-range">
                                                                                    <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" @if($firstday!='') value="@php echo date('d-m-Y', strtotime($firstday)) @endphp" @endif />
                                                                                    <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" @if($lastday!='') value=" @php echo date('d-m-Y', strtotime($lastday)) @endphp" @endif />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-12 text-center" style="margin-top: 10px;">
                                                                        <button type="submit" class="btn btn-success">SEARCH PRODUCTS</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>Search IN-Stock By Barcode</h5>
                                                            <form target="_blank" method="post" action="{{route('searchentity')}}">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label>Enter Barcode</label>
                                                                        <input type="text" name="instockbarcode" class="form-control" placeholder="Enter Barcode">
                                                                    </div>
                                                                    <div class="col-md-12 text-center" style="margin-top: 10px;">
                                                                        <button type="submit" class="btn btn-success">SEARCH PRODUCTS</button>
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
                            <hr>
                            <div class="card-body">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        @if(!empty($getdetail))
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>IMEI/SERIAL/BARCODE</th>
                                                <th>Name</th>
                                                <th>Barcode</th>
                                                <th>RRP<br>(Inc. GST)</th>
                                                <th>Pur. Price<br>(Inc. GST)</th>
                                                <th>Supplier</th>
                                                <th>PO Inv.</th>
                                                <th>PO Rec. Date</th>
                                                <th>PO Rec. By</th>
                                                <th>Receiveing Store</th>
                                                <th>Sale Inv.</th>
                                                <th>Sale Date</th>
                                                <th>Sale By</th>
                                                <th>Refund Inv.</th>
                                                <th>Refund Date.</th>
                                                <th>Refund By</th>
                                                <th>Stock Trans. Inv</th>
                                                <th>Stock Trans. To</th>
                                                <th>Stock Trans. By</th>
                                                <th>Stock Trans. Rec. By</th>
                                                <th>Stock Return Inv.</th>
                                                <th>Stock Return Date</th>
                                                <th>Stock Return By</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getdetail->groupBy('productimei') as $details)
                                            <tr>
                                                <td>
                                                    @if($details[0]->productimei != "")
                                                        {{$details[0]->productimei}}
                                                    @else
                                                        {{$details[0]->barcode}}
                                                    @endif
                                                </td>
                                                <td>{{$details[0]->productname}}</td>
                                                <td>{{$details[0]->barcode}}</td>
                                                <td>{{$details[0]->spingst}}</td>
                                                <td>{{$details[0]->ppingst}}</td>
                                                <td>{{$details[0]->suppliername}}</td>
                                                <td>{{$details[0]->ponumber}}</td>
                                                <td>
                                                    @if($details[0]->created_at != '')
                                                    @php echo date('d-m-Y H:i:s A', strtotime($details[0]->created_at)) @endphp
                                                    @endif
                                                </td>
                                                <td>
                                                    {{App\loggeduser::where('id', $details[0]->receivedby)->pluck('name')->first()}}
                                                </td>
                                                <td>{{$details[0]->store_name}}</td>
                                                <td>{{$details[0]->orderID}}</td>
                                                <td>
                                                    @if($details[0]->orderDate != '')
                                                    @php echo date('d-m-Y', strtotime($details[0]->orderDate)) @endphp
                                                    @endif
                                                </td>
                                                <td>{{$details[0]->name}}</td>
                                                <td>{{$details[0]->refundInvoiceID}}</td>
                                                <td>
                                                    @if($details[0]->refundDate != '')
                                                    @php echo date('d-m-Y', strtotime($details[0]->refundDate)) @endphp
                                                    @endif
                                                </td>
                                                <td>
                                                    {{App\loggeduser::where('id', $details[0]->refundBy)->pluck('name')->first()}}
                                                </td>
                                                <td>{{$details[0]->stocktransferID}}</td>
                                                <td>
                                                    {{App\store::where('store_id', $details[0]->toStoreID)->pluck('store_name')->first()}}
                                                </td>
                                                <td>
                                                    {{App\loggeduser::where('id', $details[0]->fromUserID)->pluck('name')->first()}}
                                                </td>
                                                <td>
                                                    {{App\loggeduser::where('id', $details[0]->toUserID)->pluck('name')->first()}}
                                                </td>
                                                <td>{{$details[0]->stockreturnID}}</td>
                                                <td>
                                                    @if($details[0]->stockreturnDate != '')
                                                    @php echo date('d-m-Y', strtotime($details[0]->stockreturnDate)) @endphp
                                                    @endif
                                                </td>
                                                <td>
                                                    {{App\loggeduser::where('id', $details[0]->userID)->pluck('name')->first()}}
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @endif

                                        @if(!empty($getbarcodedetail))
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>IMEI/SERIAL/BARCODE</th>
                                                <th>Name</th>
                                                <th>Barcode</th>
                                                <th>RRP<br>(Inc. GST)</th>
                                                <th>Pur. Price<br>(Inc. GST)</th>
                                                <th>Supplier</th>
                                                <th>PO Inv.</th>
                                                <th>PO Rec. Date</th>
                                                <th>PO Rec. By</th>
                                                <th>Receiveing Store</th>
                                                <th>Sale Inv.</th>
                                                <th>Sale Date</th>
                                                <th>Sale By</th>
                                                <th>Refund Inv.</th>
                                                <th>Refund Date.</th>
                                                <th>Refund By</th>
                                                <th>Stock Trans. Inv</th>
                                                <th>Stock Trans. To</th>
                                                <th>Stock Trans. By</th>
                                                <th>Stock Trans. Rec. By</th>
                                                <th>Stock Return Inv.</th>
                                                <th>Stock Return Date</th>
                                                <th>Stock Return By</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getbarcodedetail->groupBy('barcode') as $details)
                                            <tr>
                                                <td>
                                                    @if($details[0]->productimei != "")
                                                        {{$details[0]->productimei}}
                                                    @else
                                                        {{$details[0]->barcode}}
                                                    @endif
                                                </td>
                                                <td>{{$details[0]->productname}}</td>
                                                <td>{{$details[0]->barcode}}</td>
                                                <td>{{$details[0]->spingst}}</td>
                                                <td>{{$details[0]->ppingst}}</td>
                                                <td>{{$details[0]->suppliername}}</td>
                                                <td>
                                                    @foreach($details->groupBy('ponumber') as $purchaseorder)
                                                        <a href="purchaseordercreation/{{$purchaseorder[0]->ponumber}}" target="_blank" @if($purchaseorder[0]->poprocessstatus == '1') style="color:#ce0606" @endif @if($purchaseorder[0]->poprocessstatus == '2') style="color:#27af05" @endif>{{$purchaseorder[0]->ponumber}}</a>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('ponumber') as $purchaseorder)
                                                        @if($details[0]->created_at != '')
                                                        @php echo date('d-m-Y H:i:s A', strtotime($purchaseorder[0]->created_at)) @endphp
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('ponumber') as $purchaseorder)
                                                        {{App\loggeduser::where('id', $purchaseorder[0]->receivedby)->pluck('name')->first()}}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('ponumber') as $purchaseorder)
                                                        {{$purchaseorder[0]->store_name}}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('orderID') as $orderdetail)
                                                        <a href="sale/{{$orderdetail[0]->orderID}}" target="_blank" @if($orderdetail[0]->orderstatus == '1') style="color:#27af05" @endif @if($orderdetail[0]->orderstatus == '0') style="color:#ce0606" @endif> {{$orderdetail[0]->orderID}}</a>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('orderID') as $orderdetail)
                                                        @if($details[0]->orderDate != '')
                                                        @php echo date('d-m-Y', strtotime($orderdetail[0]->orderDate)) @endphp
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('orderID') as $orderdetail)
                                                        {{$orderdetail[0]->name}}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('refundInvoiceID') as $refunddetails)
                                                        <a href="refundinvoice/{{$refunddetails[0]->refundInvoiceID}}" target="_blank" @if($refunddetails[0]->refundStatus == '1') style="color:#27af05" @endif @if($refunddetails[0]->refundStatus == '0') style="color:#ce0606" @endif> {{$refunddetails[0]->refundInvoiceID}}</a>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    
                                                    @foreach($details->groupBy('refundInvoiceID') as $refunddetails)
                                                        @if($refunddetails[0]->refundDate != '')
                                                        @php echo date('d-m-Y', strtotime($refunddetails[0]->refundDate)) @endphp
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('refundInvoiceID') as $refunddetails)
                                                        {{App\loggeduser::where('id', $refunddetails[0]->refundBy)->pluck('name')->first()}}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('stocktransferID') as $stocktransferdetails)
                                                        <a href="stocktransferreceive/{{$stocktransferdetails[0]->stocktransferID}}" target="_blank" @if($stocktransferdetails[0]->stocktransferStatus == '2') style="color:#27af05" @endif @if($stocktransferdetails[0]->stocktransferStatus == '1') style="color:#ce0606" @endif> {{$stocktransferdetails[0]->stocktransferID}}</a>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('stocktransferID') as $stocktransferdetails)
                                                        {{App\store::where('store_id', $stocktransferdetails[0]->toStoreID)->pluck('store_name')->first()}}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('stocktransferID') as $stocktransferdetails)
                                                        {{App\loggeduser::where('id', $stocktransferdetails[0]->fromUserID)->pluck('name')->first()}}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('stocktransferID') as $stocktransferdetails)
                                                        {{App\loggeduser::where('id', $stocktransferdetails[0]->toUserID)->pluck('name')->first()}}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('stockreturnID') as $stockreturndetails)
                                                        <a href="stockreturncreation/{{$stockreturndetails[0]->stockreturnID}}" target="_blank" @if($stockreturndetails[0]->stockreturnStatus == '1') style="color:#27af05" @endif @if($stockreturndetails[0]->stockreturnStatus == '0') style="color:#ce0606" @endif> {{$stockreturndetails[0]->stockreturnID}}</a>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('stockreturnID') as $stockreturndetails)
                                                        @if($stockreturndetails[0]->stockreturnDate != '')
                                                        @php echo date('d-m-Y', strtotime($stockreturndetails[0]->stockreturnDate)) @endphp
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($details->groupBy('stockreturnID') as $stockreturndetails)
                                                        {{App\loggeduser::where('id', $stockreturndetails[0]->userID)->pluck('name')->first()}}
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @endif
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
                                title: 'SearchProductReport-@php echo date('d-m-Y', strtotime($firstday))."to".date('d-m-Y', strtotime($lastday)) @endphp',
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