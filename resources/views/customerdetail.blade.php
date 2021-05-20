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
                                <h4 class="page-title">Customer Detail</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item">Customer</li>
                                    <li class="breadcrumb-item active">Customer Detail</li>
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
                                @if(session('loggindata')['loggeduserpermission']->editcustomer=='Y')
                                <div class="card-body">
                                    <form class="" action="{{route('editcustomer')}}" method="post" novalidate="">
                                        @csrf
                                        <div class="">
                                            <label>Person Detail</label>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Customer Type *</label>
                                                    <select name="customertype" class="form-control" required="">
                                                        <option value="Customer" @if($customerdetail['getcustomer']->customertype == 'Customer') SELECTED='SELECTED' @endif>Customer</option>
                                                        <option value="Business" @if($customerdetail['getcustomer']->customertype == 'Business') SELECTED='SELECTED' @endif>Business</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Title</label>
                                                    <select name="title" class="form-control" required="">
                                                        <option value="">No Title</option>
                                                        <option value="Mr" @if($customerdetail['getcustomer']->customertitle == 'Mr') SELECTED='SELECTED' @endif>Mr</option>
                                                        <option value="Mrs" @if($customerdetail['getcustomer']->customertitle == 'Mrs') SELECTED='SELECTED' @endif>Mrs</option>
                                                        <option value="Miss" @if($customerdetail['getcustomer']->customertitle == 'Miss') SELECTED='SELECTED' @endif>Miss</option>
                                                        <option value="Ms" @if($customerdetail['getcustomer']->customertitle == 'Ms') SELECTED='SELECTED' @endif>Ms</option>
                                                        <option value="Dr" @if($customerdetail['getcustomer']->customertitle == 'Dr') SELECTED='SELECTED' @endif>Dr</option>
                                                        <option value="Prof" @if($customerdetail['getcustomer']->customertitle == 'Prof') SELECTED='SELECTED' @endif>Prof</option>
                                                        <option value="Other" @if($customerdetail['getcustomer']->customertitle == 'Other') SELECTED='SELECTED' @endif>Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>First Name</label>
                                                    <input type="text" name="firstname" class="form-control" value="{{$customerdetail['getcustomer']->customerfirstname}}" required="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Last Name</label>
                                                    <input type="text" name="lastname" class="form-control" required="" value="{{$customerdetail['getcustomer']->customerlastname}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>Mobile Number</label>
                                                    <input type="number" name="mobilenumber" class="form-control" value="{{$customerdetail['getcustomer']->customermobilenumber}}" required="" onkeypress="return isNumber(event)">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Home Number</label>
                                                    <input type="number" name="homenumber" class="form-control" value="{{$customerdetail['getcustomer']->customerhomenumber}}" onkeypress="return isNumber(event)">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Alt. Contact Number</label>
                                                    <input type="number" name="altcontactnumber" class="form-control" value="{{$customerdetail['getcustomer']->customeraltcontactnumber}}" onkeypress="return isNumber(event)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Date Of Birth</label>
                                                    <input type="date" name="dob" class="form-control" value="{{$customerdetail['getcustomer']->customerdob}}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Email</label>
                                                    <input type="text" name="email" class="form-control" value="{{$customerdetail['getcustomer']->customeremail}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <label>Business Detail</label>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Company/Business Name</label>
                                                    <input type="text" name="businessname" class="form-control" value="{{$customerdetail['getcustomer']->customerbusinessname}}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>ACN/ABN</label>
                                                    <input type="text" name="acnabn" class="form-control" value="{{$customerdetail['getcustomer']->customeracnabn}}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Company/Business Email</label>
                                                    <input type="text" name="businessemail" class="form-control" value="{{$customerdetail['getcustomer']->customerbusinessemail}}">    
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Website</label>
                                                    <input type="text" name="businesswebsite" class="form-control" value="{{$customerdetail['getcustomer']->customerbusinesswebsite}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="onaccount" @if($customerdetail['getcustomer']->onAccountPayment == '1') value="1" checked @endif> <label>On Account</label>    
                                        </div>
                                        <div class="">
                                            <label>Address</label>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Address</label>
                                                    <input type="text" name="address" class="form-control" value="{{$customerdetail['getcustomer']->customeraddress}}">    
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Post Code</label>
                                                    <input type="text" name="postcode" class="form-control" value="{{$customerdetail['getcustomer']->customerpostcode}}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Suburb Name</label>
                                                    <input type="text" name="suburbname" class="form-control" value="{{$customerdetail['getcustomer']->customersuburbname}}">    
                                                </div>
                                                <div class="col-md-3">
                                                    <label>State/Province</label>
                                                    <input type="text" name="state" class="form-control" value="{{$customerdetail['getcustomer']->customerstate}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Note</label>
                                            <input type="text" name="note" class="form-control" value="{{$customerdetail['getcustomer']->customernote}}">
                                        </div>
                                        <div class="form-group text-right">
                                            <input type="hidden" name="customerid" class="form-control" value="{{$customerdetail['getcustomer']->customerID}}">
                                            <input type="hidden" name="store" class="form-control" value="{{$customerdetail['getcustomer']->storeID}}">
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
                                @endif
                                <div class="card-body">
                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Invoice Number</th>
                                            <th>Sale Rep.</th>
                                            <th>Sale Date</th>
                                            <th>Sale Status</th>
                                            <th>Store</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($customerdetail['customersale'] as $sale)
                                            <tr>
                                                <td>{{$sale->orderID}}</td>
                                                <td>{{$sale->name}}</td>
                                                <td>{{$sale->orderDate}}</td>
                                                <td>
                                                    @if($sale->orderstatus == 1)
                                                    <span class="badge badge-success">Complete</span>
                                                    @endif
                                                </td>
                                                <td>{{$sale->store_name}}</td>
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
        