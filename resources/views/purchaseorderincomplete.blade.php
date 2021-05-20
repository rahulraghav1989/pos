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
                                <h4 class="page-title">In-Completed Purchase Order</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Purchase Order</a></li>
                                    <li class="breadcrumb-item active">In-Completed Purchase Order</li>
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
                                            @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderfilters=='Y')
                                            <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                Filters
                                            </a>
                                            @endif
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
                                            <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore" data-backdrop="static" data-keyboard="false">Change Store</button>
                                            @endif
                                        </div>
                                        @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderfilters=='Y')
                                        <div class="col-md-12">
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form action="{{route('poincomfilter')}}" method="post">
                                                      @csrf
                                                      <div class="row">
                                                        <div class="col-md-4">
                                                            <select class="form-control" name="store">
                                                                <option value="">SELECT STORE</option>
                                                                @foreach(session('filtersdata')['allstores'] as $allstores)
                                                                <option value="{{$allstores->store_id}}" @if($store==$allstores->store_id) SELECTED='SELECTED' @endif>{{$allstores->store_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <div>
                                                                    <div class="input-daterange input-group" id="date-range">
                                                                        <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($firstday)) @endphp" />
                                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($lastday)) @endphp" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 text-right">
                                                          <button type="submit" class="btn btn-success">Apply Filters</button>
                                                        </div>
                                                      </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
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
                                        	@foreach($allincompletepurchaseorder as $allpo)
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
        