@extends('main')

@section('content')
<div id="wrapper">
    @include('includes.topbar')

    @include('includes.sidebar')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
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
                                        @if(session('loggindata')['loggeduserpermission']->reportEOD=='Y')
                                        <!----EOD Today Model--->
                                        <div class="modal fade eodtoday" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0">EOD</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                        <!----EOD Today Model--->
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
                                        <!----Store Change Model--->
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <form action="{{route('todayendofday')}}" method="post">
                                          @csrf
                                          <input type="date" name="todaydate" value="{{$eoddata['todaydate']}}">
                                          <button type="submit" class="btn btn-outline-primary waves-effect waves-light">Submit</button>
                                          @if(session('loggindata')['loggeduserpermission']->changestore=='Y')
                                          <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore" data-backdrop="static" data-keyboard="false">Change Store</button>
                                          @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count(session('loggindata')['loggeduserstore']) == 0 || count(session('loggindata')['loggeduserstore']) >= 2)
                                    <p style="color: #fd0d0d;">Please select a store as you not logged in any store.</p>
                                @endif
                                
                                <div class="row">
                                    @foreach($eoddata['planproposition'] as $proposition)
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
                                                        <h5 style="color: #FFF;">
                                                            {{$eoddata['getorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count() - $eoddata['getrefundorderedplan']->where('planpropositionID', $proposition->planpropositionID)->where('plancategoryID', $proposition->pcID)->count()}}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    @foreach($eoddata['productcategory'] as $productcategory)
                                    @if(App\mastercomission::where('comossioncategory', $productcategory->categoryname)->pluck('comissioncategoryview')->first() == 1)
                                    <div class="col-sm-6 col-xl-3" style="cursor: pointer;">
                                        <div class="card" style="background-color: #3c9b30;">
                                            
                                            <div class="card-heading p-4">
                                                <div style="color: #FFF;">
                                                    <h5>{{$productcategory->categoryname}}</h5>
                                                </div>
                                                <h5 style="color: #FFF;">
                                                    ${{$eoddata['productcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal') - $eoddata['refundproductcategorysales']->where('categoryID', $productcategory->categoryID)->sum('subTotal')}}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                  <div class="table-rep-plugin" style="width: 100%;">
                                      <div class="table-responsive b-0" data-pattern="priority-columns">
                                          <table id="tech-companies-1" class="table  table-striped">
                                              <thead>
                                              <tr>
                                                  <th>Date</th>
                                                  <th data-priority="3">Invoice ID</th>
                                                  <th data-priority="1">Barcode</th>
                                                  <th data-priority="3">Item's</th>
                                                  <th data-priority="6">Price</th>
                                                  <th data-priority="6">Payments</th>
                                              </tr>
                                              </thead>
                                              <tbody>
                                              @foreach($eoddata['geteod']->groupBy('orderID') as $eod)
                                              <tr>
                                                  <th>@php echo date('d-m-Y', strtotime($eod[0]->orderDate)) @endphp </th>
                                                  <td>{{$eod[0]->orderID}}</td>
                                                  <td style="font-size: 0.8em; font-weight: 600;">
                                                      @foreach($eod as $barcode)
                                                      {{$barcode->barcode}}<br>
                                                      @endforeach
                                                  </td>
                                                  <td style="font-size: 0.8em; font-weight: 600;">
                                                      @foreach($eod as $key => $items)
                                                          <i class="fas fa-circle"></i> - 
                                                          @if($items->planID != '')
                                                            ${{$items->ppingst}} {{$items->planname}}
                                                            - {{$items->pcname}} - {{$items->stockgroupname}}
                                                            @if($items->productname != '')
                                                            <br>
                                                            {{$items->productname}}
                                                            @endif
                                                            @if($items->productimei != '')
                                                            <br>
                                                            {{$items->productimei}}
                                                            @endif
                                                          @else
                                                          {{$items->productname}}
                                                            @if($items->productimei != '')
                                                            <br>
                                                            {{$items->productimei}}
                                                            @endif
                                                          @endif
                                                      <br>
                                                      @endforeach
                                                  </td>
                                                  <td style="font-size: 0.8em; font-weight: 600;">
                                                      @foreach($eod as $price)
                                                          ${{$price->subTotal}}
                                                          <br>
                                                      @endforeach
                                                  </td>
                                                  <td>
                                                      <table width="100%">
                                                          <tbody>
                                                              <tr style="background-color: transparent;">
                                                                  @foreach($eod[0]->orderpayment->groupBy('paymentType') as $payment)
                                                                      <td>
                                                                        {{$payment[0]->paymentType}}
                                                                        <br>
                                                                        ${{$payment[0]->where('orderID', $eod[0]->orderID)->where('paymentType', $payment[0]->paymentType)->sum('paidAmount')}}
                                                                      </td>
                                                                  @endforeach
                                                              </tr>
                                                          </tbody>
                                                      </table>
                                                  </td>
                                              </tr>
                                              @endforeach
                                              @foreach($eoddata['getrefundeod']->groupBy('refundInvoiceID') as $refundeod)
                                              <tr style="background-color: #f1525247;">
                                                  <th>@php echo date('d-m-Y', strtotime($refundeod[0]->refundDate)) @endphp </th>
                                                  <td>{{$refundeod[0]->refundInvoiceID}}</td>
                                                  <td style="font-size: 0.8em; font-weight: 600;">
                                                      @foreach($refundeod as $barcode)
                                                      {{$barcode->barcode}}<br>
                                                      @endforeach
                                                  </td>
                                                  <td style="font-size: 0.8em; font-weight: 600;">
                                                      @foreach($refundeod as $key => $items)
                                                          <i class="fas fa-circle"></i> -
                                                          @if($items->planID != '')
                                                          ${{$items->ppingst}} {{$items->planname}}
                                                            @if($items->productname != '')
                                                            <br>
                                                            {{$items->productname}}
                                                            @endif
                                                            @if($items->productimei != '')
                                                            <br>
                                                            {{$items->productimei}}
                                                            @endif
                                                          @else
                                                          {{$items->productname}}
                                                            @if($items->productimei != '')
                                                            <br>
                                                            {{$items->productimei}}
                                                            @endif
                                                          @endif
                                                      <br>
                                                      @endforeach
                                                  </td>
                                                  <td style="font-size: 0.8em; font-weight: 600;">
                                                      @foreach($refundeod as $price)
                                                          -${{$price->subTotal}}
                                                          <br>
                                                      @endforeach
                                                  </td>
                                                  <td>
                                                      <table width="100%">
                                                          <tbody>
                                                              <tr style="background-color: transparent;">
                                                                  @foreach($refundeod[0]->orderpayment->groupBy('paymentType') as $payment)
                                                                      <td>
                                                                        {{$payment[0]->paymentType}}
                                                                        <br>
                                                                        -${{$payment[0]->where('refundInvoiceID', $refundeod[0]->refundInvoiceID)->where('paymentType', $payment[0]->paymentType)->sum('paidAmount')}}
                                                                      </td>
                                                                  @endforeach
                                                              </tr>
                                                          </tbody>
                                                      </table>
                                                  </td>
                                              </tr>
                                              @endforeach
                                              <tr>
                                                  <td colspan="5" align="right" style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                  <td style="font-size: 1.2em; font-weight: 600;">
                                                      <table width="100%">
                                                          <tbody>
                                                              <tr style="background-color: transparent;">
                                                                  @foreach($eoddata['paymentoptions'] as $options)
                                                                      <td>
                                                                          {{$options->paymentname}}
                                                                          <br>
                                                                          ${{$eoddata['gettotal']->where('paymentType', $options->paymentname)->sum('paidAmount') - $eoddata['getrefundtotal']->where('paymentType', $options->paymentname)->sum('paidAmount')}}
                                                                      </td>
                                                                  @endforeach
                                                              </tr>
                                                          </tbody>
                                                      </table>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td colspan="5" align="right" style="font-size: 1.2em; font-weight: 600;">Grand Total</td>
                                                  <td style="font-size: 1.2em; font-weight: 600;">${{$eoddata['gettotal']->sum('paidAmount') - $eoddata['getrefundtotal']->sum('paidAmount')}}</td>
                                              </tr>
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                                  <div class="col-md-12 text-right">
                                    <input type="button" value="Print EOD" class="btn btn-warning" onclick="window.open('{{route("eodtodayprint")}}','popUpWindow','height=400,width=900,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
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