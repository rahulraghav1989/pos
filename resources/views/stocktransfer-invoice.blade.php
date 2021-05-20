@extends('main')

@section('content')
<div id="wrapper">
    @include('includes.topbar')

    @include('includes.sidebar')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Outgoing Stock Transfer </h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Products / Plans</a></li>
                                <li class="breadcrumb-item active">Stock Transfer</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 header-title"># {{$invoicestocktransferdata['checktransfer']->stocktransferID}}</h4>
                            </div>
                            <div class="card-body">
                                <table id="" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoicestocktransferdata['transferitem'] as $items)
                                    <tr>
                                        <td>{{$items->barcode}}</td>
                                        <td>
                                            {{$items->productname}}
                                            @if($items->productimei !='')
                                            <br>
                                            {{$items->productimei}}
                                            @endif
                                            @if($items->simnumber !='')
                                            <br>
                                            Sim: {{$items->simnumber}}
                                            @endif
                                            @if($items->colour !='')
                                            <br>
                                            {{$items->colourname}}
                                            @endif
                                            @if($items->model !='')
                                            <br>
                                            {{$items->modelname}}
                                            @endif
                                            @if($items->brand !='')
                                            <br>
                                            {{$items->brandname}}
                                            @endif
                                        </td>
                                        <td>{{$items->quantity}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-3">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Transfer From</label>
                                    <div>
                                        {{$invoicestocktransferdata['checktransfer']['fromstore']->store_name}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Transfer To</label>
                                    <div>
                                        {{$invoicestocktransferdata['checktransfer']->store_name}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Received By</label>
                                    <div>
                                        {{$invoicestocktransferdata['checktransfer']->name}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Received Date</label>
                                    <div>
                                        {{$invoicestocktransferdata['checktransfer']->receivetrasnsferDate}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    @csrf
                                    <input type="hidden" id="stocktransferid" value="{{$invoicestocktransferdata['checktransfer']}}">
                                    <label>Consignment Number (Opt.)</label>
                                    <div>
                                        {{$invoicestocktransferdata['checktransfer']->consignmentnumber}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Transfer Note (Opt.)</label>
                                    <div>
                                        {{$invoicestocktransferdata['checktransfer']->transfernote}}
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
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>
    </div>
</div>
@endsection