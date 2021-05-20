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
                                <h4 class="page-title">User Groups</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>
                                    <li class="breadcrumb-item active">User Groups</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Group Name</th>
                                            <th>Group Role</th>
                                            <th>Group Description</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($usersgroup as $usersgroupdetail)
                                        <tr>
                                            <td>{{$usersgroupdetail->usertypeName}}</td>
                                            <td>{{$usersgroupdetail->usertypeRole}}</td>
                                            <td>{{$usersgroupdetail->typedescription}}</td>
                                            <td>
                                                @if(session('loggindata')['loggeduserpermission']->editusergroup=='Y')
                                                <!--EDIT MODEL-->
                                                <div class="modal fade bs-example-modal-center editmodel{{$usersgroupdetail->usertypeID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">Edit User Group</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{route('editusergroup')}}" method="post">
                                                                    @csrf
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <input type="text" name="usergroupname" class="form-control" value="{{$usersgroupdetail->usertypeName}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <textarea cols="" rows="5" name="description" class="form-control">{{$usersgroupdetail->typedescription}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <div>
                                                                            <input type="hidden" name="usertypeid" value="{{$usersgroupdetail->usertypeID}}">
                                                                            <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light">
                                                                                Save Group
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
                                                <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><a class="btn btn-outline-success waves-effect waves-light btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target=".editmodel{{$usersgroupdetail->usertypeID}}"><i class="icon-pencil"></i></a></span>
                                                @else
                                                <a class="btn btn-sm btn-light waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icon-pencil"></i></a>
                                                @endif
                                                @if(session('loggindata')['loggeduserpermission']->editusergrouppermission=='Y')
                                                <a href="usergrouppermission/{{$usersgroupdetail->usertypeID}}" class="btn btn-outline-success waves-effect waves-light btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Permission"><i class="fas fa-user-lock"></i></a>
                                                @else
                                                <a class="btn btn-sm btn-light waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="" data-original-title="Permission"><i class="fas fa-user-lock"></i></a>
                                                @endif
                                                @if(session('loggindata')['loggeduserpermission']->addusergroup=='Y')
                                                <a href="usergroupcreate/{{$usersgroupdetail->usertypeID}}" class="btn btn-outline-success waves-effect waves-light btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Duplicate"><i class="fas fa-object-ungroup"></i></a>
                                                @else
                                                <a class="btn btn-sm btn-light waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="" data-original-title="Duplicate"><i class="fas fa-object-ungroup"></i></a>
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
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
    </div>
@endsection
        