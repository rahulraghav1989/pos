@extends('main')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $(".submit").click(function () {

            var books = $('#supplier');
            var reference = $('#reference');
            var note = $('#note');
            $('.reference').val(reference.val());
            $('.note').val(note.val());
            if (books.val() === '') {
                alert("Please select an supplier");
                $('#supplier').focus();

                return false;
            }
            else 
                $('.supplierid').val(books.val());
                return books;
        });
    });
</script>
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
                            <h4 class="page-title">Purchase Order - Number {{$podata['getpo'][0]->ponumber}}</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Purchase Order</a></li>
                                <li class="breadcrumb-item active">Create Purchase Order</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                @if(session()->has('poitemsuccess'))
                                <div class="card-body">
                                    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                        {{ session()->get('poitemsuccess') }}
                                    </div>
                                </div>
                                @endif
                                @if(session()->has('poitemerror'))
                                <div class="card-body">
                                    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                        {{ session()->get('poitemerror') }}
                                    </div>
                                </div>
                                @endif
                                @if(session()->has('editpoitemsuccess'))
                                <div class="card-body">
                                    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                        {{ session()->get('editpoitemsuccess') }}
                                    </div>
                                </div>
                                @endif
                                @if(session()->has('editpoitemerror'))
                                <div class="card-body">
                                    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                        {{ session()->get('editpoitemerror') }}
                                    </div>
                                </div>
                                @endif
                                @if(session()->has('deletepoitemsuccess'))
                                <div class="card-body">
                                    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                        {{ session()->get('deletepoitemsuccess') }}
                                    </div>
                                </div>
                                @endif
                                @if(session()->has('deletepoitemerror'))
                                <div class="card-body">
                                    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                        {{ session()->get('deletepoitemerror') }}
                                    </div>
                                </div>
                                @endif
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
                                                                <form action="{{route('poaddbyproductid')}}" method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="productid" value="{{$products->productID}}">
                                                                    <input type="hidden" name="ponumber" value="{{session('productdata')['ponumber']}}">
                                                                    <input type="hidden" name="quantity" value="{{session('productdata')['quantity']}}">
                                                                    <input type="hidden" name="supplier" value="{{session('productdata')['supplierid']}}">
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
                                <form class="" action="{{route('addpositem')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>PO Number</label>
                                                <input type="text" class="form-control" readonly="" value="{{$podata['id']}}" />
                                                <input type="hidden" name="ponumber" id="ponumber" value="{{$podata['id']}}" />
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Store</label>
                                                <input type="text" readonly="" class="form-control" value="{{$podata['getpo'][0]->store_name}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Supplier</label>
                                                <select name="supplier" id="supplier" class="form-control dropdownList" required="" />
                                                    <option value="">SELECT SUPPLIER</option>
                                                    @foreach($podata['allsuppliers'] as $supplier)
                                                    <option value="{{$supplier->supplierID}}" @if($podata['getpo'][0]->supplierID==$supplier->supplierID) SELECTED=='SELECTED' @endif>{{$supplier->suppliername}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Reference Number (I-Store Order Number)</label>
                                            <input type="text" name="reference" id="reference" value="{{$podata['getpo'][0]->porefrencenumber}}" class="form-control">
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Note</label>
                                            <input type="text" name="note" id="note" value="{{$podata['getpo'][0]->ponote}}" class="form-control">
                                        </div>
                                        <!-----Reference Ajax Request--->
                                        <script>
                                        $(document).ready(function(){

                                         $('#reference').blur(function(){
                                          var error_email = '';
                                          var username = $('#reference').val();
                                          var ponumber = $('#ponumber').val();
                                          var _token = $('input[name="_token"]').val();
                                          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                          $.ajax({
                                            url:"{{ route('ajaxupdateporeference') }}",
                                            method:"POST",
                                            data:{username:username, ponumber:ponumber, _token:_token},
                                            success:function(result)
                                            {
                                             
                                            }
                                           })
                                         });
                                         
                                        });
                                        </script>
                                        <!-----Reference Ajax Request--->

                                        <!-----Note Ajax Request--->
                                        <script>
                                        $(document).ready(function(){

                                         $('#note').blur(function(){
                                          var error_email = '';
                                          var username = $('#note').val();
                                          var ponumber = $('#ponumber').val();
                                          var _token = $('input[name="_token"]').val();
                                          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                          $.ajax({
                                            url:"{{ route('ajaxupdateponote') }}",
                                            method:"POST",
                                            data:{username:username, ponumber:ponumber, _token:_token},
                                            success:function(result)
                                            {
                                             
                                            }
                                           })
                                         });
                                         
                                        });
                                        </script>
                                        <!-----Note Ajax Request--->
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Purchase Order Items</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" name="quantity" class="form-control" value="1" />
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label>Barcode</label>
                                                <input type="text" name="barcode" class="form-control" placeholder="Type Here"/>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label style="width: 100%;">Add Product</label>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">Add </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="modal fade bs-example-modal-lg-all-search" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0" id="myLargeModalLabel">All Products</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Barcode</th>
                                                                        <th>Stock Code</th>
                                                                        <th>Product</th>
                                                                        <th>RRP (Inc. GST)</th>
                                                                        <th>Supplier</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($podata['allproducts'] as $product)
                                                                    <tr>
                                                                        <td>{{$product->barcode}}</td>
                                                                        <td>{{$product->stockcode}}</td>
                                                                        <td>
                                                                            {{$product->productname}}
                                                                            <br>
                                                                            {{$product['productbrand']['brandname']}}
                                                                            <br>
                                                                            {{$product['productcolour']['colourname']}}
                                                                            <br>
                                                                            {{$product['productmodel']['modelname']}}

                                                                        </td>
                                                                        <td>{{$product->spingst}}</td>
                                                                        <td>{{$product['productsupplier']['suppliername']}}</td>
                                                                        <td>
                                                                            <form method="post" action="{{route('addpositem')}}">
                                                                            @csrf
                                                                            <input type="hidden" name="supplier" id="supplierid" class="supplierid">
                                                                            <input type="hidden" name="ponumber" value="{{$podata['id']}}" />
                                                                            <input type="hidden" name="productid" value="{{$product->productID}}">
                                                                            <input type="hidden" name="reference" class="reference">
                                                                            <input type="hidden" name="note" class="note"> 
                                                                            <input type="submit" id="submit" class="submit btn btn-sm btn-primary" name="productid{{$product->productID}}" class="btn btn-primary waves-effect waves-light" value="+">
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
                                            <a class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg-all-search" style="color: #FFF;">Search All Product</a>
                                            OR
                                            @if(session('loggindata')['loggeduserpermission']->addproducts=='Y')
                                            <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0">Add a new product</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <script type="text/javascript">
                                                            function sync()
                                                            {
                                                              var barcode = document.getElementById('barcode');
                                                              var stockcode = document.getElementById('stockcode');
                                                              stockcode.value = barcode.value;
                                                            }
                                                            </script>
                                                            <script>
                                                                $(document).ready(function () {
                                                                    $(".addproduct").click(function () {

                                                                        var books = $('#stockgroup');
                                                                        if (books.val() === '') {
                                                                            alert("Please select an stock group");
                                                                            $('#stockgroup').focus();

                                                                            return false;
                                                                        }
                                                                        else 
                                                                            return books;
                                                                    });
                                                                });
                                                            </script>
                                                            <form class="" action="{{route('productadd')}}" method="post" novalidate="">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>Product Name (*)</label>
                                                                    <input type="text" name="productname" class="form-control" required="" placeholder="Type Here">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Barcode (*)</label>
                                                                    <input type="text" id="barcode" name="barcode" class="form-control" placeholder="Type Here" autocomplete="off" onkeyup="sync()">
                                                                    <span id="error_email"></span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Stock Code (*)</label>
                                                                    <input type="text" id="stockcode" name="stockcode" class="form-control" autocomplete="off" placeholder="Type Here" required="">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <input type="text" name="description" class="form-control" placeholder="Type Here">
                                                                </div>
                                                                <div class="">
                                                                    <label>Purchase Price</label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label>Excluding GST (*)</label>
                                                                            <input type="text" name="ppexgst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>GST % (*)</label>
                                                                            <input type="text" name="ppgst" class="form-control" value="{{$podata['alltaxs'][0]['taxpercentage']}}" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Including GST (*)</label>
                                                                            <input type="text" name="ppingst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label>Minimum Markup</label>    
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Including GST</label>
                                                                            <input type="text" name="markup" class="form-control" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="">
                                                                    <label>RRP (Recommended Retail Price)</label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label>Excluding GST (*)</label>
                                                                            <input type="text" name="spexgst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>GST % (*)</label>
                                                                            <input type="text" name="spgst" class="form-control" value="{{$podata['alltaxs'][0]['taxpercentage']}}" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Including GST (*)</label>
                                                                            <input type="text" name="spingst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label style="width: 100%;">Colour </label>
                                                                            <select name="colour" class="form-control" id="colourdrop">
                                                                                <option value="0">No Colour</option>
                                                                                @foreach($podata['allcolours'] as $allcolours)
                                                                                <option value="{{$allcolours->colourID}}">{{$allcolours->colourname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label style="width: 100%;">Model </label>
                                                                            <select name="model" class="form-control" id="modeldrop">
                                                                                <option value="0">No Model</option>
                                                                                @foreach($podata['allmodels'] as $allmodels)
                                                                                <option value="{{$allmodels->modelID}}">{{$allmodels->modelname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label style="width: 100%;">Brand </label>
                                                                            <select name="brand" class="form-control" id="branddrop">
                                                                                <option value="0">No Brand</option>
                                                                                @foreach($podata['allbrands'] as $allbrands)
                                                                                <option value="{{$allbrands->brandID}}">{{$allbrands->brandname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Categories (*)</label>
                                                                            <select name="categories" class="form-control" required="">
                                                                                <option value="0">No Categories</option>
                                                                                @foreach($podata['allcategories'] as $allcategories)
                                                                                <option value="{{$allcategories->categoryID}}">{{$allcategories->categoryname}}</option>
                                                                                    @foreach($allcategories['subcategory'] as $subcategory)
                                                                                    <option value="-{{$subcategory->subcategoryID}}"> - {{$subcategory->subcategoryname}}</option>
                                                                                    @endforeach
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Product Type <br><span style="font-size: 0.7em !important; color: red;">Use for only unique type products. Like: IMEI, SERIAL OR Like Product: Phones, Tablets Etc. Leave blank for quantity products</span></label>
                                                                    <select name="producttype" class="form-control">
                                                                        <option value="">No Type </option>
                                                                        @foreach($podata['allproducttype'] as $allproducttype)
                                                                        <option value="{{$allproducttype->producttypeID}}">{{$allproducttype->producttypename}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
                                                                    <script>
                                                                    $(document).ready(function () {
                                                                            //@naresh action dynamic childs
                                                                            var next = 0;
                                                                            $("#add-more").click(function(e){
                                                                                e.preventDefault();
                                                                                var addto = "#field" + next;
                                                                                var addRemove = "#field" + (next);
                                                                                next = next + 1;
                                                                                var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input--><fieldset><legend>Column '+ next +'</legend><div class="row" style="margin-top:10px;"><div class="col-md-6"><label>Select Group (*)</label><select name="stockgroup[]" class="form-control" style="width:100%;" required><option value="">SELECT STOCK GROUP</option>@foreach($podata['allstockgroup'] as $allstockgroup)<option value="{{$allstockgroup->stockgroupID}}">{{$allstockgroup->stockgroupname}}</option>@endforeach</select></div><div class="col-md-6"><label>Bonus Type</label><select name="stockgroupbonustype[]" class="form-control" style="width:100%;"><option value="">SELECT TYPE</option><option value="percentage_profitmargin">% Of Profit Margin</option><option value="percentage_saleprice">% Of RRP (Sale Price)</option><option value="percentage_dealermargin">% Of Dealer Margin</option><option value="fixed">Fixed</option></select></div><div class="col-md-4"><label>Dealer Margin</label><input type="text" name="dealermargin[]" class="form-control" value="0.00"></div><div class="col-md-4"><label>Staff Bonus</label><input type="text" name="stockgroupbonusvalue[]" class="form-control" style="width:100%;" value="0.00"></div><div class="col-md-4"><label style="width:100%;">Remove</label><a id="remove' + (next) + '" class="btn btn-danger remove-me" style="color:#FFF;">Remove</a></div></div><div id="field"></div></fieldset></div>';
                                                                                var newInput = $(newIn);
                                                                                //var removeBtn = '';
                                                                                //var removeButton = $(removeBtn);
                                                                                $(addto).after(newInput);
                                                                                $(addRemove).after(newInput);
                                                                                $("#field" + next).attr('data-source',$(addto).attr('data-source'));
                                                                                $("#count").val(next);  
                                                                                
                                                                                    $('.remove-me').click(function(e){
                                                                                        e.preventDefault();
                                                                                        var fieldNum = this.id.charAt(this.id.length-1);
                                                                                        var fieldID = "#field" + fieldNum;
                                                                                        $(this).remove();
                                                                                        $(fieldID).remove();
                                                                                    });
                                                                            });

                                                                        });
                                                                    </script>
                                                                    <div class="col-md-12">
                                                                        <label>Stock Group</label>
                                                                    </div>
                                                                    <div id="field" class="col-md-12">
                                                                        <div id="field0">
                                                                            <fieldset>
                                                                                <legend>Column 0</legend>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <label>Select Group (*)</label>
                                                                                        <select name="stockgroup[]" id="stockgroup" class="form-control" style="width: 100%;" required="">
                                                                                            <option value="">SELECT STOCK GROUP</option>
                                                                                            @foreach($podata['allstockgroup'] as $allstockgroup)
                                                                                            <option value="{{$allstockgroup->stockgroupID}}">{{$allstockgroup->stockgroupname}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label>Bonus Type</label>
                                                                                        <select name="stockgroupbonustype[]" class="form-control" style="width: 100%;">
                                                                                            <option value="">SELECT TYPE</option>
                                                                                            <option value="percentage_profitmargin">% Of Profit Margin</option>
                                                                                            <option value="percentage_saleprice">% Of RRP (Sale Price)</option>
                                                                                            <option value="percentage_dealermargin">% Of Dealer Margin</option>
                                                                                            <option value="fixed">Fixed</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Dealer Margin</label>
                                                                                        <input type="text" class="form-control" name="dealermargin[]" value="0.00" required="">
                                                                                    </div>
                                                                                    <!-- <div class="col-md-4">
                                                                                        <label>Bonus Type</label>
                                                                                        <select name="stockgroupbonustype[]" class="form-control" style="width: 100%;">
                                                                                            <option value="">SELECT TYPE</option>
                                                                                            <option value="percentage">Percentage</option>
                                                                                            <option value="fixed">Fixed</option>
                                                                                        </select>
                                                                                    </div> -->
                                                                                    <div class="col-md-4">
                                                                                        <label>Staff Bonus</label>
                                                                                        <input type="text" name="stockgroupbonusvalue[]" class="form-control" value="0.00" style="width: 100%;">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label style="width: 100%;">Add Group</label>
                                                                                        <a id="add-more" name="add-more" class="btn btn-primary waves-effect waves-light" style="color: #FFF;">Add</a>
                                                                                    </div>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Minumum Quantity</label>
                                                                            <input type="text" name="min-qty" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="0.00">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Maximum Quantity</label>
                                                                            <input type="text" name="max-qty" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="0.00">
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <hr>
                                                                    <label>Product Supplier</label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <!-- <label>Product Name</label> -->
                                                                            <input type="hidden" name="s-productname" class="form-control" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <!-- <label>Description</label> -->
                                                                            <input type="hidden" name="s-description" class="form-control" placeholder="Type Here">
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>SKU Code</label>
                                                                            <input type="text" name="s-sku" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            
                                                                            <label style="width: 100%;">Supplier  (*)</label>
                                                                            <select name="supplier" class="form-control" required="" id="supplierdrop">
                                                                                <option value="">No Suppliers</option>
                                                                                @foreach($podata['allsuppliers'] as $allsuppliers)
                                                                                <option value="{{$allsuppliers->supplierID}}">{{$allsuppliers->suppliername}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <div>
                                                                        <button type="submit" class="btn btn-primary waves-effect waves-light addproduct">
                                                                            Submit
                                                                        </button>
                                                                        <!-- <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                                            Cancel
                                                                        </button> -->
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <a href="" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center">Create New Product</a>
                                            @else
                                            <!-- <a class="btn btn-light waves-effect waves-light">Create New Product</a> -->
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Barcode</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
                                                    <th>PP</th>
                                                    <th>GST (%)</th>
                                                    <th>PP (Inc. GST)</th>
                                                    <th>Total (Inc. GST)</th>
                                                    @endif
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($podata['getpo'] as $item)
                                                <tr>
                                                    <td>{{$item->barcode}}</td>
                                                    <td>{{$item->productname}}</td>
                                                    <td>{{$item->poquantity}}</td>
                                                    @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
                                                    <td>{{$item->popurchaseprice}}</td>
                                                    <td>{{$item->popptax}}</td>
                                                    <td>{{$item->poppingst}}</td>
                                                    <td>{{$item->poitemtotal}}</td>
                                                    @endif
                                                    <td>
                                                        @if($item->productname != "")
                                                            @if(session('loggindata')['loggeduserpermission']->editpurchaseorderitem=='Y')
                                                            <!--Edit Model-->
                                                            <div class="modal fade editmodel{{$item->poitemID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title mt-0">Edit</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="col-lg-12">
                                                                                <form action="{{route('editpoitem')}}" method="post">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <label>Quantity</label>
                                                                                        <input type="text" name="quantity" class="form-control" value="{{$item->poquantity}}" />
                                                                                        <input type="hidden" name="poitemid" value="{{$item->poitemID}}" />
                                                                                    </div>
                                                                                    <div class="row" @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='N' || session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='') style="display:none"; @endif>
                                                                                        <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                                <label>PP (Ex. GST)</label>
                                                                                                <input type="text" name="ppexgst" class="form-control" value="{{$item->popurchaseprice}}" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                                <label>GST (%)</label>
                                                                                                <input type="text" name="ppgst" class="form-control" value="{{$item->popptax}}" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                                <label>PP (Inc. GST)</label>
                                                                                                <input type="text" name="ppingst" class="form-control" value="{{$item->poppingst}}" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- /.modal-content -->
                                                                </div><!-- /.modal-dialog -->
                                                            </div>
                                                            <!--Edit Model-->
                                                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="modal" data-target=".editmodel{{$item->poitemID}}"><i class="fas fa-pencil-alt"></i></a>
                                                            @else
                                                            <a class="btn btn-light waves-effect"><i class="icon-pencil"></i></a>
                                                            @endif

                                                            @if(session('loggindata')['loggeduserpermission']->deletepurchaseorderitem=='Y')
                                                            <!--Delete Model-->
                                                            <div class="modal fade deletemodel{{$item->poitemID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title mt-0">Delete</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="col-lg-12">
                                                                                <form action="{{route('deletepoitem')}}" method="post">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <div class="form-group">
                                                                                            <h4>You really want to <span class="badge badge-primary">Delete</span> this item?.</h4>
                                                                                            
                                                                                            <p>Click on yes to continue or cancle it.</p>
                                                                                            <input type="hidden" name="poitemid" class="form-control" value="{{$item->poitemID}}">
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
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- /.modal-content -->
                                                                </div><!-- /.modal-dialog -->
                                                            </div>
                                                            <!--Delete Model-->
                                                            <a class="btn btn-outline-danger waves-effect waves-light" data-toggle="modal" data-target=".deletemodel{{$item->poitemID}}"><i class="far fa-trash-alt"></i></a>
                                                            @else
                                                            <a class="btn btn-light waves-effect"><i class="far fa-trash-alt"></i></a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
                                                <tr>
                                                    <td colspan="6" align="right">Total</td>
                                                    <td>{{$podata['getpo']->sum('poitemtotal')}}</td>
                                                    <td></td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                                
                                <form action="{{route('finalposubmission')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="reference" class="reference">
                                        <input type="hidden" name="note" class="note">
                                        <input type="hidden" name="ponumber" value="{{$podata['id']}}" />
                                        <input type="hidden" name="postatus" value="1" />
                                    </div>
                                    <div class="col-lg-12 text-right" style="margin-top: 10px;">
                                        <button type="submit" class="btn btn-primary">Process PO</button>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->      

                
            </div>
            <!-- container-fluid -->

        </div>
        @include('includes.footer')
    </div>
</div>
<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{{ asset('posview') }}/assets/js/calculation.js"></script>
<script>
$(document).ready(function(){

 $('#barcode').blur(function(){
  var error_email = '';
  var username = $('#barcode').val();
  var _token = $('input[name="_token"]').val();
  var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  $.ajax({
    url:"{{ route('ajaxcheckbarcode') }}",
    method:"POST",
    data:{username:username, _token:_token},
    success:function(result)
    {
     if(result == 'unique')
     {
      $('#error_email').html('<label class="text-success">Barcode Not Exists</label>');
      $('#barcode').removeClass('has-error');
      $('#submit').attr('disabled', false);
     }
     else
     {
      $('#error_email').html('<label class="text-danger">Barcode already in use</label>');
      $('#barcode').addClass('has-error');
      $('#submit').attr('disabled', 'disabled');
     }
    }
   })
 });
 
});
</script>
@endsection