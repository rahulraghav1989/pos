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
                            <h4 class="page-title">Sales By User</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales Report</a></li>
                                <li class="breadcrumb-item active">Sales By User</li>
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
                                                    <form method="post" action="{{route('salesbyuserfilter')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportsalesbyuserfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="store">
                                                                        <option value="">SELECT STORE</option>
                                                                        @foreach($allstore as $store)
                                                                        <option value="{{$store->store_id}}" @if($store->store_id == $storeID) SELECTED='SELECTED' @endif>{{$store->store_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="user">
                                                                        <option value="">SELECT USER</option>
                                                                        @foreach($alluser as $user)
                                                                        <option value="{{$user->id}}" @if($user->id == $userID) SELECTED='SELECTED' @endif>{{$user->name}}</option>
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
                                <p class="alert alert-warning">Showing Result: <span style="font-weight: 600; color: #000;">{{$userdetail->name}} @if($storedetail != ''), {{$storedetail->store_name}} @endif, (@php echo date('d-m-Y', strtotime($firstday)) @endphp TO @php echo date('d-m-Y', strtotime($lastday)) @endphp)</span></p>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Supplier</th>
                                                <th>Category</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Colour</th>
                                                <th>Quantity</th>
                                                <th>RRP</th>
                                                <th>Discount</th>
                                                <th>Sale Price</th>
                                                <th>Total Sale Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getusersales as $usersales)
                                                <tr>
                                                    <td>@php echo date('d-m-Y H:i:s', strtotime($usersales->created_at)) @endphp</td>
                                                    <td><a href="sale/{{$usersales->orderID}}" style="color: #007bff;"> {{$usersales->orderID}} </a></td>
                                                    <td>{{$usersales->barcode}}</td>
                                                    <td>{{$usersales->productname}}</td>
                                                    <td>{{$usersales->suppliername}}</td>
                                                    <td>
                                                        @if($usersales->subcategoryname != '')
                                                        {{$usersales->subcategoryname}}
                                                        @else
                                                        {{$usersales->categoryname}}
                                                        @endif
                                                    </td>
                                                    <td>{{$usersales->brandname}}</td>
                                                    <td>{{$usersales->modelname}}</td>
                                                    <td>{{$usersales->colourname}}</td>
                                                    <td>{{$usersales->quantity}}</td>
                                                    <td>${{$usersales->spingst}}</td>
                                                    <td>
                                                        @if($usersales->discountedAmount != "")
                                                        ${{$usersales->discountedAmount}}
                                                        @else
                                                        $0.00
                                                        @endif
                                                    </td>
                                                    <td>${{$usersales->salePrice}}</td>
                                                    <td>${{$usersales->subTotal}}</td>
                                                </tr>
                                            @endforeach
                                            @foreach($getuserrefund as $userrefund)
                                                <tr style="background-color: #f1525247;">
                                                    <td>@php echo date('d-m-Y H:i:s', strtotime($userrefund->created_at)) @endphp</td>
                                                    <td><a href="refundinvoice/{{$userrefund->refundInvoiceID}}" style="color: #007bff;"> {{$userrefund->refundInvoiceID}} </a></td>
                                                    <td>{{$userrefund->barcode}}</td>
                                                    <td>{{$userrefund->productname}}</td>
                                                    <td>{{$userrefund->suppliername}}</td>
                                                    <td>
                                                        @if($userrefund->subcategoryname != '')
                                                        {{$userrefund->subcategoryname}}
                                                        @else
                                                        {{$userrefund->categoryname}}
                                                        @endif
                                                    </td>
                                                    <td>{{$userrefund->brandname}}</td>
                                                    <td>{{$userrefund->modelname}}</td>
                                                    <td>{{$userrefund->colourname}}</td>
                                                    <td>{{$userrefund->quantity}}</td>
                                                    <td>${{$userrefund->spingst}}</td>
                                                    <td>
                                                        @if($userrefund->discountedAmount != "")
                                                        ${{$userrefund->discountedAmount}}
                                                        @else
                                                        $0.00
                                                        @endif
                                                    </td>
                                                    <td>-${{$userrefund->salePrice}}</td>
                                                    <td>-${{$userrefund->subTotal}}</td>
                                                </tr>
                                            @endforeach
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
                                title: 'SaleByUser-@php echo $userdetail->name."-".date('d-m-Y', strtotime($firstday))."to".date('d-m-Y', strtotime($lastday)) @endphp',
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