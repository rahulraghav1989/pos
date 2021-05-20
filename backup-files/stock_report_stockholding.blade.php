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
                            <h4 class="page-title">Stock Holding Report</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Report</a></li>
                                <li class="breadcrumb-item active">Stock Holding Report</li>
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
                                                    <form method="post" action="{{route('reportstockholdingfilter')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportstockholdingfilter=='Y')
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
                                                                    <select class="form-control" name="supplier">
                                                                        <option value="">SELECT SUPPLIER</option>
                                                                        @foreach($allsupplier as $supplier)
                                                                        <option value="{{$supplier->supplierID}}" @if($supplier->supplierID == $supplierID) SELECTED='SELECTED' @endif>{{$supplier->suppliername}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <select name="category" class="form-control">
                                                                    <option value="" selected="SELECTED">SELECT CATEGORY</option>
                                                                    @foreach($allcategory as $category)
                                                                    <option value="{{$category->categoryID}}" @if($category->categoryID == $categoryID) SELECTED='SELECTED' @endif>{{$category->categoryname}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                                                
                                                            </div>
                                                            @endif
                                                            
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
                                        
                                        @if($supplierID == "")
                                        Supplier (All Supplier) | 
                                        @else
                                        Supplier ( {{$supplierdetail->suppliername}} ) |
                                        @endif

                                        @if($categoryID == "")
                                        Category (All Categories)
                                        @else
                                        Category ( {{$categorydetail->categoryname}} )
                                        @endif
                                        
                                    </span>
                                </p>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Store</th>
                                                <th>Barcode</th>
                                                <th data-priority="1">Product Name</th>
                                                <th data-priority="1">IMEI/Serial</th>
                                                <th data-priority="1">Supplier</th>
                                                <th data-priority="1">Category</th>
                                                <th data-priority="1">Brand</th>
                                                <th data-priority="1">Model</th>
                                                <th data-priority="1">Colour</th>
                                                <th data-priority="1">Quantity</th>
                                                <th data-priority="1">Avg. Purchase Price<br>(Ex. GST)</th>
                                                <th data-priority="1">Avg. Purchase Price Tax</th>
                                                <th data-priority="1">Avg. Purchase Price<br>(Inc. GST)</th>
                                                <th>Total Purchase Price <br>(Ex. GST)</th>
                                                <th>Total Purchase Price <br>(Inc. GST)</th>
                                                <th>RRP <br>(Ex. GST)</th>
                                                <th>RRP Tax</th>
                                                <th>RRP <br>(Inc. GST)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getstockholding as $stockholding)
                                            <tr>
                                                <td>{{$stockholding->store_name}}</td>
                                                <td>{{$stockholding->barcode}}</td>
                                                <td>{{$stockholding->productname}}</td>
                                                <td>{{$stockholding->productimei}}</td>
                                                <td>{{$stockholding->suppliername}}</td>
                                                <td>{{$stockholding->categoryname}}</td>
                                                <td>{{$stockholding->brandname}}</td>
                                                <td>{{$stockholding->modelname}}</td>
                                                <td>{{$stockholding->colourname}}</td>
                                                <td>
                                                    {{$stockholding->productquantity}}
                                                </td>
                                                <td>${{$stockholding->ppexgst}}</td>
                                                <td>${{$stockholding->ppexgst * $stockholding->pptax / 100}}</td>
                                                <td>${{$stockholding->ppingst}}</td>
                                                <td>
                                                    ${{$stockholding->ppexgst * $stockholding->productquantity}}
                                                </td>
                                                <td>${{$stockholding->ppingst * $stockholding->productquantity}}</td>
                                                <td>${{$stockholding->spexgst}}</td>
                                                <td>${{$stockholding->spexgst * $stockholding->spgst / 100}}</td>
                                                <td>${{$stockholding->spingst}}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="10" align="right" style="font-weight: 600;">Total</td>
                                                <td style="font-weight: 600;">${{$getstockholding->sum('ppexgst')}}</td>
                                                <td style="font-weight: 600;"></td>
                                                <td style="font-weight: 600;">${{$getstockholding->sum('ppingst')}}</td>
                                                <td></td>
                                                <td></td>
                                                <td style="font-weight: 600;">${{$getstockholding->sum('spexgst')}}</td>
                                                <td></td>
                                                <td style="font-weight: 600;">${{$getstockholding->sum('spingst')}}</td>
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
                                title: 'StockHoldingReport-@php if($storedetail !=""){ echo $storedetail->store_name."-"; } if($supplierID != ""){ echo $supplierdetail->suppliername."-";} if($categoryID != ""){ echo $categorydetail->categoryname;}    @endphp',
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