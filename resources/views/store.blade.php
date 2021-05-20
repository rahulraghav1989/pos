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
                                <h4 class="page-title">Store</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Masters</a></li>
                                    <li class="breadcrumb-item active">Store Master</li>
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
                                    <h5 class="modal-title mt-0" id="myModalLabel">Add new store</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="" action="{{route('addstore')}}" method="post" novalidate="">
                                        @csrf
                                        <div class="form-group">
                                            <label>Store Code</label>
                                            <input type="text" name="storecode" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Store Name</label>
                                            <input type="text" name="storename" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Store Address</label>
                                            <input type="text" name="storeaddress" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label>Store Pincode</label>
                                                <input type="text" name="storepincode" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Store Contact</label>
                                                <input type="text" name="storecontact" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Store Email</label>
                                            <input type="email" name="storeemail" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Store Type</label>
                                            <select name="storetype" class="form-control" required="">
                                                <option value="">Select Type</option>
                                                @foreach($storedata['storetype'] as $storetype)
                                                <option value="{{$storetype->storeTypeID}}">{{$storetype->storeTypeName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Store IP</label>
                                            <input type="text" name="storeip" class="form-control" required="" placeholder="Type Here">
                                        </div>
                                        <div class="form-group">
                                            <label>Minimum Cash Flow</label>
                                            <input type="number" name="eodamount" class="form-control" required="" placeholder="Type Here">
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
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Add Store</button>
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
                                @if(session()->has('storesuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('storesuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('storeerror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('storeerror') }}
                                        </div>
                                    </div>
                                @endif

                                @if(session()->has('editstoresuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('editstoresuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('editstoreerror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('editstoreerror') }}
                                        </div>
                                    </div>
                                @endif

                                @if(session()->has('statusstoresuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('statusstoresuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('statusstoreerror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('statusstoreerror') }}
                                        </div>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Store Code</th>
                                            <th>Store Name</th>
                                            <th>Store Address</th>
                                            <th>Store Type</th>
                                            <th>Store Contact</th>
                                            <th>Store IP</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($storedata['store'] as $storedetail)
                                        <tr>
                                            <td>{{$storedetail->store_code}}</td>
                                            <td>{{$storedetail->store_name}}</td>
                                            <td>{{$storedetail->store_address}}</td>
                                            <td>{{$storedetail->storeTypeName}}</td>
                                            <td>{{$storedetail->store_contact}}</td>
                                            <td>{{$storedetail->storeIP}}</td>
                                            <td>
                                                <!--EDIT MODEL-->
                                                <div class="modal fade bs-example-modal-center editmodel{{$storedetail->store_id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">Edit Store</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editstore')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Store Name</label>
                                                                        <input type="text" name="storename" class="form-control" value="{{$storedetail->store_name}}" required="" placeholder="Type Here">
                                                                        <input type="hidden" name="storeid" value="{{$storedetail->store_id}}">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Store Address</label>
                                                                        <input type="text" name="storeaddress" class="form-control" value="{{$storedetail->store_address}}" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-6">
                                                                            <label>Store Pincode</label>
                                                                            <input type="text" name="storepincode" class="form-control" value="{{$storedetail->store_pincode}}" required="" placeholder="Type Here">
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Store Contact</label>
                                                                            <input type="text" name="storecontact" class="form-control" value="{{$storedetail->store_contact}}" required="" placeholder="Type Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Store Email</label>
                                                                        <input type="text" name="storeemail" class="form-control" value="{{$storedetail->store_email}}" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Store Type</label>
                                                                        <select name="storetype" class="form-control" required="">
                                                                            <option value="">Select Type</option>
                                                                            @foreach($storedata['storetype'] as $storetype)
                                                                            <option value="{{$storetype->storeTypeID}}" @if($storetype->storeTypeID==$storedetail->storeTypeID) SELECTED='SELECTED' @endif>{{$storetype->storeTypeName}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Store IP</label>
                                                                        <input type="text" name="storeip" class="form-control" value="{{$storedetail->storeIP}}" required="" placeholder="Type Here">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Reconciliation Amount</label>
                                                                        <input type="number" name="eodamount" class="form-control" value="{{$storedetail->storeEODAmount}}" required="" placeholder="Type Here">
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
                                                <span data-toggle="modal" data-target=".editmodel{{$storedetail->store_id}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a></span>
                                                @else
                                                <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a> 
                                                @endif
                                                | 
                                                @if($storedetail->storestatus == 1)
                                                <!---Active Model-->
                                                <div class="modal fade bs-example-modal-center activestatusmodel{{$storedetail->store_id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">{{$storedetail->store_name}} Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editstorestatus')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <h4>{{$storedetail->store_name}} is in <span class="badge badge-primary">Active Status</span></h4>
                                                                        <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
                                                                        <p>Click on OK to continue or cancle it.</p>
                                                                        <input type="hidden" name="storeid" class="form-control" value="{{$storedetail->store_id}}">
                                                                        <input type="hidden" name="storestatus" class="form-control" value="0">
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
                                                        <span data-toggle="modal" data-target=".activestatusmodel{{$storedetail->store_id}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a></span>
                                                    @else
                                                        <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a>
                                                    @endif
                                                @else
                                                <!--Inactive model-->
                                                <div class="modal fade bs-example-modal-center inactivestatusmodel{{$storedetail->store_id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">{{$storedetail->store_name}} Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editstorestatus')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <h4>{{$storedetail->store_name}} is in <span class="badge badge-primary">Inactive Status</span></h4>
                                                                        <h4>Do you want to make it <span class="badge badge-primary">Active Status</span></h4>
                                                                        <p>Click on OK to continue or cancle it.</p>
                                                                        <input type="hidden" name="storeid" class="form-control" value="{{$storedetail->store_id}}">
                                                                        <input type="hidden" name="storestatus" class="form-control" value="1">
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
                                                        <span data-toggle="modal" data-target=".inactivestatusmodel{{$storedetail->store_id}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="In-Active"><i class="icon-music-pause"></i></a></span>
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
        