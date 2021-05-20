@extends('main')

@section('content')
<div id="wrapper">
	@include('includes.topbar')

    @include('includes.sidebar')
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".submit").click(function () {

                var books = $('#month');
                var year = $('#year');
                if (books.val() === '' && year.val() === '') {
                    alert("Please select month and year");
                    $('#month').focus();

                    return false;
                }
                else 
                    $('.month').val(books.val());
                    $('.year').val(year.val());
                    return books;
            });
        });
    </script>
    <style type="text/css">
        .success{
            font-size: 0.9em;
            color: #6ede5a;
            font-weight: bold;
        }
        .fail{
            font-size: 0.9em;
            color: #e00f0f;
            font-weight: bold;
        }
    </style>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Set Personal Target</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Personal Tracker</a></li>
                                <li class="breadcrumb-item active">Set Personal Target</li>
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
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                {{$error}}
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(session()->has('success'))
                                        <div class="card-body">                                 
                                            <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                                {{ session()->get('success') }}
                                            </div>
                                        </div>
                                    @endif
                                    @if(session()->has('error'))
                                    <div class="card-body">
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('error') }}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Set Target For:</h6>
                                            <form action="{{route('personaltargetfilter')}}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Month</label>
                                                            <select class="form-control" name="month" id="month">
                                                                <option value="">SELECT</option>
                                                                @for($i=1; $i <= 12; $i++)
                                                                <option value="@php echo date('m', mktime(0,0,0,$i, 1, date('Y'))); @endphp" @if($personaltargetdata['month']== date('m', mktime(0,0,0,$i, 1, date('Y')))) SELECTED='SELECTED' @endif>@php echo date('F', mktime(0,0,0,$i, 1, date('Y'))); @endphp</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Year</label>
                                                            <select name="year" class="form-control" id="year">
                                                                <option value="">SELECT</option>
                                                                @for($i=1; $i <= 2; $i++)
                                                                <option value="@php echo date('Y', mktime(0,0,0,$i, -1, date('Y'))); @endphp" @if($personaltargetdata['year']== date('Y', mktime(0,0,0,$i, -1, date('Y')))) SELECTED='SELECTED' @endif>@php echo date('Y', mktime(0,0,0,$i, -1, date('Y'))); @endphp</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label style="width: 100%;">&nbsp;</label>
                                                            <button type="submit" class="btn btn-primary">Set</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-right">&nbsp;</h6>
                                            <!---Export Import Model-->
                                            <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0">Import Personal Target</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="" action="{{route('importpersonaldata')}}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>Choose File</label>
                                                                    <input type="file" name="personaltargetfile" class="form-control" required="">
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <div>
                                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                            Submit
                                                                        </button>
                                                                        <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                                            Cancel
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <!---Export Import Model-->
                                            <form action="{{route('exceldownloadpersonaltarget')}}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <div class="form-group">
                                                            <label style="width: 100%;">&nbsp;</label>
                                                            <button type="submit" class="btn btn-light"><i class="fas fa-download"></i> Download Target Excel</button>
                                                            <a href="" class="btn btn-light" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false"><i class="fas fa-upload"></i> Upload Target Excel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        	</div>
                            <div class="card-body">
                                <style type="text/css">
                                    .table-rep-plugin .btn-group{display: none;}
                                </style>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr>
                                                <th>User</th>
                                                @foreach($personaltargetdata['allpropositiontype'] as $allpropositiontype)
                                                    @if(App\mastercomission::where('comossioncategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->pluck('comissioncategoryview')->first() == 1)
                                                    <th data-priority="3">{{$allpropositiontype->planpropositionname}} {{$allpropositiontype->pcname}}</th>
                                                    @endif
                                                @endforeach
                                                @foreach($personaltargetdata['allproductcategory'] as $allproductcategory)
                                                    @if(App\mastercomission::where('comossioncategory', $allproductcategory->categoryname)->pluck('comissioncategoryview')->first() == 1)
                                                    <th data-priority="3">{{$allproductcategory->categoryname}}</th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <form action="{{route('addpersonaltarget')}}" method="post">
                                                @csrf
                                                <input type="hidden" id="month" name="month" class="month" value="{{$personaltargetdata['month']}}">
                                                <input type="hidden" id="year" name="year" class="year" value="{{$personaltargetdata['year']}}">
                                                @foreach($personaltargetdata['allusers']->groupBy('id') as $users)
                                                <tr>
                                                    <td>
                                                        {{$users[0]->name}}
                                                    </td>
                                                    @foreach($personaltargetdata['allpropositiontype'] as $key => $allpropositiontype)
                                                        @if(App\mastercomission::where('comossioncategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->pluck('comissioncategoryview')->first() == 1)
                                                        <td>
                                                            <input type="hidden" id="category{{$key}}{{$users[0]->id}}" value="{{$allpropositiontype->planpropositionname}}{{$allpropositiontype->pcname}}">
                                                            <input type="text" id="target{{$key}}{{$users[0]->id}}" min="0" style="width: 80px;" value="@if($personaltargetdata['personaltarget']->where('userID', $users[0]->id)->where('targetcategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->where('month', $personaltargetdata['month'])->where('year', $personaltargetdata['year'])->pluck('target')->first() == "")0.00 @else{{$personaltargetdata['personaltarget']->where('userID', $users[0]->id)->where('targetcategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->where('month', $personaltargetdata['month'])->where('year', $personaltargetdata['year'])->pluck('target')->first()}}@endif">
                                                            <input type="hidden" id="userid{{$key}}{{$users[0]->id}}" value="{{$users[0]->id}}">
                                                            <input type="hidden" id="personaltargetid{{$key}}{{$users[0]->id}}" value="{{$personaltargetdata['personaltarget']->where('userID', $users[0]->id)->where('targetcategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->where('month', $personaltargetdata['month'])->where('year', $personaltargetdata['year'])->pluck('personaltargetID')->first()}}">
                                                            <span id="returnmsg{{$key}}{{$users[0]->id}}"></span>
                                                        </td>
                                                        <script>
                                                        $(document).ready(function(){

                                                         $('#target{{$key}}{{$users[0]->id}}').blur(function(){
                                                          var error_email = '';
                                                          var target = $('#target{{$key}}{{$users[0]->id}}').val();
                                                          var category = $('#category{{$key}}{{$users[0]->id}}').val();
                                                          var userid = $('#userid{{$key}}{{$users[0]->id}}').val();
                                                          var personaltargetid = $('#personaltargetid{{$key}}{{$users[0]->id}}').val();
                                                          var month = $('#month').val();
                                                          var year = $('#year').val();
                                                          var _token = $('input[name="_token"]').val();
                                                          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                                          $.ajax({
                                                            url:"{{route('addpersonaltarget')}}",
                                                            method:"POST",
                                                            data:{target:target, category:category, userid:userid, personaltargetid:personaltargetid, month:month, year:year, _token:_token},
                                                            success:function(result)
                                                            {
                                                                if(result == 'Success')
                                                                {
                                                                    $('#returnmsg{{$key}}{{$users[0]->id}}').html('<br><span class="success">Target Updated</span>');
                                                                }
                                                                else
                                                                {
                                                                    $('#returnmsg{{$key}}{{$users[0]->id}}').html('<br><span class="fail">Target Not Updated</span>');
                                                                }
                                                            }
                                                           })
                                                         });
                                                         
                                                        });
                                                        </script>
                                                        @endif
                                                    @endforeach
                                                    @foreach($personaltargetdata['allproductcategory'] as $key => $allproductcategory)
                                                        @if(App\mastercomission::where('comossioncategory', $allproductcategory->categoryname)->pluck('comissioncategoryview')->first() == 1)
                                                        <td>
                                                            <input type="hidden" id="categoryO{{$key}}{{$users[0]->id}}" value="{{$allproductcategory->categoryname}}">
                                                            <input type="text" id="targetO{{$key}}{{$users[0]->id}}" min="0" style="width: 80px;" value="@if($personaltargetdata['personaltarget']->where('userID', $users[0]->id)->where('targetcategory', $allproductcategory->categoryname)->where('month', $personaltargetdata['month'])->where('year', $personaltargetdata['year'])->pluck('target')->first() == "")0.00 @else{{$personaltargetdata['personaltarget']->where('userID', $users[0]->id)->where('targetcategory', $allproductcategory->categoryname)->where('month', $personaltargetdata['month'])->where('year', $personaltargetdata['year'])->pluck('target')->first()}}@endif">
                                                            <input type="hidden" id="useridO{{$key}}{{$users[0]->id}}" value="{{$users[0]->id}}">
                                                            <input type="hidden" id="personaltargetidO{{$key}}{{$users[0]->id}}" value="{{$personaltargetdata['personaltarget']->where('userID', $users[0]->id)->where('targetcategory', $allproductcategory->categoryname)->where('month', $personaltargetdata['month'])->where('year', $personaltargetdata['year'])->pluck('personaltargetID')->first()}}">
                                                            <span id="returnmsgO{{$key}}{{$users[0]->id}}"></span>
                                                        </td>
                                                        <script>
                                                        $(document).ready(function(){

                                                         $('#targetO{{$key}}{{$users[0]->id}}').blur(function(){
                                                          var error_email = '';
                                                          var target = $('#targetO{{$key}}{{$users[0]->id}}').val();
                                                          var category = $('#categoryO{{$key}}{{$users[0]->id}}').val();
                                                          var userid = $('#useridO{{$key}}{{$users[0]->id}}').val();
                                                          var personaltargetid = $('#personaltargetidO{{$key}}{{$users[0]->id}}').val();
                                                          var month = $('#month').val();
                                                          var year = $('#year').val();
                                                          var _token = $('input[name="_token"]').val();
                                                          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                                          $.ajax({
                                                            url:"{{route('addpersonaltarget')}}",
                                                            method:"POST",
                                                            data:{target:target, category:category, userid:userid, personaltargetid:personaltargetid, month:month, year:year, _token:_token},
                                                            success:function(result)
                                                            {
                                                                if(result == 'Success')
                                                                {
                                                                    $('#returnmsgO{{$key}}{{$users[0]->id}}').html('<br><span class="success">Target Updated</span>');
                                                                }
                                                                else
                                                                {
                                                                    $('#returnmsgO{{$key}}{{$users[0]->id}}').html('<br><span class="fail">Target Not Updated</span>');
                                                                }
                                                            }
                                                           })
                                                         });
                                                         
                                                        });
                                                        </script>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                @endforeach
                                                <!-- <tr>
                                                    <td colspan="7" class="text-right" style="background-color: #FFF;">
                                                        <button type="submit" class="submit btn btn-primary">Set Target</button>
                                                    </td>
                                                </tr> -->
                                                </form>
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
        <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>

        <!-- App js -->
        <script src="{{ asset('posview') }}/assets/js/app.js"></script>
        <script>
            $(function() {
                $('.table-responsive').responsiveTable({
                    addDisplayAllBtn: 'btn btn-secondary',
                    "order": [[ 0, "asc" ]],
                });
            });
        </script>
    </div>
</div>
@endsection