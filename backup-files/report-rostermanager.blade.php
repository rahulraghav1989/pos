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
                                <form action="{{route('timesheetfilter')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Staff</label>
                                                <select class="form-control" name="userid">
                                                    <option value="">SELECT</option>
                                                    @foreach($timesheetdata['allusers'] as $users)
                                                    <option value="{{$users->id}}" @if(session('timesheetfilterdata')['userID']==$users->id) SELECTED='SELECTED' @endif>{{$users->name}}</option>
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
                                            <div class="col-md-4">
                                                <label>Date From</label>
                                                <input type="date" name="datefrom" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Date To</label>
                                                <input type="date" name="dateto" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                    </div>
                                </form>
                                <form action="{{route('cleartimesheetfilter')}}" method="post">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-outline-secondary waves-effect">Clear</button>
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
                                Showing Time Sheet Of:<h4 class="mt-0 header-title"> {{$timesheetdata['getuser']->name}}</h4>@if($timesheetdata['getstore'] != '')<h4 class="mt-0 header-title"> {{$timesheetdata['getstore']->store_name}}</h4>@endif
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Time Sheet : {{$timesheetdata['month']}}, {{$timesheetdata['year']}}</h4>
                                
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="tech-companies-1" class="table  table-striped">
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
                                            <form action="{{route('paysalary')}}" method="post">
                                                @csrf
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
                                                @if(session('loggindata')['loggeduserpermission']->rostermanagerpay=='Y')
                                                <tr>
                                                    <td colspan="9">
                                                        <input type="hidden" name="userID" value="{{$timesheetdata['getuser']->id}}">
                                                        <input type="hidden" name="month" value="{{$timesheetdata['month']}}">
                                                        <input type="hidden" name="year" value="{{$timesheetdata['year']}}">
                                                        <button type="submit" class="btn btn-outline-success waves-effect waves-light">Pay Selected</button>
                                                    </td>
                                                </tr>
                                                @endif
                                            </form>
                                            </tbody>
                                        </table>
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
                                                <th>Hourly Rate</th>
                                                <th>Unpaid Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    WeekDays
                                                </td>
                                                <td>
                                                    {{$timesheetdata['unpaidweekdayshours']}}
                                                </td>
                                                <td>{{$timesheetdata['getuser']->normalrate}}</td>
                                                <td>{{$timesheetdata['unpaidweekdaysamount']}}</td>
                                            </tr>
                                            <tr style="background:#d4c5b5;">
                                                <td>
                                                    Saturday
                                                </td>
                                                <td>{{$timesheetdata['unpaidsaturdayhours']}}</td>
                                                <td>{{$timesheetdata['getuser']->saturdayrate}}</td>
                                                <td>{{$timesheetdata['unpaidsaturdayamount']}}</td>
                                            </tr>
                                            <tr style="background:#b5d4b9;">
                                                <td>
                                                    Sunday
                                                </td>
                                                <td>{{$timesheetdata['unpaidsundayhours']}}</td>
                                                <td>{{$timesheetdata['getuser']->sundayrate}}</td>
                                                <td>{{$timesheetdata['unpaidsundayamount']}}</td>
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
    </div>
</div>
@endsection