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
                            <h4 class="page-title">Refund Invoice</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Refund Detail</li>
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
                                            @if ($errors->any())
                                                @foreach ($errors->all() as $error)
                                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                        {{$error}}
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if(session()->has('success'))
                                                <div class="alert alert-success alert-dismissible" role="alert" style="margin-top: 10px;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    {{ session()->get('success') }}
                                                </div>
                                            @endif
                                            @if(session()->has('error'))
                                            <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 10px;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                {{ session()->get('error') }}
                                            </div>
                                            @endif 
                                            <div class="row">
                                                <div class="col-12">
                                                @if($saledetail[0]->refundStatus == 0)
                                                @if(session('loggindata')['loggeduserpermission']->refund=='Y')
                                                <div class="text-right">
                                                    <a href="../refund/{{$saledetail[0]->refundInvoiceID}}" class="btn btn-primary">Continue Refund</a>
                                                </div>
                                                @endif
                                                @endif
                                            </div>
                                                <div class="col-md-12">
                                                    <img src="{{ asset('posview') }}/assets/images/Vodafone-Logo.png" width="200">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="invoice-title">
                                                        <h4 class="m-t-0 font-16">
                                                            <strong>Refund Date: @php echo date('d-m-Y H:i:s', strtotime($saledetail[0]->created_at)) @endphp</strong>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 text-right">
                                                    <div class="invoice-title">
                                                        <h4 class="font-16">
                                                            <strong>Refund Invoice # <span style="color: #576ddf;">{{$saledetail[0]->refundInvoiceID}}</span></strong> | 
                                                            <strong>Sale Invoice # <span style="color: #576ddf;">{{$saledetail[0]->orderID}}</span></strong>
                                                        </h4>
                                                    </div>
                                                </div>
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
                                                    <address>
                                                        <strong>Refund Rep:</strong><br>
                                                        {{$saledetail[0]->refundbyuser['username']}}
                                                    </address>
                                                </div>
                                                <div class="col-6 m-t-30 text-right">
                                                    <address>
                                                        <strong>Sale Type:</strong><br>
                                                        {{$saledetail[0]->orderType}}
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-12">
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
                                                                        @if($orderitem->simnumber !="")
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
                                                                    <td>-{{$orderitem->quantity}}</td>
                                                                    <td class="text-right">-${{$orderitem->subTotal}}</td>
                                                                </tr>
                                                                @else
                                                                <tr>
                                                                <td></td>
                                                                <td>
                                                                    ${{$orderitem->ppingst}},
                                                                    {{$orderitem->planname}},
                                                                    {{$orderitem->pcname}}-{{$orderitem->planpropositionname}}

                                                                    <br>
                                                                    <span style="font-size: 1em;color: #000;font-weight: 600;font-style: italic;">
                                                                    @if($orderitem->plandetails)
                                                                        @php
                                                                        $plandetails = explode(',', $orderitem->plandetails);
                                                                        foreach($plandetails as $plandetailss)
                                                                        {
                                                                            echo $plandetailss."<br>";
                                                                        }
                                                                        @endphp
                                                                    @endif
                                                                    @if($orderitem->simnumber != "")
                                                                    Sim: {{App\productstock::where('psID', $orderitem->stockID)->pluck('simnumber')->first()}}
                                                                    @endif
                                                                    @if($orderitem->stockgroupname)
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
                                                                <td>-{{$orderitem->quantity}}</td>
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
                                                                    -${{$saledetail->sum('subTotal')}}
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
                                                                    -${{$payments[0]->where('refundInvoiceID', $saledetail[0]->refundInvoiceID)->where('paymentType', $payments[0]->paymentType)->sum('paidAmount')}}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line text-center">
                                                                    <strong>Total Paid</strong></td>
                                                                <td class="no-line text-right"><h4 class="m-0">-${{$saledetail[0]->orderpayment()->sum('paidAmount')}}</h4></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
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
                                                            3. For Full Details visti:
                                                            http://www.vodafone.com.au/aboutvodafone/legal/repairpolicy
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="d-print-none mo-mt-2">
                                                    <div class="float-right">
                                                        <form action="{{route('sendcustomerrefundinvoice')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="customerid" value="{{$saledetail[0]->customer['customerID']}}">
                                                            <input type="text" name="customeremail" value="{{$saledetail[0]->customer['customeremail']}}">
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-envelope"></i></button>
                                                            <a href="#" onclick="codespeedy()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                        </form>
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