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
                            <h4 class="page-title">Search Products By Barcode<h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Products/Plans</a></li>
                                <li class="breadcrumb-item active">Search Product</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <h5>Product Detail</h5>
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="4">Barcode Detail</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Product Name</td>
                                                            <td>{{$product->productname}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Barcode</td>
                                                            <td>{{$product->barcode}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <h5>Stock In Stores</h5>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns" style="max-height: 400px; overflow-y: auto;">
                                                @if($product->producttype != "")
                                                	<table class="table table-striped">
	                                                    <thead>
	                                                    <tr>
	                                                        <th>IMEI/Serial</th>
	                                                        <th>Sim</th>
	                                                        <th>Quantity</th>
	                                                        <th>Store</th>
	                                                    </tr>
	                                                    </thead>
	                                                    <tbody>
	                                                    @foreach($productstock as $stock)
                                                            @if($stock->productquantity != 0)
	                                                        <tr>
	                                                            <td>
	                                                                {{$stock->productimei}}
	                                                            </td>
	                                                            <td>{{$stock->simnumber}}</td>
	                                                            <td>{{$stock->productquantity}}</td>
	                                                            <td>{{$stock->store_name}}</td>
	                                                        </tr>
                                                            @endif
	                                                    @endforeach
	                                                    </tbody>
	                                                </table>
                                                @else
                                                	<table class="table table-striped">
	                                                    <thead>
	                                                    <tr>
	                                                        <th>Quantity</th>
	                                                        <th>Store</th>
	                                                    </tr>
	                                                    </thead>
	                                                    <tbody>
	                                                    @foreach($productstock as $stock)
	                                                        <tr>
	                                                            <td>{{$stock->productquantity}}</td>
	                                                            <td>{{$stock->store_name}}</td>
	                                                        </tr>
	                                                    @endforeach
	                                                    </tbody>
	                                                </table>
                                                @endif
                                            </div>
                                        </div>
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
        
    </div>
</div>
@endsection