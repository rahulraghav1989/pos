@extends('main')

@section('content')
<div id="wrapper">
	@include('includes.topbar')

    @include('includes.sidebar')
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>        
    
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
    $(function() {
      $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        startDate: @php echo date('m/d/Y', strtotime($firstday)) @endphp,
        endDate: @php echo date('m/d/Y', strtotime($lastday)) @endphp,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        showCustomRangeLabel: true,
        alwaysShowCalendars: true
      }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
      });
    });
    </script>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Upfront Store Summary</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Upfront</a></li>
                                <li class="breadcrumb-item active">Upfront Store Summary</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                        	<div class="card-body row">
                                <div class="card-body row">
                                    <div class="col-md-12 row">
                                        <div class="col-md-6">&nbsp;</div>
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <form action="{{route('upfrontstoresummary')}}" method="post" style="text-align: right;">
                                                    @csrf
                                                    <div class="form-group" style="float: left;">
                                                        <input type="text" class="form-control" name="daterange" value="@php echo date('m/d/Y', strtotime($firstday)) .'-'. date('m/d/Y', strtotime($lastday)) @endphp" />
                                                    </div>
                                                    <div class="form-group" style="float: left;">
                                                        <button type="submit" class="btn btn-primary">Apply Date</button>
                                                    </div>
                                                    @if(session('loggindata')['loggeduserpermission']->changestore=='Y')
                                                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore">Change Store</button>
                                                    @endif
                                                </form>
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
                                                @endif
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
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
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Store Code</th>
                                                <th>Store Name</th>
                                                <th>Total Voice <br> Comission</th>
                                                <th>Total MBB<br> Comission</th>
                                                <th>Total Fixed<br>Comission</th>
                                                <th>Total Wearable<br>Comission</th>
                                                <th>Total Upfront<br>Comission</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($getconnection->groupBy('storeID') as $key => $saleconnection)
                                                <tr>
                                                    <td>{{$saleconnection[0]->store_code}}</td>
                                                    <td>{{$saleconnection[0]->store_name}}</td>
                                                    <td><span class="sum{{$key}}">{{$getconnection->where('storeID', $saleconnection[0]->store_id)->where('planpropositionID', 2)->whereIn('pcID', [1,2])->sum('plancomission')}}</span></td>
                                                    <td><span class="sum{{$key}}">{{$getconnection->where('storeID', $saleconnection[0]->store_id)->where('planpropositionID', 1)->whereIn('pcID', [1,2])->sum('plancomission')}}</span></td>
                                                    <td><span class="sum{{$key}}">{{$getconnection->where('storeID', $saleconnection[0]->store_id)->where('planpropositionID', 3)->whereIn('pcID', [1,2])->sum('plancomission')}}</span></td>
                                                    <td><span class="sum{{$key}}">{{$getconnection->where('storeID', $saleconnection[0]->store_id)->where('planpropositionID', 10)->whereIn('pcID', [1,2])->sum('plancomission')}}</span></td>
                                                    <td><span id="subtotal{{$key}}"></span></td>
                                                </tr>
                                                <script type="text/javascript">
                                                    $(document).ready(function() {
                                                        var sum = 0;
                                                        $('.sum{{$key}}').each(function() {
                                                          sum += +$(this).text()||0;
                                                        });
                                                        $("#subtotal{{$key}}").text(sum.toFixed(2));
                                                    });
                                                </script>
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
        <!-- <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script> -->
        <!-- <script type="text/javascript">
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
        </script> -->
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
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontdetailedreport',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons1').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontnew',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons1_wrapper .col-md-6:eq(0)');
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons2').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontupgrade',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons2_wrapper .col-md-6:eq(0)');
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons3').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontnbn',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons3_wrapper .col-md-6:eq(0)');
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons4').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontwearable',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons4_wrapper .col-md-6:eq(0)');
            } );
        </script>
    </div>
</div>
@endsection