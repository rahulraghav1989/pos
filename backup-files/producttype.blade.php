@extends('main')

@section('content')
	@include('includes.topbar')

    @include('includes.sidebar')
    <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">Product Type</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Masters</a></li>
                                    <li class="breadcrumb-item active">Product Type</li>
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
                                    <h5 class="modal-title mt-0">Add a new product type</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="" action="{{route('addproducttype')}}" method="post" novalidate="">
                                    	@csrf
                                        <div class="form-group">
                                            <label>Product Type Name *</label>
                                            <input type="text" name="producttypename" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Product Type *</label>
                                            <select name="producttype" class="form-control" required="">
                                                <option value="">SELECT</option>
                                                <option value="1">Unique Based</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Restriction Type *</label>
                                            <select name="restrictiontype" class="form-control" required="">
                                                <option value="">SELECT</option>
                                                <option value="1">Numeric</option>
                                                <option value="2">Alphabets</option>
                                                <option value="3">AlphaNumeric</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Restriction Word (Opt.)</label>
                                            <input type="text" name="restrictionword" class="form-control" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Additional Unique Product</label>
                                            <input type="checkbox" name="addtionalunique" value="1" id="checkbox">
                                        </div>
                                        <div id="addtionalfields" style="display: none;">
                                            <div class="form-group">
                                                <label>(Add)Product Type Name *</label>
                                                <input type="text" name="add_producttypename" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="form-group">
                                                <label>(Add)Product Type *</label>
                                                <select name="add_producttype" class="form-control" required="">
                                                    <option value="">SELECT</option>
                                                    <option value="1">Unique Based</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>(Add)Restriction Type *</label>
                                                <select name="add_restrictiontype" class="form-control" required="">
                                                    <option value="">SELECT</option>
                                                    <option value="1">Numeric</option>
                                                    <option value="2">Alphabets</option>
                                                    <option value="3">AlphaNumeric</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>(Add)Restriction Word (Opt.)</label>
                                                <input type="text" name="add_restrictionword" class="form-control" placeholder="Type Here">
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
                    <!---Add Model-->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                            	@if(session('loggindata')['loggeduserpermission']->addmasters=='Y')
                            	<div class="card-body">
                            		<div class="col-12 text-right">
                            			<button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false">Add Product Type</button>
                            		</div>
                            	</div>
                            	@endif
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
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Product Type</th>
                                            <th>Product Type Based</th>
                                            <th>Restriction Type</th>
                                            <th>Restriction Words</th>
                                            <th>Added By/On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($allproducttype as $producttype)
                                        <tr>
                                            <td>{{$producttype->producttypename}}</td>
                                            <td>
                                                @if($producttype->producttype == '1')
                                                Unique Based
                                                @endif
                                            </td>
                                            <td>
                                                @if($producttype->productrestrictiontype == 1)
                                                Numeric
                                                @elseif($producttype->productrestrictiontype == 2)
                                                Alphabets
                                                @elseif($producttype->productrestrictiontype == 3)
                                                AlphaNumeric
                                                @endif
                                            </td>
                                            <td>{{$producttype->productrestrictionword}}</td>
                                            <td>{{$producttype->name}}<br>{{$producttype->created_at}}</td>
                                            <td>
                                                @if(session('loggindata')['loggeduserpermission']->editmasters=='Y')
                                                <!--EDIT MODEL-->
                                                <div class="modal fade bs-example-modal-center editmodel{{$producttype->producttypeID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">Edit product type</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <script type="text/javascript">
                                                                    $(document).ready(function () {
                                                                        
                                                                        $("#checkbox{{$producttype->producttypeID}}").click(function () {
                                                                            if ($(this).is(":checked")) {
                                                                                $("#addtionalfields{{$producttype->producttypeID}}").show();
                                                                            } else {
                                                                                $("#addtionalfields{{$producttype->producttypeID}}").hide();
                                                                            }
                                                                        });
                                                                    });
                                                                </script>
                                                                <form class="" action="{{route('editproducttype')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <input type="hidden" name="producttypeid" value="{{$producttype->producttypeID}}">
                                                                    <div class="form-group">
                                                                        <label>Product Type Name *</label>
                                                                        <input type="text" name="producttypename" class="form-control" required="" value="{{$producttype->producttypename}}" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Product Type *</label>
                                                                        <select name="producttype" class="form-control" required="">
                                                                            <option value="">SELECT</option>
                                                                            <option value="1" @if($producttype->producttype==1) SELECTED='SELECTED' @endif>Unique Based</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Restriction Type *</label>
                                                                        <select name="restrictiontype" class="form-control" required="">
                                                                            <option value="">SELECT</option>
                                                                            <option value="1" @if($producttype->productrestrictiontype==1) SELECTED='SELECTED' @endif>Numeric</option>
                                                                            <option value="2" @if($producttype->productrestrictiontype==2) SELECTED='SELECTED' @endif>Alphabets</option>
                                                                            <option value="3" @if($producttype->productrestrictiontype==3) SELECTED='SELECTED' @endif>AlphaNumeric</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Restriction Word (Opt.)</label>
                                                                        <input type="text" name="restrictionword" class="form-control" placeholder="Type Here" value="{{$producttype->productrestrictionword}}">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Additional Unique Product</label>
                                                                        <input type="checkbox" id="checkbox{{$producttype->producttypeID}}" name="addtionalunique" @if($producttype->addtionalproducttype == '1') checked="checked" @endif>
                                                                    </div>
                                                                    <div id="addtionalfields{{$producttype->producttypeID}}" @if($producttype->addtionalproducttype != '1') style="display: none;" @endif>
                                                                        <div class="form-group">
                                                                            <label>(Add)Product Type Name *</label>
                                                                            <input type="text" name="add_producttypename" class="form-control" value="{{$producttype->add_producttypename}}" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>(Add)Product Type *</label>
                                                                            <select name="add_producttype" class="form-control" required="">
                                                                                <option value="">SELECT</option>
                                                                                <option value="1" @if($producttype->add_producttype==1) SELECTED='SELECTED' @endif>Unique Based</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>(Add)Restriction Type *</label>
                                                                            <select name="add_restrictiontype" class="form-control" required="">
                                                                                <option value="">SELECT</option>
                                                                                <option value="1" @if($producttype->add_productrestrictiontype==1) SELECTED='SELECTED' @endif>Numeric</option>
                                                                                <option value="2" @if($producttype->add_productrestrictiontype==2) SELECTED='SELECTED' @endif>Alphabets</option>
                                                                                <option value="3" @if($producttype->add_productrestrictiontype==3) SELECTED='SELECTED' @endif>AlphaNumeric</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>(Add)Restriction Word (Opt.)</label>
                                                                            <input type="text" name="add_restrictionword" class="form-control" placeholder="Type Here" value="{{$producttype->add_productrestrictionword}}">
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
                                                <!--EDIT MODEL-->
                                                <a href="" class="btn btn-outline-success waves-effect waves-light" data-toggle="modal" data-target=".editmodel{{$producttype->producttypeID}}" data-backdrop="static" data-keyboard="false"><i class="icon-pencil"></i></a>
                                                @else
                                                <a class="btn btn-light waves-effect"><i class="icon-pencil"></i></a> 
                                                @endif
                                                @if($producttype->producttypestatus == 1)
                                                <!---Active Model-->
                                                <div class="modal fade bs-example-modal-center activestatusmodel{{$producttype->producttypeID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">{{$producttype->producttypename}} Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editproducttypestatus')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <h4>{{$producttype->producttypename}} is in <span class="badge badge-primary">Active Status</span></h4>
                                                                        <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
                                                                        <p>Click on yes to continue or cancle it.</p>
                                                                        <input type="hidden" name="producttypeid" class="form-control" value="{{$producttype->producttypeID}}">
                                                                        <input type="hidden" name="producttypestatus" class="form-control" value="0">
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <div>
                                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                OK
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
                                                <!---Active Model-->
                                                @if(session('loggindata')['loggeduserpermission']->deletemaster=='Y')
                                                <a class="btn btn-outline-success waves-effect waves-light" title="Active" data-toggle="modal" data-target=".activestatusmodel{{$producttype->producttypeID}}" data-backdrop="static" data-keyboard="false"><i class="icon-music-play"></i></a>
                                                @else
                                                <a class="btn btn-light waves-effect" title="Active"><i class="icon-music-play"></i></a>
                                                @endif
                                                
                                                @else
                                                <!--Inactive model-->
                                                <div class="modal fade bs-example-modal-center inactivestatusmodel{{$producttype->producttypeID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">{{$producttype->producttypename}} Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editproducttypestatus')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <h4>{{$producttype->producttypename}} is in <span class="badge badge-primary">Inactive Status</span></h4>
                                                                        <h4>Do you want to make it <span class="badge badge-primary">Active Status</span></h4>
                                                                        <p>Click on yes to continue or cancle it.</p>
                                                                        <input type="hidden" name="producttypeid" class="form-control" value="{{$producttype->producttypeID}}">
                                                                        <input type="hidden" name="producttypestatus" class="form-control" value="1">
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <div>
                                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                OK
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
                                                <!--Inactive model-->
                                                @if(session('loggindata')['loggeduserpermission']->deletemaster=='Y')
                                                <a class="btn btn-outline-danger waves-effect waves-light" data-toggle="modal" data-target=".inactivestatusmodel{{$producttype->producttypeID}}" data-backdrop="static" data-keyboard="false" title="Inactive"><i class="icon-music-pause"></i></a>
                                                @else
                                                <a class="btn btn-light waves-effect" title="Inactive"><i class="icon-music-pause"></i></a>
                                                @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
    
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> 
                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->

            @include('includes.footer')
            
            <script type="text/javascript">
                $(document).ready(function () {
                    /*$('#checkbox').change(function () {
                        if (!this.checked) 
                        //  ^
                           $('#addtionalfields').hide('slow');
                        else 
                            $('#addtionalfields').show('slow');
                    });*/
                    /*$('#checkbox').change(function () {
                      $('#addtionalfields').fadeToggle();
                    });*/
                    $("#checkbox").click(function () {
                        if ($(this).is(":checked")) {
                            $("#addtionalfields").show();
                        } else {
                            $("#addtionalfields").hide();
                        }
                    });
                });
            </script>
            
        </div>
    </div>
@endsection
        