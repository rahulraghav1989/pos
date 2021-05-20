@extends('main')

@section('content')
    @include('includes.topbar')

    @include('includes.sidebar')
    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">Supplier</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Masters</a></li>
                                    <li class="breadcrumb-item active">Supplier</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <!---Add Model-->
                    
                    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Add new supplier</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="" action="{{route('addsupplier')}}" method="post" novalidate="">
                                        @csrf
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <input type="text" name="suppliername" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Supplier Description</label>
                                            <input type="text" name="supplierdescription" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label>Contact Number</label>
                                                <input type="text" name="contactnumber" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Alt. Contact Number</label>
                                                <input type="text" name="altcontactnumber" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>ACN/ABN</label>
                                            <input type="text" name="acbabn" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Website</label>
                                            <input type="text" name="website" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <hr>
                                        <p>Contact Person Details</p>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label>Name</label>
                                                <input type="text" name="personname" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Number</label>
                                                <input type="text" name="personnumber" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="personemail" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <hr>
                                        <p>Supplier Address</p>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label>Unit Number</label>
                                                <input type="text" name="unitnumber" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Street Number</label>
                                                <input type="text" name="streetnumber" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Street Name</label>
                                                <input type="text" name="streetname" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label>Suburb Name</label>
                                                <input type="text" name="suburbname" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Post Code</label>
                                                <input type="text" name="postcode" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="col-sm-4">
                                                <label>State</label>
                                                <input type="text" name="state" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Country</label>
                                            <input type="text" name="country" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <hr>
                                        Note (If Any)
                                        <hr>
                                        <div class="form-group">
                                            <label>Note</label>
                                            <input type="text" name="note" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group text-right">
                                            <div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                    Submit
                                                </button>
                                                <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
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
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Add Supplier</button>
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
                                @if(session()->has('suppliersuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('suppliersuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('suppliererror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('suppliererror') }}
                                        </div>
                                    </div>
                                @endif

                                @if(session()->has('editsuppliersuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('editsuppliersuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('editsuppliererror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('editsuppliererror') }}
                                        </div>
                                    </div>
                                @endif

                                @if(session()->has('statussuppliersuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('statussuppliersuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('statussuppliererror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('statussuppliererror') }}
                                        </div>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Supplier Details</th>
                                            <th>Contact Person</th>
                                            <th>Added By</th>
                                            <th>Added On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($supplier as $supplierdetail)
                                        <tr>
                                            <td>
                                                {{$supplierdetail->suppliername}} <br>
                                                {{$supplierdetail->suppliercontactnumber}} <br>
                                                {{$supplierdetail->supplieraltercontactnumber}} <br>
                                                {{$supplierdetail->supplieremail}} <br>
                                                {{$supplierdetail->supplierwebsite}} <br>
                                                {{$supplierdetail->supplierAddressstate}},{{$supplierdetail->supplierAddresscountry}} <br>
                                            </td>
                                            <td>
                                                {{$supplierdetail->supplierContactperson}} <br>
                                                {{$supplierdetail->supplierContactpersonnumber}} <br>
                                                {{$supplierdetail->supplierContactpersonemail}} <br>
                                                {{$supplierdetail->brandname}} <br>
                                            </td>
                                            <td>{{$supplierdetail->name}}</td>
                                            <td>{{$supplierdetail->created_at}}</td>
                                            <td>
                                                <!--EDIT MODEL-->
                                                <div class="modal fade bs-example-modal-center editmodel{{$supplierdetail->supplierID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">Edit model</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editsupplier')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <input type="hidden" name="supplierid" value="{{$supplierdetail->supplierID}}">
                                                                    <div class="form-group">
                                                                        <label>Supplier Name</label>
                                                                        <input type="text" name="suppliername" value="{{$supplierdetail->suppliername}}" class="form-control" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Supplier Description</label>
                                                                        <input type="text" name="supplierdescription" value="{{$supplierdetail->supplierdescription}}" class="form-control" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-6">
                                                                            <label>Contact Number</label>
                                                                            <input type="text" name="contactnumber" value="{{$supplierdetail->suppliercontactnumber}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Alt. Contact Number</label>
                                                                            <input type="text" name="altcontactnumber" value="{{$supplierdetail->supplieraltercontactnumber}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>ACN/ABN</label>
                                                                        <input type="text" name="acbabn" class="form-control" value="{{$supplierdetail->supplieracnabn}}" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Email</label>
                                                                        <input type="text" name="email" class="form-control" value="{{$supplierdetail->supplieremail}}" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Website</label>
                                                                        <input type="text" name="website" class="form-control" value="{{$supplierdetail->supplierwebsite}}" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <hr>
                                                                    <p>Contact Person Details</p>
                                                                    <hr>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-6">
                                                                            <label>Name</label>
                                                                            <input type="text" name="personname" value="{{$supplierdetail->supplierContactperson}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Number</label>
                                                                            <input type="text" name="personnumber" value="{{$supplierdetail->supplierContactpersonnumber}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Email</label>
                                                                        <input type="text" name="personemail" value="{{$supplierdetail->supplierContactpersonemail}}" class="form-control" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <hr>
                                                                    <p>Supplier Address</p>
                                                                    <hr>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-4">
                                                                            <label>Unit Number</label>
                                                                            <input type="text" name="unitnumber" value="{{$supplierdetail->supplierAddressunit}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label>Street Number</label>
                                                                            <input type="text" name="streetnumber" value="{{$supplierdetail->supplierAddressstreet}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label>Street Name</label>
                                                                            <input type="text" name="streetname" value="{{$supplierdetail->supplierAddressstreetname}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-4">
                                                                            <label>Suburb Name</label>
                                                                            <input type="text" name="suburbname" value="{{$supplierdetail->SupplierAddresssuburb}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label>Post Code</label>
                                                                            <input type="text" name="postcode" value="{{$supplierdetail->supplierAddresspostcode}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label>State</label>
                                                                            <input type="text" name="state" value="{{$supplierdetail->supplierAddressstate}}" class="form-control" required="" placeholder="Type Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Country</label>
                                                                        <input type="text" name="country" value="{{$supplierdetail->supplierAddresscountry}}" class="form-control" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <hr>
                                                                    Note (If Any)
                                                                    <hr>
                                                                    <div class="form-group">
                                                                        <label>Note</label>
                                                                        <input type="text" name="note" value="{{$supplierdetail->suppliercreatingnote}}" class="form-control" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <div>
                                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                Submit
                                                                            </button>
                                                                            <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
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
                                                @if(session('loggindata')['loggeduserpermission']->editmasters=='Y')
                                                <span data-toggle="modal" data-target=".editmodel{{$supplierdetail->supplierID}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a></span>
                                                @else
                                                <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a> 
                                                @endif
                                                | 
                                                @if($supplierdetail->supplierstatus == 1)
                                                <!---Active Model-->
                                                <div class="modal fade bs-example-modal-center activestatusmodel{{$supplierdetail->supplierID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">{{$supplierdetail->suppliername}} Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editsupplierstatus')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <h4>{{$supplierdetail->suppliername}} is in <span class="badge badge-primary">Active Status</span></h4>
                                                                        <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
                                                                        <p>Click on OK to continue or cancle it.</p>
                                                                        <input type="hidden" name="supplierid" class="form-control" value="{{$supplierdetail->supplierID}}">
                                                                        <input type="hidden" name="supplierstatus" class="form-control" value="0">
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <div>
                                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                OK
                                                                            </button>
                                                                            <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
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
                                                <span data-toggle="modal" data-target=".activestatusmodel{{$supplierdetail->supplierID}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a></span>
                                                @else
                                                <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a>
                                                @endif
                                                @else
                                                <!--Inactive model-->
                                                <div class="modal fade bs-example-modal-center inactivestatusmodel{{$supplierdetail->supplierID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">{{$supplierdetail->suppliername}} Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editsupplierstatus')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <h4>{{$supplierdetail->suppliername}} is in <span class="badge badge-primary">Inactive Status</span></h4>
                                                                        <h4>Do you want to make it <span class="badge badge-primary">Active Status</span></h4>
                                                                        <p>Click on OK to continue or cancle it.</p>
                                                                        <input type="hidden" name="supplierid" class="form-control" value="{{$supplierdetail->supplierID}}">
                                                                        <input type="hidden" name="supplierstatus" class="form-control" value="1">
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <div>
                                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                OK
                                                                            </button>
                                                                            <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
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
                                                <span data-toggle="modal" data-target=".inactivestatusmodel{{$supplierdetail->supplierID}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="In-Active"><i class="icon-music-pause"></i></a></span>
                                                @else
                                                <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="In-Active"><i class="icon-music-pause"></i></a>
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
            <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
        </div>
    </div>
@endsection
        