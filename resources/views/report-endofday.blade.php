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
/************************************************************************************/
$(document).ready(function () {      
              //Iterate through each Textbox and add keyup event handler
              $(".paymenttally").each(function () {
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
                        var totalcash = 0;
                        /// $100 value
                        

                        $("#Cash").each(function () {
                            var hundredfield = $('#Cash').text();
                             // Sum only if the text entered is number and greater than 0
                             totalcash = parseFloat(hundredfield) - $(".cash").val();
                             $("#totalCash").text(totalcash.toFixed(2));
                        });

                        $("#EFTPOS").each(function () {
                            var hundredfield = $('#EFTPOS').text();
                             // Sum only if the text entered is number and greater than 0
                             totaleftpos = parseFloat(hundredfield) - $("#inputEFTPOS").val();
                             $("#totalEFTPOS").text(totaleftpos.toFixed(2));
                        });

                        $("#Others").each(function () {
                            var hundredfield = $('#Others').text();
                             // Sum only if the text entered is number and greater than 0
                             totalothers = parseFloat(hundredfield) - $("#inputOthers").val();
                             $("#totalOthers").text(totalothers.toFixed(2));
                        });

                        $("#store").each(function () {
                            var hundredfield = $('#store').text();
                             // Sum only if the text entered is number and greater than 0
                             totalstore = parseFloat(hundredfield) - $("#inputstore").val();
                             $("#totalstore").text(totalstore.toFixed(2));
                        });                 
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
                <!------Model Calculator-------------->
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
                                    <div class="col-md-12">
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
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close Calc</button>
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <!------Model Calculator-------------->
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
                    <div class="card m-b-30">
                      <div class="card-body">
                        <h5 class="text-primary">End Of Day: Payment Tally</h5>
                        <p class="text-center" style="font-size: 1.2em; color: #30419b; font-weight: 600;">@php echo date('d M Y', strtotime($eoddata['todaydate'])) @endphp</p>
                        <form action="{{route('eodrecon')}}" method="post">
                          @csrf
                          <table class="table table-striped paymenttally">
                            <thead>
                              <tr>
                                <th>Payment Type</th>
                                <th>Expected</th>
                                <th>Counted</th>
                                <th>Difference</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($eoddata['paymentoptions'] as $options)
                                @if($options->paymentname == "Cash")
                                  <tr>
                                    <td>
                                      {{$options->paymentname}}
                                      <input type="hidden" name="amounttype[]" value="{{$options->paymentname}}">
                                      <input type="hidden" name="eoddate" value="{{$eoddata['todaydate']}}">
                                    </td>
                                    <td style="font-size: 1.2em;">
                                      $
                                      <span id="{{$options->paymentname}}">
                                        {{$eoddata['gettotal']->where('paymentType', $options->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $options->paymentname)->sum('paidAmount')}}
                                      </span>
                                      <input type="hidden" name="eodamount[]" value="{{$eoddata['gettotal']->where('paymentType', $options->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $options->paymentname)->sum('paidAmount')}}">
                                    </td>
                                    <td><i class="fas fa-calculator" data-toggle="modal" data-target=".eodtill" data-backdrop="static" data-keyboard="false" style="float: left; margin: 10px 6px;"></i><input type="number" step="any" name="tillamount[]" id="totalcashdisplay" class="form-control cash" required="" style="width: 100px !important; font-size: 1.2em;" min="0"></td>
                                    <td style="font-size: 1.2em;">$<span id="total{{$options->paymentname}}">0.00</span></td>
                                  </tr>
                                  @endif
                                  @if($options->paymentname != "Cash")
                                  <tr>
                                    <td>
                                      {{$options->paymentname}}
                                      <input type="hidden" name="amounttype[]" value="{{$options->paymentname}}">
                                    </td>
                                    <td style="font-size: 1.2em;">
                                      $
                                      <span id="{{$options->paymentname}}">
                                        {{$eoddata['gettotal']->where('paymentType', $options->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $options->paymentname)->sum('paidAmount')}}
                                      </span>
                                      <input type="hidden" name="eodamount[]" value="{{$eoddata['gettotal']->where('paymentType', $options->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $options->paymentname)->sum('paidAmount')}}">
                                    </td>
                                    <td><i style="float: left; margin: 10px 6px;"></i><input type="number" step="any" name="tillamount[]" id="input{{$options->paymentname}}" class="form-control" required="" style="width: 100px !important; font-size: 1.2em;" min="0"></td>
                                    <td style="font-size: 1.2em;">$<span id="total{{$options->paymentname}}">0.00</span></td>
                                  </tr>
                                  @endif
                                @endforeach
                                <tr>
                                  <td>
                                    Store Credit
                                    <input type="hidden" name="storecredit" value="storecredit">
                                  </td>
                                  <td style="font-size: 1.2em;">
                                    $<span id="store">
                                      @if($eoddata['geteodamount'] != "")
                                      {{$eoddata['geteodamount']->storeEODAmount}}
                                      @else
                                      0
                                      @endif
                                      <input type="hidden" name="storeamount" value="@if($eoddata['geteodamount'] != "") {{$eoddata['geteodamount']->storeEODAmount}} @else 0 @endif">
                                    </span>
                                  </td>
                                  <td>
                                    <i style="float: left; margin: 10px 6px;"></i>
                                    <input type="number" step="any" name="storeamountrecon" id="inputstore" class="form-control" required="" style="width: 100px !important; font-size: 1.2em;" min="0">
                                  </td>
                                  <td style="font-size: 1.2em;">$<span id="totalstore">0.00</span></td>
                                </tr>
                                <!-- <tr>
                                  <td>Bank Transfer (Cash)</td>
                                  <td>
                                    $0.00
                                  </td>
                                  <td><input type="number" step="any" name="" id="banktransfer" class="form-control" value="0.00" style="width: 100px !important;" min="0"></td>
                                  <td>$0</td>
                                </tr> -->
                            </tbody>
                          </table>
                          <textarea class="form-control" rows="5" cols="" name="eodnote" placeholder="Note (if any)"></textarea>

                          <div class="col-md-12 text-right" style="margin-top: 10px;">
                              <button type="submit" class="btn btn-primary">Submit EOD</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="col-md-12 text-right">
                      <div class="card m-b-30">
                        <div class="card-body">
                          @if(session('loggindata')['loggeduserpermission']->reportEOD=='Y')
                          <a href="{{route('todayendofday')}}" class="btn btn-outline-primary waves-effect waves-light">EOD Today</a>
                          @endif
                          @if(session('loggindata')['loggeduserpermission']->changestore=='Y')
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
                                                      <option value="{{$storename->store_id}}" @if(session('storeid')==$storename->store_id) SELECTED=='SELECTED' @endif>{{$storename->store_name}}</option>
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
                          <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore" data-backdrop="static" data-keyboard="false">Change Store</button>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="card m-b-30">
                        <div class="card-body">
                          <!-------Cash In Model------->
                          <div class="modal fade cashin" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title mt-0">Cash IN</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                          <form action="{{route('storecashin')}}" method="post">
                                              @csrf
                                              <div class="form-group">
                                                  <input type="hidden" name="store" value="@if($eoddata['geteodamount'] != ""){{$eoddata['geteodamount']->store_id}}@endif">
                                                  <input type="number" name="cashin" class="form-control" placeholder="Enter Amount"  step="any" required="">
                                              </div>
                                              <div class="form-group text-right">
                                                  <button type="submit" class="btn btn-primary">Open Store</button>
                                              </div>
                                          </form>
                                      </div>
                                  </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                          </div>
                          <!-------Cash In Model------->

                          <!-----Cash Out Model--------->
                          <div class="modal fade cashout" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title mt-0">Cash Out</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                          <form action="{{route('storecashout')}}" method="post">
                                              @csrf
                                              <div class="form-group">
                                                <input type="hidden" name="store" value="@if($eoddata['geteodamount'] != ""){{$eoddata['geteodamount']->store_id}}@endif">
                                                <input type="number" name="cashout" class="form-control" placeholder="Enter Amount"  step="any" required="">
                                              </div>
                                              <div class="form-group text-right">
                                                  <button type="submit" class="btn btn-primary">Close Store</button>
                                              </div>
                                          </form>
                                      </div>
                                  </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                          </div>
                          <!-----Cash Out Model---------->
                          <span class="text-primary" style="font-size: 1.2em; font-weight: 600;">Cash IN/OUT</span><span style="float: right;"><a  data-toggle="modal" data-target=".cashin" data-backdrop="static" data-keyboard="false" class="btn btn-success" style="cursor: pointer; color: #FFF;"><i class="far fa-money-bill-alt"></i> IN</a> <a  data-toggle="modal" data-target=".cashout" data-backdrop="static" data-keyboard="false" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="far fa-money-bill-alt"></i> OUT</a></span>
                          <div class="clearfix" style="margin: 20px 0px;"></div>
                          <table id="openingfloat" class="table table-bordered" style="margin-top:25px; border-collapse: collapse; border-spacing: 0; width: 100%;">
                              <thead>
                              <tr>
                                  <th>Date</th>
                                  <th>Opening Float</th>
                                  <th>Closing Float</th>
                              </tr>
                              </thead>
                              <tbody>
                                  @foreach($eoddata['storecash'] as $storecash)
                                  <tr>
                                    <td>@php echo date('d-m-Y', strtotime($storecash->storecashdate)) @endphp</td>
                                    <td>{{$storecash->storecashIn}}<br>{{$storecash->name}}</td>
                                    <td>{{$storecash->storecashOut}}<br>{{$storecash->storecashoutuser['name']}}</td>
                                  </tr>
                                  @endforeach
                              </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px;">
                      <div class="card m-b-30">
                        <div class="card-body">
                          <h6 class="text-primary">Payment Tally Summary</h6>
                          <table id="paymenttally" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                              <thead>
                              <tr>
                                  <th>Date</th>
                                  <th>EOD Note</th>
                                  <th>Staff</th>
                              </tr>
                              </thead>
                              <tbody>
                                  @foreach($eoddata['geteoddone'] as $eoddone)
                                  <tr>
                                    <script>
                                     function popitup(url) 
                                     {
                                      newwindow=window.open(url,'name','height=300,width=650,screenX=400,screenY=350');
                                      if (window.focus) {newwindow.focus()}
                                      return false;
                                     }
                                     </script>
                                    <td><a href="" onClick="return popitup('https://testingpos.just1click.com.au/reportpaymenttally/{{$eoddone->eodDate}}/{{$eoddone->storeID}}')" style="text-decoration: underline; color: #30419b !important;">{{$eoddone->eodDate}}</a></td>
                                    <td>{{$eoddone->eodNote}}</td>
                                    <td>{{$eoddone->name}}</td>
                                  </tr>
                                  @endforeach
                              </tbody>
                          </table>
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
</div>
@endsection