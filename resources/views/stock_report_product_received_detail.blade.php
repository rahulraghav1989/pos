@extends('main')

@section('content')
<div id="wrapper">
    @include('includes.topbar')

    @include('includes.sidebar')
    <div class="content-page">
                <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Product Received Detail</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sale History</a></li>
                                <li class="breadcrumb-item active">Product Received Detail</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                 <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="invoice-title">
                                                <h4 class="float-right font-16"><strong>Invoice # {{$get_productpurchaseorder->ponumber}}</strong></h4>
                                                <h4 class="m-t-0 font-16">
                                                    <strong>Sale Date: {{$get_productpurchaseorder->created_at}}</strong>
                                                </h4>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-6">
                                                    <address>
                                                        <strong>Creater:</strong><br>
                                                        {{$get_productpurchaseorder->get_user->name}}<br>
                                                        {{$get_productpurchaseorder->get_user->email}}
                                                    </address>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <address>
                                                        <strong>Store:</strong><br>
                                                        {{$get_productpurchaseorder->get_store->store_name}}<br>
                                                        {{$get_productpurchaseorder->get_store->store_address}}<br>
                                                        {{$get_productpurchaseorder->get_store->store_contact}}
                                                    </address>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 m-t-30">
                                                    <address>
                                                        <strong>Supplier:</strong><br>
                                                        {{$get_productpurchaseorder->posupplier->suppliername}}
                                                    </address>
                                                </div>
                                                <div class="col-6 m-t-30 text-right">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="panel panel-default">
                                                <div class="p-2">
                                                    <h3 class="panel-title font-20"><strong>Purchase Product Items</strong></h3>
                                                </div>
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <td><strong>PO Item Id</strong></td>
                                                                <td><strong>Barcode</strong></td>
                                                                <td><strong>Product</strong></td>
                                                                <td><strong>Quantity</strong></td>
                                                                <td><strong>Item Price</strong></td>
                                                                <td><strong>Totals</strong></td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($get_productpurchaseorder->poitem as $poitem)
                                                            <tr>
                                                                <td>{{$poitem->poitemID}}</td>
                                                                <td>{{$get_product->find($poitem->productID)->barcode}}</td>
                                                                <td>
                                                                    {{$get_product->find($poitem->productID)->productname}}
                                                                    <br>
                                                                    @foreach(App\productstock::where('productID', $get_product->find($poitem->productID)->productID)->where('ponumber', $get_productpurchaseorder->ponumber)->get('productimei') as $imei)
                                                                    IMEI: {{$imei->productimei}}<br>
                                                                    @endforeach
                                                                </td>
                                                                <td>{{$poitem->receivequantity}}</td>
                                                                <td>{{$poitem->poppingst}}</td>
                                                                <td>{{$poitem->poitemtotal}}</td>
                                                            </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
    
                                        </div>
                                    </div> <!-- end row -->

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="panel panel-default">
                                                <div class="p-2">
                                                    <h3 class="panel-title font-20"><strong>Product Received Detail</strong></h3>
                                                </div>
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <td><strong>PO Item Id</strong></td>
                                                                <td><strong>Barcode</strong></td>
                                                                <td><strong>Product</strong></td>
                                                                <td><strong>Docket No</strong></td>
                                                                <td><strong>Quantity</strong></td>
                                                                <td><strong>Received By</strong></td>
                                                                <td><strong>Received At</strong></td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($get_productpurchaseorder->get_po_received as $po_received)
                                                            <tr>
                                                                <td>{{$po_received->poitemID}}</td>
                                                                <td>{{$get_product->find($get_productpurchaseorder->poitem->find($po_received->poitemID)->productID)->barcode}}</td>
                                                                <td>{{$get_product->find($get_productpurchaseorder->poitem->find($po_received->poitemID)->productID)->productname}}</td>
                                                                <td>{{$po_received->docketnumber}}</td>
                                                                <td>{{$po_received->quantity}}</td>
                                                                <td>{{$get_user->find($po_received->receivedby)->name}}</td>
                                                                <td>{{$po_received->created_at}}</td>
                                                            </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
    
                                        </div>
                                    </div> <!-- end row -->
    
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
    </div>
</div>
@endsection