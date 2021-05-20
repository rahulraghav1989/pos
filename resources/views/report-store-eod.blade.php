@extends('main')

@section('content')
<div id="wrapper">
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
    @include('includes.topbar')

    @include('includes.sidebar')

    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
    <link href="{{ asset('posview') }}/multipleselector/styles/multiselect.css" rel="stylesheet" />
    <script src="{{ asset('posview') }}/multipleselector/scripts/multiselect.min.js"></script>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">All Store EOD</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales</a></li>
                                <li class="breadcrumb-item active">All Store EOD</li>
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
                                                                <form method="post" action="{{route('storeeodreport')}}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        @if(session('loggindata')['loggeduserpermission']->storeeodreportfilter=='Y')
                                                                        <div class="col-md-3">
                                                                            <label>Select Store</label>
                                                                            <select id='testSelect1' name="store[]" multiple>
                                                                                @foreach($eoddata['allstore'] as $allstores)
                                                                                    @if(!empty($eoddata['storeID']))
                                                                                        <option value="{{$allstores->store_id}}" @foreach($eoddata['storeID'] as $selectedstoreid) @if($allstores->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstores->store_name}}</option>
                                                                                    @else
                                                                                        <option value="{{$allstores->store_id}}">{{$allstores->store_name}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                document.multiselect('#testSelect1')
                                                                                    .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                        console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                    })
                                                                                    .setCheckBoxClick("1", function(target, args) {
                                                                                        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                    });
                                                                            </script>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>Date</label>
                                                                            <div class="form-group">
                                                                                <input type="date" name="eoddate" value="@php echo date('d-m-Y', strtotime($eoddata['todaydate'])) @endphp" style="width: 50%; padding: 5px 0px; border-radius: 5px; border: thin solid #CCC;">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>Submit</label>
                                                                            <br>
                                                                            <input type="hidden" name="appliedfilter" value="yes">
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
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>Store EOD Report</p>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="tech-companies-1" class="table  table-striped" border="1">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Store</th>
                                                <th colspan="2" style="text-align: center !important;">Cash</th>
                                                <th colspan="2" style="text-align: center !important;">EFTPOS</th>
                                                <th colspan="2" style="text-align: center !important;">Other</th>
                                                <th colspan="2" style="text-align: center !important;">Store Credit(Float)</th>
                                                <th>EOD Done By</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Expected</td>
                                                <td>Counted</td>
                                                <td>Expected</td>
                                                <td>Counted</td>
                                                <td>Expected</td>
                                                <td>Counted</td>
                                                <td>Expected</td>
                                                <td>Counted</td>
                                                <td></td>
                                            </tr>
                                            @foreach($eoddata['getalleod']->groupBy('storeID') as $storeeod)
                                            <tr>
                                                <td>
                                                  @php echo date('d-m-Y', strtotime($eoddata['todaydate'])) @endphp
                                                </td>
                                                <td>{{$storeeod[0]->store_name}}</td>
                                                <td>{{$storeeod->where('eodPaymentType', 'Cash')->where('storeID', $storeeod[0]->store_id)->pluck('storeReconAmount')->first()}}</td>
                                                <td>{{$storeeod->where('eodPaymentType', 'Cash')->where('storeID', $storeeod[0]->store_id)->pluck('eodAmount')->first()}}</td>
                                                <td>{{$storeeod->where('eodPaymentType', 'EFTPOS')->where('storeID', $storeeod[0]->store_id)->pluck('storeReconAmount')->first()}}</td>
                                                <td>{{$storeeod->where('eodPaymentType', 'EFTPOS')->where('storeID', $storeeod[0]->store_id)->pluck('eodAmount')->first()}}</td>
                                                <td>{{$storeeod->where('eodPaymentType', 'Others')->where('storeID', $storeeod[0]->store_id)->pluck('storeReconAmount')->first()}}</td>
                                                <td>{{$storeeod->where('eodPaymentType', 'Others')->where('storeID', $storeeod[0]->store_id)->pluck('eodAmount')->first()}}</td>
                                                <td>{{$storeeod->where('eodPaymentType', 'storecredit')->where('storeID', $storeeod[0]->store_id)->pluck('storeReconAmount')->first()}}</td>
                                                <td>{{$storeeod->where('eodPaymentType', 'storecredit')->where('storeID', $storeeod[0]->store_id)->pluck('eodAmount')->first()}}</td>
                                                <td>{{$storeeod[0]->name}}</td>
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
        @include('includes.footer')
    </div>
</div>
@endsection