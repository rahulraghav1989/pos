@extends('main')

@section('content')
	<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
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
                                <h4 class="page-title">Products</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                    <li class="breadcrumb-item active">Products</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <!---Add Model-->
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
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                    <!---Add Model-->
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
                            	@if(session()->has('productaddsuccess'))
									<div class="card-body">	                                
									    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
									        {{ session()->get('productaddsuccess') }}
									    </div>
									</div>
								@endif
								@if(session()->has('productadderror'))
								<div class="card-body">
								    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
								        {{ session()->get('productadderror') }}
								    </div>
								</div>
								@endif
                                <div class="card-body">
                                	<p style="color: red;">Fields with (*) are required</p>
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
		                                            <input type="text" name="ppgst" class="form-control" value="{{$productsdata['alltaxs'][0]['taxpercentage']}}" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
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
		                                            <input type="text" name="spgst" class="form-control" value="{{$productsdata['alltaxs'][0]['taxpercentage']}}" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
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
	                                        		<!---Add Colour Model-->
								                    <div class="modal fade addcolourmodel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
								                        <div class="modal-dialog modal-dialog-centered">
								                            <div class="modal-content">
								                                <div class="modal-header">
								                                    <h5 class="modal-title mt-0">Add a new colour</h5>
								                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                                        <span aria-hidden="true">&times;</span>
								                                    </button>
								                                </div>
								                                <div class="modal-body">
								                                	<span id="error_colour"></span>
							                                        <div class="form-group">
							                                            <label>Colour Name</label>
							                                            <input type="text" id="colourname" name="colourname" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <div class="form-group text-right">
							                                            <div>
							                                                <a id="addcolour" class="btn btn-primary waves-effect waves-light" style="color: #FFF;">
							                                                    Submit
							                                                </a>
							                                                <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
							                                                    Cancel
							                                                </button>
							                                            </div>
							                                        </div>
								                                </div>
								                            </div><!-- /.modal-content -->
								                        </div><!-- /.modal-dialog -->
								                    </div>
								                    <!---Add Colour Model-->
		                                            <label style="width: 100%;">Colour <span style="text-align: right; margin-left: 70%;"><a href="" style="color: #354558;" data-toggle="modal" data-target=".addcolourmodel" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Add Colour</a></span></label>
		                                            <select name="colour" class="form-control" id="colourdrop">
		                                            	<option value="0">No Colour</option>
		                                            	@foreach($productsdata['allcolours'] as $allcolours)
		                                            	<option value="{{$allcolours->colourID}}">{{$allcolours->colourname}}</option>
		                                            	@endforeach
		                                            </select>
	                                        	</div>
	                                        	<div class="col-md-6">
	                                        		<!---Add Model Model-->
								                    <div class="modal fade addmodel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
								                        <div class="modal-dialog modal-dialog-centered">
								                            <div class="modal-content">
								                                <div class="modal-header">
								                                    <h5 class="modal-title mt-0">Add a new model</h5>
								                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                                        <span aria-hidden="true">&times;</span>
								                                    </button>
								                                </div>
								                                <div class="modal-body">
								                                	<span id="error_model"></span>
								                                    <div class="form-group">
							                                            <label>Model Name</label>
							                                            <input type="text" id="modelname" name="modelname" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <div class="form-group">
							                                            <label>Related Brand</label>
							                                            <select name="brandid" id="brandid" class="form-control" required="">
							                                                <option value="0">Select Brand</option>
							                                                @foreach($productsdata['allbrands'] as $allbrand)
							                                                <option value="{{$allbrand->brandID}}">{{$allbrand->brandname}}</option>
							                                                @endforeach
							                                            </select>
							                                        </div>
							                                        <div class="form-group text-right">
							                                            <div>
							                                                <a id="addmodel" class="btn btn-primary waves-effect waves-light" style="color: #FFF;">
							                                                    Submit
							                                                </a>
							                                                <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
							                                                    Cancel
							                                                </button>
							                                            </div>
							                                        </div>
								                                </div>
								                            </div><!-- /.modal-content -->
								                        </div><!-- /.modal-dialog -->
								                    </div>
								                    <!---Add Model Model-->
		                                            <label style="width: 100%;">Model <span style="text-align: right; margin-left: 70%;"><a href="" style="color: #354558;" data-toggle="modal" data-target=".addmodel" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Add Model</a></span></label>
		                                            <select name="model" class="form-control" id="modeldrop">
		                                            	<option value="0">No Model</option>
		                                            	@foreach($productsdata['allmodels'] as $allmodels)
		                                            	<option value="{{$allmodels->modelID}}">{{$allmodels->modelname}}</option>
		                                            	@endforeach
		                                            </select>
	                                        	</div>
	                                        </div>
                                        </div>
                                        <div class="form-group">
                                        	<div class="row">
	                                        	<div class="col-md-6">
	                                        		<!---Add Brand Model-->
								                    <div class="modal fade addbrandmodel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
								                        <div class="modal-dialog modal-dialog-centered">
								                            <div class="modal-content">
								                                <div class="modal-header">
								                                    <h5 class="modal-title mt-0">Add a new brand</h5>
								                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                                        <span aria-hidden="true">&times;</span>
								                                    </button>
								                                </div>
								                                <div class="modal-body">
								                                	<span id="error_brand"></span>
								                                    <div class="form-group">
							                                            <label>Brand Name</label>
							                                            <input type="text" id="brandname" name="brandname" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <div class="form-group text-right">
							                                            <div>
							                                                <a id="addbrand" class="btn btn-primary waves-effect waves-light" style="color: #FFF;">
							                                                    Submit
							                                                </a>
							                                                <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
							                                                    Cancel
							                                                </button>
							                                            </div>
							                                        </div>
								                                </div>
								                            </div><!-- /.modal-content -->
								                        </div><!-- /.modal-dialog -->
								                    </div>
								                    <!---Add Brand Model-->
		                                            <label style="width: 100%;">Brand <span style="text-align: right; margin-left: 70%;"><a href="" style="color: #354558;" data-toggle="modal" data-target=".addbrandmodel" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Add Brand</a></span></label>
		                                            <select name="brand" class="form-control" id="branddrop">
		                                            	<option value="0">No Brand</option>
		                                            	@foreach($productsdata['allbrands'] as $allbrands)
		                                            	<option value="{{$allbrands->brandID}}">{{$allbrands->brandname}}</option>
		                                            	@endforeach
		                                            </select>
	                                        	</div>
	                                        	<div class="col-md-6">
		                                            <label>Categories (*)</label>
		                                            <select name="categories" class="form-control" required="">
		                                            	<option value="0">No Categories</option>
		                                            	@foreach($productsdata['allcategories'] as $allcategories)
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
                                            	@foreach($productsdata['allproducttype'] as $allproducttype)
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
												        var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input--><fieldset><legend>Column '+ next +'</legend><div class="row" style="margin-top:10px;"><div class="col-md-6"><label>Select Group (*)</label><select name="stockgroup[]" class="form-control" style="width:100%;" required><option value="">SELECT STOCK GROUP</option>@foreach($productsdata['allstockgroup'] as $allstockgroup)<option value="{{$allstockgroup->stockgroupID}}">{{$allstockgroup->stockgroupname}}</option>@endforeach</select></div><div class="col-md-6"><label>Bonus Type</label><select name="stockgroupbonustype[]" class="form-control" style="width:100%;"><option value="">SELECT TYPE</option><option value="percentage_profitmargin">% Of Profit Margin</option><option value="percentage_saleprice">% Of RRP (Sale Price)</option><option value="percentage_dealermargin">% Of Dealer Margin</option><option value="fixed">Fixed</option></select></div><div class="col-md-4"><label>Dealer Margin</label><input type="text" name="dealermargin[]" class="form-control" value="0.00"></div><div class="col-md-4"><label>Staff Bonus</label><input type="text" name="stockgroupbonusvalue[]" class="form-control" style="width:100%;" value="0.00"></div><div class="col-md-4"><label style="width:100%;">Remove</label><a id="remove' + (next) + '" class="btn btn-danger remove-me" style="color:#FFF;">Remove</a></div></div><div id="field"></div></fieldset></div>';
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
			                                        			<select name="stockgroup[]" id="stockgroup" class="form-control" style="width: 100%;" required>
			                                        				<option value="">SELECT STOCK GROUP</option>
			                                        				@foreach($productsdata['allstockgroup'] as $allstockgroup)
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
		                                            <input type="text" name="min-qty" class="form-control" placeholder="Type Here" onkeypress="return isNumber(event)" value="0.00">
	                                        	</div>
	                                        	<div class="col-md-6">
		                                            <label>Maximum Quantity</label>
		                                            <input type="text" name="max-qty" class="form-control" placeholder="Type Here" onkeypress="return isNumber(event)" value="0.00">
		                                            
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
	                                        		<!---Add Supplier Model-->
								                    <div id="addsuppliermodel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								                        <div class="modal-dialog">
								                            <div class="modal-content">
								                                <div class="modal-header">
								                                    <h5 class="modal-title mt-0" id="myModalLabel">Add new supplier</h5>
								                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                                        <span aria-hidden="true">&times;</span>
								                                    </button>
								                                </div>
								                                <div class="modal-body">
								                                	<span id="error_supplier"></span>
								                                    <div class="form-group">
							                                            <label>Supplier Name</label>
							                                            <input type="text" id="suppliername" name="suppliername" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <div class="form-group">
							                                            <label>Supplier Description</label>
							                                            <input type="text" id="supplierdescription" name="supplierdescription" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <div class="form-group row">
							                                            <div class="col-sm-6">
							                                                <label>Contact Number</label>
							                                                <input type="text" id="contactnumber" name="contactnumber" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                            <div class="col-sm-6">
							                                                <label>Alt. Contact Number</label>
							                                                <input type="text" id="altcontactnumber" name="altcontactnumber" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                        </div>
							                                        <div class="form-group">
							                                            <label>ACN/ABN</label>
							                                            <input type="text" id="acbabn" name="acbabn" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <div class="form-group">
							                                            <label>Email</label>
							                                            <input type="text" id="email" name="email" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <div class="form-group">
							                                            <label>Website</label>
							                                            <input type="text" id="website" name="website" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <hr>
							                                        <p>Contact Person Details</p>
							                                        <hr>
							                                        <div class="form-group row">
							                                            <div class="col-sm-6">
							                                                <label>Name</label>
							                                                <input type="text" id="personname" name="personname" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                            <div class="col-sm-6">
							                                                <label>Number</label>
							                                                <input type="text" id="personnumber" name="personnumber" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                        </div>
							                                        <div class="form-group">
							                                            <label>Email</label>
							                                            <input type="text" id="personemail" name="personemail" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <hr>
							                                        <p>Supplier Address</p>
							                                        <hr>
							                                        <div class="form-group row">
							                                            <div class="col-sm-4">
							                                                <label>Unit Number</label>
							                                                <input type="text" id="unitnumber" name="unitnumber" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                            <div class="col-sm-4">
							                                                <label>Street Number</label>
							                                                <input type="text" id="streetnumber" name="streetnumber" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                            <div class="col-sm-4">
							                                                <label>Street Name</label>
							                                                <input type="text" id="streetname" name="streetname" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                        </div>
							                                        <div class="form-group row">
							                                            <div class="col-sm-4">
							                                                <label>Suburb Name</label>
							                                                <input type="text" id="suburbname" name="suburbname" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                            <div class="col-sm-4">
							                                                <label>Post Code</label>
							                                                <input type="text" id="postcode" name="postcode" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                            <div class="col-sm-4">
							                                                <label>State</label>
							                                                <input type="text" id="state" name="state" class="form-control" required="" placeholder="Type Here">
							                                            </div>
							                                        </div>
							                                        <div class="form-group">
							                                            <label>Country</label>
							                                            <input type="text" id="country" name="country" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <hr>
							                                        Note (If Any)
							                                        <hr>
							                                        <div class="form-group">
							                                            <label>Note</label>
							                                            <input type="text" id="note" name="note" class="form-control" required="" placeholder="Type Here">
							                                        </div>
							                                        <div class="form-group text-right">
							                                            <div>
							                                                <a id="addsupplier" class="btn btn-primary waves-effect waves-light">
							                                                    Submit
							                                                </a>
							                                                <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
							                                                    Cancel
							                                                </button>
							                                            </div>
							                                        </div>
								                                </div>
								                            </div><!-- /.modal-content -->
								                        </div><!-- /.modal-dialog -->
								                    </div>
								                    <!---Add Supplier Model-->
		                                            <label style="width: 100%;">Supplier  (*)<span style="text-align: right; margin-left: 63%;"><a href="" style="color: #354558;" data-toggle="modal" data-target="#addsuppliermodel" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Add Supplier</a></span></label>
		                                            <select name="supplier" class="form-control" required="" id="supplierdrop">
		                                            	<option value="">No Suppliers</option>
		                                            	@foreach($productsdata['allsuppliers'] as $allsuppliers)
		                                            	<option value="{{$allsuppliers->supplierID}}">{{$allsuppliers->suppliername}}</option>
		                                            	@endforeach
		                                            </select>
		                                            
	                                        	</div>
	                                        </div>
                                        </div>
                                        <div class="form-group text-right">
                                            <div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light addproduct">Submit</button>
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

    <script>
    $(document).ready(function(){

     $('#addcolour').click(function(){
      var error_email = '';
      var $newoption = '';
      var colourname = $('#colourname').val();
      var _token = $('input[name="_token"]').val();
      var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      $.ajax({
        url:"{{ route('ajaxaddcolour') }}",
        method:"POST",
        data:{colourname:colourname, _token:_token},
        success:function(result)
        {
         if(result['success'] == 'unique')
         {
          $('#error_colour').html('<label class="text-success">Colour Added Successfully</label>');
          $newoption = '<option value='+result["colourID"]+'>'+result["colourname"]+'</option>';
          $("#colourdrop").append($newoption);
          $(".addcolourmodel").modal("hide");
         }
         else
         {
          $('#error_colour').html('<label class="text-danger">Colour Not Added</label>');
          
         }
        }
       })
     });
     
    });
    </script>

    <script>
    $(document).ready(function(){

     $('#addmodel').click(function(){
      var error_email = '';
      var $newoption = '';
      var modelname = $('#modelname').val();
      var brandid = $('#brandid').val();
      var _token = $('input[name="_token"]').val();
      var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      $.ajax({
        url:"{{ route('ajaxaddmodel') }}",
        method:"POST",
        data:{modelname:modelname, brandid:brandid, _token:_token},
        success:function(result)
        {
         if(result['success'] == 'unique')
         {
          $('#error_model').html('<label class="text-success">Model Added Successfully</label>');
          $newoption = '<option value='+result["modelid"]+'>'+result["modelname"]+'</option>';
          $("#modeldrop").append($newoption);
          $(".addmodel").modal("hide");
         }
         else
         {
          $('#error_model').html('<label class="text-danger">Model Not Added</label>');
          
         }
        }
       })
     });
     
    });
    </script>

    <script>
    $(document).ready(function(){

     $('#addbrand').click(function(){
      var error_email = '';
      var $newoption = '';
      var brandname = $('#brandname').val();
      var _token = $('input[name="_token"]').val();
      var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      $.ajax({
        url:"{{ route('ajaxaddbrand') }}",
        method:"POST",
        data:{brandname:brandname, _token:_token},
        success:function(result)
        {
         if(result['success'] == 'unique')
         {
          $('#error_brand').html('<label class="text-success">Brand Added Successfully</label>');
          $newoption = '<option value='+result["brandid"]+'>'+result["brandname"]+'</option>';
          $("#branddrop").append($newoption);
          $(".addbrandmodel").modal("hide");
         }
         else
         {
          $('#error_brand').html('<label class="text-danger">Brand Not Added</label>');
          
         }
        }
       })
     });
     
    });
    </script>

    <script>
    $(document).ready(function(){

     $('#addsupplier').click(function(){
      var error_email = '';
      var $newoption = '';
      var suppliername = $('#suppliername').val();
      var supplierdescription = $('#supplierdescription').val();
      var contactnumber = $('#contactnumber').val();
      var altcontactnumber = $('#altcontactnumber').val();
      var acbabn = $('#acbabn').val();
      var email = $('#email').val();
      var website = $('#website').val();
      var personname = $('#personname').val();
      var personnumber = $('#personnumber').val();
      var personemail = $('#personemail').val();
      var unitnumber = $('#unitnumber').val();
      var streetnumber = $('#streetnumber').val();
      var streetname = $('#streetname').val();
      var suburbname = $('#suburbname').val();
      var postcode = $('#postcode').val();
      var state = $('#state').val();
      var country = $('#country').val();
      var note = $('#note').val();
      var _token = $('input[name="_token"]').val();
      var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      $.ajax({
        url:"{{ route('ajaxaddsupplier') }}",
        method:"POST",
        data:{suppliername:suppliername, supplierdescription:supplierdescription, contactnumber:contactnumber, altcontactnumber:altcontactnumber, acbabn:acbabn, email:email, website:website, personname:personname, personnumber:personnumber, personemail:personemail, unitnumber:unitnumber, streetnumber:streetnumber, streetname:streetname, suburbname:suburbname, postcode:postcode, state:state, country:country, note:note, _token:_token},
        success:function(result)
        {
         if(result['success'] == 'unique')
         {
          $('#error_supplier').html('<label class="text-success">Supplier Added Successfully</label>');
          $newoption = '<option value='+result["supplierid"]+'>'+result["suppliername"]+'</option>';
          $("#supplierdrop").append($newoption);
          $("#addsuppliermodel").modal("hide");
         }
         else
         {
          $('#error_supplier').html('<label class="text-danger">Supplier Not Added</label>');
          
         }
        }
       })
     });
     
    });
    </script>
@endsection
        