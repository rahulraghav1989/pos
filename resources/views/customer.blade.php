@extends('main')

@section('content')
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
                                <h4 class="page-title">Customer's</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Customer's</li>
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
                                    <h5 class="modal-title mt-0">Add a new customer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="" action="{{route('addcustomer')}}" method="post" novalidate="">
                                        @csrf
                                        <div class="form-group">
                                            <label>Customer Type *</label>
                                            <select name="customertype" class="form-control" required="">
                                                <option value="Customer">Customer</option>
                                                <option value="Business">Business</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <label>Person Detail</label>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Title</label>
                                                    <select name="title" class="form-control" required="">
                                                        <option value="">No Title</option>
                                                        <option value="Mr">Mr</option>
                                                        <option value="Mrs">Mrs</option>
                                                        <option value="Miss">Miss</option>
                                                        <option value="Ms">Ms</option>
                                                        <option value="Dr">Dr</option>
                                                        <option value="Prof">Prof</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>First Name</label>
                                                    <input type="text" name="firstname" class="form-control" placeholder="First Name" required="">
                                                </div>
                                                <div class="col-md-5">
                                                    <label>Last Name</label>
                                                    <input type="text" name="lastname" class="form-control" required="" placeholder="Last Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Mobile Number</label>
                                                    <input type="number" name="mobilenumber" class="form-control" placeholder="Mobile Number" required="" onkeypress="return isNumber(event)">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Home Number</label>
                                                    <input type="number" name="homenumber" class="form-control" placeholder="Home Number" onkeypress="return isNumber(event)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Alt. Contact Number</label>
                                                    <input type="number" name="altcontactnumber" class="form-control" placeholder="Alt. Contact Number" onkeypress="return isNumber(event)">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Date Of Birth</label>
                                                    <input type="date" name="dob" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" class="form-control" placeholder="Email@Email.com">
                                        </div>
                                        <div class="">
                                            <label>Business Detail</label>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Company/Business Name</label>
                                                    <input type="text" name="businessname" class="form-control" placeholder="Company/Business Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>ACN/ABN</label>
                                                    <input type="text" name="acnabn" class="form-control" placeholder="ACN/ABN">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Company/Business Email</label>
                                                    <input type="text" name="businessemail" class="form-control" placeholder="Company/Business Email">    
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Website</label>
                                                    <input type="text" name="businesswebsite" class="form-control" placeholder="Website">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="onaccount"> <label>On Account</label>    
                                        </div>
                                        <div class="">
                                            <label>Address</label>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Address</label>
                                                    <input type="text" name="address" class="form-control" placeholder="Address">    
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Post Code</label>
                                                    <input type="text" name="postcode" class="form-control" placeholder="Post Code">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Suburb Name</label>
                                                    <input type="text" name="suburbname" class="form-control" placeholder="Suburb Name">    
                                                </div>
                                                <div class="col-md-6">
                                                    <label>State/Province</label>
                                                    <input type="text" name="state" class="form-control" placeholder="State/Province">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Note</label>
                                            <input type="text" name="note" class="form-control" placeholder="Note">
                                        </div>
                                        @if(session('loggindata')['loggeduserstore'] == "")
                                        	<div class="form-group">
	                                            <label>Store</label>
	                                            <select name="store" class="form-control" required="">
	                                            	<option value="">SELECT</option>
	                                            	@foreach($customerdata['stores'] as $allstore)
	                                            	<option value="{{$allstore->store_id}}">{{$allstore->store_name}}</option>
	                                            	@endforeach
	                                            </select>
	                                        </div>
                                        @else
                                        <input type="hidden" name="store" value="{{session('loggindata')['loggeduserstore']['store_id']}}">
                                        @endif
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
                            	@if(session('loggindata')['loggeduserpermission']->addcustomer=='Y')
                            	<div class="card-body">
                            		<div class="col-12 text-right">
                            			<button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false">Add Customer</button>
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
                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Business Name</th>
                                            <th>Customer Mobile</th>
                                            <th>Customer Email</th>
                                            <th>Customer Note</th>
                                            <th>Store</th>
                                            <th>Added By/On</th>
                                        </tr>
                                        </thead>
    
    
                                        <tbody>
                                        @foreach($customerdata['getcustomer'] as $customers)
                                        <tr>
                                            <td>
                                            	<a href="customerdetail/{{$customers->customerID}}" class="discount"> 
	                                            	{{$customers->customertitle}} 
	                                            	{{$customers->customerfirstname}} 
	                                            	{{$customers->customerlastname}}
	                                            </a>
                                            </td>
                                            <td>{{$customers->customerbusinessname}}</td>
                                            <td>{{$customers->customermobilenumber}}</td>
                                            <td>{{$customers->customeremail}}</td>
                                            <td>{{$customers->customernote}}</td>
                                            <td>{{$customers->store_name}}</td>
                                            <td>{{$customers->name}}<br>@php echo date('d-m-Y H:i:s', strtotime($customers->created_at)) @endphp</td>
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
            <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
        </div>
    </div>
@endsection
        