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
                            <h4 class="page-title">Search Products By Barcode<h4>
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
                                <div class="col-md-12">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <h5>Product Detail</h5>
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th colspan="4">Barcode Detail</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Product Name</td>
                                                        <td>{{$productbarcode->productname}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Available Qty 
                                                            @if(count($storeID) == 1)  
                                                            @ {{App\store::whereIn('store_id', [$storeID])->pluck('store_name')->first()}}
                                                            @else
                                                            (All locations)
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{$productstock}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Barcode</td>
                                                        <td>{{$productbarcode->barcode}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Supplier Details</td>
                                                        <td>
                                                            {{$productbarcode->suppliername}}
                                                        </td>
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
                                        <h5>Purchase Order</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table id="" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>PO Rec. Date</th>
                                                        <th>PO Inv.</th>
                                                        <th>PO Rec. Qty</th>
                                                        <th>PO Rec. By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($productpo as $po)
                                                            <tr>
                                                                <td>
                                                                    @php echo date('d-m-Y', strtotime($po->created_at)) @endphp
                                                                </td>
                                                                <td>
                                                                    <a href="purchaseorderreceiveitem/{{$po->ponumber}}" target="_blank">{{$po->ponumber}}</a>
                                                                </td>
                                                                <td>{{$po->receivequantity}}</td>
                                                                <td>
                                                                    {{App\User::where('id', $po->receivedby)->pluck('name')->first()}}
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
                                        <h5>Order Detail</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Sale Date</th>
                                                        <th>Sale Inv.</th>
                                                        <th>Sale Location</th>
                                                        <th>Sale Qty</th>
                                                        <th>Sale By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($productorderdetail as $orderdetail)
                                                        <tr>
                                                            <td>
                                                                @php
                                                                echo date('d-m-Y', strtotime($orderdetail->orderDate))
                                                                @endphp
                                                            </td>
                                                            <td><a href="sale/{{$orderdetail->orderID}}" target="_blank">{{$orderdetail->orderID}}</a></td>
                                                            <td>{{App\store::where('store_id', $orderdetail->storeID)->pluck('store_name')->first()}}</td>
                                                            <td>{{$orderdetail->quantity}}</td>
                                                            <td>{{App\User::where('id', $orderdetail->userID)->pluck('name')->first()}}</td>
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
                                        <h5>Transfer to other stores</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table id="" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Transfer Date</th>
                                                        <th>Transfer Inv</th>
                                                        <th>Transfer Qty</th>
                                                        <th>Trans. By</th>
                                                        <th>Trans. To</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($productstocktransfer as $outtransfer)
                                                        <tr>
                                                            <td>
                                                                @php
                                                                echo date('d-m-Y', strtotime($outtransfer->stocktransferDate))
                                                                @endphp
                                                            </td>
                                                            <td><a href="stocktransferinvoice/{{$outtransfer->stocktransferID}}" target="_blank">{{$outtransfer->stocktransferID}}</a></td>
                                                            <td>{{$outtransfer->quantity}}</td>
                                                            <td>{{App\User::where('id', $outtransfer->fromUserID)->pluck('name')->first()}}</td>
                                                            <td>{{App\store::where('store_id', $outtransfer->toStoreID)->pluck('store_name')->first()}}</td>
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
                                        <h5>Transfer rec. from other stores</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table id="" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Rec. Date</th>
                                                        <th>Rec. Inv</th>
                                                        <th>Rec. Qty</th>
                                                        <th>Rec. By</th>
                                                        <th>Rec. From</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($productstockintransfer as $intransfer)
                                                        <tr>
                                                            <td>
                                                                @php
                                                                echo date('d-m-Y', strtotime($intransfer->stocktransferDate))
                                                                @endphp
                                                            </td>
                                                            <td><a href="stocktransferinvoice/{{$intransfer->stocktransferID}}" target="_blank">{{$intransfer->stocktransferID}}</a></td>
                                                            <td>{{$intransfer->quantity}}</td>
                                                            <td>{{App\User::where('id', $intransfer->toUserID)->pluck('name')->first()}}</td>
                                                            <td>{{App\store::where('store_id', $intransfer->fromStoreID)->pluck('store_name')->first()}}</td>
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
                                        <h5>Refund Detail</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                <table id="" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Refund Date</th>
                                                        <th>Refund Inv.</th>
                                                        <th>Refund Location</th>
                                                        <th>Refund Qty</th>
                                                        <th>Refund By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($productrefund as $refund)
                                                        <tr>
                                                            <td>
                                                                @php
                                                                echo date('d-m-Y', strtotime($refund->refundDate))
                                                                @endphp
                                                            </td>
                                                            <td><a href="refundinvoice/{{$refund->refundInvoiceID}}" target="_blank">{{$refund->refundInvoiceID}}</a></td>
                                                            <td>{{App\store::where('store_id', $refund->storeID)->pluck('store_name')->first()}}</td>
                                                            <td>{{$refund->quantity}}</td>
                                                            <td>{{App\store::where('store_id', $refund->refundBy)->pluck('store_name')->first()}}</td>
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
                                                        <th>Stock Return Date</th>
                                                        <th>Stock Return Inv.</th>
                                                        <th>Stock Return By</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($productstockreturn as $stockreturn)
                                                        <tr>
                                                            <td>
                                                                @php
                                                                echo date('d-m-Y', strtotime($stockreturn->stockreturnDate))
                                                                @endphp
                                                            </td>
                                                            <td><a href="stockreturncreation/{{$stockreturn->stockreturnID}}" target="_blank">{{$stockreturn->stockreturnID}}</a></td>
                                                            <td>{{App\User::where('id', $stockreturn->userID)->pluck('name')->first()}}</td>
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