@extends('main')

@section('content')
<div id="wrapper">
    @include('includes.topbar')

    @include('includes.sidebar')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Stock Transfer</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Products / Plans</a></li>
                                <li class="breadcrumb-item active">Stock Transfer</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{$error}}
                        </div>
                    @endforeach
                @endif
                @if(session()->has('success'))
                    <div class="card-body">                                 
                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                            {{ session()->get('success') }}
                        </div>
                    </div>
                @endif
                @if(session()->has('error'))
                <div class="card-body">
                    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                        {{ session()->get('error') }}
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" style="margin-bottom: 10px;">
                            <div class="card-body">
                                <div class="modal fade cancelmodel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0">Cancel Stock Transfer</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="" action="{{route('cancelstocktransfer')}}" method="post" novalidate="">
                                                    @csrf
                                                    <div class="form-group">
                                                        <h4>You are about to cancel stock transfer #<span class="badge badge-primary">{{$createstocktransferdata['checktransfer']->stocktransferID}}</span></h4>
                                                        <p>Click on <span class="badge badge-primary">Yes</span> to continue or cancle it.</p>
                                                        <input type="hidden" name="stocktransferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <div>
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                Yes
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
                                <div class="text-right">
                                    <button type="button" class="btn btn-danger waves-effect waves-light" data-toggle="modal" data-target=".cancelmodel" data-backdrop="static" data-keyboard="false">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card m-b-30">
                            <div class="card-body">

                                <h4 class="mt-0 header-title"># {{$createstocktransferdata['checktransfer']->stocktransferID}}</h4>
                                <br>
                                    <!---Multibarcode Pop Model--->
                                    @if(session('multibarcodedata')['openmultibarcode']==1)
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
                                                            @foreach(session('multibarcodedata')['getproductdetail'] as $products)
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
                                                                    <form action="{{route('stbyproductid')}}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="productid" value="{{$products->productID}}">
                                                                        <input type="hidden" name="quantity" value="{{session('multibarcodedata')['quantity']}}">
                                                                        <input type="hidden" name="stocktransferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
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
                                    <div class="form-group">
                                        <label>Add By Barcode</label>
                                        <form class="" action="{{route('stbybarcode')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="stocktransferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <input type="number" name="quantity" class="form-control" required value="1" min="1" />    
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" name="barcode" class="form-control" required placeholder="Type Barcode" />    
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-primary">Add</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="form-group text-center">
                                        <label>OR</label>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Add IMEI</label>
                                                <form class="" action="{{route('stbyimei')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="stocktransferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input type="text" name="imei" class="form-control" required placeholder="Type IMEI"/>    
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-primary">Add</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Add Serial</label>
                                                <form class="" action="{{route('stbyserial')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="stocktransferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input type="text" name="imei" class="form-control" required placeholder="Type Serial"/>    
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-primary">Add</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="card-body">
                                <table id="" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($createstocktransferdata['transferitem'] as $items)
                                    <tr>
                                        <td>{{$items->barcode}}</td>
                                        <td>
                                            {{$items->productname}}
                                            @if($items->productimei !='')
                                            <br>
                                            {{$items->productimei}}
                                            @endif
                                            @if($items->simnumber !='')
                                            <br>
                                            Sim: {{$items->simnumber}}
                                            @endif
                                            @if($items->colour !='')
                                            <br>
                                            {{$items->colourname}}
                                            @endif
                                            @if($items->model !='')
                                            <br>
                                            {{$items->modelname}}
                                            @endif
                                            @if($items->brand !='')
                                            <br>
                                            {{$items->brandname}}
                                            @endif
                                        </td>
                                        <td>{{$items->quantity}}</td>
                                        <td>
                                            @if($items->producttype == '')
                                            <!----Edit Modal---->
                                            <div class="modal fade editmodel{{$items->stitemsID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0">Edit {{$items->productname}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="" action="{{route('editquantity')}}" method="post" novalidate="">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>Quantity</label>
                                                                    <input type="number" name="quantity" class="form-control" required="" value="{{$items->quantity}}">
                                                                    <input type="hidden" name="itemid" value="{{$items->stitemsID}}">
                                                                    <input type="hidden" name="productid" value="{{$items->productID}}">
                                                                    <input type="hidden" name="stockid" value="{{$items->stockID}}">
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <div>
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
                                            <!----Edit Modal---->
                                            <a href="" class="btn btn-primary" data-toggle="modal" data-target=".editmodel{{$items->stitemsID}}" data-backdrop="static" data-keyboard="false"><i class="fas fa-pencil-alt"></i></a>
                                            @else
                                            <a class="btn btn-light waves-effect"><i class="fas fa-pencil-alt"></i></a>
                                            @endif
                                            <!----Delete Modal---->
                                            <div class="modal fade deletemodel{{$items->stitemsID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0">Delete {{$items->productname}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5>You are about to remove <span class="badge badge-primary">{{$items->productname}}</span></h5>
                                                            <h6>To continue press <span class="badge badge-primary">Yes</span> OR Cancel it</h6>
                                                            <form class="" action="{{route('deleteitem')}}" method="post" novalidate="">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <input type="hidden" name="quantity" class="form-control" required="" value="{{$items->quantity}}">
                                                                    <input type="hidden" name="itemid" value="{{$items->stitemsID}}">
                                                                    <input type="hidden" name="productid" value="{{$items->productID}}">
                                                                    <input type="hidden" name="stockid" value="{{$items->stockID}}">
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <div>
                                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                            Yes
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
                                            <!----Delete Modal---->
                                            <a href="" class="btn btn-danger" data-toggle="modal" data-target=".deletemodel{{$items->stitemsID}}" data-backdrop="static" data-keyboard="false"><i class="far fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Transfer By</label>
                                    <div>
                                        <input type="text" class="form-control" readonly="" value="{{$createstocktransferdata['checktransfer']->name}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Transfer To</label>
                                    <div>
                                        <!---SELECT STORE MODEL---->
                                        <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0">Transfer To</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                            <tr>
                                                                <th>Store</th>
                                                                <th>Select</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($createstocktransferdata['allstore'] as $storename)
                                                                    @if(session('storeid')!=$storename->store_id)
                                                                    <tr>
                                                                        <td>{{$storename->store_name}}</td>
                                                                        <td>
                                                                            <form action="{{route('addtransferstore')}}" method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="tostoreid" value="{{$storename->store_id}}">
                                                                                <input type="hidden" name="stocktransferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                                                                <button type="submit" class="btn btn-outline-primary">SELECT</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                        <!---SELECT STORE MODEL---->
                                        @if($createstocktransferdata['checktransfer']->toStoreID == '')
                                        <button class="btn btn-sm btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false">Select Store</button>
                                        @else
                                        {{$createstocktransferdata['checktransfer']->store_name}}
                                        <br>
                                        <a href="#" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false">change store</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    @csrf
                                    <input type="hidden" id="stocktransferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                    <label>Consignment Number (Opt.)</label>
                                    <div>
                                        <input type="text" name="consignmentnumber" id="consignmentnumber" class="form-control" value="{{$createstocktransferdata['checktransfer']->consignmentnumber}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Transfer Note (Opt.)</label>
                                    <div>
                                        <textarea cols="" rows="8" name="note" id="note" class="form-control">{{$createstocktransferdata['checktransfer']->transfernote}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <form action="{{route('proceedtransfer')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="transferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                            <button type="submit" class="btn btn-primary">Proceed Transfer</button>
                                        </form>
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
        <script>
        $(document).ready(function(){

         $('#note').blur(function(){
          var error_email = '';
          var username = $('#note').val();
          var stid = $('#stocktransferid').val();
          var _token = $('input[name="_token"]').val();
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          $.ajax({
            url:"{{ route('ajaxupdatetransfernote') }}",
            method:"POST",
            data:{username:username, stid:stid, _token:_token},
            success:function(result)
            {
             
            }
           })
         });
         
        });
        </script>
        <script>
        $(document).ready(function(){

         $('#consignmentnumber').blur(function(){
          var error_email = '';
          var username = $('#consignmentnumber').val();
          var stid = $('#stocktransferid').val();
          var _token = $('input[name="_token"]').val();
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          $.ajax({
            url:"{{ route('ajaxupdateconsignmentnumber') }}",
            method:"POST",
            data:{username:username, stid:stid, _token:_token},
            success:function(result)
            {
             
            }
           })
         });
         
        });
        </script>
    </div>
</div>
@endsection