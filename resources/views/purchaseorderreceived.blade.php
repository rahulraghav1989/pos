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
                                <h4 class="page-title">Purchase Order</h4>
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
                                                                    <option value="">Select Store</option>
                                                                    @foreach(session('allstore') as $storename)
                                                                    <option value="{{$storename->store_id}}" @if(session('storeid')==$storename->store_id) SELECTED=='SELECTED' @endif>{{$storename->store_name}}</option>
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
                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore" data-backdrop="static" data-keyboard="false">Change Store</button>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form action="{{route('poreceivefilter')}}" method="post">
                                                      @csrf
                                                      <div class="row">
                                                        @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderreceivefilters=='Y')
                                                        <div class="col-md-3">
                                                            <div style="display: none;">
                                                                @php
                                                                $storeid = 0;
                                                                @endphp
                                                                @foreach($receivepodata['store'] as $storeid)
                                                                    $storeid = $storeid;
                                                                @endforeach
                                                            </div>
                                                            <select class="form-control" name="store">
                                                                <option value="">SELECT STORE</option>
                                                                @foreach(session('allstore') as $allstores)
                                                                <option value="{{$allstores->store_id}}" @if($storeid==$allstores->store_id) SELECTED='SELECTED' @endif>{{$allstores->store_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select class="form-control" name="supplier">
                                                                <option value="">SELECT SUPPLIER</option>
                                                                @foreach(session('filtersdata')['allsuppliers'] as $allsuppliers)
                                                                <option value="{{$allsuppliers->supplierID}}" @if($receivepodata['supplier']==$allsuppliers->supplierID) SELECTED='SELECTED' @endif>{{$allsuppliers->suppliername}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @endif
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <div>
                                                                    <div class="input-daterange input-group" id="date-range">
                                                                        <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($receivepodata['firstday'])) @endphp" />
                                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($receivepodata['lastday'])) @endphp" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 text-right">
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
                            	@if(session()->has('poreceiverror'))
									<div class="card-body">	                                
									    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
									        {{ session()->get('poreceiverror') }}
									    </div>
									</div>
								@endif
								@if(session()->has('poreceivsuccess'))
								<div class="card-body">
								    <div class="alert alert-success" role="alert" style="margin-top: 10px;">
								        {{ session()->get('poreceivsuccess') }}
								    </div>
								</div>
								@endif
                                <div class="card-body">
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                        	@foreach($receivepodata['allpurchaseorder']->groupBy('ponumber') as $allpo)
                                        	<tr>
                                        		<td>{{$allpo[0]->ponumber}}</td>
                                                <td>{{$allpo[0]->porefrencenumber}}</td>
                                                <td>{{$allpo[0]->store_name}}</td>
                                                <td>{{$allpo[0]['posupplier']->suppliername}}</td>
                                                <td>{{count($allpo[0]['poitem'])}}</td>
                                                <td>{{$allpo[0]->name}}<br>@php echo date('d-m-Y H:i:s', strtotime($allpo[0]->created_at)) @endphp</td>
                                                <td>
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
                                                                    <table id="datatable2" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Product Name</th>
                                                                            <th>Quantity</th>
                                                                            <th>Received Quantity</th>
                                                                            @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
                                                                            <th>PP</th>
                                                                            <th>RRP</th>
                                                                            @endif
                                                                            <th>Status</th>
                                                                            @if(session('loggindata')['loggeduserpermission']->receivepurchaseorder=='Y')
                                                                            
                                                                            <th>
                                                                                Action
                                                                            </th>
                                                                            @endif
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($allpo as $allpoitem)
                                                                            <tr>
                                                                                <td>
                                                                                    {{$allpoitem->productname}}
                                                                                </td>
                                                                                <td>{{$allpoitem->poquantity}}</td>
                                                                                <td>{{$allpoitem->receivequantity}}</td>
                                                                                @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
                                                                                <td>{{$allpoitem->popurchaseprice}}</td>
                                                                                <td>{{$allpoitem->posalesprice}}</td>
                                                                                @endif
                                                                                <td>
                                                                                    @if($allpoitem->poitemstatus=='0')
                                                                                    Pending
                                                                                    @elseif($allpoitem->poitemstatus=='1')
                                                                                    Parital Received
                                                                                    @elseif($allpoitem->poitemstatus=='2')
                                                                                    Complete Received
                                                                                    @endif
                                                                                </td>
                                                                                @if(session('loggindata')['loggeduserpermission']->receivepurchaseorder=='Y')
                                                                                    <td>
                                                                                        @if($allpoitem->poitemstatus=='2')
                                                                                        Stock Received
                                                                                        @else
                                                                                        <form action="poreceivestep1" method="post">
                                                                                            @csrf
                                                                                            <div class="form-group">
                                                                                                <label>Docket (Optional)</label>
                                                                                                <input type="text" name="docketnumber" placeholder="Type Here" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Recevied Quantity *</label>
                                                                                                <input type="text" name="receivedquantity" placeholder="Type Here" required="" class="form-control">
                                                                                                <input type="hidden" name="poitemid" placeholder="Type Here" required="" class="form-control" value="{{$allpoitem->poitemID}}">
                                                                                                <input type="hidden" name="storeid" placeholder="Type Here" required="" class="form-control" value="{{$allpo[0]->storeID}}">
                                                                                            </div>
                                                                                            <button type="submit" class="btn btn-outline-success waves-effect waves-light">Receive</button>
                                                                                        </form>
                                                                                        @endif
                                                                                    </td>
                                                                                @endif
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
                                                    <!---Item View Model --->
                                                    <a href="purchaseorderreceiveitem/{{$allpo[0]->ponumber}}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="View"><i class="icon-eye"></i></a>
                                                    <form action="{{route('partialpo')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="ponumber" value="{{$allpo[0]->ponumber}}">
                                                        <button class="btn btn-outline-success waves-effect waves-light" onclick="return confirm('Are you sure? You want to mark this PO as Partial Received')" data-toggle="tooltip" data-placement="top" title="Partial"><i class="fas fa-chart-pie"></i></button>
                                                    </form>
                                                </td>
                                        	</tr>
                                        	@endforeach
                                        </tbody>
                                    </table>
    
                                </div>
                                <div class="card-body">
                                    <hr>
                                    <h6>Stock In Orders</h6>
                                    <table id="datatable1" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%; text-align: left;">
                                        <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Orderd Quantity</th>
                                            <th>Received Quantity</th>
                                            <th>PO Number</th>
                                            <th>Ref. Number</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($receivepodata['stockinhand'] as $stockinhand)
                                            <tr>
                                                <td>
                                                    {{$stockinhand->productname}}
                                                    
                                                </td>
                                                <td>
                                                    {{$stockinhand->poquantity}}
                                                </td>
                                                <td>
                                                    {{$stockinhand->receivequantity}}
                                                </td>
                                                <td>
                                                    {{$stockinhand->ponumber}}
                                                </td>
                                                <td>
                                                    {{$stockinhand->porefrencenumber}}
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
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
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
@endsection
        