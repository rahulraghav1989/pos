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
                                <h4 class="page-title">Comission Master</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Masters</a></li>
                                    <li class="breadcrumb-item active">Comission Master</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                	@if(session()->has('success'))
                                        <div class="alert alert-success alert-dismissible" role="alert" style="margin-top: 10px;">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            {{ session()->get('success') }}
                                        </div>
                                    @endif
                                    <table id="datatable-buttons" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Show</th>
                                            <th>Count Comission</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        	<form action="{{route('addcomissionmaster')}}" method="post">
                                        	@csrf
                                        	@foreach($planproposition as $key => $proposition)
                                        	<tr>
                                        		<td>
                                        			<input type="hidden" name="categoryid[]" value="{{$key}}">
                                        			<input type="hidden" name="category_{{$key}}" value="{{$proposition->planpropositionname}}{{$proposition->pcname}}"> 
                                        			{{$proposition->planpropositionname}} {{$proposition->pcname}}
                                        		</td>
                                        		<td>
                                        			<select name="show_{{$key}}" class="form-control">
                                        				<option value="0" @if(App\mastercomission::where('comossioncategory', $proposition->planpropositionname.$proposition->pcname)->pluck('comissioncategoryview')->first() == 0) SELECTED='SELECTED' @endif>NO</option>
                                        				<option value="1" @if(App\mastercomission::where('comossioncategory', $proposition->planpropositionname.$proposition->pcname)->pluck('comissioncategoryview')->first() == 1) SELECTED='SELECTED' @endif>YES</option>
                                        			</select>
                                        		</td>
                                        		<td>
                                        			<select name="counton_{{$key}}" class="form-control">
                                        				<option value="1" @if(App\mastercomission::where('comossioncategory', $proposition->planpropositionname.$proposition->pcname)->pluck('comissioncounton')->first() == 1) SELECTED='SELECTED' @endif>On Sold Total Count</option>
                                        				<option value="2" @if(App\mastercomission::where('comossioncategory', $proposition->planpropositionname.$proposition->pcname)->pluck('comissioncounton')->first() == 2) SELECTED='SELECTED' @endif>On Sold Total Amount</option>
                                        			</select>
                                        		</td>
                                        	</tr>
                                        	@endforeach
                                        	@foreach($productcategory as $key => $productcategory)
                                        	<tr>
                                        		<td>
                                        			<input type="hidden" name="categoryid[]" value="{{$productcategory->categoryID}}{{$key}}">
                                        			<input type="hidden" name="category_{{$productcategory->categoryID}}{{$key}}" value="{{$productcategory->categoryname}}">
                                        			{{$productcategory->categoryname}}
                                        		</td>
                                        		<td>
                                        			<select name="show_{{$productcategory->categoryID}}{{$key}}" class="form-control">
                                        				<option value="0" @if(App\mastercomission::where('comossioncategory', $productcategory->categoryname)->pluck('comissioncategoryview')->first() == 0) SELECTED='SELECTED' @endif>NO</option>
                                        				<option value="1" @if(App\mastercomission::where('comossioncategory', $productcategory->categoryname)->pluck('comissioncategoryview')->first() == 1) SELECTED='SELECTED' @endif>YES</option>
                                        			</select>
                                        		</td>
                                        		<td>
                                        			<select name="counton_{{$productcategory->categoryID}}{{$key}}" class="form-control">
                                        				<option value="1" @if(App\mastercomission::where('comossioncategory', $productcategory->categoryname)->pluck('comissioncounton')->first() == 1) SELECTED='SELECTED' @endif>On Sold Total Count</option>
                                        				<option value="2" @if(App\mastercomission::where('comossioncategory', $productcategory->categoryname)->pluck('comissioncounton')->first() == 2) SELECTED='SELECTED' @endif>On Sold Total Amount</option>
                                        			</select>
                                        		</td>
                                        	</tr>
                                        	@endforeach
                                        	<tr>
                                        		<td colspan="3" align="right">
                                        			<button type="submit" class="btn btn-primary">Save Comission Settings</button>
                                        		</td>
                                        	</tr>
                                        	</form>
                                        </tbody>
                                    </table>
    
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
        
        </div>
    </div>
@endsection
        