@extends('main')

@section('content')
    @include('includes.topbar')

    @include('includes.sidebar')
    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">Users</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>
                                    <li class="breadcrumb-item active">Users</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    <!---Add Model-->
                    
                    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Add staff</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <span style="color: red;">Fields With (*) sign are required</span>
                                    <form action="{{route('adduser')}}" method="post">
                                        @csrf
                                        <fieldset>
                                            <legend>Personal Info</legend>
                                            <div class="form-group">
                                                <label>Name (*)</label>
                                                <input type="text" name="name" class="form-control" required placeholder="Type Here">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Mobile (*)</label>
                                                        <input type="number" name="mobile" class="form-control" required="" placeholder="Type Here">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Emergency Mobile (*)</label>
                                                        <input type="number" name="emergencymobile" class="form-control" required="" placeholder="Type Here">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Email (*)</label>
                                                <input type="email" name="email" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="form-group">
                                                <label>Date Of Birth</label>
                                                <input type="date" name="dob" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="address" class="form-control" placeholder="Type Here">
                                            </div>
                                        </fieldset>

                                        <fieldset>
                                            <legend>System Info </legend>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label>Siebel Id</label>
                                                    <input type="text" name="siebelid" class="form-control" placeholder="Type Here">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Salesforce</label>
                                                    <input type="text" name="salesforece" class="form-control" placeholder="Type Here">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>VF Learning</label>
                                                <input type="text" name="vflearning" class="form-control" placeholder="Type Here">
                                            </div>
                                        </fieldset>

                                        <fieldset>
                                            <script type="text/javascript">
                                            function CheckColors(val){
                                            /************************************************/
                                             var element=document.getElementById('TFN');
                                             if(val=='color'||val=='TFN')
                                               element.style.display='block';
                                             else  
                                               element.style.display='none';
                                            /************************************************/
                                                
                                            var element=document.getElementById('ABN');
                                             if(val=='color'||val=='ABN')
                                               element.style.display='block';
                                             else  
                                               element.style.display='none';
                                                   
                                            }
                                            
                                            </script>
                                            <legend>Payment Info </legend>
                                            <div class="form-group">
                                                <label>Pay Method (*)</label>
                                                <select name="paymethod" class="form-control" required="" onchange='CheckColors(this.value);'>
                                                    <option value="">SELECT</option>
                                                    <option value="TFN">TFN</option>
                                                    <option value="ABN">ABN</option>
                                                </select>
                                            </div>
                                            <div id="TFN" style="display:none;">
                                                <div class="form-group">
                                                    <label>TFN (*)</label>
                                                    <input type="text" name="tfn" class="form-control" placeholder="Type Here">
                                                </div>
                                            </div>
                                            <div id="ABN" style="display:none;">
                                                <div class="form-group">
                                                    <label>ABN (*)</label>
                                                    <input type="text" name="abn" class="form-control" placeholder="Type Here">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Normal Rate (*)</label>
                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                    <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </span>
                                                    <input id="demo2" type="text" value="0" name="normalrate" required="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Saturday Rate (*)</label>
                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                    <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </span>
                                                    <input id="demo2" type="text" value="0" name="saturdayrate" required="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Sunday Rate (*)</label>
                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                    <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </span>
                                                    <input id="demo2" type="text" value="0" name="sundayrate" required="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Feul (*)</label>
                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                    <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </span>
                                                    <input id="demo2" type="text" value="0" name="feul" required="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label>Bank Details</label>
                                                    <input type="text" name="bankdetail1" class="form-control" placeholder="Type Here">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>&nbsp;</label>
                                                    <input type="text" name="bankdetail2" class="form-control" placeholder="Type Here">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Employment (*)</label>
                                                <select name="employment" class="form-control" required="">
                                                    <option value="">SELECT</option>
                                                    <option value="Casual">Casual</option>
                                                    <option value="Part Time">Part Time</option>
                                                    <option value="Full Time">Full Time</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Tax Scale (*)</label>
                                                <select name="taxScale" class="form-control" required="">
                                                    <option value="">-- Select --</option>
                                                    <option value="1">Where the tax-free threshold is not claimed in Tax file number declaration</option>
                                                     <option value="2">Where the employee claimed the tax-free threshold in Tax file number declaration</option> 
                                                    <option value="3">Foreign residents </option> 
                                                    <option value="4">Where a tax file number (TFN)
                                                    was not provided by employee</option> 
                                                    <option value="5">FULL exemption from
                                                    Medicare levy in Medicare
                                                    levy variation declaration</option> 
                                                    <option value="6">HALF exemption from
                                                    Medicare levy in Medicare
                                                    levy variation declaration</option>                     
                                                </select>
                                            </div>
                                        </fieldset>

                                        <fieldset>
                                            <legend>Login Info </legend>
                                            <div class="form-group">
                                                <label>UserName (*) (This must be unique)</label>
                                                <input type="text" id="username" name="username" class="form-control" required="" placeholder="Type Here">
                                                <span id="error_email"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Password (*)</label>
                                                <input type="password" name="password" class="form-control" required="" placeholder="Type Here">
                                            </div>
                                            <div class="form-group">
                                                <label>User Type / Group (*)</label>
                                                <select name="usertype" class="form-control" required="">
                                                    <option value="">SELECT</option>
                                                    @foreach($userdata['allusergroup'] as $usergroup)
                                                    <option value="{{$usergroup->usertypeID}}">{{$usergroup->usertypeName}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Working Role (*)</label>
                                                <input type="text" name="workingrole" class="form-control" required="" placeholder="Type Here eg:(Sales Executive, Sales Head, ETC)">
                                            </div>
                                            <div class="form-group">
                                                <label>Store</label>
                                                <br>
                                                @foreach($userdata['allstore'] as $allstore)
                                                <input type="checkbox" name="store[]" value="{{$allstore->store_id}}">{{$allstore->store_name}} <br>
                                                @endforeach
                                            </div>
                                        </fieldset>
                                        <div class="form-group text-right">
                                            <div>
                                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light">
                                                    Create User
                                                </button>
                                                <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                                @if(session('loggindata')['loggeduserpermission']->adduser=='Y')
                                <div class="card-body">
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Add User</button>
                                    </div>
                                </div>
                                @endif
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
                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Group</th>
                                            <th>Role</th>
                                            <th>Store</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($userdata['users']->groupBy('id') as $usersdetail)
                                        <tr>
                                            <td>{{$usersdetail[0]->name}}</td>
                                            <td>{{$usersdetail[0]->email}}</td>
                                            <td>{{$usersdetail[0]->usertypeName}}</td>
                                            <td>{{$usersdetail[0]->workingrole}}</td>
                                            <td>
                                                @foreach($usersdetail as $stores)
                                                {{$stores->store_name}}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if(session('loggindata')['loggeduserpermission']->edituser=='Y')
                                                <!--EDIT MODEL-->
                                                <div class="modal fade bs-example-modal-center editmodel{{$usersdetail[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title mt-0">Edit User</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <span style="color: red;">Fields With (*) sign are required</span>
                                                                <form action="{{route('edituser')}}" method="post">
                                                                    @csrf
                                                                    <fieldset>
                                                                        <legend>Personal Info</legend>
                                                                        <div class="form-group">
                                                                            <label>Name (*)</label>
                                                                            <input type="text" name="name" class="form-control" required placeholder="Type Here" value="{{$usersdetail[0]->name}}">
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label>Mobile (*)</label>
                                                                                    <input type="number" name="mobile" class="form-control" required="" placeholder="Type Here" value="{{$usersdetail[0]->mobile}}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label>Emergency Mobile (*)</label>
                                                                                    <input type="number" name="emergencymobile" class="form-control" required="" placeholder="Type Here" value="{{$usersdetail[0]->emergencymobile}}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Email (*)</label>
                                                                            <input type="email" name="email" class="form-control" required="" placeholder="Type Here" value="{{$usersdetail[0]->email}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Date Of Birth</label>
                                                                            <input type="date" name="dob" class="form-control" value="@php echo date('d-m-Y', strtotime($usersdetail[0]->dateofbirth)) @endphp">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Address</label>
                                                                            <input type="text" name="address" class="form-control" placeholder="Type Here" value="{{$usersdetail[0]->address}}">
                                                                        </div>
                                                                    </fieldset>

                                                                    <fieldset>
                                                                        <legend>System Info </legend>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-6">
                                                                                <label>Siebel Id</label>
                                                                                <input type="text" name="siebelid" class="form-control" placeholder="Type Here" value="{{$usersdetail[0]->siebelid}}">
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <label>Salesforce</label>
                                                                                <input type="text" name="salesforece" class="form-control" placeholder="Type Here" value="{{$usersdetail[0]->salesforece}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>VF Learning</label>
                                                                            <input type="text" name="vflearning" class="form-control" placeholder="Type Here" value="{{$usersdetail[0]->vflearning}}">
                                                                        </div>
                                                                    </fieldset>

                                                                    <fieldset>
                                                                        <script type="text/javascript">
                                                                        function getOption() { 
                                                                                selectElement =  
                                                                                        document.querySelector('#select1'); 
                                                                                          
                                                                                output = selectElement.value; 
                                                                      
                                                                                document.querySelector('.output').textContent 
                                                                                        = output; 
                                                                            }
                                                                        </script>
                                                                        <legend>Payment Info </legend>
                                                                        <div class="form-group">
                                                                            <label>Pay Method (*)</label>
                                                                            <select name="paymethod" class="form-control" required="" id="select1" onchange='getOption(this.value);'>
                                                                                <option value="">SELECT</option>
                                                                                <option value="TFN" @if($usersdetail[0]->paymethod == 'TFN') SELECTED='SELECTED' @endif>TFN</option>
                                                                                <option value="ABN" @if($usersdetail[0]->paymethod == 'ABN') SELECTED='SELECTED' @endif>ABN</option>
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <div class="form-group">
                                                                                <label class="output">{{$usersdetail[0]->paymethod}}</label>(*)
                                                                                <input type="text" name="tfn_abn" class="form-control" placeholder="Type Here" value="{{$usersdetail[0]->tfn_abn}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Normal Rate (*)</label>
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$</span>
                                                                                </span>
                                                                                <input id="demo2" type="text" value="{{$usersdetail[0]->normalrate}}" name="normalrate" required="" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Saturday Rate (*)</label>
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$</span>
                                                                                </span>
                                                                                <input id="demo2" type="text" value="{{$usersdetail[0]->saturdayrate}}" name="saturdayrate" required="" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Sunday Rate (*)</label>
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$</span>
                                                                                </span>
                                                                                <input id="demo2" type="text" value="{{$usersdetail[0]->sundayrate}}" name="sundayrate" required="" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Feul (*)</label>
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$</span>
                                                                                </span>
                                                                                <input id="demo2" type="text" value="{{$usersdetail[0]->feul}}" name="feul" required="" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-6">
                                                                                <label>Bank Details</label>
                                                                                <input type="text" name="bankdetail1" class="form-control" placeholder="Type Here" value="{{$usersdetail[0]->bankdetail1}}">
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <label>&nbsp;</label>
                                                                                <input type="text" name="bankdetail2" class="form-control" placeholder="Type Here" value="{{$usersdetail[0]->bankdetail2}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Employment (*)</label>
                                                                            <select name="employment" class="form-control" required="">
                                                                                <option value="">SELECT</option>
                                                                                <option value="Casual" @if($usersdetail[0]->employment == 'Casual') SELECTED='SELECTED' @endif>Casual</option>
                                                                                <option value="Part Time" @if($usersdetail[0]->employment == 'Part Time') SELECTED='SELECTED' @endif>Part Time</option>
                                                                                <option value="Full Time" @if($usersdetail[0]->employment == 'Full Time') SELECTED='SELECTED' @endif>Full Time</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Tax Scale (*)</label>
                                                                            <select name="taxScale" class="form-control" required="">
                                                                                <option value="">-- Select --</option>
                                                                                <option value="1" @if($usersdetail[0]->taxScale == '1') SELECTED='SELECTED' @endif>Where the tax-free threshold is not claimed in Tax file number declaration</option>
                                                                                 <option value="2" @if($usersdetail[0]->taxScale == '2') SELECTED='SELECTED' @endif>Where the employee claimed the tax-free threshold in Tax file number declaration</option> 
                                                                                <option value="3" @if($usersdetail[0]->taxScale == '3') SELECTED='SELECTED' @endif>Foreign residents </option> 
                                                                                <option value="4" @if($usersdetail[0]->taxScale == '4') SELECTED='SELECTED' @endif>Where a tax file number (TFN)
                                                                                was not provided by employee</option> 
                                                                                <option value="5" @if($usersdetail[0]->taxScale == '5') SELECTED='SELECTED' @endif>FULL exemption from
                                                                                Medicare levy in Medicare
                                                                                levy variation declaration</option> 
                                                                                <option value="6" @if($usersdetail[0]->taxScale == '6') SELECTED='SELECTED' @endif>HALF exemption from
                                                                                Medicare levy in Medicare
                                                                                levy variation declaration</option>                     
                                                                            </select>
                                                                        </div>
                                                                    </fieldset>

                                                                    <fieldset>
                                                                        <legend>Login Info </legend>
                                                                        <div class="form-group">
                                                                            <label>UserName (*) (This must be unique)</label>
                                                                            <input type="text" id="username" class="form-control" required="" placeholder="Type Here" readonly="" value="{{$usersdetail[0]->username}}">
                                                                            <span id="error_email"></span>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>User Type / Group (*)</label>
                                                                            <select name="usertype" class="form-control" required="">
                                                                                <option value="">SELECT</option>
                                                                                @foreach($userdata['allusergroup'] as $usergroup)
                                                                                <option value="{{$usergroup->usertypeID}}" @if($usergroup->usertypeID == App\userlogintype::where('userID', $usersdetail[0]->id)->pluck('usertypeID')->first()) SELECTED='SELECTED' @endif>{{$usergroup->usertypeName}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Working Role (*)</label>
                                                                            <input type="text" name="workingrole" class="form-control" required="" placeholder="Type Here eg:(Sales Executive, Sales Head, ETC)" value="{{$usersdetail[0]->workingrole}}">
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="form-group text-right">
                                                                        <div>
                                                                            <input type="hidden" name="userid" value="{{$usersdetail[0]->id}}">
                                                                            <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light">
                                                                                Create User
                                                                            </button>
                                                                            <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                                                Cancel
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div>
                                                <!--EDIT MODEL-->
                                                <span data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target=".editmodel{{$usersdetail[0]->id}}"><a class="btn btn-sm btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a></span> 
                                                @else
                                                <a href="#" class="btn btn-sm btn-light waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a> 
                                                @endif

                                                @if(session('loggindata')['loggeduserpermission']->deleteuser=='Y')
                                                    @if($usersdetail[0]->userstatus == 1)
                                                    <!---Active Model-->
                                                    <div class="modal fade bs-example-modal-center activestatusmodel{{$usersdetail[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0">{{$usersdetail[0]->name}} Status</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="" action="{{route('edituserstatus')}}" method="post" novalidate="">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <h4>{{$usersdetail[0]->name}} is in <span class="badge badge-primary">Active Status</span></h4>
                                                                            <h4>Do you want to make it <span class="badge badge-primary">Inactive Status</span></h4>
                                                                            <p>Click on OK to continue or cancle it.</p>
                                                                            <input type="hidden" name="id" class="form-control" value="{{$usersdetail[0]->id}}">
                                                                            <input type="hidden" name="userstatus" class="form-control" value="0">
                                                                        </div>
                                                                        <div class="form-group text-right">
                                                                            <div>
                                                                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                    OK
                                                                                </button>
                                                                                <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                                                                    Cancel
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
                                                    <!---Active Model-->
                                                    <span data-toggle="modal" data-target=".activestatusmodel{{$usersdetail[0]->id}}"><a class="btn btn-sm btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a></span>
                                                    
                                                    @else
                                                    <!--Inactive model-->
                                                    <div class="modal fade bs-example-modal-center inactivestatusmodel{{$usersdetail[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title mt-0">{{$usersdetail[0]->suppliername}} Status</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="" action="{{route('edituserstatus')}}" method="post" novalidate="">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <h4>{{$usersdetail[0]->name}} is in <span class="badge badge-primary">Inactive Status</span></h4>
                                                                            <h4>Do you want to make it <span class="badge badge-primary">Active Status</span></h4>
                                                                            <p>Click on OK to continue or cancle it.</p>
                                                                            <input type="hidden" name="id" class="form-control" value="{{$usersdetail[0]->id}}">
                                                                            <input type="hidden" name="userstatus" class="form-control" value="1">
                                                                        </div>
                                                                        <div class="form-group text-right">
                                                                            <div>
                                                                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                                    OK
                                                                                </button>
                                                                                <button  data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
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
                                                    <span data-toggle="modal" data-target=".inactivestatusmodel{{$usersdetail[0]->id}}"><a class="btn btn-sm btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="In-Active"><i class="icon-music-pause"></i></a></span>
                                                    @endif
                                                @else
                                                <a class="btn btn-sm btn-light waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Active"><i class="icon-music-play"></i></a>
                                                @endif
                                                @if(session('loggindata')['loggeduserpermission']->editpermission=='Y')
                                                <a href="userpermission/{{$usersdetail[0]->id}}" class="btn btn-sm btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Permission"><i class="fas fa-user-lock"></i></a>
                                                @endif
                                                @if(session('loggindata')['loggeduserpermission']->edituser=='Y')
                                                <a href="usersstore/{{$usersdetail[0]->id}}" class="btn btn-sm btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Store"><i class="fas fa-store"></i></a>
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
            <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
            <script>
            $(document).ready(function(){

             $('#username').blur(function(){
              var error_email = '';
              var username = $('#username').val();
              var _token = $('input[name="_token"]').val();
              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
              $.ajax({
                url:"{{ route('ajaxcheckusername') }}",
                method:"POST",
                data:{username:username, _token:_token},
                success:function(result)
                {
                 if(result == 'unique')
                 {
                  $('#error_email').html('<label class="text-success">Username Available</label>');
                  $('#username').removeClass('has-error');
                  $('#submit').attr('disabled', false);
                 }
                 else
                 {
                  $('#error_email').html('<label class="text-danger">Username not Available</label>');
                  $('#username').addClass('has-error');
                  $('#submit').attr('disabled', 'disabled');
                 }
                }
               })
             });
             
            });
            </script>
        </div>
    </div>
@endsection
        