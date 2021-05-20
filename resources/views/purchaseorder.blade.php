@extends('main')

@section('content')
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
	@include('includes.topbar')

    @include('includes.sidebar')
    <script>
    	function isNumber(evt) {
		    evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;
		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		}
    </script>
    <style type="text/css">
    	.hide{display: none;}
    </style>
    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                            
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                    <li class="breadcrumb-item active">Purchase Order</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                            	<div class="card-body">
                            		<div class="col-12">
                                        <div class="text-right">
                                            <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                Filters
                                            </a>
                                            @if(session('loggindata')['loggeduserpermission']->changestore=='Y')
                                            <!----Store Change Model--->
                                            <div class="modal fade changestore" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0">Change Store</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{route('changestore')}}" method="post">
                                                                @csrf
                                                                <select name="store" class="form-control">
                                                                    <option value="">SELECT STORE</option>
                                                                    @foreach(session('allstore') as $storename)
                                                                    <option value="{{$storename->store_id}}" @if(session('storeid') == $storename->store_id) SELECTED='SELECTED' @endif>{{$storename->store_name}}</option>
                                                                    @endforeach           
                                                                </select>
                                                                <br>
                                                                <button type="submit" class="btn btn-primary">Select</button>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <!----Store Change Model--->
                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore">Change Store</button>
                                            @endif
                                            @if(count(session('loggindata')['loggeduserstore'])==1)
                                                @if(session('loggindata')['loggeduserpermission']->addpurchaseorder=='Y')
                                                
                                                <form action="createpostepfirst" method="post" style="float: right; margin-left: 10px;">
                                                    @csrf
                                                    <input type="hidden" name="store" value="@if(session('storeid') == '')@foreach(session('loggindata')['loggeduserstore'] as $postore) {{$postore->store_id}} @endforeach @else {{session('storeid')}} @endif">
                                                    <button type="submit" class="btn btn-outline-primary waves-effect waves-light">Create Purchase Order</button>
                                                </form>
                                                @endif
                                            @else
                                                @if(session('loggindata')['loggeduserpermission']->addpurchaseorder=='Y')
                                                    <button type="button" class="btn btn-light waves-effect waves-light">Create Purchase Order</button>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form action="{{route('pofilter')}}" method="post">
                                                      @csrf
                                                      <div class="row">
                                                        @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderfilters=='Y')
                                                        <div class="col-md-3">
                                                            <div style="display: none;">
                                                                @php
                                                                $storeid = 0;
                                                                @endphp
                                                                @foreach($poviewdata['store'] as $storeid)
                                                                    $storeid = $storeid;
                                                                @endforeach
                                                            </div>
                                                            <select class="form-control" name="store">
                                                                <option value="">SELECT STORE</option>
                                                                @foreach(session('filtersdata')['allstores'] as $allstores)
                                                                <option value="{{$allstores->store_id}}" @if($storeid==$allstores->store_id) SELECTED='SELECTED' @endif>{{$allstores->store_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select class="form-control" name="supplier">
                                                                <option value="">SELECT SUPPLIER</option>
                                                                @foreach(session('filtersdata')['allsuppliers'] as $allsuppliers)
                                                                <option value="{{$allsuppliers->supplierID}}" @if($poviewdata['supplier']==$allsuppliers->supplierID) SELECTED='SELECTED' @endif>{{$allsuppliers->suppliername}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select name="postatus" class="form-control">
                                                                <option value="">SELECT PO TYPE</option>
                                                                <option value="1" @if($poviewdata['processstatus']=='1') SELECTED='SELECTED' @endif>Pending PO</option>
                                                                <option value="2" @if($poviewdata['processstatus']=='2') SELECTED='SELECTED' @endif>Completed PO</option>
                                                                <option value="4" @if($poviewdata['processstatus']=='4') SELECTED='SELECTED' @endif>Partital PO</option>
                                                                <option value="3" @if($poviewdata['processstatus']=='3') SELECTED='SELECTED' @endif>In-Active PO</option>          
                                                            </select>
                                                        </div>
                                                        @endif
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <div>
                                                                    <div class="input-daterange input-group" id="date-range">
                                                                        <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($poviewdata['firstday'])) @endphp" />
                                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($poviewdata['lastday'])) @endphp" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 text-right">
                                                          <button type="submit" class="btn btn-success">Apply Filters</button>
                                                        </div>
                                                      </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            	</div>
                            	@if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                            	@if(session()->has('posuccess'))
									<div class="card-body">	                                
									    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
									        {{ session()->get('posuccess') }}
									    </div>
									</div>
								@endif
								@if(session()->has('poerror'))
								<div class="card-body">
								    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
								        {{ session()->get('poerror') }}
								    </div>
								</div>
								@endif
                                @if(session()->has('statusposuccess'))
                                <div class="card-body">
                                    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                        {{ session()->get('statusposuccess') }}
                                    </div>
                                </div>
                                @endif
                                @if(session()->has('statuspoerror'))
                                <div class="card-body">
                                    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                        {{ session()->get('statuspoerror') }}
                                    </div>
                                </div>
                                @endif
                                <div class="card-body">
                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                        	<th>PO Number</th>
                                        	<th>PO Ref. Number</th>
                                        	<th>Store</th>
                                            <th>Supplier</th>
                                            <th>Items</th>
                                            <th>Status</th>
                                            <th>Added By/On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        	@foreach($poviewdata['allpurchaseorder']->groupBy('poID') as $allpo)
                                        	<tr>
                                        		<td>{{$allpo[0]->ponumber}}</td>
                                        		<td>{{$allpo[0]->porefrencenumber}}</td>
                                        		<td>{{$allpo[0]->store_name}}</td>
                                        		<td>{{$allpo[0]['posupplier']->suppliername}}</td>
                                        		<td>{{count($allpo[0]['poitem'])}}</td>
                                                <td>
                                                    @if($allpo[0]['poprocessstatus'] == 1)
                                                    <span class="badge badge-danger">Pending</span>
                                                    @elseif($allpo[0]['poprocessstatus'] == 2)
                                                    <span class="badge badge-success">Completed</span>
                                                    @elseif($allpo[0]['poprocessstatus'] == 3)
                                                    <span class="badge badge-warning">In-Active</span>
                                                    @elseif($allpo[0]['poprocessstatus'] == 4)
                                                    <span class="badge badge-info">Partital</span>
                                                    @endif
                                                </td>
                                        		<td>{{$allpo[0]->name}}<br>@php echo date('d-m-Y H:i:s', strtotime($allpo[0]->created_at)) @endphp</td>
                                        		<td>
                                                    @if(session('loggindata')['loggeduserpermission']->editpurchaseorder=='Y')
                                                        @if(session('loggindata')['loggeduserstore']!='')
                                                            @if($allpo[0]['poprocessstatus'] != 2 && $allpo[0]['poprocessstatus'] != 4)
                                                            <a href="purchaseordercreation/{{$allpo[0]->ponumber}}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a> |
                                        			         @endif
                                                         @else
                                                            <a class="btn btn-light waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a> |
                                                         @endif
                                                    @endif
                                                    <!---Item View Model --->
                                        			<div class="modal fade bs-example-modal-lg{{$allpo[0]->ponumber}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
	                                                    <div class="modal-dialog modal-lg">
	                                                        <div class="modal-content">
	                                                            <div class="modal-header">
	                                                                <h5 class="modal-title mt-0" id="myLargeModalLabel">PO Number : {{$allpo[0]->ponumber}}</h5>
	                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                                                    <span aria-hidden="true">×</span>
	                                                                </button>
	                                                            </div>
	                                                            <div class="modal-body">
	                                                                <table id="datatable1" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								                                        <thead>
								                                        <tr>
								                                        	<th>Product Name</th>
								                                        	<th>Quantity</th>
                                                                            <th>Rec. Qty</th>
                                                                            @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
								                                            <th>PP (Inc. GST)</th>
                                                                            @endif
								                                            <th>Status</th>
								                                        </tr>
								                                        </thead>
								                                        <tbody>
								                                        	@foreach($allpo as $allpoitem)
								                                        	<tr>
								                                        		<td>
								                                        			{{$allpoitem->productname}}
                                                                                    @if($allpoitem->brandname!='')
                                                                                    <br>
                                                                                    {{$allpoitem->brandname}}
                                                                                    @endif
                                                                                    @if($allpoitem->colourname!='')
                                                                                    <br>
                                                                                    {{$allpoitem->colourname}}
                                                                                    @endif
                                                                                    @if($allpoitem->modelname!='')
                                                                                    <br>
                                                                                    {{$allpoitem->modelname}}
                                                                                    @endif
								                                        		</td>
								                                        		<td>{{$allpoitem->poquantity}}</td>
                                                                                <td>{{$allpoitem->receivequantity}}</td>
                                                                                @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
								                                        		<td>{{$allpoitem->poppingst}}</td>
                                                                                @endif
								                                        		<td>
								                                        			@if($allpoitem->poitemstatus=='0')
								                                        			Not Received
								                                        			@elseif($allpoitem->poitemstatus=='1')
								                                        			Parital Received
								                                        			@elseif($allpoitem->poitemstatus=='2')
								                                        			Complete Received
								                                        			@endif
								                                        		</td>
								                                        	</tr>
								                                        	@endforeach
								                                        </tbody>
								                                    </table>
	                                                            </div>
	                                                        </div><!-- /.modal-content -->
	                                                    </div><!-- /.modal-dialog -->
	                                                </div>
                                        			<!---Item View Model --->
                                        			<span data-toggle="modal" data-target=".bs-example-modal-lg{{$allpo[0]->ponumber}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="View"><i class="icon-eye"></i></a></span> |
                                        			@if($allpo[0]->poprocessstatus=='1')
                                                        <!---Active Status Model--->
                                                        <div class="modal fade bs-example-modal-center activestatusmodel{{$allpo[0]->poID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0">{{$allpo[0]->ponumber}} Status</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form class="" action="{{route('editpostatus')}}" method="post" novalidate="">
                                                                            @csrf
                                                                            <div class="form-group">
                                                                                <h4>{{$allpo[0]->ponumber}} is in <span class="badge badge-primary">Active Status</span></h4>
                                                                                <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
                                                                                <p>Click on OK to continue or cancle it.</p>
                                                                                <input type="hidden" name="poid" class="form-control" value="{{$allpo[0]->poID}}">
                                                                                <input type="hidden" name="processstatus" class="form-control" value="3">
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
                                                        <!---Active Status Model--->
                                                        @if(session('loggindata')['loggeduserpermission']->deletepurchaseorder=='Y')
                                                        <span data-toggle="modal" data-target=".activestatusmodel{{$allpo[0]->poID}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a></span>
                                            		    @else
                                                        <a class="btn btn-light waves-effect" title="Active" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a>
                                                        @endif
                                                    @endif
                                                </td>
                                        	</tr>
                                        	@endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-body">
                                    <h4>Un-Proccessed PO</h4>
                                    <table id="datatable1" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>PO Ref. Number</th>
                                            <th>Store</th>
                                            <th>Supplier</th>
                                            <th>Items</th>
                                            <th>Added By/On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($poviewdata['allincompletepurchaseorder'] as $allpo)
                                            <tr>
                                                <td>{{$allpo->ponumber}}</td>
                                                <td>{{$allpo->porefrencenumber}}</td>
                                                <td>{{$allpo->store_name}}</td>
                                                <td>{{$allpo->posupplier['suppliername']}}</td>
                                                <td>{{count($allpo['poitem'])}}</td>
                                                <td>{{$allpo->name}}<br>@php echo date('d-m-Y H:i:s', strtotime($allpo->created_at)) @endphp</td>
                                                <td>
                                                    @if(session('loggindata')['loggeduserpermission']->addpurchaseorder=='Y')
                                                        @if(session('loggindata')['loggeduserstore']!='')
                                                        <a href="purchaseordercreation/{{$allpo->ponumber}}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Re-use"><i class="fas fa-reply"></i></a>
                                                        @else
                                                        <a class="btn btn-light waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Re-use"><i class="fas fa-reply"></i></a>
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
                    </div> <!-- end row -->
                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->

            @include('includes.footer')

        </div>
    </div>
    <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
    <script src="{{ asset('posview') }}/assets/js/po-calculation.js"></script>

    <script src="{{ asset('posview') }}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="{{ asset('posview') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('posview') }}/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="{{ asset('posview') }}/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>
    <script src="{{ asset('posview') }}/assets/pages/form-advanced.js"></script>
    <!-- <script type="text/javascript">
	    $(document).ready(function() {
	      $(".add-more").click(function(){ 
	          var html = $(".copy").html();
	          $(".after-add-more").after(html);
	      });

	      $("body").on("click",".remove",function(){ 
	          $(this).parents(".removeable").remove();
	      });
	    });
	</script> -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable1').DataTable();
        } );
    </script>
@endsection
        