@extends('main')

@section('content')
    @include('includes.topbar')

    @include('includes.sidebar')
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">User Stores</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>
                                    <li class="breadcrumb-item active">User Stores</li>
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
                                <div class="card-body">
                                    <p style="color: red;">You are editing stores of <span style="font-weight: 600; font-size: 16px;">{{$getuserstore[0]->name}}</span></p>
                                    <div class="col-md-12">
                                        <h5>Allot user to a store</h5>
                                        <form action="{{route('addstoretouser')}}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                @foreach($editallstore as $allstore)
                                                <input type="checkbox" name="store[]" value="{{$allstore->store_id}}"> <label>{{$allstore->store_name}}</label> <br>
                                                @endforeach
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="userid" value="{{$getuserstore[0]->id}}">
                                                <button type="submit" class="btn btn-primary">Add store to user</button>
                                            </div>
                                        </form>
                                    </div>
                                    <h5>{{$getuserstore[0]->name}} can login in below stores</h5>
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <table id="tech-companies-1" class="table  table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Store Name</th>
                                                    <th>Remove</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($getuserstore as $store)
                                                @if($store->store_name != "")
                                                <tr>
                                                    <th>{{$store->store_name}}</th>
                                                    <td>
                                                        <form action="{{route('removeuserstore')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="storeuserid" value="{{$store->storeUserID}}">
                                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
    
                                    </div>
    
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
            <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('.permission').on('change', function(){
                        var column= $(this).attr("name");
                        var option= $(this).val();
                        var userid = $('#userid').val();
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                        url:"{{ route('ajaxupdateuserpermission') }}",
                        method:"POST",
                        data:{column:column, option:option, userid:userid, _token:_token},
                        success:function(result)
                        {
                         
                        }
                       })
                    });

                });
            </script>
        </div>
    </div>
@endsection
        