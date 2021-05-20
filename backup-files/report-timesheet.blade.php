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
                            <h4 class="page-title">Timesheet</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Roster</a></li>
                                <li class="breadcrumb-item active">Timesheet</li>
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
                    @if(session('loggindata')['loggeduserpermission']->addtimesheet=='Y')
                    <div class="col-lg-4">
                        <div class="card m-b-30">
                            <div class="card-body">

                                <h4 class="mt-0 header-title">Fill Time Sheet</h4>

                                <form action="{{route('addtimesheet')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label>Dated</label>
                                        <input type="date" name="timesheetdate" class="form-control" required="" />
                                    </div>
                                    <div class="form-group">
                                        <label>Start Time</label>    
                                        <select class="form-control" name="starttime">
                                            <option value="">SELECT</option>
                                            @php
                                            $begin = new DateTime("08:45");
                                            $end   = new DateTime("22:00");

                                            $interval = DateInterval::createFromDateString('15 min');

                                            $times    = new DatePeriod($begin, $interval, $end);

                                            foreach ($times as $time) 
                                            {
                                                echo "<option value='".$time->add($interval)->format('H:i')."'>";
                                                    echo $time->format('H:i');
                                                echo "</option>";
                                            }
                                            @endphp
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <select class="form-control" name="endtime">
                                            <option value="">SELECT</option>
                                            @php
                                            $begin = new DateTime("09:00");
                                            $end   = new DateTime("22:00");

                                            $interval = DateInterval::createFromDateString('15 min');

                                            $times    = new DatePeriod($begin, $interval, $end);

                                            foreach ($times as $time) 
                                            {
                                                echo "<option value='".$time->add($interval)->format('H:i')."'>";
                                                    echo $time->format('H:i');
                                                echo "</option>";
                                            }
                                            @endphp
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Break Time</label>
                                        <select name="breaktime" class="form-control" required="">
                                            <option value="">SELECT</option>
                                            <option value="15">15 Min</option>
                                            <option value="30">30 Min</option>
                                            <option value="45">45 Min</option>
                                            <option value="60">60 Min</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Note (If any)</label>
                                        <textarea name="timesheetnote" cols="" rows="8" class="form-control" placeholder="Type Here"></textarea>
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div> <!-- end col -->
                    @endif

                    <div @if(session('loggindata')['loggeduserpermission']->addtimesheet=='Y')class="col-lg-8" @else class="col-lg-12" @endif>
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Time Sheet : {{$timesheetdata['month']}}, {{$timesheetdata['year']}}</h4>
                                
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Break Time</th>
                                                <th>Working Hours</th>
                                                <th>Edit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($timesheetdata['gettimesheet'] as $timesheet)
                                            <tr @if($timesheet->timesheetDay == 'Sat') style="background:#d4c5b5;" @elseif($timesheet->timesheetDay == 'Sun') style="background:#b5d4b9;" @endif>
                                                <th>{{date('d-m-Y', strtotime($timesheet->timesheetDate))}}</th>
                                                <td>{{$timesheet->timesheetStarttime}}</td>
                                                <td>{{$timesheet->timesheetEndtime}}</td>
                                                <td>{{$timesheet->timesheetBreaktime}}</td>
                                                <td>{{$timesheet->timesheetWorkinghours}}</td>
                                                <td>
                                                    <!---Edit Timesheet Model---->
                                                    <div class="modal fade edittimesheet{{$timesheet->timesheetID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0">Editing Timesheet For {{$timesheet->timesheetDate}}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{route('edittimesheet')}}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="timesheetid" value="{{$timesheet->timesheetID}}">
                                                                        <div class="form-group">
                                                                            <label>Dated</label>
                                                                            <input type="date" name="timesheetdate" class="form-control" value="{{$timesheet->timesheetDate}}" required="" />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Start Time</label>    
                                                                            <select class="form-control" name="starttime">
                                                                                <option value="">SELECT</option>
                                                                                @php
                                                                                $begin = new DateTime("08:45");
                                                                                $end   = new DateTime("22:00");

                                                                                $interval = DateInterval::createFromDateString('15 min');

                                                                                $times    = new DatePeriod($begin, $interval, $end);

                                                                                foreach ($times as $time) 
                                                                                {
                                                                                    echo "<option value='".$time->add($interval)->format("H:i")."'>";
                                                                                        echo $time->format('H:i');
                                                                                    echo "</option>";
                                                                                }
                                                                                @endphp
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>End Time</label>
                                                                            <select class="form-control" name="endtime">
                                                                                <option value="">SELECT</option>
                                                                                @php
                                                                                $begin = new DateTime("09:00");
                                                                                $end   = new DateTime("22:00");

                                                                                $interval = DateInterval::createFromDateString('15 min');

                                                                                $times    = new DatePeriod($begin, $interval, $end);

                                                                                foreach ($times as $time) 
                                                                                {
                                                                                    echo "<option value='".$time->add($interval)->format('H:i')."'>";
                                                                                        echo $time->format('H:i');
                                                                                    echo "</option>";
                                                                                }
                                                                                @endphp
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Break Time</label>
                                                                            <select name="breaktime" class="form-control" required="">
                                                                                <option value="">SELECT</option>
                                                                                <option value="15">15 Min</option>
                                                                                <option value="30">30 Min</option>
                                                                                <option value="45">45 Min</option>
                                                                                <option value="60">60 Min</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Note (If any)</label>
                                                                            <textarea name="timesheetnote" cols="" rows="8" class="form-control" placeholder="Type Here"></textarea>
                                                                        </div>
                                                                        <div class="form-group text-right">
                                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
                                                    <!---Edit Timesheet Model---->
                                                    <button class="btn btn-primary" data-toggle="modal" data-target=".edittimesheet{{$timesheet->timesheetID}}" data-backdrop="static" data-keyboard="false"><i class="fas fa-pencil-alt"></i></button>
                                                </td>
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
    </div>
</div>
@endsection