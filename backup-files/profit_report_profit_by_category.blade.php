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
                            <h4 class="page-title">Profit By Category</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Profit Report</a></li>
                                <li class="breadcrumb-item active">Profit By Category</li>
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
                                                    <form method="post" action="{{route('categoryprofitfilter')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportprofitbycategoryfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="store">
                                                                        <option value="">SELECT STORE</option>
                                                                        @foreach($userprofitdata['allstore'] as $allstore)
                                                                        <option value="{{$allstore->store_id}}" @if($allstore->store_id == $userprofitdata['storeID']) SELECTED='SELECTED' @endif>{{$allstore->store_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="user">
                                                                        <option value="">SELECT USER</option>
                                                                        @foreach($userprofitdata['allusers'] as $allusers)
                                                                        <option value="{{$allusers->id}}" @if($allusers->id == $userprofitdata['userID']) SELECTED='SELECTED' @endif>{{$allusers->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($userprofitdata['firstday'])) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('m/d/Y', strtotime($userprofitdata['lastday'])) @endphp" />
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
                                        @if($userprofitdata['storeID'] == "")
                                        Store (All Store) | 
                                        @else
                                        Store ( {{$userprofitdata['storedetail']->store_name}} )
                                        @endif
                                        @if($userprofitdata['userID'] == "")
                                        User (All Users) | 
                                        @else
                                        User ( {{$userprofitdata['userdetail']->name}} )
                                        @endif
                                        Date (@php echo date('d-m-Y', strtotime($userprofitdata['firstday'])) @endphp to @php echo date('d-m-Y', strtotime($userprofitdata['lastday'])) @endphp)</span></p>
                                
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Sale Invoice</th>
                                                <th>Store</th>
                                                <th data-priority="1">Sale Rep.</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Supplier</th>
                                                <th>Category</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Colour</th>
                                                <th>Quantity</th>
                                                <th>PP (Inc. GST)</th>
                                                <th>SP (Inc. GST)</th>
                                                <th data-priority="1">Discount</th>
                                                <th data-priority="1">Sold Price (Inc. GST)</th>
                                                <th data-priority="1">Total Price (Inc. GST)</th>
                                                <th data-priority="1">Profit (inc. GST)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($userprofitdata['userprofit'] as $userprofittab)
                                                <tr>
                                                    <td>@php echo date('d-m-Y H:i:s', strtotime($userprofittab->created_at)) @endphp</td>
                                                    <th>
                                                        <a href="sale/{{$userprofittab->orderID}}" style="color: #007bff;">{{$userprofittab->orderID}}</a>
                                                    </th>
                                                    <td>{{$userprofittab->store_name}}</td>
                                                    <td>{{$userprofittab->name}}</td>
                                                    <td>{{$userprofittab->barcode}}</td>
                                                    <td>{{$userprofittab->productname}}</td>
                                                    <td>{{$userprofittab->suppliername}}</td>
                                                    <td>
                                                        @if($userprofittab->subcategoryname != "")
                                                        {{$userprofittab->subcategoryname}}
                                                        @else
                                                        {{$userprofittab->categoryname}}
                                                        @endif
                                                    </td>
                                                    <td>{{$userprofittab->brandname}}</td>
                                                    <td>{{$userprofittab->modelname}}</td>
                                                    <td>{{$userprofittab->colourname}}</td>
                                                    <td>{{$userprofittab->quantity}}</td>
                                                    <td>${{$userprofittab->ppingst}}</td>
                                                    <td>${{$userprofittab->spingst}}</td>
                                                    <td>
                                                        @if($userprofittab->discountedAmount == '')
                                                        $0.00
                                                        @else
                                                        ${{$userprofittab->discountedAmount}}
                                                        @endif
                                                    </td>
                                                    <td>${{$userprofittab->salePrice}}</td>
                                                    <td>${{$userprofittab->subTotal}}</td>
                                                    <td>${{$userprofittab->salePrice - $userprofittab->ppingst - $userprofittab->discountedAmount}}</td>
                                                </tr>
                                            @endforeach
                                            @foreach($userprofitdata['userrefund'] as $userrefund)
                                                <tr style="background-color: #f1525247;">
                                                    <td>@php echo date('d-m-Y H:i:s', strtotime($userrefund->created_at)) @endphp</td>
                                                    <th>
                                                        <a href="refundsale/{{$userrefund->refundInvoiceID}}" style="color: #007bff;">{{$userrefund->refundInvoiceID}}</a>
                                                    </th>
                                                    <td>{{$userrefund->store_name}}</td>
                                                    <td>{{$userrefund->name}}</td>
                                                    <td>{{$userrefund->barcode}}</td>
                                                    <td>{{$userrefund->productname}}</td>
                                                    <td>{{$userrefund->suppliername}}</td>
                                                    <td>
                                                        @if($userrefund->subcategoryname != "")
                                                        {{$userrefund->subcategoryname}}
                                                        @else
                                                        {{$userrefund->categoryname}}
                                                        @endif
                                                    </td>
                                                    <td>{{$userrefund->brandname}}</td>
                                                    <td>{{$userrefund->modelname}}</td>
                                                    <td>{{$userrefund->colourname}}</td>
                                                    <td>{{$userrefund->quantity}}</td>
                                                    <td>-${{$userrefund->ppingst}}</td>
                                                    <td>-${{$userrefund->spingst}}</td>
                                                    <td>
                                                        @if($userrefund->discountedAmount == '')
                                                        -$0.00
                                                        @else
                                                        -${{$userrefund->discountedAmount}}
                                                        @endif
                                                    </td>
                                                    <td>-${{$userrefund->salePrice}}</td>
                                                    <td>-${{$userrefund->subTotal}}</td>
                                                    <td>-${{$userrefund->salePrice - $userrefund->ppingst - $userrefund->discountedAmount}}</td>
                                                </tr>
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
                                title: 'CategoryProfit-@php if($userprofitdata['storedetail']!=""){ echo $userprofitdata['storedetail']->store_name."-"; } if($userprofitdata['userdetail']!=""){ echo $userprofitdata['userdetail']->name."-"; } echo date('d-m-Y', strtotime($userprofitdata['firstday']))."to".date('d-m-Y', strtotime($userprofitdata['lastday'])) @endphp',
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