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
                            <h4 class="page-title">Live Stock Take</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Live Stock Take</a></li>
                                <li class="breadcrumb-item active">Stock Return Invoice</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30" style="border: 1px solid #CCC;">
                            <div class="card-body">
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
                                
                                <div class="col-md-12">
                                    <span class="error_barcode" id="error_email"></span>
                                    <div class="row">
                                        @CSRF
                                        <div class="col-md-6">
                                            <label>#ID</label>
                                            <input type="text" id="id" class="form-control" value="{{$getdata->istID}}" readonly="">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Store</label>
                                            <input type="text" class="form-control" value="{{$getdata->store_name}}" readonly="">
                                            <input type="hidden" id="storeID" class="form-control" value="{{$getdata->store_id}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control">
                                            @if(session('openpopup')==1)
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
                                                                    @foreach(session('getbarcode') as $products)
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
                                        </div>
                                        <div class="col-md-6">
                                            <label>IMEI</label>
                                            <input type="text" name="imei" id="imei" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30" style="border: 1px solid #CCC;">
                            <div class="card-body">
                                <div class="col-md-12">
                                    <table id="datatable-buttons" class="table table-striped" style="width: 100%;">
                                        <thead>
                                            <th>Barcode</th>
                                            <th>Product</th>
                                            <th>Scaned Qty</th>
                                            <th>System Qty</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach($getlivestockitems as $stockitems)
                                            <tr>
                                                <td>{{$stockitems->barcode}}</td>
                                                <td>
                                                    {{$stockitems->productname}}
                                                    @if($stockitems->lstiimei != '')
                                                    <br>
                                                    {{$stockitems->lstiimei}}
                                                    @endif
                                                </td>
                                                <td>{{$stockitems->lstiquantity}}</td>
                                                <td>{{$stockitems->lstiavailableQuantity}}</td>
                                                <td></td>
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

        </div>
        <!-- content -->

        @include('includes.footer')
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
        <script src="{{ asset('posview') }}/assets/js/calculation.js"></script>
        <script>
        $(document).ready(function(){

         $('#barcode').keyup(function(){
          var error_email = '';
          var barcode = $('#barcode').val();
          var id = $('#id').val();
          var storeID = $('#storeID').val();
          var _token = $('input[name="_token"]').val();
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          $.ajax({
            url:"{{ route('ajaxfindbarcode') }}",
            method:"POST",
            data:{barcode:barcode, id:id, storeID:storeID, _token:_token},
            success:function(result)
            {
                if(result == 'unique')
               {
                 location.reload();
               }
               else
               {
                $('.error_barcode').html('<label class="text-danger">Barcode Not Found</label>');
                $('.checkvalidsim').addClass('has-error');
                /*$('.submit').attr('disabled', 'disabled');*/
               }
            }
           })
         });
         
        });
        </script>
        <script>
        $(document).ready(function(){

         $('#imei').keyup(function(){
          var error_email = '';
          var imei = $('#imei').val();
          var id = $('#id').val();
          var storeID = $('#storeID').val();
          var _token = $('input[name="_token"]').val();
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          $.ajax({
            url:"{{ route('ajaxfindimei') }}",
            method:"POST",
            data:{imei:imei, id:id, storeID:storeID, _token:_token},
            success:function(result)
            {
                if(result == 'unique')
               {
                 location.reload();
               }
               else
               {
                $('.error_barcode').html('<label class="text-danger">IMEI Not Found</label>');
                $('.checkvalidsim').addClass('has-error');
                /*$('.submit').attr('disabled', 'disabled');*/
               }
            }
           })
         });
         
        });
        </script>
    </div>
</div>
@endsection