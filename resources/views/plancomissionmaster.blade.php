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
                                <h4 class="page-title">Plan Comission Master</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Masters</a></li>
                                    <li class="breadcrumb-item active">Plan Comission Master</li>
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
                                    <form action="{{route('addplancomtax')}}" method="post">
                                    	@csrf
                                    	<div class="row">
                                    		<div class="col-md-8">
                                    			<div class="form-group">
                                    				<label>GST/TAX</label>
                                    				<input type="text" name="gst" class="form-control" value="{{$comissiontax->plancomtaxValue}}">
                                    			</div>
                                    		</div>
                                    		<div class="col-md-4">
                                    			<div class="form-group" style="margin-top: 25px">
                                    				<input type="submit" name="gstsubmit" class="btn btn-primary" value="Save">
                                    			</div>
                                    		</div>
                                    	</div>
                                    </form>
    
                                </div>
                                <hr>
                                <h6>Comission Range</h6>
                                <hr>
                                <div class="card-body">
                                	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
                                    <script>
                                    $(document).ready(function () {
                                            //@Rahul Raghav action dynamic childs
                                            var next = 0;
                                            $("#add-more").click(function(e){
                                                e.preventDefault();
                                                var addto = "#field" + next;
                                                var addRemove = "#field" + (next);
                                                next = next + 1;
                                                var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input--><fieldset><legend>Column '+ next +'</legend><div class="row"><div class="col-md-2"><label>From Range</label><input type="text" name="fromrange[]" class="form-control" placeholder=""></div><div class="col-md-2"><label>To Range</label><input type="text" name="torange[]" class="form-control" placeholder=""></div><div class="col-md-2"><label>Multiplier</label><input type="text" name="multipler[]" class="form-control" placeholder=""></div><div class="col-md-2"><label>Handset Term</label><select class="form-control" name="term[]"><option value="">SELECT</option> @foreach($planterm as $term) <option value="{{$term->planhandsettermID}}">{{$term->planhandsettermname}}</option> @endforeach</select></div><div class="col-md-2"><label>Plan Category</label><select class="form-control" name="category[]"><option value="">SELECT</option>@foreach($plancategory as $category)<option value="{{$category->pcID}}">{{$category->pcname}}</option>@endforeach</select></div><div class="col-md-12 text-right" style="margin-top:10px;"><input type="button" id="remove'+ next +'" class="btn btn-danger" value="Remove"></div></div></fieldset></div>';
                                                var newInput = $(newIn);
                                                //var removeBtn = '';
                                                //var removeButton = $(removeBtn);
                                                $(addto).after(newInput);
                                                $(addRemove).after(newInput);
                                                $("#field" + next).attr('data-source',$(addto).attr('data-source'));
                                                $("#count").val(next);  
                                                
                                                    $('.remove-me').click(function(e){
                                                        e.preventDefault();
                                                        var fieldNum = this.id.charAt(this.id.length-1);
                                                        var fieldID = "#field" + fieldNum;
                                                        $(this).remove();
                                                        $(fieldID).remove();
                                                    });
                                            });

                                        });
                                    </script>
                                    <div class="col-md-12">
                                        <a id="add-more" name="add-more" class="btn btn-primary waves-effect waves-light" style="color: #FFF; margin-top: 10px;">Add New Category</a>
                                    </div>
                                	<form action="{{route('plancomissionadd')}}" method="post">
                                		@csrf
                                		<div id="field" class="col-md-12">
                                			@foreach($comissionrange as $key => $range)
                                			<div id="field">
                                                <fieldset>
                                                    <legend>Column {{$key+1}}</legend>
			                                		<div class="row">
			                                			<input type="hidden" name="updaterangeid[]" value="{{$range->plancomissionID}}">
			                                			<div class="col-md-2">
			                                				<label>From Range</label>
			                                				<input type="text" name="updatefromrange[]" class="form-control" value="{{$range->plancomissionFrom}}" placeholder="">
			                                			</div>
			                                			<div class="col-md-2">
			                                				<label>To Range</label>
			                                				<input type="text" name="updatetorange[]" class="form-control" value="{{$range->plancomissionTo}}" placeholder="">
			                                			</div>
			                                			<div class="col-md-2">
			                                				<label>Multiplier</label>
			                                				<input type="text" name="updatemultipler[]" class="form-control" value="{{$range->plancomissionMultiplier}}" placeholder="">
			                                			</div>
                                                        <div class="col-md-2">
                                                            <label>Handset Term</label>
                                                            <select class="form-control" name="updateterm[]">
                                                                <option value="">SELECT</option>
                                                                @foreach($planterm as $term)
                                                                <option value="{{$term->planhandsettermID}}" @if($range->plancomissionTerm == $term->planhandsettermID) SELECTED='SELECTED' @endif>{{$term->planhandsettermname}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
			                                			<div class="col-md-2">
			                                				<label>Plan Category</label>
			                                				<select class="form-control" name="updatecategory[]">
			                                					<option value="">SELECT</option>
			                                					@foreach($plancategory as $category)
			                                					<option value="{{$category->pcID}}" @if($range->plancomissionCategory == $category->pcID) SELECTED='SELECTED' @endif>{{$category->pcname}}</option>
			                                					@endforeach
			                                				</select>
			                                			</div>
			                                			<div class="col-md-12 text-right" style="margin-top: 10px;">
			                                				<a class="btn btn-danger waves-effect waves-light deletebtn" data-pointid="{{$range->plancomissionID}}" style="color: #FFF;">Remove</a>
			                                			</div>
			                                		</div>
			                                	</fieldset>
			                                </div>
			                                @endforeach
                                            <div id="field0">
                                                <fieldset>
                                                    <legend>Column 0</legend>
			                                		<div class="row">
			                                			<div class="col-md-2">
			                                				<label>From Range</label>
			                                				<input type="text" name="fromrange[]" class="form-control" value="" placeholder="">
			                                			</div>
			                                			<div class="col-md-2">
			                                				<label>To Range</label>
			                                				<input type="text" name="torange[]" class="form-control" value="" placeholder="">
			                                			</div>
			                                			<div class="col-md-2">
			                                				<label>Multiplier</label>
			                                				<input type="text" name="multipler[]" class="form-control" value="" placeholder="">
			                                			</div>
                                                        <div class="col-md-2">
                                                            <label>Handset Term</label>
                                                            <select class="form-control" name="term[]">
                                                                <option value="">SELECT</option>
                                                                @foreach($planterm as $term)
                                                                <option value="{{$term->planhandsettermID}}">{{$term->planhandsettermname}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
			                                			<div class="col-md-2">
			                                				<label>Plan Category</label>
			                                				<select class="form-control" name="category[]">
			                                					<option value="">SELECT</option>
			                                					@foreach($plancategory as $category)
			                                					<option value="{{$category->pcID}}">{{$category->pcname}}</option>
			                                					@endforeach
			                                				</select>
			                                			</div>
			                                		</div>
			                                	</fieldset>
			                                </div>
			                            </div>
			                            <div class="col-md-12 text-right" style="margin-top: 20px;">
			                            	<input type="submit" name="" class="btn btn-success" value="Save Range">
			                            </div>
                                	</form>
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
        <script>
        $(document).ready(function(){

         $(".deletebtn").click(function(ev){
            let pointid = $(this).attr("data-pointid");
            $.ajax({
               type: 'POST',
               url: "{{route('ajaxdeleteplancomission')}}",
               dataType: 'json',
               headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
               data: {id:pointid,"_token": "{{ csrf_token() }}"},

               success: function (data) {
                      location.reload(true);            
               },
               error: function (data) {
                    location.reload(true);
               }
            });
        });
         
        });
        </script>
        </div>
    </div>
@endsection
