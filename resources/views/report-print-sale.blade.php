@extends('main')

@section('content')
<style type="text/css">
    .content-page{
        margin-left: 0px !important;
    }
    .content-page .content{
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }
</style>
<div id="wrapper">

    <div class="content-page">
                <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <!-- end page-title -->

                 <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12"> 
                                            <div class="col-md-12" id="printthis">
                                                <div class="col-md-12">
                                                    <img src="{{ asset('posview') }}/assets/images/Vodafone-Logo.png" width="200">
                                                </div>
                                                <div class="invoice-title">
                                                    <h4 class="float-right font-16"><strong>Invoice # {{$saledetail[0]->orderID}}</strong></h4>
                                                    <h4 class="m-t-0 font-16">
                                                        <strong>Sale Date: @php echo date('d-m-Y H:i:s', strtotime($saledetail[0]->created_at)) @endphp</strong>
                                                    </h4>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <address>
                                                            <strong>Customer:</strong><br>
                                                            @if($saledetail[0]->customer['customerbusinessname'] == "")
                                                            {{$saledetail[0]->customer['customertitle']}} {{$saledetail[0]->customer['customerfirstname']}} {{$saledetail[0]->customer['customerlastname']}}<br>
                                                            {{$saledetail[0]->customer['customermobilenumber']}}
                                                            @else
                                                            {{$saledetail[0]->customer['customerbusinessname']}}<br>
                                                            {{$saledetail[0]->customer['customerbusinessemail']}}
                                                            @endif
                                                            <br>
                                                            @if($saledetail[0]->planOrderID != "")
                                                            Order ID: {{$saledetail[0]->planOrderID}}
                                                            <br>
                                                            @endif
                                                            @if($saledetail[0]->planFullfillmentOrderid != "")
                                                            Ful. Order ID: {{$saledetail[0]->planFullfillmentOrderid}}
                                                            @endif
                                                        </address>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <address>
                                                            <strong>Store:</strong><br>
                                                            {{$saledetail[0]->store_name}}<br>
                                                            {{$saledetail[0]->store_address}}<br>
                                                            {{$saledetail[0]->store_contact}}<br>
                                                            {{$saledetail[0]->store_email}}<br>
                                                            <br>
                                                            Jainish Pty Ltd<br>
                                                            is a service provider for<br>
                                                            Vodafone Mobile Store<br>
                                                            ABN: 86 605 918 670
                                                        </address>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 m-t-30">
                                                        <address>
                                                            <strong>Served By:</strong><br>
                                                            {{$saledetail[0]->username}}
                                                        </address>

                                                        @if(!empty($saledetail[0]->refundorder))
                                                        <address>
                                                            <strong>Refund Invoice:</strong>
                                                            @foreach($saledetail[0]->refundorder as $refundinv)
                                                                <strong><a href="../refundinvoice/{{$refundinv->refundInvoiceID}}" style="color: #007bff;">{{$refundinv->refundInvoiceID}}</a></strong><br>
                                                            @endforeach
                                                        </address>
                                                        @endif
                                                    </div>
                                                    <div class="col-6 m-t-30 text-right">
                                                        <address>
                                                            <strong>Sale Type:</strong><br>
                                                            {{$saledetail[0]->orderType}}
                                                        </address>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <div class="p-2">
                                                                <h3 class="panel-title font-20"><strong>Sale summary</strong></h3>
                                                            </div>
                                                            <div class="">
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                        <tr>
                                                                            <td><strong>Barcode</strong></td>
                                                                            <td><strong>Product</strong></td>
                                                                            <td><strong>Item Price</strong></td>
                                                                            <td><strong>Discount</strong></td>
                                                                            <td><strong>Quantity</strong></td>
                                                                            <td class="text-right"><strong>Totals</strong></td>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                                        @foreach($saledetail as $orderitem)
                                                                            @if($orderitem->plancode == "")
                                                                            <tr>
                                                                                <td>{{$orderitem->barcode}}</td>
                                                                                <td>
                                                                                    {{$orderitem->productname}}
                                                                                    <span style="font-size: 1em;color: #000;font-weight: 600;font-style: italic;">
                                                                                    @if($orderitem->productimei)
                                                                                    <br>
                                                                                    {{$orderitem->productimei}}
                                                                                    @endif
                                                                                    @if($orderitem->simnumber != "")
                                                                                    <br>
                                                                                    Sim: {{$orderitem->simnumber}}
                                                                                    @endif
                                                                                    </span>
                                                                                </td>
                                                                                <td>{{$orderitem->salePrice}}</td>
                                                                                <td>
                                                                                    @if($orderitem->discountedType == '')
                                                                                    0.00
                                                                                    @elseif($orderitem->discountedType == 'Percentage')
                                                                                    {{$orderitem->discount}}%
                                                                                    @elseif($orderitem->discountedType == 'Amount')
                                                                                    ${{$orderitem->discount}}
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{$orderitem->quantity}}</td>
                                                                                <td class="text-right">${{$orderitem->subTotal}}</td>
                                                                            </tr>
                                                                            @else
                                                                            <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <span style="font-size: 1em;color: #000;font-weight: 600;">
                                                                                    {{$orderitem->planname}}
                                                                                </span>
                                                                                <br>
                                                                                <span style="font-size: 0.9em;color: #000;font-weight: normal;font-style: italic;">
                                                                                @if($orderitem->productID != "")
                                                                                    <span style="font-size: 1em;">{{App\product::where('productID', $orderitem->productID)->pluck('productname')->first()}}
                                                                                    <br>
                                                                                    </span>
                                                                                @endif

                                                                                @if($orderitem->stockID != "")
                                                                                    @if(App\productstock::where('productID', $orderitem->productID)->where('psID', $orderitem->stockID)->pluck('productimei')->first() != "")
                                                                                    IMEI/SR.: {{App\productstock::where('productID', $orderitem->productID)->where('psID', $orderitem->stockID)->pluck('productimei')->first()}}
                                                                                    <br>
                                                                                    @endif
                                                                                @endif
                                                                                @if($orderitem->simnumber != "")
                                                                                Sim: {{App\productstock::where('psID', $orderitem->stockID)->pluck('simnumber')->first()}}
                                                                                @endif
                                                                                @if($orderitem->stockgroupname != "")
                                                                                {{$orderitem->stockgroupname}}
                                                                                @endif
                                                                                </span>
                                                                            </td>
                                                                            <td>{{$orderitem->salePrice}}</td>
                                                                            <td>
                                                                                @if($orderitem->discountedType == '')
                                                                                0.00
                                                                                @elseif($orderitem->discountedType == 'Percentage')
                                                                                {{$orderitem->discount}}%
                                                                                @elseif($orderitem->discountedType == 'Amount')
                                                                                ${{$orderitem->discount}}
                                                                                @endif
                                                                            </td>
                                                                            <td>{{$orderitem->quantity}}</td>
                                                                            <td class="text-right">${{$orderitem->subTotal}}</td>
                                                                        </tr>
                                                                            @endif
                                                                        @endforeach
                                                                        <tr>
                                                                            <td class="thick-line"></td>
                                                                            <td class="thick-line"></td>
                                                                            <td class="thick-line"></td>
                                                                            <td class="thick-line"></td>
                                                                            <td class="thick-line text-center">
                                                                                <strong>Subtotal</strong></td>
                                                                            <td class="thick-line text-right">
                                                                                ${{$saledetail->sum('subTotal')}}
                                                                            </td>
                                                                        </tr>
                                                                        @foreach($saledetail[0]->orderpayment->groupBy('paymentType') as $payments)
                                                                        <tr>
                                                                            <td class="thick-line"></td>
                                                                            <td class="thick-line"></td>
                                                                            <td class="thick-line"></td>
                                                                            <td class="thick-line"></td>
                                                                            <td class="thick-line text-center" style="color: #229c31;">
                                                                                <strong>{{$payments[0]->paymentType}}</strong></td>
                                                                            <td class="thick-line text-right" style="color: #229c31;">
                                                                                ${{$payments[0]->where('orderID', $saledetail[0]->orderID)->where('paymentType', $payments[0]->paymentType)->sum('paidAmount')}}
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                        <tr>
                                                                            <td class="no-line"></td>
                                                                            <td class="no-line"></td>
                                                                            <td class="no-line"></td>
                                                                            <td class="no-line"></td>
                                                                            <td class="no-line text-center">
                                                                                <strong>Total Paid(Inc. GST)</strong></td>
                                                                            <td class="no-line text-right"><h4 class="m-0">${{$saledetail[0]->orderpayment()->sum('paidAmount')}}</h4></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="border-top: 2px dotted #000;">
                                                                <p>
                                                                    Thank you for shopping at<br>
                                                                    Jainish Pty Ltd<br>
                                                                    - {{$saledetail[0]->store_name}}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-12" style="border-top: 2px dotted #000; border-bottom: 2px dotted #000;">
                                                                <p>
                                                                    1. Please choose carefully. We don't refund or exchange for
                                                                    change of mind or wrong selections (unless a statutory
                                                                    cooling-off period applies)<br>
                                                                    2.Please keep your receipt as
                                                                    proof of purchase. If we need to help you with an issue,
                                                                    please keep your receipt as proof of purchase. File it away
                                                                    somewhere safe in case you need it.<br> 
                                                                    3. For Full Details visit:
                                                                    http://www.vodafone.com.au/aboutvodafone/legal/repairpolicy
                                                                </p>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->     

                
            </div>
            <!-- container-fluid -->

        </div>
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
    </div>
</div>
@endsection