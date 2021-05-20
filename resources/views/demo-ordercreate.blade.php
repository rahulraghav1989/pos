@extends('main')

@section('content')
@include('includes.topbar')

@include('includes.sidebar')
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
                                <form class="" action="{{route('adddritem')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Demo Receive Number</label>
                                                <input type="text" class="form-control" readonly="" value="{{$podata['id']}}" />
                                                <input type="hidden" name="drreceive" id="drreceive" value="{{$podata['id']}}" />
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
                                                <label>Supplier {{$podata['getpo'][0]->supplierID}}</label>
                                                <select name="supplier" id="supplier" class="form-control dropdownList" required="" />
                                                    <option value="">SELECT SUPPLIER</option>
                                                    @foreach($podata['allsuppliers'] as $supplier)
                                                    <option value="{{$supplier->supplierID}}" @if($podata['getpo'][0]->supplierID==$supplier->supplierID) SELECTED=='SELECTED' @endif>{{$supplier->suppliername}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Reference Number</label>
                                            <input type="text" name="reference" id="reference" value="{{$podata['getpo'][0]->referenceNumber}}" class="form-control">
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Note</label>
                                            <input type="text" name="note" id="note" value="{{$podata['getpo'][0]->note}}" class="form-control">
                                        </div>
                                        <!-----Reference Ajax Request--->
                                        <script>
                                        $(document).ready(function(){

                                         $('#reference').blur(function(){
                                          var error_email = '';
                                          var username = $('#reference').val();
                                          var drreceive = $('#drreceive').val();
                                          var _token = $('input[name="_token"]').val();
                                          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                          $.ajax({
                                            url:"{{ route('ajaxupdatedrreference') }}",
                                            method:"POST",
                                            data:{username:username, drreceive:drreceive, _token:_token},
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
                                          var drreceive = $('#drreceive').val();
                                          var _token = $('input[name="_token"]').val();
                                          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                          $.ajax({
                                            url:"{{ route('ajaxupdatedrnote') }}",
                                            method:"POST",
                                            data:{username:username, drreceive:drreceive, _token:_token},
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
                                                                            <form method="post" action="{{route('adddritem')}}">
                                                                            @csrf
                                                                            <input type="hidden" name="supplier" id="supplierid" class="supplierid">
                                                                            <input type="hidden" name="drreceive" value="{{$podata['id']}}" />
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
                                                            <form class="" action="{{route('productadd')}}" method="post" novalidate="">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>Product Name *</label>
                                                                    <input type="text" name="productname" class="form-control" required="" placeholder="Type Here">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Barcode</label>
                                                                    <input type="text" id="barcode" name="barcode" class="form-control" placeholder="Type Here" onkeyup="sync()">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Stock Code</label>
                                                                    <input type="text" id="stockcode" name="stockcode" class="form-control" placeholder="Type Here" required="">
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
                                                                            <label>Excluding GST</label>
                                                                            <input type="text" name="ppexgst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>GST %</label>
                                                                            <input type="text" name="ppgst" class="form-control" value="{{$podata['alltaxs'][0]['taxpercentage']}}" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Including GST</label>
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
                                                                            <label>Excluding GST</label>
                                                                            <input type="text" name="spexgst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>GST %</label>
                                                                            <input type="text" name="spgst" class="form-control" value="{{$podata['alltaxs'][0]['taxpercentage']}}" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Including GST</label>
                                                                            <input type="text" name="spingst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Colour</label>
                                                                            <select name="colour" class="form-control">
                                                                                <option value="">No Colour</option>
                                                                                @foreach($podata['allcolours'] as $allcolours)
                                                                                <option value="{{$allcolours->colourID}}">{{$allcolours->colourname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Model</label>
                                                                            <select name="model" class="form-control">
                                                                                <option value="">No Model</option>
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
                                                                            <label>Brand</label>
                                                                            <select name="brand" class="form-control">
                                                                                <option value="">No Brand</option>
                                                                                @foreach($podata['allbrands'] as $allbrands)
                                                                                <option value="{{$allbrands->brandID}}">{{$allbrands->brandname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Categories</label>
                                                                            <select name="categories" class="form-control" required="">
                                                                                <option value="">No Categories</option>
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
                                                                                var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input--><div class="row" style="margin-top:10px;"><div class="col-md-3"><select name="stockgroup[]" class="" style="width:100%;"><option value="">SELECT STOCK GROUP</option>@foreach($podata['allstockgroup'] as $allstockgroup)<option value="{{$allstockgroup->stockgroupID}}">{{$allstockgroup->stockgroupname}}</option>@endforeach</select></div><div class="col-md-3"><select name="stockgroupbonustype[]" class="" style="width:100%;"><option value="">SELECT TYPE</option><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div><div class="col-md-3"><input type="text" name="stockgroupbonusvalue[]" class="" style="width:100%;" value="0.00"></div><div class="col-md-3"><a id="remove' + (next) + '" class="btn btn-danger remove-me" style="color:#FFF;">Remove</a></div></div><div id="field"></div></div>';
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
                                                                            <div class="row">
                                                                                <div class="col-md-3">
                                                                                    <label>Select Group</label>
                                                                                    <select name="stockgroup[]" class="" style="width: 100%;">
                                                                                        <option value="">SELECT STOCK GROUP</option>
                                                                                        @foreach($podata['allstockgroup'] as $allstockgroup)
                                                                                        <option value="{{$allstockgroup->stockgroupID}}">{{$allstockgroup->stockgroupname}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label>Bonus Type</label>
                                                                                    <select name="stockgroupbonustype[]" class="" style="width: 100%;">
                                                                                        <option value="">SELECT TYPE</option>
                                                                                        <option value="percentage">Percentage</option>
                                                                                        <option value="fixed">Fixed</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label>Staff Bonus</label>
                                                                                    <input type="text" name="stockgroupbonusvalue[]" class="" value="0.00" style="width: 100%;">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label>Add Group</label>
                                                                                    <a id="add-more" name="add-more" class="btn btn-primary waves-effect waves-light" style="color: #FFF;">Add</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Minumum Quantity</label>
                                                                            <input type="text" name="min-qty" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Maximum Quantity</label>
                                                                            <input type="text" name="max-qty" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
                                                                            
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
                                                                            <label>Product Name</label>
                                                                            <input type="text" name="s-productname" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Description</label>
                                                                            <input type="text" name="s-description" class="form-control" required="" placeholder="Type Here">
                                                                            
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
                                                                            <label>Supplier</label>
                                                                            <select name="supplier" class="form-control" required="">
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
                                            <a href="" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center">Create New Product</a>
                                            @else
                                            <a class="btn btn-light waves-effect waves-light">Create New Product</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>PP</th>
                                                    <th>GST (%)</th>
                                                    <th>PP (Inc. GST)</th>
                                                    <th>Total (Inc. GST)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($podata['getpo'] as $item)
                                                <tr>
                                                    <td>{{$item->productname}}</td>
                                                    <td>{{$item->orderitemquantity}}</td>
                                                    <td>{{$item->drppexgst}}</td>
                                                    <td>{{$item->drpptax}}</td>
                                                    <td>{{$item->drppingst}}</td>
                                                    <td>{{$item->dritemtotal}}</td>
                                                    <td>
                                                        @if($item->productname != "")
                                                            <div class="modal fade editmodel{{$item->drorderitemID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                                                                                <form action="{{route('editdemoitem')}}" method="post">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <label>Quantity</label>
                                                                                        <input type="text" name="quantity" class="form-control" value="{{$item->poquantity}}" />
                                                                                        <input type="hidden" name="demoitemid" value="{{$item->drorderitemID}}" />
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                                <label>PP (Ex. GST)</label>
                                                                                                <input type="text" name="ppexgst" class="form-control" value="{{$item->drppexgst}}" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                                <label>GST (%)</label>
                                                                                                <input type="text" name="ppgst" class="form-control" value="{{$item->drpptax}}" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-4">
                                                                                            <div class="form-group">
                                                                                                <label>PP (Inc. GST)</label>
                                                                                                <input type="text" name="ppingst" class="form-control" value="{{$item->drppingst}}" />
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
                                                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="modal" data-target=".editmodel{{$item->drorderitemID}}"><i class="fas fa-pencil-alt"></i></a>

                                                            <div class="modal fade deletemodel{{$item->drorderitemID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                                                                                <form action="{{route('deletedemopoitem')}}" method="post">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <div class="form-group">
                                                                                            <h4>You really want to <span class="badge badge-primary">Delete</span> this item?.</h4>
                                                                                            
                                                                                            <p>Click on yes to continue or cancle it.</p>
                                                                                            <input type="hidden" name="demoitemid" class="form-control" value="{{$item->drorderitemID}}">
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
                                                            <a class="btn btn-outline-danger waves-effect waves-light" data-toggle="modal" data-target=".deletemodel{{$item->drorderitemID}}"><i class="far fa-trash-alt"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="5" align="right">Total</td>
                                                    <td>{{$podata['getpo']->sum('dritemtotal')}}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                                
                                <form action="{{route('finaldrsubmission')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="drnumber" value="{{$podata['getpo'][0]->receiveInvoiceID}}" />
                                        <input type="hidden" name="drstatus" value="1" />
                                    </div>
                                    <div class="col-lg-12 text-right" style="margin-top: 10px;">
                                        <button type="submit" class="btn btn-primary">Process Demo Receive</button>
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
@endsection