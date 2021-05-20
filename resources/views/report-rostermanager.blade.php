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
                            <h4 class="page-title">Roster Manager</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Roster</a></li>
                                <li class="breadcrumb-item active">Roster Manager</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{$error}}
                        </div>
                    @endforeach
                @endif
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        {{ session()->get('success') }}
                    </div>
                @endif
                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 10px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    {{ session()->get('error') }}
                </div>
                @endif
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <label>Filter's</label>
                                <form action="{{route('rostermanager')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Staff</label>
                                                <select class="form-control" name="userid">
                                                    <option value="">SELECT</option>
                                                    @foreach($timesheetdata['allusers'] as $users)
                                                    <option value="{{$users->id}}" @if($timesheetdata['userID'] == $users->id) SELECTED='SELECTED' @endif>{{$users->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Month</label>
                                                <select class="form-control" name="month">
                                                    <option value="">SELECT</option>
                                                    @for($i=1; $i <= 12; $i++)
                                                    <option value="@php echo date('m', mktime(0,0,0,$i, 1, date('Y'))); @endphp" @if(session('timesheetfilterdata')['month']== date('m', mktime(0,0,0,$i, 1, date('Y')))) SELECTED='SELECTED' @endif>@php echo date('F', mktime(0,0,0,$i, 1, date('Y'))); @endphp</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Year</label>
                                                <select class="form-control" name="year">
                                                    <option value="">SELECT</option>
                                                    @for($i=1; $i <= 2; $i++)
                                                    <option value="@php echo date('Y', mktime(0,0,0,$i, -1, date('Y'))); @endphp" @if(session('timesheetfilterdata')['year']== date('Y', mktime(0,0,0,$i, -1, date('Y')))) SELECTED='SELECTED' @endif>@php echo date('Y', mktime(0,0,0,$i, -1, date('Y'))); @endphp</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-12" style="margin-top: 50px;">
                                                <label>Date Range</label>
                                                <div class="form-group">
                                                    <div>
                                                        <div class="input-daterange input-group" id="date-range">
                                                            <input type="text" class="form-control" name="datefrom" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($timesheetdata['datefrom'])) @endphp" />
                                                            <input type="text" class="form-control" name="dateto" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($timesheetdata['dateto'])) @endphp" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                Showing Time Sheet Of:<h4 class="mt-0 header-title"> @if($timesheetdata['getuser']!="") {{$timesheetdata['getuser']->name}} @endif</h4>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Time Sheet : @php echo date('d-m-Y', strtotime($timesheetdata['datefrom'])) @endphp TO @php echo date('d-m-Y', strtotime($timesheetdata['dateto'])) @endphp</h4>
                                
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <form action="{{route('paysalary')}}" method="post">
                                            @csrf
                                        <table id="datatable-buttons" class="table  table-striped" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Day</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Break Time</th>
                                                <th>Working Hours</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($timesheetdata['gettimesheet'] as $timesheet)
                                                <tr @if($timesheet->timesheetDay == 'Sat') style="background:#d4c5b5;" @elseif($timesheet->timesheetDay == 'Sun') style="background:#b5d4b9;" @endif>
                                                    <td>
                                                        @if(session('loggindata')['loggeduserpermission']->rostermanagerpay=='Y')
                                                            @if($timesheet->timesheetPayStatus == 'Unpaid')
                                                                <input type="checkbox" name="payday[]" value="{{$timesheet->timesheetID}}"> 
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>{{date('d-m-Y', strtotime($timesheet->timesheetDate))}}</td>
                                                    <td>{{$timesheet->timesheetDay}}</td>
                                                    <td>{{$timesheet->timesheetStarttime}}</td>
                                                    <td>{{$timesheet->timesheetEndtime}}</td>
                                                    <td>{{$timesheet->timesheetBreaktime}}</td>
                                                    <td>{{$timesheet->timesheetWorkinghours}}</td>
                                                    <td>{{$timesheet->timesheetPayStatus}}</td>
                                                    <td>{{$timesheet->timesheetNote}}</td>
                                                </tr>
                                                @endforeach
                                            
                                            </tbody>
                                        </table>
                                        <div class="col-md-12">
                                            @if(session('loggindata')['loggeduserpermission']->rostermanagerpay=='Y')
                                                
                                                <input type="hidden" name="userID" value="{{$timesheetdata['userID']}}">
                                                <button type="submit" class="btn btn-outline-success waves-effect waves-light">Pay Selected</button>
                                                
                                            @endif
                                        </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">
                                <h4 class="mt-0 header-title">Payment</h4>
                                
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Unpaid Working Hours</th>
                                                <th>Unpaid Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    WeekDays
                                                </td>
                                                <td>
                                                    @php
                                                    $total = 0;

                                                    foreach($timesheetdata['unpaidweekdayshours'] as $weekdayhours)
                                                    {
                                                        $temp = explode(":", $weekdayhours->timesheetWorkinghours);

                                                        

                                                        $total+= (int) $temp[0] * 3600;
                                                        $total+= (int) $temp[1] * 60;
                                                    }
                                                    $formatted = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60)); 
                                                      
                                                    echo $formatted;
                                                    @endphp
                                                </td>
                                                <td>{{$timesheetdata['unpaidweekdaysamount']}}</td>
                                            </tr>
                                            <tr style="background:#d4c5b5;">
                                                <td>
                                                    Saturday
                                                </td>
                                                <td>
                                                    @php
                                                    $total = 0;

                                                    foreach($timesheetdata['unpaidsaturdayhours'] as $saturdayhours)
                                                    {
                                                        $temp = explode(":", $saturdayhours->timesheetWorkinghours);

                                                        

                                                        $total+= (int) $temp[0] * 3600;
                                                        $total+= (int) $temp[1] * 60;
                                                    }
                                                    $formatted1 = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60)); 
                                                      
                                                    echo $formatted1;
                                                    @endphp
                                                </td>
                                                <td>{{$timesheetdata['unpaidsaturdayamount']}}</td>
                                            </tr>
                                            <tr style="background:#b5d4b9;">
                                                <td>
                                                    Sunday
                                                </td>
                                                <td>
                                                    @php
                                                    $total = 0;

                                                    foreach($timesheetdata['unpaidsundayhours'] as $sundayhours)
                                                    {
                                                        $temp = explode(":", $sundayhours->timesheetWorkinghours);

                                                        

                                                        $total+= (int) $temp[0] * 3600;
                                                        $total+= (int) $temp[1] * 60;
                                                    }
                                                    $formatted2 = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60)); 
                                                      
                                                    echo $formatted2;
                                                    @endphp
                                                </td>
                                                <td>{{$timesheetdata['unpaidsundayamount']}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td><b>
                                                    
                                                    @php
                                                    $total = 0;
                                                    $formatted = explode(":", $formatted);
                                                    $formatted1 = explode(":", $formatted1);
                                                    $formatted2 = explode(":", $formatted2);

                                                    $total+= (int) ($formatted[0] + $formatted1[0] + $formatted2[0]) * 3600;
                                                    $total+= (int) ($formatted[1] + $formatted1[1] + $formatted2[1]) * 60;
                                                    
                                                    $gtotal = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60));
                                                    echo $gtotal;
                                                    @endphp
                                                </b>
                                                </td>
                                                <td>
                                                    <b>
                                                    {{$timesheetdata['unpaidweekdaysamount'] + $timesheetdata['unpaidsaturdayamount'] + $timesheetdata['unpaidsundayamount']}}
                                                    </b>
                                                </td>
                                            </tr>
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
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'Timesheet',
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