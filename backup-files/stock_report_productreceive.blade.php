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
                            <h4 class="page-title">Product Received Report</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales</a></li>
                                <li class="breadcrumb-item active">Product Received Report</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="col-lg-12">
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
                                                                <option value="">SELECT STORE</option>
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
                                <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice</th>
                                        <th>Refrence No</th>
                                        <th>Supplier</th>
                                        <th>Store</th>
                                        <th>Products total</th>
                                        <th>Total Inc Tax</th>
                                        <th>Invoice Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($get_productpurchaseorder as $row)
                                        <tr>
                                            <td>{{$row->created_at}}</td>
                                            <td><a href="{{route('productreceived_detail',['id'=>$row->ponumber])}}" style="color: #007bff;"> {{$row->ponumber}} </a></td>
                                            <td>{{$row->porefrencenumber}}</td>
                                            <td>{{$row->posupplier->suppliername}}</td>
                                            <td>{{$row->get_store->store_name}}</td>
                                            <td>{{$row->poitem->sum('popurchaseprice')}}</td>
                                            <td>{{$row->poitem->sum('poitemtotal')}}</td>
                                            <td>
                                                @if($row->poprocessstatus == 1)
                                                    <span class="text-danger">Pending</span>
                                                @elseif($row->poprocessstatus == 2)
                                                    <span class="text-success">Completed</span>
                                                @elseif($row->poprocessstatus == 3)
                                                    <span class="text-warning">Inactive</span>
                                                @elseif($row->poprocessstatus == 4)
                                                    <span class="text-success">Partial Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
            </div>
            <!-- container-fluid -->

        </div>
        <!-- content -->

        @include('includes.footer')
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
    </div>
</div>
@endsection