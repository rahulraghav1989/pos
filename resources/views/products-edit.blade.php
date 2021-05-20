@extends('main')

@section('content')
	@include('includes.topbar')

    @include('includes.sidebar')
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

    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">Edit Product</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                    <li class="breadcrumb-item active">Edit Product</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
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
                                <div class="card-body">
                                	<script type="text/javascript">
									function sync()
									{
									  /*var barcode = document.getElementById('barcode');
									  var stockcode = document.getElementById('stockcode');
									  stockcode.value = barcode.value;*/
									}
									</script>
                                    <form class="" action="{{route('editproduct')}}" method="post">
                                    	@csrf
                                    	<input type="hidden" name="productid" value="{{$editproductsdata['products']->productID}}">
                                        <div class="form-group">
                                            <label>Product Name *</label>
                                            <input type="text" name="productname" class="form-control" required="" placeholder="Type Here" value="{{$editproductsdata['products']->productname}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Barcode</label>
                                            <input type="text" id="barcode" name="barcode" class="form-control" placeholder="Type Here" onkeyup="sync()" value="{{$editproductsdata['products']->barcode}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Stock Code</label>
                                            <input type="text" id="stockcode" name="stockcode" class="form-control" placeholder="Type Here" required="" value="{{$editproductsdata['products']->stockcode}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" name="description" class="form-control" placeholder="Type Here" value="{{$editproductsdata['products']->description}}">
                                        </div>
                                        <div class="">
                                            <label>Purchase Price</label>
                                        </div>
                                        <div class="form-group">
                                        	<div class="row">
	                                        	<div class="col-md-4">
		                                            <label>Excluding GST</label>
		                                            <input type="text" name="ppexgst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="{{$editproductsdata['products']->ppexgst}}">
	                                        	</div>
	                                        	<div class="col-md-4">
		                                            <label>GST %</label>
		                                            <input type="text" name="ppgst" class="form-control" value="{{$editproductsdata['products']->ppgst}}" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
	                                        	</div>
	                                        	<div class="col-md-4">
		                                            <label>Including GST</label>
		                                            <input type="text" name="ppingst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="{{$editproductsdata['products']->ppingst}}">
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
		                                            <input type="text" name="spexgst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="{{$editproductsdata['products']->spexgst}}">
	                                        	</div>
	                                        	<div class="col-md-4">
		                                            <label>GST %</label>
		                                            <input type="text" name="spgst" class="form-control" value="{{$editproductsdata['products']->spgst}}" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
	                                        	</div>
	                                        	<div class="col-md-4">
		                                            <label>Including GST</label>
		                                            <input type="text" name="spingst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="{{$editproductsdata['products']->spingst}}">
	                                        	</div>
	                                        </div>
                                        </div>
                                        <div class="form-group">
                                        	<div class="row">
	                                        	<div class="col-md-6">
		                                            <label>Colour</label>
		                                            <select name="colour" class="form-control">
		                                            	<option value="0">No Colour</option>
		                                            	@foreach($editproductsdata['allcolours'] as $allcolours)
		                                            	<option value="{{$allcolours->colourID}}" @if($editproductsdata['products']->colour == $allcolours->colourID) SELECTED='SELECTED' @endif>{{$allcolours->colourname}}</option>
		                                            	@endforeach
		                                            </select>
	                                        	</div>
	                                        	<div class="col-md-6">
		                                            <label>Model</label>
		                                            <select name="model" class="form-control">
		                                            	<option value="0">No Model</option>
		                                            	@foreach($editproductsdata['allmodels'] as $allmodels)
		                                            	<option value="{{$allmodels->modelID}}" @if($editproductsdata['products']->model == $allmodels->modelID) SELECTED='SELECTED' @endif>{{$allmodels->modelname}}</option>
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
		                                            	<option value="0">No Brand</option>
		                                            	@foreach($editproductsdata['allbrands'] as $allbrands)
		                                            	<option value="{{$allbrands->brandID}}" @if($editproductsdata['products']->brand == $allbrands->brandID) SELECTED='SELECTED' @endif>{{$allbrands->brandname}}</option>
		                                            	@endforeach
		                                            </select>
	                                        	</div>
	                                        	<div class="col-md-6">
		                                            <label>Categories</label>
		                                            <select name="categories" class="form-control" required="">
		                                            	<option value="0">No Categories</option>
		                                            	@foreach($editproductsdata['allcategories'] as $allcategories)
		                                            	<option value="{{$allcategories->categoryID}}" @if($editproductsdata['products']->categories == $allcategories->categoryID) SELECTED='SELECTED' @endif>{{$allcategories->categoryname}}</option>
			                                            	@foreach($allcategories['subcategory'] as $subcategory)
			                                            	<option value="-{{$subcategory->subcategoryID}}" @if($editproductsdata['products']->subcategory == $subcategory->subcategoryID) SELECTED='SELECTED' @endif> - {{$subcategory->subcategoryname}}</option>
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
                                            	@foreach($editproductsdata['allproducttype'] as $allproducttype)
                                            	<option value="{{$allproducttype->producttypeID}}" @if($editproductsdata['products']->producttype == $allproducttype->producttypeID) SELECTED='SELECTED' @endif>{{$allproducttype->producttypename}}</option>
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
												        var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input--><fieldset><legend>Column '+ next +'</legend><div class="row" style="margin-top:10px;"><div class="col-md-6"><label>Select Group</label><select name="stockgroup[]" class="form-control" style="width:100%;"><option value="">SELECT STOCK GROUP</option>@foreach($editproductsdata['allstockgroup'] as $allstockgroup)<option value="{{$allstockgroup->stockgroupID}}">{{$allstockgroup->stockgroupname}}</option>@endforeach</select></div><div class="col-md-6"><label>Bonus Type</label><select name="stockgroupbonustype[]" class="form-control" style="width:100%;"><option value="">SELECT TYPE</option><option value="percentage_profitmargin">% Of Profit Margin</option><option value="percentage_saleprice">% Of RRP (Sale Price)</option><option value="percentage_dealermargin">% Of Dealer Margin</option><option value="fixed">Fixed</option></select></div><div class="col-md-4"><label>Dealer Margin</label><input type="text" name="dealermargin[]" class="form-control" value="0.00"></div><div class="col-md-4"><label>Staff Bonus</label><input type="text" name="stockgroupbonusvalue[]" class="form-control" style="width:100%;" value="0.00"></div><div class="col-md-4"><label>Remove</label><a id="remove' + (next) + '" class="btn btn-danger remove-me" style="color:#FFF;">Remove</a></div></div><div id="field"></div></fieldset></div>';
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
                                    		@foreach($editproductsdata['products']->productstockgroup as $key => $productstockgroup)
                                    		<div>
                                    			<fieldset>
                                                    <legend>Column {{$key+1}}</legend>
                                        			<div class="row">
		                                        		<div class="col-md-6">
		                                        			<label>Select Group</label>
		                                        			<input type="hidden" name="editstockgroupid[]" value="{{$productstockgroup->productSGID}}">
		                                        			<select name="editstockgroup[]" class="form-control" style="width: 100%;">
		                                        				<option value="">SELECT STOCK GROUP</option>
		                                        				@foreach($editproductsdata['allstockgroup'] as $allstockgroup)
		                                        				<option value="{{$allstockgroup->stockgroupID}}" @if($productstockgroup->stockgroupID == $allstockgroup->stockgroupID) SELECTED='SELECTED' @endif>{{$allstockgroup->stockgroupname}}</option>
		                                        				@endforeach
		                                        			</select>
		                                        		</div>
		                                        		<div class="col-md-6">
		                                        			<label>Bonus Type</label>
		                                        			<select name="editstockgroupbonustype[]" class="form-control" style="width: 100%;">
		                                        				<option value="">SELECT TYPE</option>
		                                        				<option value="percentage_profitmargin" @if($productstockgroup->staffbonustype == 'percentage_profitmargin') SELECTED='SELECTED' @endif>% Of Profit Margin</option>
		                                        				<option value="percentage_saleprice" @if($productstockgroup->staffbonustype == 'percentage_saleprice') SELECTED='SELECTED' @endif>% Of RRP (Sale Price)</option>
		                                        				<option value="percentage_dealermargin" @if($productstockgroup->staffbonustype == 'percentage_dealermargin') SELECTED='SELECTED' @endif>% Of Dealer Margin</option>
		                                        				<option value="fixed" @if($productstockgroup->staffbonustype == 'fixed') SELECTED='SELECTED' @endif>Fixed Amount</option>
		                                        			</select>
		                                        		</div>
		                                        		<div class="col-md-4">
		                                        			<label>Dealer Margin</label>
		                                        			<input type="text" class="form-control" name="editdealermargin[]" value="{{$productstockgroup->dealermargin}}" required="">
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
		                                        			<input type="text" name="editstockgroupbonusvalue[]" class="form-control" value="{{$productstockgroup->staffbonus}}" style="width: 100%;">
		                                        		</div>
		                                        		<div class="col-md-4">
		                                        			<label style="width: 100%;">Remove</label>
		                                        			<a class="btn btn-danger waves-effect waves-light deletebtn" data-pointid="{{$productstockgroup->productSGID}}" style="color: #FFF;">Remove</a>
		                                        		</div>
		                                        	</div>
		                                        </fieldset>
                                        	</div>
                                        	@endforeach
                                    		<div id="field">
                                        		<div id="field0">
                                        			<fieldset>
                                                        <legend>Column 0</legend>
	                                        			<div class="row">
			                                        		<div class="col-md-6">
			                                        			<label>Select Group</label>
			                                        			<select name="stockgroup[]" class="form-control" style="width: 100%;">
			                                        				<option value="">SELECT STOCK GROUP</option>
			                                        				@foreach($editproductsdata['allstockgroup'] as $allstockgroup)
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
			                                        			<input type="text" class="form-control" name="dealermargin[]">
			                                        		</div>
			                                        		<div class="col-md-4">
			                                        			<label>Staff Bonus</label>
			                                        			<input type="text" name="stockgroupbonusvalue[]" class="form-control" style="width: 100%;">
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
		                                            <input type="hidden" name="productqtyid" value="{{$editproductsdata['products']->productqtyID}}">
		                                            <input type="text" name="min-qty" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="{{$editproductsdata['products']->minimumqty}}">
	                                        	</div>
	                                        	<div class="col-md-6">
		                                            <label>Maximum Quantity</label>
		                                            <input type="text" name="max-qty" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="{{$editproductsdata['products']->maximumqty}}">
		                                            
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
		                                            <input type="hidden" name="s-psdid" value="{{$editproductsdata['products']->psdID}}">
		                                            <input type="hidden" name="s-productname" class="form-control" placeholder="Type Here" value="{{$editproductsdata['products']->product_name}}">
	                                        	</div>
	                                        	<div class="col-md-6">
		                                            <!-- <label>Description</label> -->
		                                            <input type="hidden" name="s-description" class="form-control" placeholder="Type Here" value="{{$editproductsdata['products']->product_description}}">
		                                            
	                                        	</div>
	                                        </div>
                                        </div>
                                        <div class="form-group">
                                        	<div class="row">
	                                        	<div class="col-md-6">
		                                            <label>SKU Code</label>
		                                            <input type="text" name="s-sku" class="form-control" placeholder="Type Here" value="{{$editproductsdata['products']->productsku}}">
	                                        	</div>
	                                        	<div class="col-md-6">
		                                            <label>Supplier</label>
		                                            <select name="supplier" class="form-control" required="">
		                                            	<option value="">No Suppliers</option>
		                                            	@foreach($editproductsdata['allsuppliers'] as $allsuppliers)
		                                            	<option value="{{$allsuppliers->supplierID}}" @if($editproductsdata['products']->productsupplier == $allsuppliers->supplierID) SELECTED='SELECTED' @endif>{{$allsuppliers->suppliername}}</option>
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
                                                <!-- <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                    Cancel
                                                </button> -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->

            @include('includes.footer')

        </div>
    </div>
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="{{ asset('posview') }}/assets/js/calculation.js"></script>
    <script>
    $(document).ready(function(){

     $(".deletebtn").click(function(ev){
	    let pointid = $(this).attr("data-pointid");
	    $.ajax({
           type: 'POST',
           url: "{{route('ajaxdeletestockgroup')}}",
           dataType: 'json',
           headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
           data: {id:pointid,"_token": "{{ csrf_token() }}"},

           success: function (data) {
                  location.reload(true);            
           },
           error: function (data) {
                location.reload(true);
           }
	    });
	});
     
    });
    </script>
@endsection
        