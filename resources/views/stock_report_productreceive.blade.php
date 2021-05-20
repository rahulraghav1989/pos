@extends('main')

@section('content')
<div id="wrapper">
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    
    <link href="{{ asset('posview') }}/multipleselector/styles/multiselect.css" rel="stylesheet" />
    <script src="{{ asset('posview') }}/multipleselector/scripts/multiselect.min.js"></script>
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-right">
                                                <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                    Filters
                                                </a>
                                            </div>
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form method="post" action="{{route('productreceived')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportproductreceivefilters=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Select Store</label>
                                                                    <select id="testSelect1" name="store[]" multiple>
                                                                        @foreach($allstore as $allstore)
                                                                            @if($storeID!='')
                                                                                <option value="{{$allstore->store_id}}" @foreach($storeID as $selectedstoreid) @if($allstore->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstore->store_name}}</option>
                                                                            @else
                                                                                <option value="{{$allstore->store_id}}">{{$allstore->store_name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                    <script>
                                                                        document.multiselect('#testSelect1')
                                                                            .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                            })
                                                                            .setCheckBoxClick("1", function(target, args) {
                                                                                console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                            });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Select User</label>
                                                                    <select id="testSelect2" name="user[]" multiple>
                                                                        @foreach($alluser as $allusers)
                                                                            @if($userID!='')
                                                                                <option value="{{$allusers->id}}" @foreach($userID as $selecteduserid) @if($allusers->id == $selecteduserid) SELECTED='SELECTED' @endif @endforeach>{{$allusers->name}}</option>
                                                                            @else
                                                                                <option value="{{$allusers->id}}">{{$allusers->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                    <script>
                                                                        document.multiselect('#testSelect2')
                                                                            .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                            })
                                                                            .setCheckBoxClick("1", function(target, args) {
                                                                                console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                            });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Date Range</label>
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($firstday)) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($lastday)) @endphp" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit" class="btn btn-success">Apply Filter</button>
                                                                
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
                                    @foreach($purchaseorder as $row)
                                        <tr>
                                            <td>
                                                @php
                                                echo date('d-m-Y H:i:s', strtotime($row->created_at))
                                                @endphp
                                            </td>
                                            <td><a href="{{route('productreceived_detail',['id'=>$row->ponumber])}}" style="color: #007bff;"> {{$row->ponumber}} </a></td>
                                            <td>{{$row->porefrencenumber}}</td>
                                            <td>
                                                @if($row->posupplier != '')
                                                {{$row->posupplier->suppliername}}
                                                @endif
                                            </td>
                                            <td>{{$row->store_name}}</td>
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

        <script src="{{ asset('posview') }}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="{{ asset('posview') }}/assets/pages/form-advanced.js"></script>
        <!-- Responsive-table-->
        <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>
    </div>
</div>
@endsection