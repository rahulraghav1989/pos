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
                            <h4 class="page-title">Search Products By IMEI<h4>
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
                            <div class="card-body row">
                                <div class="col-md-6">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th colspan="2" align="center">Product Detail</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Name</td>
                                                    <td>{{$getdetail[0]->productname}}</td>
                                                </tr>
                                                <tr>
                                                    <td>IMEI</td>
                                                    <td>{{$getdetail[0]->productimei}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Date</td>
                                                    <td>@php echo date('d-m-Y', strtotime($getdetail[0]->created_at)) @endphp</td>
                                                </tr>
                                                <tr>
                                                    <td>Location</td>
                                                    <td>
                                                        @if($getdetail[0]->stocktransferID != "")
                                                            {{App\store::where('store_id', $getdetail[0]->toStoreID)->pluck('store_name')->first()}}
                                                        @else
                                                            {{$getdetail[0]->store_name}}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>
                                                        @if($getdetail[0]->stockreturnID != "")
                                                            Stock Returned
                                                        @elseif($getdetail[0]->productquantity != "0")
                                                            In-Stock
                                                        @else
                                                            Out-Of-Stock
                                                        @endif
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th colspan="2" align="center">Purchase Order</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>PO Number</td>
                                                    <td><a href="purchaseorderreceiveitem/{{$getdetail[0]->ponumber}}" target="_blank">{{$getdetail[0]->ponumber}}</a></td>
                                                </tr>
                                                <tr>
                                                    <td>PO Ref. Number</td>
                                                    <td>{{$getdetail[0]->porefrencenumber}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Docket Number</td>
                                                    <td>{{$getdetail[0]->docketnumber}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Received At</td>
                                                    <td>{{$getdetail[0]->store_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Received By</td>
                                                    <td>{{App\loggeduser::where('id', $getdetail[0]->receivedby)->pluck('name')->first()}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <h5>Order Detail</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Sale Inv.</th>
                                                        <th>Sale Date</th>
                                                        <th>Sale By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($getdetail->groupBy('orderID') as $details)
                                                    <tr>
                                                        <td><a href="sale/{{$details[0]->orderID}}" target="_blank">{{$details[0]->orderID}}</a></td>
                                                        <td>
                                                            @if($details[0]->orderDate != '')
                                                            @php echo date('d-m-Y', strtotime($details[0]->orderDate)) @endphp
                                                            @endif
                                                        </td>
                                                        <td>{{$details[0]->name}}</td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <h5>Refund Detail</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table id="" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Refund Inv.</th>
                                                        <th>Refund Date.</th>
                                                        <th>Refund By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($getdetail->groupBy('refundInvoiceID') as $details)
                                                    <tr>
                                                        <td><a href="refundinvoice/{{$details[0]->refundInvoiceID}}" target="_blank"> {{$details[0]->refundInvoiceID}}</a></td>
                                                        <td>
                                                            @if($details[0]->refundDate != '')
                                                            @php echo date('d-m-Y', strtotime($details[0]->refundDate)) @endphp
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{App\loggeduser::where('id', $details[0]->refundBy)->pluck('name')->first()}}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <h5>Stock Transfer</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table id="" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Stock Trans. Date</th>
                                                        <th>Stock Trans. Inv</th>
                                                        <th>Stock Trans. To</th>
                                                        <th>Stock Trans. By</th>
                                                        <th>Stock Trans. Rec. Date</th>
                                                        <th>Stock Trans. Rec. By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($getdetail->groupBy('stocktransferID') as $details)
                                                    <tr>
                                                        <td>
                                                            @if($details[0]->stocktransferDate != '')
                                                            @php echo date('d-m-Y', strtotime($details[0]->stocktransferDate)) @endphp
                                                            @endif
                                                        </td>
                                                        <td><a href="stocktransferinvoice/{{$details[0]->stocktransferID}}" target="_blank"> {{$details[0]->stocktransferID}}</a></td>
                                                        <td>
                                                            {{App\store::where('store_id', $details[0]->toStoreID)->pluck('store_name')->first()}}
                                                        </td>
                                                        <td>
                                                            {{App\loggeduser::where('id', $details[0]->fromUserID)->pluck('name')->first()}}
                                                        </td>
                                                        <td>
                                                            @if($details[0]->receivetrasnsferDate != '')
                                                            @php echo date('d-m-Y', strtotime($details[0]->receivetrasnsferDate)) @endphp
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{App\loggeduser::where('id', $details[0]->toUserID)->pluck('name')->first()}}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <h5>Stock Return</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table id="" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Stock Return Inv.</th>
                                                        <th>Stock Return Date</th>
                                                        <th>Stock Return By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($getdetail->groupBy('stockreturnID') as $details)
                                                    <tr>
                                                        <td><a href="stockreturncreation/{{$details[0]->stockreturnID}}" target="_blank"> {{$details[0]->stockreturnID}}</a></td>
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
                                            </div>
                                        </div>
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