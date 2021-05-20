@extends('main')

@section('content')
<div id="wrapper">
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    @include('includes.topbar')

    @include('includes.sidebar')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Stock History</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Report</a></li>
                                <li class="breadcrumb-item active">Stock History</li>
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
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form method="post" action="{{route('stockhistoryfilter')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportstockhistoryfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="store">
                                                                        <option value="">SELECT STORE</option>
                                                                        @foreach($allstore as $allstores)
                                                                        <option value="{{$allstores->store_id}}" @if($allstores->store_id == $storeID) SELECTED='SELECTED' @endif>{{$allstores->store_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="user">
                                                                        <option value="">SELECT USER</option>
                                                                        @foreach($allusers as $alluserss)
                                                                        <option value="{{$alluserss->id}}" @if($alluserss->id == $userID) SELECTED='SELECTED' @endif>{{$alluserss->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($firstday)) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($lastday)) @endphp" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                                                
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
                                <p class="alert alert-warning">Showing Result: 
                                    <span style="font-weight: 600; color: #000;">
                                        @if($storeID == "")
                                        Store (All Store) | 
                                        @else
                                        Store ( {{$storedetail->store_name}} ) |
                                        @endif
                                        @if($userID == "")
                                        User (All Users) | 
                                        @else
                                        User ( {{$userdetail->name}} ) |
                                        @endif
                                        Date (@php echo date('d-m-Y', strtotime($firstday)) @endphp to @php echo date('d-m-Y', strtotime($lastday)) @endphp)</span></p>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice</th>
                                                <th>Store</th>
                                                <th>Sale Rep</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Qty</th>
                                                <th>RRP</th>
                                                <th>Discount</th>
                                                <th>Sale Price</th>
                                                <th>Total Sale Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getorderdetail as $orders)
                                            <tr>
                                                <td>{{$orders->created_at}}</td>
                                                <td><a href="sale/{{$orders->orderID}}" style="color: #007bff;"> {{$orders->orderID}}</a></td>
                                                <td>{{$orders->store_name}}</td>
                                                <td>{{$orders->name}}</td>
                                                <td>{{$orders->barcode}}</td>
                                                <td>{{$orders->productname}}</td>
                                                <td>{{$orders->quantity}}</td>
                                                <td>${{$orders->spingst}}</td>
                                                <td>
                                                    @if($orders->discountedAmount == "")
                                                    $0.00
                                                    @else
                                                    {{$orders->discountedAmount}}
                                                    @endif
                                                </td>
                                                <td>${{$orders->salePrice}}</td>
                                                <td>${{$orders->subTotal}}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="7" align="right" style="font-weight: 600;">Total</td>
                                                <td style="font-weight: 600;">${{$getorderdetail->sum('spingst')}}</td>
                                                <td style="font-weight: 600;">${{$getorderdetail->sum('discountedAmount')}}</td>
                                                <td style="font-weight: 600;">${{$getorderdetail->sum('salePrice')}}</td>
                                                <td style="font-weight: 600;">${{$getorderdetail->sum('subTotal')}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
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
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>

        <script src="{{ asset('posview') }}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="{{ asset('posview') }}/assets/pages/form-advanced.js"></script>
        <!-- Responsive-table-->
        <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'StockHistory-@php if($storedetail !=""){ echo $storedetail->store_name."-"; } if($userdetail !=""){ echo $userdetail->name."-"; } echo date('d-m-Y', strtotime($firstday))."to".date('d-m-Y', strtotime($lastday)) @endphp',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            } );
        </script>
    </div>
</div>
@endsection