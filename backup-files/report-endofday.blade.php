@extends('main')

@section('content')
<div id="wrapper">
    @include('includes.topbar')

    @include('includes.sidebar')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {      
              //Iterate through each Textbox and add keyup event handler
              $(".calculator").each(function () {
                   $(this).keyup(function () {
                        //Initialize total to 0
                        var hundred = 0;
                        var fifty = 0;
                        var twenty = 0;
                        var ten = 0;
                        var five = 0;
                        var two = 0;
                        var one = 0;
                        var fiftyc = 0;
                        var twentyc = 0;
                        var tenc = 0;
                        var fivec = 0;
                        var totaltill = 0;
                        var total = 0;
                        var onec = 0;
                        /// $100 value
                        $("#100").each(function () {
                            var hundredfield = $("#100").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(hundredfield) && hundredfield.length != 0) {
                                  hundred = parseFloat(hundredfield) * 100;
                             }
                        });
                        /// $50 value 
                        $("#50").each(function () {
                            var fiftyfield = $("#50").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(fiftyfield) && fiftyfield.length != 0) {
                                  fifty = parseFloat(fiftyfield) * 50;
                             }
                        });
                        /// $20 value 
                        $("#20").each(function () {
                            var twentyfield = $("#20").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(twentyfield) && twentyfield.length != 0) {
                                  twenty = parseFloat(twentyfield) * 20;
                             }
                        });
                        /// $10 value 
                        $("#10").each(function () {
                            var tenfield = $("#10").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(tenfield) && tenfield.length != 0) {
                                  ten = parseFloat(tenfield) * 10;
                             }
                        });
                        /// $5 value 
                        $("#5").each(function () {
                            var fivefield = $("#5").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(fivefield) && fivefield.length != 0) {
                                  five = parseFloat(fivefield) * 5;
                             }
                        });
                        /// $2 value 
                        $("#2").each(function () {
                            var twofield = $("#2").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(twofield) && twofield.length != 0) {
                                  two = parseFloat(twofield) * 2;
                             }
                        });
                        /// $1 value 
                        $("#1").each(function () {
                            var onefield = $("#1").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(onefield) && onefield.length != 0) {
                                  one = parseFloat(onefield) * 1;
                             }
                        });
                        /// cent 50 value 
                        $("#c50").each(function () {
                            var cfiftyfield = $("#c50").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(cfiftyfield) && cfiftyfield.length != 0) {
                                  fiftyc = parseFloat(cfiftyfield) * 0.50;
                             }
                        });
                        /// cent 20 value 
                        $("#c20").each(function () {
                            var ctwentyfield = $("#c20").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(ctwentyfield) && ctwentyfield.length != 0) {
                                  twentyc = parseFloat(ctwentyfield) * 0.20;
                             }
                        });
                        /// cent 10 value 
                        $("#c10").each(function () {
                            var ctenfield = $("#c10").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(ctenfield) && ctenfield.length != 0) {
                                  tenc = parseFloat(ctenfield) * 0.10;
                             }
                        });
                        /// cent 5 value 
                        $("#c5").each(function () {
                            var cfivefield = $("#c5").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(cfivefield) && cfivefield.length != 0) {
                                  fivec = parseFloat(cfivefield) * 0.05;
                             }
                        });
                        $("#c1").each(function () {
                            var conefield = $("#c1").val();
                             // Sum only if the text entered is number and greater than 0
                             if (!isNaN(conefield) && conefield.length != 0) {
                                  onec = parseFloat(conefield) * 0.01;
                             }
                        });
                        total = hundred + fifty + twenty + ten + five + two + one + fiftyc + twentyc + tenc + fivec + onec;
                        //Assign the total to label
                        //.toFixed() method will roundoff the final sum to 2 decimal places
                        $('#totalcashdisplay').val(parseFloat(total).toFixed(2));                          
                   });
              });
         ///// on click eod event
         $( "#eodinsert" ).click(function() {
            
            var inserttillcash = $("#totalcashdisplay").text();
            var inserttilleftpos = $("#totaleftdisplay").text();
            var inserttillgst = $("#totalgstdisplay").val();
            var actualcashdisplay = $("#actualcashdisplay").text();
            var difference_eoddate = $('#difference_eoddate').val();
            var difference_cash = $('#difference_cash').val();
            var difference_eftpos = $('#difference_eftpos').val();
            var difference_totalgst = $('#difference_totalgst').val();
            var eodArray = $('#eodArray').val();
            if(!isNaN(inserttillcash)){
                if(inserttillcash == actualcashdisplay){
                $("#eodinsert").attr("hidden", true);
                $('#img').show();
                  $.ajax({url: "inserteod.php", type: "POST",
                  data: {inserttillcash:inserttillcash, inserttilleftpos:inserttilleftpos, inserttillgst:inserttillgst, difference_eoddate:difference_eoddate, difference_cash: difference_cash,
                        difference_eftpos: difference_eftpos, difference_totalgst:difference_totalgst, eodArray:eodArray},
                  success: function(result){
                        $("#resultoutPut").html(result);
    ////////// -------------- Close fancy box on sucess -----------------------------------
                        parent.$.fancybox.close();
                    }});    
                }else{
                    alert('Till cash and Actual cash are '+ inserttillcash +' And '+ actualcashdisplay)+ ', It should be same.';
                }
              }else{
                    alert('All field required');
              }
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
                            <h4 class="page-title">End Of Day (EOD)</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales</a></li>
                                <li class="breadcrumb-item active">End Of Day (EOD)</li>
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
                                    <div class="">
                                        @if(session('loggindata')['loggeduserpermission']->reportEODtill=='Y')
                                        <!----EOD TILL Model--->
                                        <div class="modal fade eodtill" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0">Calculator</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                               <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$100 X </span>
                                                                                </span>
                                                                                <input id="100" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$50 X </span>
                                                                                </span>
                                                                                <input id="50" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$20 X </span>
                                                                                </span>
                                                                                <input id="20" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$10 X </span>
                                                                                </span>
                                                                                <input id="10" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$5 X </span>
                                                                                </span>
                                                                                <input id="5" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$2 X </span>
                                                                                </span>
                                                                                <input id="2" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">$1 X </span>
                                                                                </span>
                                                                                <input id="1" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">¢50 X </span>
                                                                                </span>
                                                                                <input id="c50" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">¢20 X </span>
                                                                                </span>
                                                                                <input id="c20" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">¢10 X </span>
                                                                                </span>
                                                                                <input id="c10" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">¢5 X </span>
                                                                                </span>
                                                                                <input id="c5" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <span class="bootstrap-touchspin-prefix input-group-prepend">
                                                                                    <span class="input-group-text">¢1 X </span>
                                                                                </span>
                                                                                <input id="c1" type="number" name="demo2" class="form-control calculator" min="1">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="table-rep-plugin">
                                                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                                                        <table id="" class="table  table-striped">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>&nbsp;</th>
                                                                                <th data-priority="3">Actual</th>
                                                                                <th data-priority="1">Till</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <form action="{{route('eodrecon')}}" method="post">
                                                                              @csrf
                                                                              <input type="hidden" name="eoddate" value="{{$eoddata['todaydate']}}">
                                                                              @foreach($eoddata['paymentoptions'] as $payoptions)
                                                                              <tr>
                                                                                  <td>
                                                                                      {{$payoptions->paymentname}}
                                                                                      <input type="hidden" name="amounttype[]" value="{{$payoptions->paymentname}}">
                                                                                  </td> 
                                                                                  <td>
                                                                                      @if($payoptions->paymentname == 'Cash')
                                                                                        @if(!empty(session('loggindata')['loggeduserstore']))
                                                                                        {{($eoddata['gettotal']->where('paymentType', $payoptions->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $payoptions->paymentname)->sum('paidAmount')) + $eoddata['geteodamount']->storeEODAmount}}
                                                                                        <input type="hidden" name="eodamount" value="{{($eoddata['gettotal']->where('paymentType', $payoptions->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $payoptions->paymentname)->sum('paidAmount')) + $eoddata['geteodamount']->storeEODAmount}}"> 
                                                                                        @endif
                                                                                      @elseif($eoddata['gettotal']->where('paymentType', $payoptions->paymentname))
                                                                                      {{$eoddata['gettotal']->where('paymentType', $payoptions->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $payoptions->paymentname)->sum('paidAmount')}}
                                                                                      @endif

                                                                                  </td>
                                                                                  <td>
                                                                                      @if($payoptions->paymentname == 'Cash')
                                                                                      <!-- <span id="totalcashdisplay"></span> -->
                                                                                      <input type="text" id="totalcashdisplay" name="tillamount[]" readonly="" style="width: 75px;">
                                                                                      @elseif($eoddata['gettotal']->where('paymentType', $payoptions->paymentname))
                                                                                      <input type="number" name="tillamount[]" style="width: 75px;">
                                                                                      @endif
                                                                                  </td>
                                                                              </tr>
                                                                              @endforeach
                                                                              <tr>
                                                                                <td colspan="3">
                                                                                  <textarea class="form-control" cols="" rows="3" name="eodnote" placeholder="Type Note Here"></textarea>
                                                                                </td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td colspan="3">
                                                                                  <div class="form-group text-center">
                                                                                      <button class="btn btn-primary">Submit EOD</button>
                                                                                  </div>
                                                                                </td>
                                                                              </tr>
                                                                            </form>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                        <!----EOD TILL Model--->
                                        @endif

                                        @if(session('loggindata')['loggeduserpermission']->changestore=='Y')
                                        <!----Store Change Model--->
                                        <div class="modal fade changestore" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0">Change Store</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('changestore')}}" method="post">
                                                            @csrf
                                                            <div class="form-group">
                                                                <select name="store" class="form-control">
                                                                    <option value="">SELECT STORE</option>
                                                                    @foreach(session('allstore') as $storename)
                                                                    <option value="{{$storename->store_id}}" @if(session('loggindata')['loggeduserstore']['store_id']==$storename->store_id) SELECTED=='SELECTED' @endif>{{$storename->store_name}}</option>
                                                                    @endforeach           
                                                                </select>
                                                            </div>
                                                            <div class="form-group text-right">
                                                                <button type="submit" class="btn btn-primary">Select</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                        <!----Store Change Model--->
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if(session('loggindata')['loggeduserpermission']->reportEODtill=='Y')
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".eodtill" data-backdrop="static" data-keyboard="false">EOD TILL</button>
                                        @endif
                                        @if(session('loggindata')['loggeduserpermission']->reportEOD=='Y')
                                        <a href="{{route('todayendofday')}}" class="btn btn-outline-primary waves-effect waves-light">EOD Today</a>
                                        @endif
                                        @if(session('loggindata')['loggeduserpermission']->changestore=='Y')
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore" data-backdrop="static" data-keyboard="false">Change Store</button>
                                        @endif
                                    </div>
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
                                @if(empty(session('loggindata')['loggeduserstore']))
                                    <p style="color: #fd0d0d;">Please select a store as you not logged in any store.</p>
                                @endif
                                <p>Showing EOD For: <span style="font-size: 1.2em; color: #fd0d0d; font-weight: 600;">{{$eoddata['todaydate']}} - @if(!empty(session('loggindata')['loggeduserstore'])){{session('loggindata')['loggeduserstore']->store_name}}@endif</span></p>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <!-- <th data-priority="3">Cash</th>
                                                <th data-priority="1">EFTPOS</th> -->
                                                @foreach($eoddata['paymentoptions'] as $options)
                                                    <th>
                                                        {{$options->paymentname}}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                            
                                            <tr>
                                                <th>Actual</th>
                                                @foreach($eoddata['paymentoptions'] as $options)
                                                    <td>
                                                        {{$eoddata['gettotal']->where('paymentType', $options->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $options->paymentname)->sum('paidAmount')}}
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <th>EOD Done</th>
                                                @foreach($eoddata['paymentoptions'] as $options)
                                                    <td>
                                                        {{$eoddata['geteoddone']->where('eodPaymentType', $options->paymentname)->sum('eodAmount')}}
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <th>Difference</th>
                                                @foreach($eoddata['paymentoptions'] as $options)
                                                    <td>
                                                        {{($eoddata['gettotal']->where('paymentType', $options->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $options->paymentname)->sum('paidAmount')) - $eoddata['geteoddone']->where('eodPaymentType', $options->paymentname)->sum('eodAmount')}}
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach($eoddata['geteoddone']->groupBy('name') as $reconuser)
                                                <th colspan="4" style="text-align: right;">
                                                  Reconciliation by - {{$reconuser[0]->name}}
                                                </th>
                                                @endforeach
                                            </tr>
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

        @include('includes.footer')
    </div>
</div>
@endsection