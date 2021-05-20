@extends('main')

@section('content')
<div id="wrapper">
    @include('includes.topbar')

    @include('includes.sidebar')
    <script type="text/javascript">
        
    function codespeedy(){
      var print_div = document.getElementById("printthis");
        var print_area = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        print_area.document.write(print_div.innerHTML);
        print_area.document.write('<link href="{{ asset("posview") }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">');
        print_area.document.write('<link href="{{ asset("posview") }}/assets/css/metismenu.min.css" rel="stylesheet" type="text/css">');
        print_area.document.write('<link href="{{ asset("posview") }}/assets/css/icons.css" rel="stylesheet" type="text/css">');
        print_area.document.write('<link href="{{ asset("posview") }}/assets/css/style.css" rel="stylesheet" type="text/css">');
        print_area.document.write('<link href="{{ asset("posview") }}/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />');
        print_area.document.write('<link href="{{ asset("posview") }}/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />');
        print_area.document.write('<link href="{{ asset("posview") }}/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />');
        
        print_area.document.close();
        print_area.focus();
        print_area.print();
        print_area.close();
        // This is the code print a particular div element
            }
    </script>

    <div class="content-page">
                <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Invoice</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sale History</a></li>
                                <li class="breadcrumb-item active">Sale Detail</li>
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
                                            <div class="col-12">
                                                @if($saledetail[0]->orderstatus == 1)
                                                @if(session('loggindata')['loggeduserpermission']->refund=='Y')
                                                <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">Refund Item</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                        <tr>
                                                                            <td><strong>#</strong></td>
                                                                            <td><strong>Product</strong></td>
                                                                            <td><strong>Item Price</strong></td>
                                                                            <td><strong>Quantity</strong></td>
                                                                            <td><strong>Reason</strong></td>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                                        <form action="{{route('refunditem')}}" method="post">
                                                                        @csrf
                                                                        @foreach($saledetail as $orderitem)
                                                                            @if($orderitem->plancode == "")
                                                                                @if($orderitem->quantity > 0)
                                                                                <tr>
                                                                                    <td>
                                                                                        
                                                                                        <input type="checkbox" name="itemid[]" value="{{$orderitem->orderitemID}}">
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$orderitem->productname}}
                                                                                        <span style="font-size: 1em;color: #000;font-weight: 600;font-style: italic;">
                                                                                        @if($orderitem->productimei)
                                                                                        <br>
                                                                                        {{$orderitem->productimei}}
                                                                                        @endif
                                                                                        </span>
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$orderitem->salePrice}}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number" name="refundquantity_{{$orderitem->orderitemID}}" value="{{$orderitem->quantity}}" min="1" max="{{$orderitem->quantity}}" style="width: 75px;">
                                                                                        <input type="hidden" name="soldquantity_{{$orderitem->orderitemID}}" value="{{$orderitem->quantity}}">
                                                                                    </td>
                                                                                    <td>
                                                                                        <select class="form-control" name="reason_{{$orderitem->orderitemID}}">
                                                                                            <option value="Other">Other</option>
                                                                                            <option value="Faulty">Faulty</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                @endif
                                                                            @else
                                                                                @if($orderitem->quantity > 0)
                                                                                    <tr>
                                                                                        <td>
                                                                                            
                                                                                            <input type="checkbox" name="itemid[]" value="{{$orderitem->orderitemID}}">
                                                                                        </td>
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
                                                                                            @if($orderitem->stockgroupname)
                                                                                            {{$orderitem->stockgroupname}}
                                                                                            @endif
                                                                                            </span>
                                                                                        </td>
                                                                                        <td>
                                                                                            {{$orderitem->salePrice}}
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="number" name="refundquantity_{{$orderitem->orderitemID}}" value="{{$orderitem->quantity}}" min="1" max="{{$orderitem->quantity}}" style="width: 75px;">
                                                                                            <input type="hidden" name="soldquantity_{{$orderitem->orderitemID}}" value="{{$orderitem->quantity}}">
                                                                                        </td>
                                                                                        <td>
                                                                                            <select class="form-control" name="reason_{{$orderitem->orderitemID}}">
                                                                                                <option value="Other">Other</option>
                                                                                                <option value="Faulty">Faulty</option>
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                        <tr>
                                                                            <td colspan="5" style="text-align: center;">
                                                                                <input type="hidden" name="invoiceid" value="{{$saledetail[0]->orderID}}">
                                                                                <input type="hidden" name="storeid" value="{{$saledetail[0]->storeID}}">
                                                                                <button type="submit" class="btn btn-primary">Refund Products</button>
                                                                                <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">Cancel</button>
                                                                            </td>
                                                                        </tr>
                                                                        </form>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div>
                                                <div class="text-right">
                                                    <button class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false">Refund</button>
                                                </div>
                                                @endif
                                                @elseif($saledetail[0]->orderstatus == 0)
                                                @if(session('loggindata')['loggeduserpermission']->newsale=='Y')
                                                <div class="text-right">
                                                    <a href="../newsale/{{$saledetail[0]->orderID}}" class="btn btn-primary">Continue Sale</a>
                                                </div>
                                                @endif
                                                @endif
                                            </div>
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
                                                            {{$saledetail[0]->customer['customertitle']}} {{$saledetail[0]->customer['customerfirstname']}} {{$saledetail[0]->customer['customerlastname']}}<br>
                                                            {{$saledetail[0]->customer['customermobilenumber']}}
                                                        </address>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <address>
                                                            <strong>Store:</strong><br>
                                                            {{$saledetail[0]->store_name}}<br>
                                                            {{$saledetail[0]->store_address}}<br>
                                                            {{$saledetail[0]->store_contact}}<br>
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
                                                            {{$saledetail[0]->name}}
                                                        </address>

                                                        @if(!empty($saledetail[0]->refundorder))
                                                        <address>
                                                            <strong>Refund Invoice:</strong>
                                                            @foreach($saledetail as $refundinv)
                                                                <strong><a href="../refundinvoice/{{$refundinv->refundorder['refundInvoiceID']}}" style="color: #007bff;">{{$refundinv->refundorder['refundInvoiceID']}}</a></strong><br>
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
                                                                                    </span>
                                                                                </td>
                                                                                <td>${{$orderitem->salePrice}}</td>
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
                                                                                    ${{$orderitem->ppingst}},
                                                                                    {{$orderitem->planname}},
                                                                                    {{$orderitem->pcname}}-{{$orderitem->planpropositionname}}
                                                                                </span>
                                                                                <br>
                                                                                <span style="font-size: 0.9em;color: #000;font-weight: normal;font-style: italic;">
                                                                                @if($orderitem->plandetails)
                                                                                    @php
                                                                                    $plandetails = explode(',', $orderitem->plandetails);
                                                                                    foreach($plandetails as $plandetailss)
                                                                                    {
                                                                                        echo $plandetailss."<br>";
                                                                                    }
                                                                                    @endphp
                                                                                @endif
                                                                                @if($orderitem->stockgroupname)
                                                                                {{$orderitem->stockgroupname}}
                                                                                @endif
                                                                                </span>
                                                                            </td>
                                                                            <td>${{$orderitem->salePrice}}</td>
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
                                                                                <strong>Total Paid</strong></td>
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
                                            <div class="col-md-12">
                                                <div class="d-print-none mo-mt-2">
                                                    <div class="float-right">
                                                        <form action="{{route('sendcustomerinvoice')}}" method="post">
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
    </div>
</div>
@endsection