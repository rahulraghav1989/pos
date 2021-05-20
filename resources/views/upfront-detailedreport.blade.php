@extends('main')

@section('content')
<div id="wrapper">
	@include('includes.topbar')

    @include('includes.sidebar')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
    $(function() {
      $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        startDate: @php echo date('m/d/Y', strtotime($firstday)) @endphp,
        endDate: @php echo date('m/d/Y', strtotime($lastday)) @endphp,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        showCustomRangeLabel: true,
        alwaysShowCalendars: true
      }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
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
                            <h4 class="page-title">Upfront Report</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Upfront</a></li>
                                <li class="breadcrumb-item active">Upfront Detailed Report</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                        	<div class="card-body row">
                        		<div class="col-md-12 row">
                                    <div class="col-md-6">&nbsp;</div>
                                    <div class="col-md-6">
                                        <div class="text-right">
                                            <form action="{{route('upfrontdetailedreport')}}" method="post" style="text-align: right;">
                                                @csrf
                                                <div class="form-group" style="float: left;">
                                                    <input type="text" class="form-control" name="daterange" value="@php echo date('m/d/Y', strtotime($firstday)) .'-'. date('m/d/Y', strtotime($lastday)) @endphp" />
                                                </div>
                                                <div class="form-group" style="float: left;">
                                                    <button type="submit" class="btn btn-primary">Apply Date</button>
                                                </div>
                                                @if(session('loggindata')['loggeduserpermission']->changestore=='Y')
                                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".changestore">Change Store</button>
                                                @endif
                                            </form>
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
                                                                <select name="store" class="form-control">
                                                                    <option value="">SELECT STORE</option>
                                                                    @foreach(session('allstore') as $storename)
                                                                    <option value="{{$storename->store_id}}" @if(session('storeid')==$storename->store_id) SELECTED=='SELECTED' @endif>{{$storename->store_name}}</option>
                                                                    @endforeach           
                                                                </select>
                                                                <br>
                                                                <button type="submit" class="btn btn-primary">Select</button>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <!----Store Change Model--->
                                            @endif
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                        	</div>
                            <div class="card-body">
                                <style type="text/css">
                                    .dt-buttons{
                                        width: 50%;
                                        float: left;
                                    }
                                </style>
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                            <span class="d-none d-md-block">Upfront Detailed</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span> 
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                            <span class="d-none d-md-block">Upfront NEW</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
                                            <span class="d-none d-md-block">Upfront Upgrades</span><span class="d-block d-md-none"><i class="mdi mdi-email h5"></i></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                                            <span class="d-none d-md-block">Upfront NBN</span><span class="d-block d-md-none"><i class="mdi mdi-settings h5"></i></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#wearable" role="tab">
                                            <span class="d-none d-md-block">Upfront Wearable</span><span class="d-block d-md-none"><i class="mdi mdi-settings h5"></i></span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active p-3" id="home" role="tabpanel">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="datatable-buttons" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Store</th>
                                                        <th>Date</th>
                                                        <th>Sale Invoice</th>
                                                        <th data-priority="1">Customer</th>
                                                        <th>Order Number</th>
                                                        <th>Order Date</th>
                                                        <th data-priority="1">Customer Type</th>
                                                        <th>Plan Type</th>
                                                        <th>Plan Term</th>
                                                        <th>Plan Code</th>
                                                        <th>Plan Name</th>
                                                        <th>Plan Proposition</th>
                                                        <th>Plan Category</th>
                                                        <th>Plan Handset Term</th>
                                                        <th data-priority="1">Barcode</th>
                                                        <th data-priority="1">Stock Code</th>
                                                        <th data-priority="1">Product Name</th>
                                                        <th>Product Supplier</th>
                                                        <th data-priority="1">Device</th>
                                                        <th data-priority="1">Quantity</th>
                                                        <th data-priority="1">PP (Inc. GST)</th>
                                                        <th>MAF</th>
                                                        <th>Comission <br>(Ex. GST)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $salessp = 0;
                                                    $refundsp = 0;
                                                    $salemaf= 0;
                                                    $salecomission = 0;
                                                    $refundmaf = 0;
                                                    $refundcomission= 0;
                                                    @endphp
                                                    @foreach($getconnection as $sales)
                                                    <tr>
                                                        <td>{{$sales->store_code}}<br>{{$sales->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$sales->customerfirstname}} {{$sales->customerlastname}}
                                                        </td>
                                                        <td>{{$sales->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <td>
                                                            {{$sales->customertype}}
                                                        </td>
                                                        <td>{{$sales->plantypename}}</td>
                                                        <td>{{$sales->plantermname}}</td>
                                                        <td>{{$sales->plancode}}</td>
                                                        <td>{{$sales->planname}}</td>
                                                        <td>{{$sales->planpropositionname}}</td>
                                                        <td>{{$sales->pcname}}</td>
                                                        <td>{{$sales->planhandsettermname}}</td>
                                                        <td>{{$sales->barcode}}</td>
                                                        <td>{{$sales->stockcode}}</td>
                                                        <td>{{$sales->productname}}</td>
                                                        <td>{{$sales->suppliername}}</td>
                                                        <td>{{$sales->productimei}}</td>
                                                        <td>{{$sales->quantity}}</td>
                                                        <td>
                                                            {{$sales->ppingst}}
                                                            @php
                                                            $salessp += $sales->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            {{$sales->planexgstamount}}
                                                            @php
                                                            $salemaf += $sales->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($sales->actualcomission != "" || $sales->actualcomission != "0.00")
                                                                {{$sales->actualcomission}}
                                                                @php
                                                                $salecomission += $sales->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$sales->plancomission}}
                                                                @php
                                                                $salecomission += $sales->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @foreach($getrefundconnection as $refund)
                                                    <tr style="background-color: #f1525247;">
                                                        <td>{{$refund->store_code}}<br>{{$refund->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$refund->orderID}}" style="color: #007bff;">{{$refund->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$refund->customerfirstname}} {{$refund->customerlastname}}
                                                        </td>
                                                        <td>{{$refund->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <td>
                                                            {{$refund->customertype}}
                                                        </td>
                                                        <td>{{$refund->plantypename}}</td>
                                                        <td>{{$refund->plantermname}}</td>
                                                        <td>{{$refund->plancode}}</td>
                                                        <td>{{$refund->planname}}</td>
                                                        <td>{{$refund->planpropositionname}}</td>
                                                        <td>{{$refund->pcname}}</td>
                                                        <td>{{$refund->planhandsettermname}}</td>
                                                        <td>{{$refund->barcode}}</td>
                                                        <td>{{$refund->stockcode}}</td>
                                                        <td>{{$refund->productname}}</td>
                                                        <td>{{$refund->suppliername}}</td>
                                                        <td>{{$refund->productimei}}</td>
                                                        <td>{{$refund->quantity}}</td>
                                                        <td>
                                                            {{$refund->ppingst}}
                                                            @php
                                                            $refundsp += $refund->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            -{{$refund->planexgstamount}}
                                                            @php
                                                            $refundmaf += $refund->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($refund->actualcomission != "" || $refund->actualcomission != "0.00")
                                                                {{$refund->actualcomission}}
                                                                @php
                                                                $refundcomission += $refund->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$refund->plancomission}}
                                                                @php
                                                                $refundcomission += $refund->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <th></th>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                {{$salessp - $refundsp}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                @if($getconnection->count() != 0)
                                                                    @php
                                                                    $salemaf = $salemaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                @if($getrefundconnection->count() != 0)
                                                                    @php
                                                                    $refundmaf = $refundmaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                {{round($salemaf - $refundmaf, 2)}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">{{$salecomission - $refundcomission}}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-3" id="profile" role="tabpanel">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="datatable-buttons1" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Store</th>
                                                        <th>Date</th>
                                                        <th>Sale Invoice</th>
                                                        <th data-priority="1">Customer</th>
                                                        <th>Order Number</th>
                                                        <th>Order Date</th>
                                                        <th data-priority="1">Customer Type</th>
                                                        <th>Plan Type</th>
                                                        <th>Plan Term</th>
                                                        <th>Plan Code</th>
                                                        <th>Plan Name</th>
                                                        <th>Plan Proposition</th>
                                                        <th>Plan Category</th>
                                                        <th>Plan Handset Term</th>
                                                        <th data-priority="1">Barcode</th>
                                                        <th data-priority="1">Stock Code</th>
                                                        <th data-priority="1">Product Name</th>
                                                        <th>Product Supplier</th>
                                                        <th data-priority="1">Device</th>
                                                        <th data-priority="1">Quantity</th>
                                                        <th data-priority="1">PP (Inc. GST)</th>
                                                        <th>MAF</th>
                                                        <th>Comission <br>(Ex. GST)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $salessp = 0;
                                                    $refundsp = 0;
                                                    $salemaf= 0;
                                                    $salecomission = 0;
                                                    $refundmaf = 0;
                                                    $refundcomission= 0;
                                                    @endphp
                                                    @foreach($getconnection2 as $sales)
                                                    <tr>
                                                        <td>{{$sales->store_code}}<br>{{$sales->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$sales->customerfirstname}} {{$sales->customerlastname}}
                                                        </td>
                                                        <td>{{$sales->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <td>
                                                            {{$sales->customertype}}
                                                        </td>
                                                        <td>{{$sales->plantypename}}</td>
                                                        <td>{{$sales->plantermname}}</td>
                                                        <td>{{$sales->plancode}}</td>
                                                        <td>{{$sales->planname}}</td>
                                                        <td>{{$sales->planpropositionname}}</td>
                                                        <td>{{$sales->pcname}}</td>
                                                        <td>{{$sales->planhandsettermname}}</td>
                                                        <td>{{$sales->barcode}}</td>
                                                        <td>{{$sales->stockcode}}</td>
                                                        <td>{{$sales->productname}}</td>
                                                        <td>{{$sales->suppliername}}</td>
                                                        <td>{{$sales->productimei}}</td>
                                                        <td>{{$sales->quantity}}</td>
                                                        <td>
                                                            {{$sales->ppingst}}
                                                            @php
                                                            $salessp += $sales->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            {{$sales->planexgstamount}}
                                                            @php
                                                            $salemaf += $sales->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($sales->actualcomission != "" || $sales->actualcomission != "0.00")
                                                                {{$sales->actualcomission}}
                                                                @php
                                                                $salecomission += $sales->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$sales->plancomission}}
                                                                @php
                                                                $salecomission += $sales->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @foreach($getrefundconnection2 as $refund)
                                                    <tr style="background-color: #f1525247;">
                                                        <td>{{$refund->store_code}}<br>{{$refund->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$refund->orderID}}" style="color: #007bff;">{{$refund->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$refund->customerfirstname}} {{$refund->customerlastname}}
                                                        </td>
                                                        <td>{{$refund->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <td>
                                                            {{$refund->customertype}}
                                                        </td>
                                                        <td>{{$refund->plantypename}}</td>
                                                        <td>{{$refund->plantermname}}</td>
                                                        <td>{{$refund->plancode}}</td>
                                                        <td>{{$refund->planname}}</td>
                                                        <td>{{$refund->planpropositionname}}</td>
                                                        <td>{{$refund->pcname}}</td>
                                                        <td>{{$refund->planhandsettermname}}</td>
                                                        <td>{{$refund->barcode}}</td>
                                                        <td>{{$refund->stockcode}}</td>
                                                        <td>{{$refund->productname}}</td>
                                                        <td>{{$refund->suppliername}}</td>
                                                        <td>{{$refund->productimei}}</td>
                                                        <td>{{$refund->quantity}}</td>
                                                        <td>
                                                            {{$refund->ppingst}}
                                                            @php
                                                            $refundsp += $refund->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            -{{$refund->planexgstamount}}
                                                            @php
                                                            $refundmaf += $refund->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($refund->actualcomission != "" || $refund->actualcomission != "0.00")
                                                                {{$refund->actualcomission}}
                                                                @php
                                                                $refundcomission += $refund->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$refund->plancomission}}
                                                                @php
                                                                $refundcomission += $refund->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <th></th>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                {{$salessp - $refundsp}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                @if($getconnection->count() != 0)
                                                                    @php
                                                                    $salemaf = $salemaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                @if($getrefundconnection->count() != 0)
                                                                    @php
                                                                    $refundmaf = $refundmaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                {{round($salemaf - $refundmaf, 2)}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">{{$salecomission - $refundcomission}}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-3" id="messages" role="tabpanel">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="datatable-buttons2" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Store</th>
                                                        <th>Date</th>
                                                        <th>Sale Invoice</th>
                                                        <th data-priority="1">Customer</th>
                                                        <th>Order Number</th>
                                                        <th>Order Date</th>
                                                        <th data-priority="1">Customer Type</th>
                                                        <th>Plan Type</th>
                                                        <th>Plan Term</th>
                                                        <th>Plan Code</th>
                                                        <th>Plan Name</th>
                                                        <th>Plan Proposition</th>
                                                        <th>Plan Category</th>
                                                        <th>Plan Handset Term</th>
                                                        <th data-priority="1">Barcode</th>
                                                        <th data-priority="1">Stock Code</th>
                                                        <th data-priority="1">Product Name</th>
                                                        <th>Product Supplier</th>
                                                        <th data-priority="1">Device</th>
                                                        <th data-priority="1">Quantity</th>
                                                        <th data-priority="1">PP (Inc. GST)</th>
                                                        <th>MAF</th>
                                                        <th>Comission <br>(Ex. GST)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $salessp = 0;
                                                    $refundsp = 0;
                                                    $salemaf= 0;
                                                    $salecomission = 0;
                                                    $refundmaf = 0;
                                                    $refundcomission= 0;
                                                    @endphp
                                                    @foreach($getconnection3 as $sales)
                                                    <tr>
                                                        <td>{{$sales->store_code}}<br>{{$sales->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$sales->customerfirstname}} {{$sales->customerlastname}}
                                                        </td>
                                                        <td>{{$sales->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <td>
                                                            {{$sales->customertype}}
                                                        </td>
                                                        <td>{{$sales->plantypename}}</td>
                                                        <td>{{$sales->plantermname}}</td>
                                                        <td>{{$sales->plancode}}</td>
                                                        <td>{{$sales->planname}}</td>
                                                        <td>{{$sales->planpropositionname}}</td>
                                                        <td>{{$sales->pcname}}</td>
                                                        <td>{{$sales->planhandsettermname}}</td>
                                                        <td>{{$sales->barcode}}</td>
                                                        <td>{{$sales->stockcode}}</td>
                                                        <td>{{$sales->productname}}</td>
                                                        <td>{{$sales->suppliername}}</td>
                                                        <td>{{$sales->productimei}}</td>
                                                        <td>{{$sales->quantity}}</td>
                                                        <td>
                                                            {{$sales->ppingst}}
                                                            @php
                                                            $salessp += $sales->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            {{$sales->planexgstamount}}
                                                            @php
                                                            $salemaf += $sales->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($sales->actualcomission != "" || $sales->actualcomission != "0.00")
                                                                {{$sales->actualcomission}}
                                                                @php
                                                                $salecomission += $sales->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$sales->plancomission}}
                                                                @php
                                                                $salecomission += $sales->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @foreach($getrefundconnection3 as $refund)
                                                    <tr style="background-color: #f1525247;">
                                                        <td>{{$refund->store_code}}<br>{{$refund->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$refund->orderID}}" style="color: #007bff;">{{$refund->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$refund->customerfirstname}} {{$refund->customerlastname}}
                                                        </td>
                                                        <td>{{$refund->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <td>
                                                            {{$refund->customertype}}
                                                        </td>
                                                        <td>{{$refund->plantypename}}</td>
                                                        <td>{{$refund->plantermname}}</td>
                                                        <td>{{$refund->plancode}}</td>
                                                        <td>{{$refund->planname}}</td>
                                                        <td>{{$refund->planpropositionname}}</td>
                                                        <td>{{$refund->pcname}}</td>
                                                        <td>{{$refund->planhandsettermname}}</td>
                                                        <td>{{$refund->barcode}}</td>
                                                        <td>{{$refund->stockcode}}</td>
                                                        <td>{{$refund->productname}}</td>
                                                        <td>{{$refund->suppliername}}</td>
                                                        <td>{{$refund->productimei}}</td>
                                                        <td>{{$refund->quantity}}</td>
                                                        <td>
                                                            {{$refund->ppingst}}
                                                            @php
                                                            $refundsp += $refund->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            -{{$refund->planexgstamount}}
                                                            @php
                                                            $refundmaf += $refund->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($refund->actualcomission != "" || $refund->actualcomission != "0.00")
                                                                {{$refund->actualcomission}}
                                                                @php
                                                                $refundcomission += $refund->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$refund->plancomission}}
                                                                @php
                                                                $refundcomission += $refund->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <th></th>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                {{$salessp - $refundsp}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                @if($getconnection->count() != 0)
                                                                    @php
                                                                    $salemaf = $salemaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                @if($getrefundconnection->count() != 0)
                                                                    @php
                                                                    $refundmaf = $refundmaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                {{round($salemaf - $refundmaf, 2)}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">{{$salecomission - $refundcomission}}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-3" id="settings" role="tabpanel">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="datatable-buttons3" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Store</th>
                                                        <th>Date</th>
                                                        <th>Sale Invoice</th>
                                                        <th data-priority="1">Customer</th>
                                                        <th>Order Number</th>
                                                        <th>Order Date</th>
                                                        <th data-priority="1">Customer Type</th>
                                                        <th>Plan Type</th>
                                                        <th>Plan Term</th>
                                                        <th>Plan Code</th>
                                                        <th>Plan Name</th>
                                                        <th>Plan Proposition</th>
                                                        <th>Plan Category</th>
                                                        <th>Plan Handset Term</th>
                                                        <th data-priority="1">Barcode</th>
                                                        <th data-priority="1">Stock Code</th>
                                                        <th data-priority="1">Product Name</th>
                                                        <th>Product Supplier</th>
                                                        <th data-priority="1">Device</th>
                                                        <th data-priority="1">Quantity</th>
                                                        <th data-priority="1">PP (Inc. GST)</th>
                                                        <th>MAF</th>
                                                        <th>Comission <br>(Ex. GST)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $salessp = 0;
                                                    $refundsp = 0;
                                                    $salemaf= 0;
                                                    $salecomission = 0;
                                                    $refundmaf = 0;
                                                    $refundcomission= 0;
                                                    @endphp
                                                    @foreach($getconnection4 as $sales)
                                                    <tr>
                                                        <td>{{$sales->store_code}}<br>{{$sales->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$sales->customerfirstname}} {{$sales->customerlastname}}
                                                        </td>
                                                        <td>{{$sales->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <td>
                                                            {{$sales->customertype}}
                                                        </td>
                                                        <td>{{$sales->plantypename}}</td>
                                                        <td>{{$sales->plantermname}}</td>
                                                        <td>{{$sales->plancode}}</td>
                                                        <td>{{$sales->planname}}</td>
                                                        <td>{{$sales->planpropositionname}}</td>
                                                        <td>{{$sales->pcname}}</td>
                                                        <td>{{$sales->planhandsettermname}}</td>
                                                        <td>{{$sales->barcode}}</td>
                                                        <td>{{$sales->stockcode}}</td>
                                                        <td>{{$sales->productname}}</td>
                                                        <td>{{$sales->suppliername}}</td>
                                                        <td>{{$sales->productimei}}</td>
                                                        <td>{{$sales->quantity}}</td>
                                                        <td>
                                                            {{$sales->ppingst}}
                                                            @php
                                                            $salessp += $sales->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            {{$sales->planexgstamount}}
                                                            @php
                                                            $salemaf += $sales->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($sales->actualcomission != "" || $sales->actualcomission != "0.00")
                                                                {{$sales->actualcomission}}
                                                                @php
                                                                $salecomission += $sales->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$sales->plancomission}}
                                                                @php
                                                                $salecomission += $sales->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @foreach($getrefundconnection4 as $refund)
                                                    <tr style="background-color: #f1525247;">
                                                        <td>{{$refund->store_code}}<br>{{$refund->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$refund->orderID}}" style="color: #007bff;">{{$refund->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$refund->customerfirstname}} {{$refund->customerlastname}}
                                                        </td>
                                                        <td>{{$refund->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <td>
                                                            {{$refund->customertype}}
                                                        </td>
                                                        <td>{{$refund->plantypename}}</td>
                                                        <td>{{$refund->plantermname}}</td>
                                                        <td>{{$refund->plancode}}</td>
                                                        <td>{{$refund->planname}}</td>
                                                        <td>{{$refund->planpropositionname}}</td>
                                                        <td>{{$refund->pcname}}</td>
                                                        <td>{{$refund->planhandsettermname}}</td>
                                                        <td>{{$refund->barcode}}</td>
                                                        <td>{{$refund->stockcode}}</td>
                                                        <td>{{$refund->productname}}</td>
                                                        <td>{{$refund->suppliername}}</td>
                                                        <td>{{$refund->productimei}}</td>
                                                        <td>{{$refund->quantity}}</td>
                                                        <td>
                                                            {{$refund->ppingst}}
                                                            @php
                                                            $refundsp += $refund->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            -{{$refund->planexgstamount}}
                                                            @php
                                                            $refundmaf += $refund->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($refund->actualcomission != "" || $refund->actualcomission != "0.00")
                                                                {{$refund->actualcomission}}
                                                                @php
                                                                $refundcomission += $refund->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$refund->plancomission}}
                                                                @php
                                                                $refundcomission += $refund->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <th></th>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                {{$salessp - $refundsp}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                @if($getconnection->count() != 0)
                                                                    @php
                                                                    $salemaf = $salemaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                @if($getrefundconnection->count() != 0)
                                                                    @php
                                                                    $refundmaf = $refundmaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                {{round($salemaf - $refundmaf, 2)}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">{{$salecomission - $refundcomission}}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-3" id="wearable" role="tabpanel">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="datatable-buttons4" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Store</th>
                                                        <th>Date</th>
                                                        <th>Sale Invoice</th>
                                                        <th data-priority="1">Customer</th>
                                                        <th>Order Number</th>
                                                        <th>Order Date</th>
                                                        <th data-priority="1">Customer Type</th>
                                                        <th>Plan Type</th>
                                                        <th>Plan Term</th>
                                                        <th>Plan Code</th>
                                                        <th>Plan Name</th>
                                                        <th>Plan Proposition</th>
                                                        <th>Plan Category</th>
                                                        <th>Plan Handset Term</th>
                                                        <th data-priority="1">Barcode</th>
                                                        <th data-priority="1">Stock Code</th>
                                                        <th data-priority="1">Product Name</th>
                                                        <th>Product Supplier</th>
                                                        <th data-priority="1">Device</th>
                                                        <th data-priority="1">Quantity</th>
                                                        <th data-priority="1">PP (Inc. GST)</th>
                                                        <th>MAF</th>
                                                        <th>Comission <br>(Ex. GST)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $salessp = 0;
                                                    $refundsp = 0;
                                                    $salemaf= 0;
                                                    $salecomission = 0;
                                                    $refundmaf = 0;
                                                    $refundcomission= 0;
                                                    @endphp
                                                    @foreach($getconnection5 as $sales)
                                                    <tr>
                                                        <td>{{$sales->store_code}}<br>{{$sales->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$sales->customerfirstname}} {{$sales->customerlastname}}
                                                        </td>
                                                        <td>{{$sales->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                        <td>
                                                            {{$sales->customertype}}
                                                        </td>
                                                        <td>{{$sales->plantypename}}</td>
                                                        <td>{{$sales->plantermname}}</td>
                                                        <td>{{$sales->plancode}}</td>
                                                        <td>{{$sales->planname}}</td>
                                                        <td>{{$sales->planpropositionname}}</td>
                                                        <td>{{$sales->pcname}}</td>
                                                        <td>{{$sales->planhandsettermname}}</td>
                                                        <td>{{$sales->barcode}}</td>
                                                        <td>{{$sales->stockcode}}</td>
                                                        <td>{{$sales->productname}}</td>
                                                        <td>{{$sales->suppliername}}</td>
                                                        <td>{{$sales->productimei}}</td>
                                                        <td>{{$sales->quantity}}</td>
                                                        <td>
                                                            {{$sales->ppingst}}
                                                            @php
                                                            $salessp += $sales->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            {{$sales->planexgstamount}}
                                                            @php
                                                            $salemaf += $sales->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($sales->actualcomission != "" || $sales->actualcomission != "0.00")
                                                                {{$sales->actualcomission}}
                                                                @php
                                                                $salecomission += $sales->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$sales->plancomission}}
                                                                @php
                                                                $salecomission += $sales->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @foreach($getrefundconnection5 as $refund)
                                                    <tr style="background-color: #f1525247;">
                                                        <td>{{$refund->store_code}}<br>{{$refund->store_name}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <th>
                                                            <a href="sale/{{$refund->orderID}}" style="color: #007bff;">{{$refund->orderID}}</a>
                                                        </th>
                                                        <td>
                                                            {{$refund->customerfirstname}} {{$refund->customerlastname}}
                                                        </td>
                                                        <td>{{$refund->planOrderID}}</td>
                                                        <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                        <td>
                                                            {{$refund->customertype}}
                                                        </td>
                                                        <td>{{$refund->plantypename}}</td>
                                                        <td>{{$refund->plantermname}}</td>
                                                        <td>{{$refund->plancode}}</td>
                                                        <td>{{$refund->planname}}</td>
                                                        <td>{{$refund->planpropositionname}}</td>
                                                        <td>{{$refund->pcname}}</td>
                                                        <td>{{$refund->planhandsettermname}}</td>
                                                        <td>{{$refund->barcode}}</td>
                                                        <td>{{$refund->stockcode}}</td>
                                                        <td>{{$refund->productname}}</td>
                                                        <td>{{$refund->suppliername}}</td>
                                                        <td>{{$refund->productimei}}</td>
                                                        <td>{{$refund->quantity}}</td>
                                                        <td>
                                                            {{$refund->ppingst}}
                                                            @php
                                                            $refundsp += $refund->ppingst;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            -{{$refund->planexgstamount}}
                                                            @php
                                                            $refundmaf += $refund->planexgstamount;
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if($refund->actualcomission != "" || $refund->actualcomission != "0.00")
                                                                {{$refund->actualcomission}}
                                                                @php
                                                                $refundcomission += $refund->actualcomission;
                                                                @endphp
                                                            @else
                                                                {{$refund->plancomission}}
                                                                @php
                                                                $refundcomission += $refund->plancomission;
                                                                @endphp
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <th></th>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                {{$salessp - $refundsp}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">
                                                                @if($getconnection->count() != 0)
                                                                    @php
                                                                    $salemaf = $salemaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                @if($getrefundconnection->count() != 0)
                                                                    @php
                                                                    $refundmaf = $refundmaf / $getconnection->count();
                                                                    @endphp
                                                                @endif
                                                                {{round($salemaf - $refundmaf, 2)}}
                                                            </td>
                                                            <td style="font-size: 1.2em; font-weight: 600;">{{$salecomission - $refundcomission}}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
            </div>
            <!-- container-fluid -->

        </div>
        <!-- content -->

        @include('includes.footer')
        <!-- <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script> -->
        <!-- <script type="text/javascript">
            $(document).ready(function() {
                $('#datatable').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'pdf',
                            text : 'Export PDF',
                            title: 'DevicesInstock',
                            exportOptions: {
                                columns: [0,2,3,4,5,6]
                            },
                        },
                        {
                            extend: 'excel',
                            text : 'Export Excel',
                            title: 'DevicesInstock',
                        }
                    ]
                } );
            } );
        </script> -->
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontdetailedreport',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons1').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontnew',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons1_wrapper .col-md-6:eq(0)');
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons2').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontupgrade',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons2_wrapper .col-md-6:eq(0)');
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons3').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontnbn',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons3_wrapper .col-md-6:eq(0)');
            } );
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                //Buttons examples
                var table = $('#datatable-buttons4').DataTable({
                    lengthChange: true,
                    /*buttons: ['excel', 'pdf']*/
                    "scrollY": "500px",
                    "scrollX": "100%",
                    "scrollCollapse": true,
                    "paging": false,
                    "order": [[ 0, "desc" ]],
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'upfrontwearable',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons4_wrapper .col-md-6:eq(0)');
            } );
        </script>
    </div>
</div>
@endsection