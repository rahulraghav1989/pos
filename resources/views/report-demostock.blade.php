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
                            <h4 class="page-title">Demo Stock</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Report</a></li>
                                <li class="breadcrumb-item active">Demo Stock</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                        	<div class="card-body">
                        		<div class="col-12">
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
                                                                <option value="{{$storename->store_id}}" @if(session('storeid')==$storename->store_id) SELECTED=='SELECTED' @endif>{{$storename->store_name}}</option>
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
                                <style type="text/css">
                                    .dt-buttons{
                                        width: 50%;
                                        float: left;
                                    }
                                </style>

                                
                                <div class="col-md-12" style="margin-bottom: 20px;">
                                    <h4 class="mt-0 header-title">Demo Device In-Stock</h4>
                                    <button type="button" style="background-color: #FFF; color: #000; border: thin solid #000;" class="btn waves-effect waves-light">White Colour <br>Less than 45 days</button>
                                    <button type="button" style="background-color: #ffff80; color: #000; border: thin solid #000;" class="btn waves-effect waves-light">Yellow Colour <br>45+ Days Old</button>
                                    <button type="button" style="background-color: #ff8080; color: #000; border: thin solid #000;" class="btn waves-effect waves-light">Red Colour <br>60+ Days Old</button>
                                </div>
                                <div class="col-md-4">
                                    
                                </div>
                                <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Stock Code</th>
                                        <th>IMEI/SERIAL</th>
                                        <th>Device</th>
                                        <th>RRP <br> (Inc. GST)</th>
                                        <th>Store</th>
                                        <th>Stock-In Date</th>
                                    </tr>
                                    </thead>


                                    <tbody>
                                    @foreach($demostock['getdevice'] as $devices)
                                    	@if($devices->productimei != '')
	                                    <tr 
                                            @php 
                                                $startdate = strtotime(date('Y-m-d', strtotime($devices->created_at)));
                                                $enddate = strtotime(date('Y-m-d'));
                                                $timediff = abs($enddate - $startdate);
                                                $days = $timediff/86400;

                                                if($days >= 30 && $days < 45)
                                                {
                                                    echo "style='background-color: #FFF !important;'";
                                                }
                                                elseif($days >= 45 && $days < 60)
                                                {
                                                    echo "style='background-color: #ffff80 !important;'";
                                                }
                                                elseif($days >= 60)
                                                {
                                                    echo "style='background-color: #ff8080 !important;'";
                                                }
                                                
                                            @endphp
                                        >
	                                        <td>{{$devices->barcode}}</td>
                                            <td>{{$devices->stockcode}}</td>
	                                        <td>{{$devices->productimei}}</td>
	                                        <td>{{$devices->productname}}</td>
                                            <td>{{$devices->spingst}}</td>
	                                        <td>{{$devices->store_name}}</td>
	                                        <td>{{$devices->created_at}}</td>
	                                    </tr>
                                    	@endif
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
        <script type="text/javascript">
            $(document).ready(function() {
                $('#datatable').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'pdf',
                            text : 'Export PDF',
                            title: 'DevicesInstock',
                            exportOptions: {
                                columns: [0,2,3,4,5,6]
                            },
                        },
                        {
                            extend: 'excel',
                            text : 'Export Excel',
                            title: 'DevicesInstock',
                        }
                    ]
                } );
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#datatable1').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'pdf',
                            text : 'Export PDF',
                            title: 'QuantityProductsInstock',
                            exportOptions: {
                                columns: [0,2,3,4,5]
                            },
                        },
                        {
                            extend: 'excel',
                            text : 'Export Excel',
                            title: 'QuantityProductsInstock',
                            exportOptions: {
                                columns: [0,2,3,4,5]
                            },
                        }
                    ]
                } );
            } );
        </script>
    </div>
</div>
@endsection