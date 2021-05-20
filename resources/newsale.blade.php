@extends('main')

@section('content')
<div id="wrapper">
    <!-- <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script> -->
    @include('includes.topbar')

    @include('includes.sidebar')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".submit").click(function () {

                var books = $('#saletype');
                if (books.val() === '') {
                    alert("Please select an sale type");
                    $('#saletype').focus();

                    return false;
                }
                else 
                    $('#saletypevalue').val(books.val());
                    return books;
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".plansubmit").click(function () {

                var books = $('#customerdropdown');
                if (books.val() === '') {
                    alert("Please select an customer");
                    $('#customerdropdown').focus();

                    return false;
                }
                else 
                    return books;
            });
        });
    </script>
    <script>
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>
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

                            @if(session('deletedata')['opendeletemodel']==1)
                            <script type="text/javascript">
                                $(window).on('load',function(){
                                    $('#cancelorder').modal({backdrop: 'static', keyboard: false},'show');
                                });
                            </script>
                            <div id="cancelorder" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">Products on barcode</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if(session('deletedata')['errormsg']!="")
                                            <div class="alert alert-danger">
                                                {{session('deletedata')['errormsg']}}
                                            </div>
                                            @endif
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                            @endif
                            <!---CANCEL ORDER MODEL--->
                            <div class="card-body" style="padding-bottom: 0px;">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h4 class="page-title" style="padding: 0px; margin: 0px;">New Sale</h4>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <form class="" action="{{route('cancelorder')}}" method="post">
                                            <div class="form-group">
                                                @csrf
                                                <input type="hidden" name="orderid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                <button type="submit" class="btn btn-primary">Cancel Sale</button>
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
                                <!---Customer Add Model-->
                                <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0">Add a new customer</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="" action="{{route('customeradd')}}" method="post" novalidate="">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>Customer Type *</label>
                                                        <select name="customertype" class="form-control" required="">
                                                            <option value="Consumer">Consumer</option>
                                                            <option value="Business">Business</option>
                                                        </select>
                                                    </div>
                                                    <div class="">
                                                        <label>Person Detail</label>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>Title</label>
                                                                <select name="title" class="form-control" required="">
                                                                    <option value="">No Title</option>
                                                                    <option value="Mr">Mr</option>
                                                                    <option value="Mrs">Mrs</option>
                                                                    <option value="Miss">Miss</option>
                                                                    <option value="Ms">Ms</option>
                                                                    <option value="Dr">Dr</option>
                                                                    <option value="Prof">Prof</option>
                                                                    <option value="Other">Other</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>First Name</label>
                                                                <input type="text" name="firstname" class="form-control" placeholder="First Name" required="">
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label>Last Name</label>
                                                                <input type="text" name="lastname" class="form-control" required="" placeholder="Last Name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Mobile Number</label>
                                                                <input type="number" name="mobilenumber" class="form-control" placeholder="Mobile Number" required="" onkeypress="return isNumber(event)">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Home Number</label>
                                                                <input type="number" name="homenumber" class="form-control" placeholder="Home Number" onkeypress="return isNumber(event)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Alt. Contact Number</label>
                                                                <input type="number" name="altcontactnumber" class="form-control" placeholder="Alt. Contact Number" onkeypress="return isNumber(event)">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Date Of Birth</label>
                                                                <input type="date" name="dob" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="text" name="email" class="form-control" placeholder="Email@Email.com">
                                                    </div>
                                                    <div class="">
                                                        <label>Business Detail</label>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Company/Business Name</label>
                                                                <input type="text" name="businessname" class="form-control" placeholder="Company/Business Name">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>ACN/ABN</label>
                                                                <input type="text" name="acnabn" class="form-control" placeholder="ACN/ABN">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Company/Business Email</label>
                                                                <input type="text" name="businessemail" class="form-control" placeholder="Company/Business Email">    
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Website</label>
                                                                <input type="text" name="businesswebsite" class="form-control" placeholder="Website">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="checkbox" name="onaccount"> <label>On Account</label>    
                                                    </div>
                                                    <div class="">
                                                        <label>Address</label>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Address</label>
                                                                <input type="text" name="address" class="form-control" placeholder="Address">    
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Post Code</label>
                                                                <input type="text" name="postcode" class="form-control" placeholder="Post Code">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Suburb Name</label>
                                                                <input type="text" name="suburbname" class="form-control" placeholder="Suburb Name">    
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>State/Province</label>
                                                                <input type="text" name="state" class="form-control" placeholder="State/Province">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Note</label>
                                                        <input type="text" name="note" class="form-control" placeholder="Note">
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <div>
                                                            <input type="hidden" name="invoiceid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                Submit
                                                            </button>
                                                            <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div>
                                <!---Customer Add Model-->
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Invoice</label>
                                            <input type="text" readonly="" id="invoiceid" class="form-control" value="{{$invoicedata['getorderdetails']->orderID}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Store</label>
                                            <input type="text" readonly="" class="form-control" value="{{$invoicedata['getstore']->store_name}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label style="width: 100%;">Customer <span style="margin-left: 60%;"><a href="" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false"><i class="fas fa-user-plus"></i></a></span></label>
                                            
                                            <link href="{{ asset('posview') }}/assets/css/select2.min.css" rel="stylesheet" type="text/css">

                                            <script src="{{ asset('posview') }}/assets/js/select2.min.js"></script>
                                            <script type="text/javascript">
                                                $(document).ready(function() {
                                                    $(".js-example-basic-single").select2();
                                                });
                                            </script>
                                            <script type="text/javascript">
                                            $(document).ready(function(){
                                            $('#customerdropdown').change(function(){
                                                   var id = $(this).val();
                                                   $.ajax({
                                                      url : "{{ route( 'savecustomer' ) }}",
                                                      data: {
                                                        "_token": "{{ csrf_token() }}",
                                                        "id": id,
                                                        "orderid": "{{$invoicedata['getorderdetails']->orderID}}"
                                                        },
                                                      type: 'post',
                                                      dataType: 'json',
                                                      success: function( result )
                                                      {
                                                        location.reload(true);
                                                      },
                                                      error: function()
                                                     {
                                                         //handle errors
                                                        location.reload(true);
                                                     }
                                                   });
                                                });
                                            });
                                            </script>
                                            <select class="js-example-basic-single form-control" id="customerdropdown">
                                                <option value="">SELECT CUSTOMER</option>
                                                @foreach($invoicedata['getcustomer'] as $customer)
                                                <option value="{{$customer->customerID}}" @if($invoicedata['getorderdetails']->customerID == $customer->customerID) SELECTED='SELECTED' @endif>
                                                    @if($customer->customerbusinessname != "")
                                                    {{$customer->customerbusinessname}}
                                                    {{$customer->customerbusinessemail}}
                                                    {{$customer->customerfirstname}} {{$customer->customerlastname}}
                                                    {{$customer->customermobilenumber}}
                                                    @else
                                                    {{$customer->customerfirstname}} {{$customer->customerlastname}}
                                                    {{$customer->customermobilenumber}}
                                                    @endif
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Sale Type</label>
                                            <select name="saletype" id="saletype" class="form-control">
                                                <option value="InStore" @if($invoicedata['getorderdetails']->orderType == 'InStore') SELECTED='SELECTED' @endif>In-Store</option>
                                                <option value="layby" @if($invoicedata['getorderdetails']->orderType == 'layby') SELECTED='SELECTED' @endif>Layby</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Sale Note (Opt.)</label>
                                            @csrf
                                            <input type="text" name="salenote" id="salenote" class="form-control" value="{{$invoicedata['getorderdetails']->salenote}}" placeholder="Type Here">
                                            <!-----Sale Note Ajax Request--->
                                            <script>
                                            $(document).ready(function(){

                                             $('#salenote').blur(function(){
                                              var error_email = '';
                                              var username = $('#salenote').val();
                                              var invoiceid = $('#invoiceid').val();
                                              var _token = $('input[name="_token"]').val();
                                              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                              $.ajax({
                                                url:"{{ route('ajaxupdatesalenote') }}",
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
                    <div class="col-lg-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Add By Barcode</label>
                                            <!---Multi Barcode Product Modal--->
                                            @if(session('productdata')['multibarcodeopenmodel']==1)
                                            <script type="text/javascript">
                                                $(window).on('load',function(){
                                                    $('#multibarcode').modal({backdrop: 'static', keyboard: false},'show');
                                                });
                                            </script>
                                            <div id="multibarcode" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">Products on barcode</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table id="datatable1" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                <thead>
                                                                <tr>
                                                                    <th>Barcode</th>
                                                                    <th>Product Name</th>
                                                                    <th>Supplier</th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach(session('productdata')['getproduct'] as $products)
                                                                    <tr>
                                                                        <td>{{$products->barcode}}</td>
                                                                        <td>
                                                                            {{$products->productname}}
                                                                            @if($products->productbrand['brandname']!='')
                                                                            <br>
                                                                            {{$products->productbrand['brandname']}}
                                                                            @endif
                                                                            @if($products->productcolour['colourname']!='')
                                                                            <br>
                                                                            {{$products->productcolour['colourname']}}
                                                                            @endif
                                                                            @if($products->productmodel['modelname']!='')
                                                                            <br>
                                                                            {{$products->productmodel['modelname']}}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{$products->productsupplier['suppliername']}}</td>
                                                                        <td>
                                                                            <form action="{{route('addbyproductid')}}" method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="productid" value="{{$products->productID}}">
                                                                                <input type="hidden" name="orderid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                                                <input type="hidden" name="quantity" value="{{session('productdata')['quantity']}}">
                                                                                <button type="submit" class="btn btn-light">Choose</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            @endif
                                            <!---Multi Barcode Product Modal--->
                                            
                                            <form action="{{route('addbybarcode')}}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Quantity</label>
                                                        <input type="number" name="quantity" class="form-control" value="1" required="">    
                                                    </div>
                                                    <div class="col-md-10">
                                                        <label style="width: 100%;">Barcode</label>
                                                        <input type="text" name="barcode" class="form-control" placeholder="Barcode" autocomplete="off" required="" style="width: 85%;float: left;">
                                                        <input type="hidden" name="orderid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                        <button type="submit" class="btn btn-primary" style="float: left;">Add</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Add By Stock Code</label>
                                            <!---Multi Barcode Product Modal--->
                                            @if(session('stockcodeproductdata')['multistockcodeopenmodel']==1)
                                            <script type="text/javascript">
                                                $(window).on('load',function(){
                                                    $('#multistockcode').modal({backdrop: 'static', keyboard: false},'show');
                                                });
                                            </script>
                                            <div id="multistockcode" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">Products on stock code</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table id="datatable1" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                <thead>
                                                                <tr>
                                                                    <th>Stock Code</th>
                                                                    <th>Product Name</th>
                                                                    <th>Supplier</th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach(session('stockcodeproductdata')['getproduct'] as $products)
                                                                    <tr>
                                                                        <td>{{$products->stockcode}}</td>
                                                                        <td>
                                                                            {{$products->productname}}
                                                                            @if($products->productbrand['brandname']!='')
                                                                            <br>
                                                                            {{$products->productbrand['brandname']}}
                                                                            @endif
                                                                            @if($products->productcolour['colourname']!='')
                                                                            <br>
                                                                            {{$products->productcolour['colourname']}}
                                                                            @endif
                                                                            @if($products->productmodel['modelname']!='')
                                                                            <br>
                                                                            {{$products->productmodel['modelname']}}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{$products->productsupplier['suppliername']}}</td>
                                                                        <td>
                                                                            <form action="{{route('addbyproductid')}}" method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="productid" value="{{$products->productID}}">
                                                                                <input type="hidden" name="orderid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                                                <input type="hidden" name="quantity" value="{{session('productdata')['quantity']}}">
                                                                                <button type="submit" class="btn btn-light">Choose</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            @endif
                                            <!---Multi Barcode Product Modal--->
                                            <form action="{{route('addstockbyid')}}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Quantity</label>
                                                        <input type="number" name="quantity" class="form-control" value="1" required="">    
                                                    </div>
                                                    <div class="col-md-10">
                                                        <label style="width: 100%;">Stock Code</label>
                                                        <input type="text" name="stockcode" class="form-control" placeholder="Stock Code" required="" autocomplete="off" style="width: 85%;float: left;">
                                                        <input type="hidden" name="orderid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                        <button type="submit" class="btn btn-primary" style="float: left;">Add</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!---All Product Search--->
                                                    <div class="modal fade allsearchproduct" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0" id="myLargeModalLabel">All Products</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="card m-b-30">
                                                                        <div class="card-body">
                                                                            <ul class="nav nav-pills nav-justified" role="tablist">
                                                                                @foreach($invoicedata['productcategory']->groupBy('categoryID') as $pc)
                                                                                    
                                                                                <li class="nav-item waves-effect waves-light">
                                                                                    <a class="nav-link @php
                                                                                    if($invoicedata['productcategory'][0]->categoryID == $pc[0]->categoryID)
                                                                                    {
                                                                                        echo $tabclass = 'active';
                                                                                    }
                                                                                    @endphp" data-toggle="tab" href="#home-1{{$pc[0]->categoryID}}" role="tab">
                                                                                        <span class="d-none d-md-block">{{$pc[0]->categoryname}}</span>
                                                                                        <span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span> 
                                                                                    </a>
                                                                                </li>
                                                                                @endforeach
                                                                            </ul>
                                            
                                                                            <!-- Tab panes -->
                                                                            <div class="tab-content">
                                                                                @foreach($invoicedata['productcategory']->groupBy('categoryID') as $pc)
                                                                                <div class="tab-pane p-3 @php
                                                                                    if($invoicedata['productcategory'][0]->categoryID == $pc[0]->categoryID)
                                                                                    {
                                                                                        echo $tabclass = 'active';
                                                                                    }
                                                                                    @endphp" id="home-1{{$pc[0]->categoryID}}" role="tabpanel">
                                                                                    <table id="datatable{{$pc[0]->categoryID}}" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>Barcode</th>
                                                                                            <th>Stock Code</th>
                                                                                            <th>Product Name</th>
                                                                                            <th>Quantity</th>
                                                                                            <th>Select</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @foreach($pc as $pcp)
                                                                                            <tr>
                                                                                                <td>{{$pcp->barcode}}</td>
                                                                                                <td>{{$pcp->stockcode}}</td>
                                                                                                <td>{{$pcp->productname}}</td>
                                                                                                <td>
                                                                                                    @if($pcp->productquantity == "")
                                                                                                    0
                                                                                                    @else
                                                                                                    {{$pcp->productquantity}}
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    <form action="{{route('addallbyproductid')}}" method="post">
                                                                                                        @csrf
                                                                                                        <input type="hidden" name="productid" value="{{$pcp->productID}}">
                                                                                                        <input type="hidden" name="orderid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                                                                        <input type="hidden" name="quantity" value="1">
                                                                                                        <button type="submit" class="btn btn-primary">SELECT</button>
                                                                                                    </form>
                                                                                                </td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                @endforeach
                                                                            </div>
                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
                                                    <!---All Product Search--->
                                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect waves-light" data-toggle="modal" data-target=".allsearchproduct" data-backdrop="static" data-keyboard="false"><i class="fas fa-luggage-cart" style="color: red; font-size: 1.3em;"></i> Search Products</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <!---Recharge Model--->
                                                    <div class="modal fade recharge" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0" id="myLargeModalLabel">Recharge</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="card m-b-30">
                                                                        <div class="card-body">
                                                                            <table id="datatable20" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>Barcode</th>
                                                                                    <th>Stock Code</th>
                                                                                    <th>Product Name</th>
                                                                                    <th>Select</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($invoicedata['recharge'] as $recharge)
                                                                                    <tr>
                                                                                        <td>{{$recharge->barcode}}</td>
                                                                                        <td>{{$recharge->stockcode}}</td>
                                                                                        <td>{{$recharge->productname}}</td>
                                                                                        <td>
                                                                                            <form action="{{route('addrecharge')}}" method="post">
                                                                                                @csrf
                                                                                                <input type="hidden" name="productid" value="{{$recharge->productID}}">
                                                                                                <input type="hidden" name="orderid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                                                                <input type="hidden" name="quantity" value="1">
                                                                                                <button type="submit" class="btn btn-primary">SELECT</button>
                                                                                            </form>
                                                                                        </td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
                                                    <!---Recharge Model--->
                                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect waves-light" data-toggle="modal" data-target=".recharge" data-backdrop="static" data-keyboard="false"><i class="fas fa-bolt" style="color: red; font-size: 1.3em;"></i> Recharge</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!--- Plan Stock Group Model--->
                                            @if(session('planstockdata')['openplanstockmodel']==1)
                                            <script type="text/javascript">
                                                $(window).on('load',function(){
                                                    $('#planstockgroup').modal({backdrop: 'static', keyboard: false},'show');
                                                });
                                            </script>
                                            <div id="planstockgroup" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog modal-lg" style="max-width: 950px;">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">Plan Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            
                                                            <form action="{{route('addplandetail')}}" method="post">
                                                            	@csrf
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Order Number *</label>
                                                                            <input type="text" name="ordernumber" placeholder="Type Here" class="form-control" value="{{session('planstockdata')['planorderid']}}" maxlength="15" required="">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Fulfilment Order</label>
                                                                            <input type="text" name="fulfilmentorder" placeholder="Type Here" class="form-control" value="{{session('planstockdata')['planfullfillid']}}">
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label>Mobile Number *</label>
                                                                            <input type="number" name="mobilenumber" placeholder="Type Here" class="form-control" required="" value="{{session('planstockdata')['planMobilenumber']}}">
                                                                        </div>
                                                                        @if(session('planstockdata')['showdropdown']==1)
                                                                        <div class="col-md-6">
                                                                            <link href="{{ asset('posview') }}/assets/css/select2.min.css" rel="stylesheet" type="text/css">
                                            
                                                                            <script src="{{ asset('posview') }}/assets/js/select2.min.js"></script>
                                                                            <script type="text/javascript">
                                                                                $(document).ready(function() {
                                                                                    $(".js-example-basic-single").select2();
                                                                                    $("#select2insidemodal").select2({
                                                                                        dropdownParent: $("#planstockgroup")
                                                                                      });
                                                                                    $("#select2insidemodal1").select2({
                                                                                        dropdownParent: $("#planstockgroup")
                                                                                      });
                                                                                });
                                                                            </script>
                                                                            <script type="text/javascript">
                                                                            function stockdevice(){
                                                                                var e = document.getElementById("select2insidemodal").value;
                                                                                //var strUser = e.options[e.selectedIndex].text;

                                                                                if(e != '')
                                                                                {
                                                                                    $('#select2insidemodal1').prop('disabled', 'disabled');
                                                                                }
                                                                                else
                                                                                {
                                                                                    $('#select2insidemodal1').prop('disabled', false);
                                                                                }
                                                                            }
                                                                            </script>
                                                                            <script type="text/javascript">
                                                                            function deffereddevice(){
                                                                                var e = document.getElementById("select2insidemodal1").value;
                                                                                //var strUser = e.options[e.selectedIndex].text;

                                                                                if(e != '')
                                                                                {
                                                                                    $('#select2insidemodal').prop('disabled', 'disabled');
                                                                                }
                                                                                else
                                                                                {
                                                                                    $('#select2insidemodal').prop('disabled', false);
                                                                                }
                                                                            }
                                                                            </script>
                                                                            <label>Device From Stock</label>
                                                                            <style type="text/css">
                                                                                .select2-results__options .select2-results__option
                                                                                {
                                                                                    font-size: 1.2em;
                                                                                    white-space: pre-line; 
                                                                                  }
                                                                                  .select2-results__options li:hover
                                                                                  {
                                                                                    border-bottom: thin dotted #333;
                                                                                  }
                                                                            </style>
                                                                            <select name="postpaiddevice" class="js-example-basic-single form-control devicedropdown" id="select2insidemodal" onchange="stockdevice(this)">
                                                                                <option value="">SELECT DEVICE</option>
                                                                                @foreach(session('planstockdata')['stockproducts'] as $device)
                                                                                <option value="{{$device->psID}}">
                                                                                    @if($device->productimei != "")
                                                                                    {{$device->producttypedata['producttypename']}}: {{$device->productimei}}
                                                                                    @endif
                                                                                    @if($device->productname != "")
                                                                                    {{$device->productname}}
                                                                                    @endif
                                                                                    @if($device->barcode)
                                                                                    {{$device->barcode}}
                                                                                    @endif
                                                                                </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Deferred Device</label>
                                                                            <select name="deferreddevice" class="js-example-basic-single form-control" id="select2insidemodal1" onchange="deffereddevice(this)">
                                                                                <option value="">SELECT DEVICE</option>
                                                                                @foreach(session('planstockdata')['deffereddevice'] as $deffereddevice)
                                                                                <option value="{{$deffereddevice->productID}}">
                                                                                    {{$deffereddevice->productname}}
                                                                                    {{$deffereddevice->barcode}}
                                                                                </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 text-center">
                                                                    <input type="hidden" name="planid" value="{{session('planstockdata')['planid']}}">
                                                                    <input type="hidden" name="orderid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                                    <input type="hidden" name="stockgroup" value="{{session('planstockdata')['stockgroups']}}">
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            @endif
                                            <!---Plan Stock Group Model--->
                                            <!---Search Result Plan Model--->
                                            @if(session('plandata')['openplanmodel']==1)
                                            <script type="text/javascript">
                                                $(window).on('load',function(){
                                                    $('#plansearchresult').modal({backdrop: 'static', keyboard: false}, 'show');
                                                });
                                            </script>
                                            <div id="plansearchresult" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog modal-lg" style="max-width: 90%;">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">Plans</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table id="datatable1" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                <thead>
                                                                <tr>
                                                                    <th>Plan Code</th>
                                                                    <th>Plan Name</th>
                                                                    <th>Plan Term</th>
                                                                    <th>Plan Type</th>
                                                                    <th>Plan Proposition</th>
                                                                    <th>Plan Category</th>
                                                                    <th>Stock Group</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach(session('plandata')['findplan'] as $plans)
                                                                    <tr>
                                                                        <td>{{$plans->plancode}}</td>
                                                                        <td>{{$plans->planname}}<br>{{$plans->planhandsettermname}}<br>{{$plans->ppingst}}</td>
                                                                        <td>{{$plans->plantermre['plantermname']}}</td>
                                                                        <td>{{$plans->plantypere['plantypename']}}</td>
                                                                        <td>{{$plans->planpropositionre['planpropositionname']}}</td>
                                                                        <td>{{$plans->plancategoryre['pcname']}}</td>
                                                                        <td>
                                                                            @if(session('plandata')['stockgroup'] != "")
                                                                            {{session('plandata')['stockgroup']->stockgroupname}}
                                                                            @else
                                                                            {{$plans->stockgroupname}}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <form action="{{route('addbyplanid')}}" method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="planid" value="{{$plans->planID}}">
                                                                                <input type="hidden" name="planstockgroup" value="@if(session('plandata')['stockgroup'] != ""){{session('plandata')['stockgroup']->stockgroupID}}@else{{$plans->stockgroupID}}@endif">
                                                                                <input type="hidden" name="invoiceid" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                                                <button type="submit" class="btn btn-light">SELECT</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            @endif
                                            <!---Search Result Plan Model--->
                                            <!--Plan Model--->
                                            <div class="modal fade planmodel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">Search For Plan</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{route('searchplan')}}" method="post">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label>Plan Type</label>
                                                                            <select name="plantype" class="form-control">
                                                                                @foreach($invoicedata['plantype'] as $plantype)
                                                                                <option value="{{$plantype->plantypeID}}">{{$plantype->plantypename}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Plan Stock Group</label>
                                                                            <select name="planstockgroup" class="form-control">
                                                                                <option value="">Plan Stock Group</option>
                                                                                @foreach($invoicedata['planstockgroup'] as $planstockgroup)
                                                                                <option value="{{$planstockgroup->stockgroupID}}">{{$planstockgroup->stockgroupname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Plan Category</label>
                                                                            <select name="plancategory" class="form-control">
                                                                                <option value="">Plan Category</option>
                                                                                @foreach($invoicedata['plancategory'] as $plancategory)
                                                                                <option value="{{$plancategory->pcID}}">{{$plancategory->pcname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Plan Term</label>
                                                                            <select name="planterm" class="form-control">
                                                                                <option value="">Plan Term</option>
                                                                                @foreach($invoicedata['planterm'] as $planterm)
                                                                                <option value="{{$planterm->plantermID}}">{{$planterm->plantermname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Plan Handset Term</label>
                                                                            <select name="planhandsetterm" class="form-control">
                                                                                <option value="">Plan Handset Term</option>
                                                                                @foreach($invoicedata['planhandsetterm'] as $planhandsetterm)
                                                                                <option value="{{$planhandsetterm->planhandsettermID}}">{{$planhandsetterm->planhandsettermname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Plan Proposition Type</label>
                                                                            <select name="planpropositiontype" class="form-control">
                                                                                <option value="">Plan Proposition Type</option>
                                                                                @foreach($invoicedata['planprositiontype'] as $planprositiontype)
                                                                                <option value="{{$planprositiontype->planpropositionID}}">{{$planprositiontype->planpropositionname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Plan Amount</label>
                                                                            <select name="planamount" class="form-control">
                                                                                <option value="">Plan Amount</option>
                                                                                @foreach($invoicedata['planamount'] as $planamount)
                                                                                <option value="{{$planamount->ppingst}}">${{$planamount->ppingst}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-12 text-right" style="margin-top: 20px;">
                                                                            <button type="submit" class="btn btn-primary">Search</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <!--Plan Model--->
                                            <button type="button" class="btn btn-outline-primary btn-block waves-effect waves-light plansubmit" data-toggle="modal" data-target=".planmodel" data-backdrop="static" data-keyboard="false"><i class="fas fa-signal" style="color: red; font-size: 1.3em;"></i> Add Plan</button>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                @foreach($invoicedata['producttype'] as $producttype)
                                                <div class="modal fade producttype{{$producttype->producttypeID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0" id="myLargeModalLabel">Enter {{$producttype->producttypename}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{route('addimeinumber')}}" method="post">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label>{{$producttype->producttypename}}</label>
                                                                                @if($producttype->productrestrictiontype == 1)
                                                                                <input type="text" name="number" class="form-control" placeholder="Type Here" required="" minlength="{{$producttype->productrestrictionword}}" maxlength="{{$producttype->productrestrictionword}}" onkeypress="return isNumber(event)">
                                                                                @else
                                                                                <input type="text" name="number" class="form-control" placeholder="Type Here" required="" minlength="{{$producttype->productrestrictionword}}" maxlength="{{$producttype->productrestrictionword}}">
                                                                                @endif
                                                                                <input type="hidden" name="producttype" class="form-control" value="{{$producttype->producttypeID}}">
                                                                                <input type="hidden" name="invoicenumber" class="form-control" value="{{$invoicedata['getorderdetails']->orderID}}">
                                                                            </div>
                                                                            <div class="col-md-12 text-right" style="margin-top: 20px;">
                                                                                <button type="submit" class="btn btn-primary">Search</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect waves-light" data-toggle="modal" data-target=".producttype{{$producttype->producttypeID}}"><i class="fas fa-mobile-alt" style="color: red; font-size: 1.3em;"></i> @if($producttype->producttypename == 'IMEI') Add Outright Device @else Search Serial @endif</button>
                                                </div>
                                                @endforeach
                                            </div>
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
                                            <th data-priority="6">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        
                                        @foreach($invoicedata['getorderitems'] as $orderitems)
                                            @if($orderitems->plancode == "" && $orderitems->planOrderID == "")
                                                <tr>
                                                    <td>{{$orderitems->barcode}} </td>
                                                    <td>
                                                        {{$orderitems->productname}}
                                                        <span style="font-size: 1em;color: #000;font-weight: 600;font-style: italic;">
                                                        @if($orderitems['productstock'] != '')
                                                        <br>
                                                        {{$orderitems['productstock']->productimei}}
                                                        @endif
                                                        @if($orderitems->stockgroupname != '')
                                                        <br>
                                                        {{$orderitems->stockgroupname}}
                                                        @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        ${{$orderitems->salePrice}}
                                                        @if($orderitems->discountedType != "")
                                                        <br>
                                                        <strike style="color: #999;">${{$orderitems->spingst}}</strike>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <!---Discount Modal--->
                                                        <div class="modal fade discountmodal{{$orderitems->orderitemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{$orderitems->productname}}</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{route('calculatediscount')}}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="itemid" value="{{$orderitems->orderitemID}}">
                                                                            <div class="form-group">
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <label>Purchase Price (Inc. GST)</label>
                                                                                        <input type="text" value="$ {{$orderitems->ppingst}}" class="form-control" readonly="">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>RRP (Inc. GST)</label>
                                                                                        <input type="text" value="$ {{$orderitems->spingst}}" class="form-control" readonly="">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Sale Price (Inc. GST)</label>
                                                                                        <input type="text" value="$ {{$orderitems->salePrice}}" class="form-control" readonly="">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Discount Type</label>
                                                                                        <select name="discounttype" class="form-control">
                                                                                            <option value="Percentage" @if($orderitems->discountedType == 'Percentage') SELECTED='SELECTED' @endif>Percentage (%)</option>
                                                                                            <option value="Amount" @if($orderitems->discountedType == 'Amount') SELECTED='SELECTED' @endif>Amount</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Discount</label>
                                                                                        <input type="number" name="discount" value="{{$orderitems->discount}}" class="form-control">
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
                                                        <!---Discount Modal--->
                                                        @if($orderitems->discountedType == '')
                                                        <a href="" class="discount" data-toggle="modal" data-target=".discountmodal{{$orderitems->orderitemID}}" data-backdrop="static" data-keyboard="false">0.00</a>
                                                        @elseif($orderitems->discountedType == 'Percentage')
                                                        <a href="" class="discount" data-toggle="modal" data-target=".discountmodal{{$orderitems->orderitemID}}" data-backdrop="static" data-keyboard="false">{{$orderitems->discount}}%</a>
                                                        @elseif($orderitems->discountedType == 'Amount')
                                                        <a href="" class="discount" data-toggle="modal" data-target=".discountmodal{{$orderitems->orderitemID}}" data-backdrop="static" data-keyboard="false">${{$orderitems->discount}}</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <!---Quantity Modal--->
                                                        <div class="modal fade quantitymodal{{$orderitems->orderitemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{$orderitems->productname}}</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{route('updatequantity')}}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="itemid" value="{{$orderitems->orderitemID}}">
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
                                                        <a href="" class="discount" data-toggle="modal" data-target=".quantitymodal{{$orderitems->orderitemID}}" data-backdrop="static" data-keyboard="false">{{$orderitems->quantity}}</a>
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
                                                        <div class="modal fade deletemodal{{$orderitems->orderitemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
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
                                                                        <form action="{{route('invoicedeleteitem')}}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="itemid" value="{{$orderitems->orderitemID}}">
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
                                                        <a href="" class="btn btn-sm btn-danger" data-toggle="modal" data-target=".deletemodal{{$orderitems->orderitemID}}" data-backdrop="static" data-keyboard="false"><i class="far fa-trash-alt"></i></a>
                                                    </td>
                                                </tr>
                                            @elseif($orderitems->plancode != "" && $orderitems->planOrderID != "")
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <span style="font-size: 1em;color: #000;font-weight: 600;">
                                                            {{$orderitems->planname}},
                                                            {{$orderitems->pcname}}-{{$orderitems->planpropositionname}}
                                                        </span>
                                                        <br>
                                                        <span style="font-size: 0.9em;color: #000;font-weight: normal;font-style: italic;">
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
                                                        <div class="modal fade deletemodal{{$orderitems->orderitemID}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
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
                                                                        <form action="{{route('invoicedeleteplan')}}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="itemid" value="{{$orderitems->orderitemID}}">
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
                                                        <a href="" class="btn btn-sm btn-danger" data-toggle="modal" data-target=".deletemodal{{$orderitems->orderitemID}}" data-backdrop="static" data-keyboard="false"><i class="far fa-trash-alt"></i></a>
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
                                                <h4>You are closing the invoice by taking complete amount for <span class="badge badge-primary">{{session('paymentdata')['invoiceid']}}</span></h4>
                                                <h5>Click on <span class="badge badge-success">Process</span> OR Cancel it.</h5>

                                                <form action="{{route('confirmfullpayment')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="invoiceid" value="{{session('paymentdata')['invoiceid']}}">
                                                    <input type="hidden" name="payingamount" value="{{session('paymentdata')['payingamount']}}">
                                                    <input type="hidden" name="totalitemamount" value="{{session('paymentdata')['totalitemamount']}}">
                                                    <input type="hidden" name="paymenttype" value="{{session('paymentdata')['paymenttype']}}">
                                                    <input type="hidden" name="saletype" value="{{session('paymentdata')['saletype']}}">
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
                                <form class="" action="{{route('orderpayment')}}" method="post">
                                    @csrf
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @foreach($invoicedata['paymentoptions'] as $key => $paymentoptions)
                                        <label class="btn btn-outline-primary waves-effect waves-light {{$key == 0 ? 'active': ''}}" style="width: 100%; margin: 2px 0px;">
                                            <input type="radio" name="paymenttype" id="option{{$paymentoptions->poID}}" {{$key == 0 ? 'checked': ''}} value="{{$paymentoptions->paymentname}}"> {{$paymentoptions->paymentname}}
                                        </label>
                                        @endforeach
                                        @if($invoicedata['getorderdetails']->customerID != '')
                                            @if($invoicedata['getsavedcustomer']->onAccountPayment == 1)
                                                @foreach($invoicedata['paymentoptionaccount'] as $paymentoptionaccount)
                                                <label class="btn btn-outline-primary waves-effect waves-light" style="width: 100%; margin: 2px 0px;">
                                                    <input type="radio" name="paymenttype" id="option{{$paymentoptionaccount->poID}}" value="{{$paymentoptionaccount->paymentname}}"> {{$paymentoptionaccount->paymentname}}
                                                </label>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </span>
                                            <input id="demo2" type="text" name="payingamount" value="{{$invoicedata['getorderitemssum'] - $invoicedata['orderpaidamount']}}" name="demo2" class="form-control">
                                            <input type="hidden" name="invoiceid" value="{{$invoicedata['getorderdetails']->orderID}}">
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