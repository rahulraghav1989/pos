@extends('main')

@section('content')
    <script>
    	function isNumber(evt) {
		    evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;
		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		}
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
                                <h4 class="page-title">Plan Edit</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                    <li class="breadcrumb-item active">Plan Edit</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
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
                                <div class="card-body">
                                    <form class="" action="{{route('planedit')}}" method="post" novalidate="">
                                        @csrf
                                        <input type="hidden" name="planid" value="{{$plandata['allplans']->planID}}">
                                        <div class="form-group">
                                            <label>Plan Code *</label>
                                            <input type="text" name="plancode" class="form-control" placeholder="Type Here" required="" value="{{$plandata['allplans']->plancode}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Plan Name *</label>
                                            <input type="text" name="planname" class="form-control" required="" placeholder="Type Here" value="{{$plandata['allplans']->planname}}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" name="description" class="form-control" placeholder="Type Here" value="{{$plandata['allplans']->description}}">
                                        </div>
                                        <div class="">
                                            <label>Plan Price *</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Including GST</label>
                                                    <input type="text" name="ppingst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)" value="{{$plandata['allplans']->ppingst}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Plan Type</label>
                                                    <select name="plantype" class="form-control">
                                                        <option value="">SELECT</option>
                                                        @foreach($plandata['plantype'] as $plantype)
                                                        <option value="{{$plantype->plantypeID}}" @if($plandata['allplans']->plantypeID == $plantype->plantypeID) SELECTED='SELECTED' @endif>{{$plantype->plantypename}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Plan Proposition Type</label>
                                                    <select name="planpropositiontype" class="form-control">
                                                        <option value="">SELECT</option>
                                                        @foreach($plandata['planpropositiontype'] as $planpropositiontype)
                                                        <option value="{{$planpropositiontype->planpropositionID}}" @if($plandata['allplans']->planpropositionID == $planpropositiontype->planpropositionID) SELECTED='SELECTED' @endif>{{$planpropositiontype->planpropositionname}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Plan Term</label>
                                            <select name="planterm" class="form-control">
                                                <option value="">SELECT</option>
                                                @foreach($plandata['planterm'] as $planterm)
                                                <option value="{{$planterm->plantermID}}" @if($plandata['allplans']->planterm == $planterm->plantermID) SELECTED='SELECTED' @endif>{{$planterm->plantermname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
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
                                                        var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input--><fieldset><legend>Column '+ next +'</legend><div class="row"><div class="col-md-6"><label>Plan Handset Term</label><select name="planhandsetterm[]" class="form-control"><option value="">SELECT TERM</option>@foreach($plandata['planhandsetterm'] as $planhandsetterm)<option value="{{$planhandsetterm->planhandsettermID}}">{{$planhandsetterm->planhandsettermname}}</option>@endforeach</select></div><div class="col-md-6"><label>Category</label><select name="plancategory[]" class="form-control"><option value="">SELECT CATEGORY</option>@foreach($plandata['plancategory'] as $plancategory)<option value="{{$plancategory->pcID}}">{{$plancategory->pcname}}</option>@endforeach</select></div><div class="col-md-4"><label>Voda Comission</label><input type="text" name="plancomission[]" class="form-control" value="0.00"></div><div class="col-md-4"><label>Staff Bonus Type</label><select name="planstaffbonustype[]" class="form-control"><option value="">SELECT TYPE</option><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div><div class="col-md-4"><label>Staff Bonus</label><input type="text" name="planstaffbonusvalue[]" class="form-control" value="0.00"></div><div class="col-md-12 text-right"><a id="remove' + (next) + '" class="btn btn-danger remove-me" style="color:#FFF; margin-top: 10px;">Remove</a></div><div id="field"></div></fieldset></div>';
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
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label>Plan Category & Comission</label>
                                                </div>
                                                <div id="field" class="col-md-12">
                                                    <div id="field0">
                                                        <fieldset>
                                                            <legend>Column 0</legend>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Plan Handset Term</label>
                                                                    <select name="planhandsetterm" class="form-control" style="width: 100%;">
                                                                        <option value="">SELECT TERM</option>
                                                                        @foreach($plandata['planhandsetterm'] as $planhandsetterm)
                                                                        <option value="{{$planhandsetterm->planhandsettermID}}" @if($plandata['allplans']->planhandsetterm == $planhandsetterm->planhandsettermID) SELECTED='SELECTED' @endif>{{$planhandsetterm->planhandsettermname}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Category</label>
                                                                    <select name="plancategory" class="form-control" style="width: 100%;">
                                                                        <option value="">SELECT CATEGORY</option>
                                                                        @foreach($plandata['plancategory'] as $plancategory)
                                                                        <option value="{{$plancategory->pcID}}" @if($plandata['allplans']->plancategoryID == $plancategory->pcID) SELECTED='SELECTED' @endif>{{$plancategory->pcname}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Voda Comission</label>
                                                                    <input type="text" name="plancomission" class="form-control" value="{{$plandata['allplans']->plancomission}}" style="width: 100%;">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Staff Bonus Type</label>
                                                                    <select name="planstaffbonustype"  class="form-control" style="width: 100%;">
                                                                        <option value="">SELECT TYPE</option>
                                                                        <option value="percentage" @if($plandata['allplans']->planbonustype == 'percentage') SELECTED='SELECTED' @endif>% Of Comission</option>
                                                                        <option value="fixed" @if($plandata['allplans']->planbonustype == 'fixed') SELECTED='SELECTED' @endif>Fixed</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Staff Bonus</label>
                                                                    <input type="text" name="planstaffbonusvalue"  class="form-control" value="{{$plandata['allplans']->planbonus}}" style="width: 100%;">
                                                                </div>
                                                                <!-- <div class="col-md-12 text-right">
                                                                    <a id="add-more" name="add-more" class="btn btn-primary waves-effect waves-light" style="color: #FFF; margin-top: 10px;">Add New Category</a>
                                                                    
                                                                </div> -->
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label><input type="checkbox" name="planadditionalcom" value="1" @if($plandata['allplans']->planaddtionalcomission == 1) checked @endif> Allow Additional Comission</label>
                                        </div>
                                        <div class="form-group">
                                            <label>Plan Group</label><br>
                                            @php
                                            $stockgrouparray = explode(',', $plandata['allplans']->planstockgroup);
                                            @endphp
                                            @foreach($plandata['planstockgroup'] as $planstockgroup)
                                                <label><input type="checkbox" name="planstockgroup[]" value="{{$planstockgroup->stockgroupID}}" @if(in_array($planstockgroup->stockgroupID, $stockgrouparray)) checked @endif> {{$planstockgroup->stockgroupname}}</label>
                                            @endforeach
                                        </div>
                                        <div class="form-group text-right">
                                            <div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                    Submit
                                                </button>
                                                <!-- <button data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                    Cancel
                                                </button> -->
                                            </div>
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

        </div>
    </div>
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
@endsection
        