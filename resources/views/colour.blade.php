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
                                <h4 class="page-title">Colour</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Masters</a></li>
                                    <li class="breadcrumb-item active">Colour</li>
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
                                    <h5 class="modal-title mt-0">Add a new colour</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="" action="{{route('addcolour')}}" method="post" novalidate="">
                                        @csrf
                                        <div class="form-group">
                                            <label>Colour Name</label>
                                            <input type="text" name="colourname" class="form-control" required="" placeholder="Type Here">
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
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false">Add Colour</button>
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
                                @if(session()->has('coloursuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('coloursuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('colourerror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('colourerror') }}
                                        </div>
                                    </div>
                                @endif

                                @if(session()->has('editcoloursuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('editcoloursuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('editcolourerror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('editcolourerror') }}
                                        </div>
                                    </div>
                                @endif

                                @if(session()->has('statuscoloursuccess'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('statuscoloursuccess') }}
                                        </div>
                                    </div>
                                @endif
                                @if(session()->has('statuscolourerror'))
                                    <div class="card-body">                                 
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('statuscolourerror') }}
                                        </div>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Colour Name</th>
                                            <th>Added By</th>
                                            <th>Added On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
    
    
                                        <tbody>
                                        @foreach($colour as $color)
                                        <tr>
                                            <td>{{$color->colourname}}</td>
                                            <td>{{$color->name}}</td>
                                            <td>{{$color->created_at}}</td>
                                            <td>
                                                <!--EDIT MODEL-->
                                                <div class="modal fade bs-example-modal-center editmodel{{$color->colourID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">Edit colour</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editcolour')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Colour Name</label>
                                                                        <input type="text" name="colourname" class="form-control" value="{{$color->colourname}}" required="" placeholder="Type Here">
                                                                        <input type="hidden" name="colourid" class="form-control" value="{{$color->colourID}}">
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
                                                <span data-toggle="modal" data-target=".editmodel{{$color->colourID}}" data-backdrop="static" data-keyboard="false"><a href="" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a></span>
                                                @else
                                                <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a> 
                                                @endif
                                                | 
                                                @if($color->colourstatus == 1)
                                                <!---Active Model-->
                                                <div class="modal fade bs-example-modal-center activestatusmodel{{$color->colourID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">{{$color->colourname}} Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editcolourstatus')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <h4>{{$color->colourname}} is in <span class="badge badge-primary">Active Status</span></h4>
                                                                        <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
                                                                        <p>Click on OK to continue or cancle it.</p>
                                                                        <input type="hidden" name="colourid" class="form-control" value="{{$color->colourID}}">
                                                                        <input type="hidden" name="colourstatus" class="form-control" value="0">
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
                                                <span data-toggle="modal" data-target=".activestatusmodel{{$color->colourID}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a></span>
                                                @else
                                                <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a>
                                                @endif
                                                @else
                                                <!--Inactive model-->
                                                <div class="modal fade bs-example-modal-center inactivestatusmodel{{$color->colourID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">{{$color->colourname}} Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="" action="{{route('editcolourstatus')}}" method="post" novalidate="">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <h4>{{$color->colourname}} is in <span class="badge badge-primary">Inactive Status</span></h4>
                                                                        <h4>Do you want to make it <span class="badge badge-primary">Active Status</span></h4>
                                                                        <p>Click on OK to continue or cancle it.</p>
                                                                        <input type="hidden" name="colourid" class="form-control" value="{{$color->colourID}}">
                                                                        <input type="hidden" name="colourstatus" class="form-control" value="1">
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
                                                <span data-toggle="modal" data-target=".inactivestatusmodel{{$color->colourID}}" data-backdrop="static" data-keyboard="false"><a class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="In-Active"><i class="icon-music-pause"></i></a></span>
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
        