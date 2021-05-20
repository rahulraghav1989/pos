@extends('main')

@section('content')
    <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
            
    <script type="text/javascript">
        $(document).ready(function() {
            var sum = 0;
            $('.sum').each(function() {
              sum += +$(this).text()||0;
            });
            $("#subtotal").text(sum.toFixed(2));
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var sum = $('#subtotal').text()||0;
            var hours = $('#hours').text()||0;
            var a = hours.split(':');
            var hour = a[0];
            /*var perminutsbonus = sum/60;
            var bonusperminut = sum*minutes;*/
            var avg = sum/hour;
            $("#avg").text(avg.toFixed(2));
        });
    </script>
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
                                <h4 class="page-title">Tracker <span style="color: #30419b;">{{$comissiondata['getuser']->name}} ( {{$comissiondata['month']}} - {{$comissiondata['year']}} )</span></h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Tracker</li>
                                </ol>
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            
                                            <div id="accordion">
                                                <div class="card mb-0">
                                                    <a data-toggle="collapse" data-parent="#accordion"
                                                                href="#collapseOne" aria-expanded="true"
                                                                aria-controls="collapseOne" class="text-dark">
                                                        <div class="card-header" id="headingOne" style="background-color: #FFF; border-bottom: none;">
                                                            
                                                                <h5 class="mb-0 mt-0 font-14">
                                                                        Filter
                                                                </h5>
                                                            
                                                        </div>
                                                    </a>
                                                    <div id="collapseOne" class="collapse"
                                                            aria-labelledby="headingOne" data-parent="#accordion">
                                                        <div class="card-body">
                                                            <form action="{{route('usertrackerfilter')}}" method="post">
                                                                @csrf
                                                                <div class="row">
                                                                    @if(session('loggindata')['loggeduserpermission']->viewtrackerfilter=='Y')
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>User</label>
                                                                            <select name="userid" class="form-control">
                                                                                <option value="">SELECT</option>
                                                                                @foreach($comissiondata['allusers'] as $allusers)
                                                                                <option value="{{$allusers->id}}" @if($comissiondata['userID']== $allusers->id) SELECTED='SELECTED' @endif>{{$allusers->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Month</label>
                                                                            <select name="month" class="form-control">
                                                                                <option value="">SELECT</option>
                                                                                @for($i=1; $i <= 12; $i++)
                                                                                <option value="@php echo date('m', mktime(0,0,0,$i, 1, date('Y'))); @endphp" @if($comissiondata['month']== date('m', mktime(0,0,0,$i, 1, date('Y')))) SELECTED='SELECTED' @endif>@php echo date('F', mktime(0,0,0,$i, 1, date('Y'))); @endphp</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Year</label>
                                                                            <select name="year" class="form-control">
                                                                                <option value="">SELECT</option>
                                                                                @for($i=1; $i <= 2; $i++)
                                                                                <option value="@php echo date('Y', mktime(0,0,0,$i, -1, date('Y'))); @endphp" @if($comissiondata['year']== date('Y', mktime(0,0,0,$i, -1, date('Y')))) SELECTED='SELECTED' @endif>@php echo date('Y', mktime(0,0,0,$i, -1, date('Y'))); @endphp</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 text-right">
                                                                        <button type="submit" class="btn btn-primary">Apply</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            @if(session('loggindata')['loggeduserpermission']->addpersonaltarget=='Y')
                                                <a href="{{route('setpersonaltarget')}}" class="btn btn-light waves-effect"><i class="fas fa-crosshairs"></i> Set Personal Target</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                    <h5>Total Bonus: <span id="subtotal"></span></h5>
                                    @endif
                                    <h5>Total Working Hours: <span id="hours">{{$comissiondata['formatted']}}</span></h5>
                                    <h5>Average: <span id="avg"></span></h5>
                                    <div class="row">
                                        @foreach($comissiondata['planproposition'] as $proposition)
                                        <!----Comission Model---->
                                        <div class="modal fade comissionmodel@php echo str_replace(' ', '', $proposition->planpropositionname.$proposition->pcname) @endphp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{$proposition->planpropositionname}} {{$proposition->pcname}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="datatable1" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Invoice ID</th>
                                                                <th>Order ID</th>
                                                                <th>Phone</th>
                                                                <th>Plan</th>
                                                                <th>Product</th>
                                                                @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                                <th>Bonus (Inc. GST)</th>
                                                                @endif
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID) as $categorycomission)
                                                            <tr>
                                                                <td>@php echo date('d-m-Y', strtotime($categorycomission->orderDate)) @endphp</td>
                                                                <td>{{$categorycomission->orderID}}</td>
                                                                <td>{{$categorycomission->planOrderID}}</td>
                                                                <td>{{$categorycomission->planMobilenumber}}</td>
                                                                <td>{{$categorycomission->plancode}}<br>{{$categorycomission->planname}}</td>
                                                                <td>{{$categorycomission->productname}}<br>{{$categorycomission->barcode}}</td>
                                                                @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                                <th>${{$categorycomission->Comission}}</th>
                                                                @endif
                                                            </tr>
                                                            @endforeach   
                                                            @foreach($comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID) as $refundcategorycomission)
                                                            <tr>
                                                                <td>@php echo date('d-m-Y', strtotime($refundcategorycomission->refundDate)) @endphp</td>
                                                                <td>{{$refundcategorycomission->refundInvoiceID}}</td>
                                                                <td>{{$refundcategorycomission->planOrderID}}</td>
                                                                <td>{{$refundcategorycomission->planMobilenumber}}</td>
                                                                <td>{{$refundcategorycomission->plancode}}<br>{{$refundcategorycomission->planname}}</td>
                                                                <td>{{$refundcategorycomission->productname}}<br>{{$refundcategorycomission->barcode}}</td>
                                                                @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                                <th>-${{$refundcategorycomission->Comission}}</th>
                                                                @endif
                                                            </tr>
                                                            @endforeach
                                                            @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                            <tr>
                                                                <td colspan="5" align="right">Total Bonus</td>
                                                                <td>${{$comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('Comission') - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('Comission')}}</td>
                                                            </tr>
                                                            @endif  
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                        <!----Comission Model---->
                                        @if(App\mastercomission::where('comossioncategory', $proposition->planpropositionname.$proposition->pcname)->pluck('comissioncategoryview')->first() == 1)
                                        <div class="col-sm-6 col-xl-3" data-toggle="modal" data-target=".comissionmodel@php echo str_replace(' ', '', $proposition->planpropositionname.$proposition->pcname) @endphp" data-backdrop="static" data-keyboard="false" style="cursor: pointer;">
                                            <div class="card" style="background-color: #30419b;">
                                                <div class="card-heading p-4">
                                                    <div style="color: #FFF;">
                                                        <h5>
                                                            @if($proposition->pcname == 'New')
                                                            {{$proposition->planpropositionname}}
                                                            @else
                                                            {{$proposition->pcname}}
                                                            @endif
                                                        </h5>
                                                    </div>
                                                    @if(App\mastercomission::where('comossioncategory', $proposition->planpropositionname.$proposition->pcname)->pluck('comissioncounton')->first() == 1)
                                                        <h5 class="font-16" style="color: #FFF;">
                                                            Target: {{$comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target')}}
                                                        </h5>
                                                        <h5 class="font-16" style="color: #FFF;">
                                                            Achieve: {{$comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count() - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count()}}
                                                        </h5>
                                                        @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                        <h5 class="font-16" style="color: #FFF;">
                                                            Bonus: $<span class="sum">{{$comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('Comission') - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('Comission')}}</span>
                                                        </h5>
                                                        @endif
                                                        <h5 class="font-16" style="color: #FFF;">
                                                            @if($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->where('plandiscount', '!=', '')->sum('planexgstamount') != 0)
                                                            MAF: {{round($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->where('plandiscount', '!=', '')->sum('planexgstamount') / $comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->where('plandiscount', '!=', '')->count(), 2)}}
                                                            @endif
                                                        </h5>
                                                        @if($comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target') !=0)
                                                        <div class="progress mt-4" style="height: 20px;">
                                                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                                            role="progressbar" 
                                                            style="width: {{($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count() - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count()) * 100 / $comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target')}}%; color: #000; text-align: center; font-weight: 600;" 
                                                            aria-valuenow="{{($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count() - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count()) * 100 / $comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target')}}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                                {{round(($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count() - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count()) * 100 / $comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target'), 2)}}%
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @else
                                                        <h5 class="font-16" style="color: #FFF;">
                                                            Target: ${{$comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target')}}
                                                        </h5>
                                                        <h5 class="font-16" style="color: #FFF;">
                                                            Achieve: {{$comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('subTotal') - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('subTotal')}}
                                                        </h5>
                                                        @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                        <h5 class="font-16" style="color: #FFF;">
                                                            Bonus: $<span class="sum">{{$comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('Comission') - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('Comission')}}</span>
                                                        </h5>
                                                        @endif
                                                        @if($comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target') !=0)
                                                        <div class="progress mt-4" style="height: 20px;">
                                                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                                            role="progressbar" 
                                                            style="width: {{($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('subTotal') - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('subTotal')) * 100 / $comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target')}}%; color: #000; text-align: center; font-weight: 600;" 
                                                            aria-valuenow="{{($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('subTotal') - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('subTotal')) * 100 / $comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target')}}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                                {{round(($comissiondata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('subTotal') - $comissiondata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->sum('subTotal')) * 100 / $comissiondata['usertarget']->where('targetcategory', $proposition->planpropositionname.$proposition->pcname)->sum('target'), 2)}}%
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        @foreach($comissiondata['productcategory'] as $key => $productcategory)
                                        <!----Product Comission Model---->
                                        <div class="modal fade productcomissionmodel{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{$productcategory->categoryname}} </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="datatable1" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Invoice ID</th>
                                                                <th>Barcode</th>
                                                                <th>Product</th>
                                                                <th>Quantity</th>
                                                                @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                                <th>Bonus (Inc. GST)</th>
                                                                @endif
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID) as $productcomission)     
                                                            <tr>
                                                                <td>@php echo date('d-m-Y', strtotime($productcomission->orderDate)) @endphp</td>
                                                                <td>{{$productcomission->orderID}}</td>
                                                                <td>{{$productcomission->barcode}}</td>
                                                                <td>{{$productcomission->productname}}</td>
                                                                <td>{{$productcomission->quantity}}</td>
                                                                @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                                <td>${{$productcomission->Comission}}</td>
                                                                @endif
                                                            </tr>
                                                            @endforeach
                                                            @foreach($comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID) as $refundproductcomission)     
                                                            <tr style="background-color: #f1525247;">
                                                                <td>@php echo date('d-m-Y', strtotime($refundproductcomission->refundDate)) @endphp</td>
                                                                <td>{{$refundproductcomission->refundInvoiceID}}</td>
                                                                <td>{{$refundproductcomission->barcode}}</td>
                                                                <td>{{$refundproductcomission->productname}}</td>
                                                                <td>-{{$refundproductcomission->quantity}}</td>
                                                                @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                                <td>-${{$refundproductcomission->Comission}}</td>
                                                                @endif
                                                            </tr>
                                                            @endforeach
                                                            @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                            <tr>
                                                                <td colspan="5" align="right">Total Bonus</td>
                                                                <td>${{$comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->sum('Comission') - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->sum('Comission')}}</td>
                                                            </tr>
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                        <!----Product Comission Model---->
                                        @if(App\mastercomission::where('comossioncategory', $productcategory->categoryname)->pluck('comissioncategoryview')->first() == 1)
                                        <div class="col-sm-6 col-xl-3" data-toggle="modal" data-target=".productcomissionmodel{{$key}}" data-backdrop="static" data-keyboard="false" style="cursor: pointer;">
                                            <div class="card" style="background-color: #3c9b30;">
                                                @if(App\mastercomission::where('comossioncategory', $productcategory->categoryname)->pluck('comissioncounton')->first() == 1)
                                                <div class="card-heading p-4">
                                                    <div style="color: #FFF;">
                                                        <h5>{{$productcategory->categoryname}}</h5>
                                                    </div>
                                                    <h5 class="font-16" style="color: #FFF;">
                                                        Target: {{$comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target')}}
                                                    </h5>
                                                    <h5 class="font-16" style="color: #FFF;">
                                                        Achieve: {{$comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->count() - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->count()}}
                                                    </h5>
                                                    @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                    <h5 class="font-16" style="color: #FFF;">
                                                        Bonus: $<span class="sum">{{$comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->sum('Comission') - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->sum('Comission')}}</span>
                                                    </h5>
                                                    @endif
                                                    @if($comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target') != 0)
                                                    <div class="progress mt-4" style="height: 20px;">
                                                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                                        role="progressbar" 
                                                        style="width: {{($comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->count() - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->count()) * 100 / $comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target')}}%; color: #000; text-align: center; font-weight: 600;" 
                                                        aria-valuenow="{{($comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->count() - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->count()) * 100 / $comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target')}}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                            {{round(($comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->count() - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->count()) * 100 / $comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target'), 2)}}%
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                @else
                                                <div class="card-heading p-4">
                                                    <div style="color: #FFF;">
                                                        <h5>{{$productcategory->categoryname}}</h5>
                                                    </div>
                                                    <h5 class="font-16" style="color: #FFF;">
                                                        Target: ${{$comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target')}}
                                                    </h5>
                                                    <h5 class="font-16" style="color: #FFF;">
                                                        Achieve: ${{$comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal') - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal')}}
                                                    </h5>
                                                    @if(session('loggindata')['loggeduserpermission']->viewtrackerbonus=='Y')
                                                    <h5 class="font-16" style="color: #FFF;">
                                                        Bonus: $<span class="sum">{{$comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->sum('Comission') - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->sum('Comission')}}</span>
                                                    </h5>
                                                    @endif
                                                    @if($comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target') != 0)
                                                    <div class="progress mt-4" style="height: 20px;">
                                                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                                        role="progressbar" 
                                                        style="width: {{($comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal') - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal')) * 100 / $comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target')}}%; color: #000; text-align: center; font-weight: 600;" 
                                                        aria-valuenow="{{($comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal') - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal')) * 100 / $comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target')}}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                            {{round(($comissiondata['productcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal') - $comissiondata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal')) * 100 / $comissiondata['usertarget']->where('targetcategory', $productcategory->categoryname)->sum('target'), 2)}}%
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->

            @include('includes.footer')
            
        </div>
       
@endsection
