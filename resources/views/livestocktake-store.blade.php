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
                            <h4 class="page-title">Live Stock Take</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                <li class="breadcrumb-item active">Live Stock Take</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <p style="color: #ef0b16;">You'r not loggedin in any store. So for continue please select the store</p>
                                        <form action="{{route('startlivestocktake')}}" method="post">
                                            @csrf
                                            <select name="store" class="form-control">
                                                <option value="">SELECT STORE</option>
                                                @foreach(session('allstore') as $storename)
                                                <option value="{{$storename->store_id}}">{{$storename->store_name}}</option>
                                                @endforeach           
                                            </select>
                                            <br>
                                            <button type="submit" class="btn btn-primary">Start Live Stock Take</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> 
            </div>
            <!-- container-fluid -->

        </div>
        <!-- content -->
        @include('includes.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->
    <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
</div>
@endsection