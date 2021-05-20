@extends('main')

@section('content')
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
                                <h4 class="page-title">Completed Purchase Order</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                    <li class="breadcrumb-item active">Completed Purchase Order</li>
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
                                                                    <option value="{{$storename->store_id}}" @if(session('loggindata')['loggeduserstore']['store_id']==$storename->store_id) SELECTED=='SELECTED' @endif>{{$storename->store_name}}</option>
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
                                        </div>
                                    </div>
                            	</div>
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
                                        	@foreach($allcompletepurchaseorder->groupBy('ponumber') as $allpo)
                                        	<tr>
                                        		<td>{{$allpo[0]->ponumber}}</td>
                                        		<td>{{$allpo[0]->porefrencenumber}}</td>
                                        		<td>{{$allpo[0]->store_name}}</td>
                                                <td>{{$allpo[0]['posupplier']->suppliername}}</td>
                                        		<td>{{count($allpo[0]['poitem'])}}</td>
                                        		<td>{{$allpo[0]->name}}<br>{{$allpo[0]->created_at}}</td>
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
	                                                                <table id="datatable1" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								                                        <thead>
								                                        <tr>
								                                        	<th>Product Name</th>
								                                        	<th>Quantity</th>
								                                            <th>PP (Inc. Gst)</th>
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
								                                        		<td>{{$allpoitem->poppingst}}</td>
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
                                        			<a href="" class="btn btn-outline-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg{{$allpo[0]->ponumber}}"><i class="icon-eye"></i></a>
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
        