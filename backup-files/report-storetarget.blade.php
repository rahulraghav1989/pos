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
                                            <form action="{{route('storetargetfilter')}}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Month</label>
                                                            <select class="form-control" name="month" id="month">
                                                                <option value="">SELECT</option>
                                                                @for($i=1; $i <= 12; $i++)
                                                                <option value="@php echo date('m', mktime(0,0,0,$i, 1, date('Y'))); @endphp" @if($storetargetdata['month']== date('m', mktime(0,0,0,$i, 1, date('Y')))) SELECTED='SELECTED' @endif>@php echo date('F', mktime(0,0,0,$i, 1, date('Y'))); @endphp</option>
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
                                                                <option value="@php echo date('Y', mktime(0,0,0,$i, -1, date('Y'))); @endphp" @if($storetargetdata['year']== date('Y', mktime(0,0,0,$i, -1, date('Y')))) SELECTED='SELECTED' @endif>@php echo date('Y', mktime(0,0,0,$i, -1, date('Y'))); @endphp</option>
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
                                            <form action="{{route('exceldownloadstoretarget')}}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <div class="form-group">
                                                            <label style="width: 100%;">&nbsp;</label>
                                                            <button type="submit" class="btn btn-light"><i class="fas fa-download"></i> Download Target Excel</button>
                                                            <button type="submit" class="btn btn-light"><i class="fas fa-upload"></i> Upload Target Excel</button>
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
                                                    <th data-priority="1">Store</th>
                                                    @foreach($storetargetdata['allpropositiontype'] as $allpropositiontype)
                                                        @if(App\mastercomission::where('comossioncategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->pluck('comissioncategoryview')->first() == 1)
                                                        <th data-priority="3">{{$allpropositiontype->planpropositionname}} {{$allpropositiontype->pcname}}</th>
                                                        @endif
                                                    @endforeach
                                                    @foreach($storetargetdata['allproductcategory'] as $allproductcategory)
                                                        @if(App\mastercomission::where('comossioncategory', $allproductcategory->categoryname)->pluck('comissioncategoryview')->first() == 1)
                                                        <th data-priority="3">{{$allproductcategory->categoryname}}</th>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <form action="{{route('addstoretarget')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="month" class="month">
                                                    <input type="hidden" name="year" class="year">
                                                    @foreach($storetargetdata['allstore'] as $stores)
                                                    <tr>
                                                        <td>
                                                            {{$stores->store_name}}
                                                        </td>
                                                        @foreach($storetargetdata['allpropositiontype'] as $allpropositiontype)
                                                            @if(App\mastercomission::where('comossioncategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->pluck('comissioncategoryview')->first() == 1)
                                                            <td>
                                                                <input type="hidden" name="category[]" value="{{$allpropositiontype->planpropositionname}}{{$allpropositiontype->pcname}}">
                                                                <input type="number" name="target[]" min="0" style="width: 80px;" value="{{$storetargetdata['storetarget']->where('storeID', $stores->store_id)->where('targetcategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->where('month', $storetargetdata['month'])->where('year', $storetargetdata['year'])->pluck('target')->first()}}">
                                                                <input type="hidden" name="storeid[]" value="{{$stores->store_id}}">
                                                                <input type="hidden" name="storetargetid[]" value="{{$storetargetdata['storetarget']->where('storeID', $stores->store_id)->where('targetcategory', $allpropositiontype->planpropositionname.$allpropositiontype->pcname)->where('month', $storetargetdata['month'])->where('year', $storetargetdata['year'])->pluck('storetargetID')->first()}}">
                                                            </td>
                                                            @endif
                                                        @endforeach
                                                        @foreach($storetargetdata['allproductcategory'] as $allproductcategory)
                                                            @if(App\mastercomission::where('comossioncategory', $allproductcategory->categoryname)->pluck('comissioncategoryview')->first() == 1)
                                                            <td>
                                                                <input type="hidden" name="category[]" value="{{$allproductcategory->categoryname}}">
                                                                <input type="number" name="target[]" min="0" style="width: 80px;" value="{{$storetargetdata['storetarget']->where('storeID', $stores->store_id)->where('targetcategory', $allproductcategory->categoryname)->where('month', $storetargetdata['month'])->where('year', $storetargetdata['year'])->pluck('target')->first()}}">
                                                                <input type="hidden" name="storeid[]" value="{{$stores->store_id}}">
                                                                <input type="hidden" name="storetargetid[]" value="{{$storetargetdata['storetarget']->where('storeID', $stores->store_id)->where('targetcategory', $allproductcategory->categoryname)->where('month', $storetargetdata['month'])->where('year', $storetargetdata['year'])->pluck('storetargetID')->first()}}">
                                                            </td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="7" class="text-right" style="background-color: #FFF;">
                                                            <button type="submit" class="submit btn btn-primary">Set Target</button>
                                                        </td>
                                                    </tr>
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
                    addDisplayAllBtn: 'btn btn-secondary'
                });
            });
        </script>
    </div>
</div>
@endsection