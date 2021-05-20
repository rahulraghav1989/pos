@extends('main')

@section('content')
    <script>
    	function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
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
                                <h4 class="page-title">Plans</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                                    <li class="breadcrumb-item active">Plans</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <div class="row"> 
                                        <!---Add Model-->
                                        <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0">Add a new plan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="" action="{{route('planadd')}}" method="post" novalidate="">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label>Plan Code *</label>
                                                                <input type="text" name="plancode" class="form-control" placeholder="Type Here" required="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Plan Name *</label>
                                                                <input type="text" name="planname" class="form-control" required="" placeholder="Type Here">
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <input type="text" name="description" class="form-control" placeholder="Type Here">
                                                            </div>
                                                            <div class="">
                                                                <label>Plan Price *</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label>Including GST</label>
                                                                        <input type="text" name="ppingst" class="form-control" required="" placeholder="Type Here" onkeypress="return isNumber(event)">
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
                                                                            <option value="{{$plantype->plantypeID}}">{{$plantype->plantypename}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label>Plan Proposition Type</label>
                                                                        <select name="planpropositiontype" class="form-control">
                                                                            <option value="">SELECT</option>
                                                                            @foreach($plandata['planpropositiontype'] as $planpropositiontype)
                                                                            <option value="{{$planpropositiontype->planpropositionID}}">{{$planpropositiontype->planpropositionname}}</option>
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
                                                                    <option value="{{$planterm->plantermID}}">{{$planterm->plantermname}}</option>
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
                                                                            var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input--><fieldset><legend>Column '+ next +'</legend><div class="row"><div class="col-md-6"><label>Plan Handset Term</label><select name="planhandsetterm[]" class="form-control"><option value="">SELECT TERM</option>@foreach($plandata['planhandsetterm'] as $planhandsetterm)<option value="{{$planhandsetterm->planhandsettermID}}">{{$planhandsetterm->planhandsettermname}}</option>@endforeach</select></div><div class="col-md-6"><label>Category</label><select name="plancategory[]" class="form-control"><option value="">SELECT CATEGORY</option>@foreach($plandata['plancategory'] as $plancategory)<option value="{{$plancategory->pcID}}">{{$plancategory->pcname}}</option>@endforeach</select></div><div class="col-md-4"><label>Voda Comission</label><input type="text" name="plancomission[]" class="form-control" value="0.00"></div><div class="col-md-4"><label>Staff Bonus Type</label><select name="planstaffbonustype[]" class="form-control"><option value="">SELECT TYPE</option><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div><div class="col-md-4"><label>Staff Bonus</label><input type="text" name="planstaffbonusvalue[]" class="form-control" value="0.00"></div><div class="col-md-12"><a id="remove' + (next) + '" class="btn btn-danger remove-me" style="color:#FFF; margin-top: 10px;">Remove</a></div><div id="field"></div></fieldset></div>';
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
                                                                                        <select name="planhandsetterm[]" class="form-control" style="width: 100%;">
                                                                                            <option value="">SELECT TERM</option>
                                                                                            @foreach($plandata['planhandsetterm'] as $planhandsetterm)
                                                                                            <option value="{{$planhandsetterm->planhandsettermID}}">{{$planhandsetterm->planhandsettermname}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label>Category</label>
                                                                                        <select name="plancategory[]" class="form-control" style="width: 100%;">
                                                                                            <option value="">SELECT CATEGORY</option>
                                                                                            @foreach($plandata['plancategory'] as $plancategory)
                                                                                            <option value="{{$plancategory->pcID}}">{{$plancategory->pcname}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Voda Comission</label>
                                                                                        <input type="text" name="plancomission[]"  class="form-control" value="0.00" style="width: 100%;">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Staff Bonus Type</label>
                                                                                        <select name="planstaffbonustype[]"  class="form-control" style="width: 100%;">
                                                                                            <option value="">SELECT TYPE</option>
                                                                                            <option value="percentage">% Of Comission</option>
                                                                                            <option value="fixed">Fixed</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Staff Bonus</label>
                                                                                        <input type="text" name="planstaffbonusvalue[]"  class="form-control" value="0.00" style="width: 100%;">
                                                                                    </div>
                                                                                    <div class="col-md-12">
                                                                                        <a id="add-more" name="add-more" class="btn btn-primary waves-effect waves-light" style="color: #FFF; margin-top: 10px;">Add New Category</a>
                                                                                    </div>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label><input type="checkbox" name="planadditionalcom" value="1"> Allow Additional Comission</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Plan Group</label><br>
                                                                @foreach($plandata['planstockgroup'] as $planstockgroup)
                                                                <label><input type="checkbox" name="planstockgroup[]" value="{{$planstockgroup->stockgroupID}}"> {{$planstockgroup->stockgroupname}}</label>
                                                                @endforeach
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
                                        <!---Add Model-->
                                        <div class="col-md-12 text-right">
                                            @if(session('loggindata')['loggeduserpermission']->viewplansfilters=='Y')
                                            <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                Filters
                                            </a>
                                            @endif
                                            @if(session('loggindata')['loggeduserpermission']->addplans=='Y')
                                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center" data-backdrop="static" data-keyboard="false">Add Plan</a>
                                            @endif
                                        </div>
                                        @if(session('loggindata')['loggeduserpermission']->viewplansfilters=='Y')
                                        <div class="col-md-12">
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form action="{{route('planfilters')}}" method="post">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <select class="form-control" name="planprice">
                                                                    <option value="">SELECT PLAN PRICE</option>
                                                                    @foreach($plandata['planamount'] as $amount)
                                                                    <option value="{{$amount->ppingst}}" @if($amount->ppingst == $plandata['pprice']) SELECTED='SELECTED'  @endif>{{$amount->ppingst}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <select class="form-control" name="plantype">
                                                                    <option value="">SELECT PLAN TYPE</option>
                                                                    @foreach($plandata['plantype'] as $plantype)
                                                                    <option value="{{$plantype->plantypeID}}" @if($plantype->plantypeID == $plandata['ptype']) SELECTED='SELECTED'  @endif>{{$plantype->plantypename}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <select class="form-control" name="propositiontype">
                                                                    <option value="">SELECT PROPOSITION</option>
                                                                    @foreach($plandata['planpropositiontype'] as $planpropositiontype)
                                                                    <option value="{{$planpropositiontype->planpropositionID}}" @if($planpropositiontype->planpropositionID == $plandata['propositiontype']) SELECTED='SELECTED'  @endif>{{$planpropositiontype->planpropositionname}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <select class="form-control" name="planterm">
                                                                    <option value="">SELECT PLAN TERM</option>
                                                                    @foreach($plandata['planterm'] as $planterm)
                                                                    <option value="{{$planterm->plantermID}}" @if($planterm->plantermID == $plandata['pterm']) SELECTED='SELECTED'  @endif>{{$planterm->plantermname}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <select class="form-control" name="planhandsetterm">
                                                                    <option value="">SELECT PLAN HANDSET TERM</option>
                                                                    @foreach($plandata['planhandsetterm'] as $planhandsetterm)
                                                                    <option value="{{$planhandsetterm->planhandsettermID}}" @if($planhandsetterm->planhandsettermID == $plandata['phandsetterm']) SELECTED='SELECTED'  @endif>{{$planhandsetterm->planhandsettermname}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <select class="form-control" name="planstockgroup">
                                                                    <option value="">SELECT PLAN GROUP</option>
                                                                    @foreach($plandata['planstockgroup'] as $planstockgroup)
                                                                    <option value="{{$planstockgroup->stockgroupID}}" @if($planstockgroup->stockgroupID == $plandata['stockgroup']) SELECTED='SELECTED'  @endif>{{$planstockgroup->stockgroupname}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="row" style="margin-top: 10px;">
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit" class="btn btn-success">Apply</button>
                                                            </div>
                                                        </div>  
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                {{$error}}
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(session()->has('plansuccess'))
                                        <div class="card-body">                                 
                                            <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                                {{ session()->get('plansuccess') }}
                                            </div>
                                        </div>
                                    @endif
                                    @if(session()->has('planerror'))
                                    <div class="card-body">
                                        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                            {{ session()->get('planerror') }}
                                        </div>
                                    </div>
                                    @endif
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                        	<th>Plan Code</th>
                                            <th>Plan Name</th>
                                            <th>Plan Type</th>
                                            <th>Plan Term</th>
                                            <th>Added By/On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        	@foreach($plandata['allplans'] as $allplans)
                                        	<tr>
                                        		<td>{{$allplans->plancode}}</td>
                                        		<td>{{$allplans->planname}}<br>{{$allplans->planhandsettermname}}<br>{{$allplans->pcname}}<br>{{$allplans->ppingst}}</td>
                                        		<td>{{$allplans->plantypere['plantypename']}}</td>
                                        		<td>{{$allplans->plantermre['plantermname']}}</td>
                                        		<td>{{$allplans->name}}<br>{{$allplans->created_at}}</td>
                                        		<td>
                                                    @if(session('loggindata')['loggeduserpermission']->editplans=='Y')
                                        			<a href="changeplan/{{$allplans->planID}}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a>
                                        			@else
                                                    <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a>
                                                    @endif
                                                    @if($allplans->planstatus == 1)
                                                        @if(session('loggindata')['loggeduserpermission']->deleteplans=='Y')
                                                        <!--Active Model-->
                                                        <div class="modal fade bs-example-modal-center activestatusmodel{{$allplans->planID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0">{{$allplans->planname}} Status</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form class="" action="{{route('editplanstatus')}}" method="post" novalidate="">
                                                                            @csrf
                                                                            <div class="form-group">
                                                                                <h4>{{$allplans->planname}} is in <span class="badge badge-primary">Active Status</span></h4>
                                                                                <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
                                                                                <p>Click on yes to continue or cancle it.</p>
                                                                                <input type="hidden" name="planid" class="form-control" value="{{$allplans->planID}}">
                                                                                <input type="hidden" name="planstatus" class="form-control" value="0">
                                                                            </div>
                                                                            <div class="form-group text-right">
                                                                                <div>
                                                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                        Yes
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
                                                        <!--Active Model-->
                                                        <span data-toggle="modal" data-target=".activestatusmodel{{$allplans->planID}}"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a>
                                                        @else
                                                        <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a>
                                                        @endif
                                                    @else
                                                        @if(session('loggindata')['loggeduserpermission']->deleteplans=='Y')
                                                        <!--Inactive model-->
                                                        <div class="modal fade bs-example-modal-center inactivestatusmodel{{$allplans->planID}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0">{{$allplans->planname}} Status</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form class="" action="{{route('editplanstatus')}}" method="post" novalidate="">
                                                                            @csrf
                                                                            <div class="form-group">
                                                                                <h4>{{$allplans->planname}} is in <span class="badge badge-primary">Inactive Status</span></h4>
                                                                                <h4>Do you want to make it <span class="badge badge-primary">Active Status</span></h4>
                                                                                <p>Click on yes to continue or cancle it.</p>
                                                                                <input type="hidden" name="planid" class="form-control" value="{{$allplans->planID}}">
                                                                                <input type="hidden" name="planstatus" class="form-control" value="1">
                                                                            </div>
                                                                            <div class="form-group text-right">
                                                                                <div>
                                                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                        Yes
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
                                                        <!--Inactive model-->
                                                        <span data-toggle="modal" data-target=".inactivestatusmodel{{$allplans->planID}}"><a class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="In-Active"><i class="icon-music-pause"></i></a></span>
                                                        @else
                                                        <a class="btn btn-light waves-effect" data-toggle="tooltip" data-placement="top" title="In-Active"><i class="icon-music-pause"></i></a>
                                                        @endif
                                                    @endif
                                        		</td>
                                        	</tr>
                                        	@endforeach
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

        </div>
    </div>
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
@endsection
        