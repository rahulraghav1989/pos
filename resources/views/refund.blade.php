@extends('main')

@section('content')
<div id="wrapper">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    @include('includes.topbar')

    @include('includes.sidebar')
    
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box" style="padding: 2px 0px;"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" style="margin-bottom: 5px;">
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
                            <!---CANCEL ORDER MODEL--->

                            
                            <!---CANCEL ORDER MODEL--->
                            <div class="card-body" style="padding-bottom: 0px;">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h4 class="page-title" style="padding: 0px; margin: 0px;">Refund</h4>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <form class="" action="{{route('cancelrefund')}}" method="post">
                                            <div class="form-group">
                                                @csrf
                                                <input type="hidden" name="refundinvoiceid" value="{{$invoicedata['getorderdetails']->refundInvoiceID}}">
                                                <button type="submit" class="btn btn-danger">Cancel Refund</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card" style="margin-bottom: 5px;">
                            <div class="card-body" style="padding-bottom: 0px;">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Refund Inv / Invoice</label>
                                            <input type="text" readonly="" class="form-control" value="{{$invoicedata['getorderdetails']->refundInvoiceID}}/{{$invoicedata['getorderdetails']->orderID}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Store</label>
                                            <input type="text" readonly="" class="form-control" value="{{$invoicedata['getorderdetails']->store_name}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Customer </label>
                                            <a href="" data-toggle="modal" data-target=".bs-example-modal-lgselectcustomer" data-backdrop="static" data-keyboard="false" style="/*border: 1px solid #CCC;*/padding: 3px 0px;border-radius: 3px;line-height: 1.5;">
                                                <textarea class="form-control" readonly="" style="width: 100%; height: calc(1.5em + .75rem + 2px);">{{$invoicedata['getorderdetails']->customertitle}} {{$invoicedata['getorderdetails']->customerfirstname}} {{$invoicedata['getorderdetails']->customerlastname}} {{$invoicedata['getorderdetails']->customermobilenumber}} </textarea>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Sale Type</label>
                                            <input type="text" class="form-control" readonly="" value="@if($invoicedata['getorderdetails']->orderType == 'InStore') In-Store @elseif($invoicedata['getorderdetails']->orderType == 'layby') Layby @endif">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Sale Note (Opt.)</label>
                                            <input type="text" name="salenote" class="form-control" value="{{$invoicedata['getorderdetails']->salenote}}" readonly="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Refund Note (Opt.)</label>
                                            @csrf
                                            <input type="hidden" id="refundinvoiceid" class="form-control" value="{{$invoicedata['getorderdetails']->refundInvoiceID}}">
                                            <input type="text" name="refundnote" id="refundnote" class="form-control" value="{{$invoicedata['getorderdetails']->refundnote}}" placeholder="Type Here">
                                            <!-----Sale Note Ajax Request--->
                                            <script>
                                            $(document).ready(function(){

                                             $('#refundnote').blur(function(){
                                              var error_email = '';
                                              var username = $('#refundnote').val();
                                              var invoiceid = $('#refundinvoiceid').val();
                                              var _token = $('input[name="_token"]').val();
                                              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                              $.ajax({
                                                url:"{{ route('ajaxupdaterefundnote') }}",
                                                method:"POST",
                                                data:{username:username, invoiceid:invoiceid, _token:_token},
                                                success:function(result)
                                                {
                                                 
                                                }
                                               })
                                             });
                                             
                                            });
                                            </script>
                                            <!-----Sale Note Ajax Request--->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table  table-striped">
                                        <thead>
                                        <tr>
                                            <th>Barcode</th>
                                            <th data-priority="3">Product Name</th>
                                            <th data-priority="1">Item Price</th>
                                            <th data-priority="3">Discount</th>
                                            <th data-priority="6">Quantity</th>
                                            <th data-priority="6">Total</th>
                                            <th data-priority="6">Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        
                                        @foreach($invoicedata['getorderitems'] as $orderitems)
                                            @if($orderitems->plancode == "")
                                                <tr>
                                                    <td>{{$orderitems->barcode}}</td>
                                                    <td>
                                                        {{$orderitems->productname}}
                                                        <span style="font-size: 1em;color: #000;font-weight: 600;font-style: italic;">
                                                        @if($orderitems['productstock'] != "")
                                                            @if($orderitems['productstock']->productimei)
                                                            <br>
                                                            {{$orderitems['productstock']->productimei}}
                                                            @endif
                                                            @if($orderitems['productstock'] != "")
                                                            <br>
                                                            Sim: {{$orderitems['productstock']->simnumber}}
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        ${{$orderitems->salePrice}}
                                                        @if($orderitems->discountedType != "")
                                                        <br>
                                                        <strike style="color: #999;">${{$orderitems->spingst}}</strike>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($orderitems->discountedType == '')
                                                        <a class="discount" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false">0.00</a>
                                                        @elseif($orderitems->discountedType == 'Percentage')
                                                        <a class="discount" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false">{{$orderitems->discount}}%</a>
                                                        @elseif($orderitems->discountedType == 'Amount')
                                                        <a class="discount" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false">${{$orderitems->discount}}</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <!---Quantity Modal--->
                                                        <div class="modal fade quantitymodal{{$orderitems->refunditemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{$orderitems->productname}}</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{route('updaterefundquantity')}}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="itemid" value="{{$orderitems->refunditemID}}">
                                                                            <div class="form-group">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <label>Quantity</label>
                                                                                        <input type="number" name="quantity" value="{{$orderitems->quantity}}" class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-12 text-right" style="margin-top: 20px;">
                                                                                        <button type="submit" class="btn btn-primary">Apply</button>
                                                                                        <button type="button" class="btn btn-light" class="close" data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div><!-- /.modal-content -->
                                                            </div><!-- /.modal-dialog -->
                                                        </div>
                                                        <!---Quantity Modal--->
                                                        <a class="discount">{{$orderitems->quantity}}</a>
                                                    </td>
                                                    <td>
                                                        {{$orderitems->subTotal}}
                                                        @if($orderitems->discountedType != "")
                                                        <br>
                                                        <strike style="color: #999;">${{$orderitems->spingst * $orderitems->quantity}}</strike>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <!---Delete Modal--->
                                                        <div class="modal fade deletemodal{{$orderitems->refunditemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0" id="myLargeModalLabel">Remove Item</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h5>You are about to remove <span class="badge badge-primary">{{$orderitems->productname}}</span> from invoice</h5>
                                                                        <h5>Press <span class="badge badge-primary">Yes</span> to continue OR Cancel it</h5>
                                                                        <form action="{{route('refundinvoiceitemdelete')}}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="itemid" value="{{$orderitems->refunditemID}}">
                                                                            <input type="hidden" name="quantity" value="{{$orderitems->quantity}}">
                                                                            <div class="form-group">
                                                                                <div class="col-md-12 text-right" style="margin-top: 20px;">
                                                                                    <button type="submit" class="btn btn-primary">Yes</button>
                                                                                    <button type="button" class="btn btn-light" class="close" data-dismiss="modal">Close</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div><!-- /.modal-content -->
                                                            </div><!-- /.modal-dialog -->
                                                        </div>
                                                        <!---Delete Modal--->
                                                        <a href="" class="btn btn-sm btn-danger" data-toggle="modal" data-target=".deletemodal{{$orderitems->refunditemID}}" data-backdrop="static" data-keyboard="false"><i class="far fa-trash-alt"></i></a>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        ${{$orderitems->ppingst}},
                                                        {{$orderitems->planname}},
                                                        {{$orderitems->pcname}}-{{$orderitems->planpropositionname}}

                                                        <br>
                                                        <span style="font-size: 1em;color: #000;font-weight: 600;font-style: italic;">
                                                        @if($orderitems->plandetails)
                                                            @php
                                                            $plandetails = explode(',', $orderitems->plandetails);
                                                            foreach($plandetails as $plandetailss)
                                                            {
                                                                echo $plandetailss."<br>";
                                                            }
                                                            @endphp
                                                        @endif
                                                        @if($orderitems->stockgroupname)
                                                        {{$orderitems->stockgroupname}}
                                                        @endif
                                                        @if($orderitems->stockID != "")
                                                        Sim: {{App\productstock::where('psID', $orderitems->stockID)->pluck('simnumber')->first()}}
                                                        @endif
                                                        </span>
                                                    </td>
                                                    <td>{{$orderitems->spingst}}</td>
                                                    <td>
                                                        @if($orderitems->discountedType == '')
                                                        <a class="discount">0.00</a>
                                                        @endif
                                                    </td>
                                                    <td>{{$orderitems->quantity}}</td>
                                                    <td>{{$orderitems->subTotal}}</td>
                                                    <td>
                                                        <!---Delete Modal--->
                                                        <div class="modal fade deletemodal{{$orderitems->refunditemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0" id="myLargeModalLabel">Remove Item</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h5>You are about to remove <span class="badge badge-primary">{{$orderitems->productname}}</span> from invoice</h5>
                                                                        <h5>Press <span class="badge badge-primary">Yes</span> to continue OR Cancel it</h5>
                                                                        <form action="{{route('refundinvoiceitemdelete')}}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="itemid" value="{{$orderitems->refunditemID}}">
                                                                            <input type="hidden" name="quantity" value="{{$orderitems->quantity}}">
                                                                            <div class="form-group">
                                                                                <div class="col-md-12 text-right" style="margin-top: 20px;">
                                                                                    <button type="submit" class="btn btn-primary">Yes</button>
                                                                                    <button type="button" class="btn btn-light" class="close" data-dismiss="modal">Close</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div><!-- /.modal-content -->
                                                            </div><!-- /.modal-dialog -->
                                                        </div>
                                                        <!---Delete Modal--->
                                                        <a href="" class="btn btn-sm btn-danger" data-toggle="modal" data-target=".deletemodal{{$orderitems->refunditemID}}" data-backdrop="static" data-keyboard="false"><i class="far fa-trash-alt"></i></a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card m-b-30">
                            <div class="card-body">
                                @if(session('paymentdata')['openconfirmpopup']==1)
                                <script type="text/javascript">
                                    $(window).on('load',function(){
                                        $('#paymentconfirmation').modal({backdrop: 'static', keyboard: false}, 'show');
                                    });
                                </script>
                                <div id="paymentconfirmation" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg" style="max-width: 950px;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myLargeModalLabel">Confirm Payment and Order</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>You are closing the invoice by taking complete amount for <span class="badge badge-primary">{{session('paymentdata')['refundinvoiceid']}}</span></h4>
                                                <h5>Click on <span class="badge badge-success">Process</span> OR Cancel it.</h5>

                                                <form action="{{route('refundconfirmfullpayment')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="refundinvoiceid" value="{{session('paymentdata')['refundinvoiceid']}}">
                                                    <input type="hidden" name="payingamount" value="{{session('paymentdata')['payingamount']}}">
                                                    <input type="hidden" name="totalitemamount" value="{{session('paymentdata')['totalitemamount']}}">
                                                    <input type="hidden" name="paymenttype" value="{{session('paymentdata')['paymenttype']}}">
                                                    
                                                    <div class="form-group text-right">
                                                        <button type="submit" class="btn btn-success">Process</button>
                                                        <button type="submit" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div>
                                @endif
                                <form class="" action="{{route('refundorderpayment')}}" method="post">
                                    @csrf
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @foreach($invoicedata['paymentoptions'] as $key => $paymentoptions)
                                        <label class="btn btn-outline-primary waves-effect waves-light {{$key == 0 ? 'active': ''}}" style="width: 100%; margin: 2px 0px;">
                                            <input type="radio" name="paymenttype" id="option{{$paymentoptions->poID}}" {{$key == 0 ? 'checked': ''}} value="{{$paymentoptions->paymentname}}"> {{$paymentoptions->paymentname}}
                                        </label>
                                        @endforeach
                                        @if($invoicedata['getorderdetails']->onAccountPayment == '1')
                                            @foreach($invoicedata['paymentoptionaccount'] as $paymentoptionaccount)
                                            <label class="btn btn-outline-primary waves-effect waves-light" style="width: 100%; margin: 2px 0px;">
                                                <input type="radio" name="paymenttype" id="option{{$paymentoptionaccount->poID}}" value="{{$paymentoptionaccount->paymentname}}"> {{$paymentoptionaccount->paymentname}}
                                            </label>
                                            @endforeach
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </span>
                                            <input id="demo2" type="text" name="payingamount" value="{{$invoicedata['getorderitemssum'] - $invoicedata['orderpaidamount']}}" name="demo2" class="form-control">
                                            <input type="hidden" name="refundinvoiceid" value="{{$invoicedata['getorderdetails']->refundInvoiceID}}">
                                            <input type="hidden" name="totalitemamount" value="{{$invoicedata['getorderitemssum']}}">
                                            <input type="hidden" name="saletype" id="saletypevalue">
                                            <span class="input-group-btn input-group-append">
                                                <button type="submit" class="btn btn-primary submit" type="button">Pay</button>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <div>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                Submit
                                            </button>
                                            <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                                                Cancel
                                            </button>
                                        </div>
                                    </div> -->
                                </form>
                                <table width="100%">
                                    <thead>
                                        <th>Payment Type</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-bold">Discount</td>
                                            <td class="text-bold">
                                                @if($invoicedata['getorderdiscountsum'] == "")
                                                $0
                                                @else
                                                ${{$invoicedata['getorderdiscountsum']}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold">Sale Total</td>
                                            <td class="text-bold">${{$invoicedata['getorderitemssum']}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold">Total Paid</td>
                                            <td class="text-bold">
                                                @if($invoicedata['orderpaidamount'] != "")
                                                    ${{$invoicedata['orderpaidamount']}}
                                                @else
                                                    $0
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold">Remaining</td>
                                            <td class="text-bold">
                                                ${{$invoicedata['getorderitemssum'] - $invoicedata['orderpaidamount']}}
                                            </td>
                                        </tr>
                                        <tr class="grandtotal">
                                            <td class="text-bold">Paid</td>
                                            <td class="text-bold">
                                                @if($invoicedata['orderpaidamount'] != "")
                                                    ${{$invoicedata['orderpaidamount']}}
                                                @else
                                                    $0
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <!-- content -->

        @include('includes.footer')

    </div>
    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->
</div>
@endsection