@extends('main')

@section('content')
<div id="wrapper">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
    <style type="text/css">
        .chosen-container{
            width: 100% !important;
            margin-bottom: 20px;
        }
        .chosen-choices{
            display: block !important;
            height: auto !important;
            padding: .375rem .75rem !important;
            font-size: 1rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            color: #495057 !important;
            background-color: #000 !important;
            background-clip: padding-box !important;
            border: 1px solid #ced4da !important;
            border-radius: .25rem !important;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .col-md-3{
            margin-bottom: 20px !important;
        }
    </style>
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
    <link href="{{ asset('posview') }}/multipleselector/styles/multiselect.css" rel="stylesheet" />
    <script src="{{ asset('posview') }}/multipleselector/scripts/multiselect.min.js"></script>
    
    @include('includes.topbar')

    @include('includes.sidebar')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Roster Report</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Roster</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Roster Report</a></li>
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
                                                    <div class="text-right">
                                                        <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                            Filters
                                                        </a>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="collapse" id="collapseExample">
                                                            <div class="card card-body mt-3 mb-0">
                                                                <form method="post" action="{{route('rosterreport')}}">
                                                                    @csrf
                                                                    <div class="row" style="margin-top: 70px;">
                                                                        @if(session('loggindata')['loggeduserpermission']->reportrosterfilter=='Y')
                                                                        
                                                                        <div class="col-md-3" style="width: 200px !important;">
                                                                            <label>Select User</label>
                                                                            <select id='testSelect2' name="user[]" multiple>
                                                                                @foreach($allusers as $allusers)
                                                                                    @if(!empty($userID))
                                                                                        <option value="{{$allusers->id}}" @foreach($userID as $selecteduserid) @if($allusers->id == $selecteduserid) SELECTED='SELECTED' @endif @endforeach>{{$allusers->name}}</option>
                                                                                    @else
                                                                                        <option value="{{$allusers->id}}">{{$allusers->name}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                document.multiselect('#testSelect2')
                                                                                    .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                        console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                    })
                                                                                    .setCheckBoxClick("1", function(target, args) {
                                                                                        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                    });
                                                                            </script>
                                                                        </div>
                                                                        @endif
                                                                        <div class="col-md-9">
                                                                            <label>Date Range</label>
                                                                            <div class="form-group">
                                                                                <div>
                                                                                    <div class="input-daterange input-group" id="date-range">
                                                                                        <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($firstday)) @endphp" />
                                                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($lastday)) @endphp" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 text-right">
                                                                            <input type="hidden" name="appliedfilter" value="yes">
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
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Date (From)</th>
                                                <th>Date (To)</th>
                                                <th>Paid Hrs.</th>
                                                <th>Paid Amt.</th>
                                                <th>Unpaid Weekdays Hrs.</th>
                                                <th>Unpaid Weekdays Amt.</th>
                                                <th>Unpaid Sat. Hrs.</th>
                                                <th>Unpaid Sat. Amt.</th>
                                                <th>Unpaid Sun. Hrs.</th>
                                                <th>Unpaid Sun Amt.</th>
                                                <th>Total Working Hrs.</th>
                                                <th>Total Amt.</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            	@foreach($roster->groupBy('id') as $rosterdata)
                                            		<tr>
                                            			<td>{{$rosterdata[0]->name}}</td>
                                            			<td>
                                            				@php
                                            				echo date('d-m-Y', strtotime($firstday))
                                            				@endphp
                                            			</td>
                                                        <td>
                                                            @php
                                                            echo date('d-m-Y', strtotime($lastday))
                                                            @endphp
                                                        </td>
                                            			<td>
                                            				@php
                                            				$total = 0;
                                            				foreach(App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->where('timesheetPayStatus', 'Paid')->get() as $paidhours)
                                            				{
                                            					$temp = explode(":", $paidhours->timesheetWorkinghours);
		                                                        $total+= (int) $temp[0] * 3600;
		                                                        $total+= (int) $temp[1] * 60;
                                            				}
                                            				$formatted = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60)); 
                                                      
                                                    		echo $formatted;
                                            				@endphp
                                            			</td>
                                            			<td>{{App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->where('timesheetPayStatus', 'Paid')->sum('timesheetHoursAmount')}}</td>
                                            			<td>
                                            				@php
                                            				$total = 0;
                                            				foreach(App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->whereNotIn('timesheetDay', ['Sat', 'Sun'])->where('timesheetPayStatus', 'Unpaid')->get() as $unpaidhours)
                                            				{
                                            					$temp = explode(":", $unpaidhours->timesheetWorkinghours);
		                                                        $total+= (int) $temp[0] * 3600;
		                                                        $total+= (int) $temp[1] * 60;
                                            				}
                                            				$formatted = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60)); 
                                                      
                                                    		echo $formatted;
                                            				@endphp
                                            			</td>
                                            			<td>{{App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->whereNotIn('timesheetDay', ['Sat', 'Sun'])->where('timesheetPayStatus', 'Unpaid')->sum('timesheetHoursAmount')}}</td>
                                            			<td>
                                                            @php
                                                            $total = 0;
                                                            foreach(App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->where('timesheetDay', 'Sat')->where('timesheetPayStatus', 'Unpaid')->get() as $unpaidhours)
                                                            {
                                                                $temp = explode(":", $unpaidhours->timesheetWorkinghours);
                                                                $total+= (int) $temp[0] * 3600;
                                                                $total+= (int) $temp[1] * 60;
                                                            }
                                                            $formatted = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60)); 
                                                      
                                                            echo $formatted;
                                                            @endphp
                                                        </td>
                                                        <td>{{App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->where('timesheetDay', 'Sat')->where('timesheetPayStatus', 'Unpaid')->sum('timesheetHoursAmount')}}</td>
                                                        <td>
                                                            @php
                                                            $total = 0;
                                                            foreach(App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->where('timesheetDay', 'Sun')->where('timesheetPayStatus', 'Unpaid')->get() as $unpaidhours)
                                                            {
                                                                $temp = explode(":", $unpaidhours->timesheetWorkinghours);
                                                                $total+= (int) $temp[0] * 3600;
                                                                $total+= (int) $temp[1] * 60;
                                                            }
                                                            $formatted = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60)); 
                                                      
                                                            echo $formatted;
                                                            @endphp
                                                        </td>
                                                        <td>{{App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->where('timesheetDay', 'Sun')->where('timesheetPayStatus', 'Unpaid')->sum('timesheetHoursAmount')}}</td>
                                                        <td>
                                            				@php
                                            				$total = 0;
                                            				foreach(App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->get() as $totalhours)
                                            				{
                                            					$temp = explode(":", $totalhours->timesheetWorkinghours);
		                                                        $total+= (int) $temp[0] * 3600;
		                                                        $total+= (int) $temp[1] * 60;
                                            				}
                                            				$formatted = sprintf('%02d:%02d',  
                                                                    ($total / 3600), 
                                                                    ($total / 60 % 60)); 
                                                      
                                                    		echo $formatted;
                                            				@endphp
                                            			</td>
                                            			<td>{{App\rostertimesheet::where('rostertimesheet.timesheetDate', '>=', $firstday)->where('rostertimesheet.timesheetDate', '<=', $lastday)->where('userID', $rosterdata[0]->id)->sum('timesheetHoursAmount')}}</td>
                                            		</tr>
                                            	@endforeach
                                            </tbody>
                                            <tfoot>
                                                
                                            </tfoot>
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
        <!-----multi select-->
        <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
        
        <script type="text/javascript">
            $(".chosen-select").chosen({
              no_results_text: "Oops, nothing found!"
            })
        </script>


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
                                title: 'Roster Report',
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