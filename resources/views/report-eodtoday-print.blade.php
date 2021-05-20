@extends('main')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript">
<!--
window.print();
//-->
</script>
<div id="wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
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
                                              {{$key+1}} - 
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
                                              {{$key+1}} -
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
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->  
</div>
@endsection