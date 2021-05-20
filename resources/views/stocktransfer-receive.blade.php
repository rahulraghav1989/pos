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
                            <h4 class="page-title">Stock Transfer</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Transfer</a></li>
                                <li class="breadcrumb-item active">Stock Transfer Receive</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->
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
                    <div class="col-lg-9">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <h4 class="mt-0 header-title"># {{$createstocktransferdata['checktransfer']->stocktransferID}}</h4>
                                
                            </div>
                            <div class="card-body">
                                <table id="" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($createstocktransferdata['transferitem'] as $items)
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
                                        <td>
                                            @if($items->receiveStatus == '')
                                            <form action="{{route('receivestocktransfer')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="transferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                                <input type="hidden" name="productid" value="{{$items->productID}}">
                                                <input type="hidden" name="stockid" value="{{$items->stockID}}">
                                                <input type="hidden" name="quantity" value="{{$items->quantity}}">
                                                <input type="hidden" name="imei" value="{{$items->productimei}}">
                                                <input type="hidden" name="acceptstore" value="{{$createstocktransferdata['checktransfer']->toStoreID}}">
                                                <button type="submit" class="btn btn-primary">Receive</button>
                                            </form>
                                            @elseif($items->receiveStatus == '1')
                                            Received
                                            @endif
                                        </td>
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
                                        {{$createstocktransferdata['checktransfer']->store_name}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Transfer To</label>
                                    <div>
                                        {{App\store::where('store_id', $createstocktransferdata['checktransfer']->toStoreID)->pluck('store_name')->first()}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    @csrf
                                    <input type="hidden" id="stocktransferid" value="{{$createstocktransferdata['checktransfer']->stocktransferID}}">
                                    <label>Consignment Number</label>
                                    <div>
                                        {{$createstocktransferdata['checktransfer']->consignmentnumber}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Transfer Note</label>
                                    <div>
                                        {{$createstocktransferdata['checktransfer']->transfernote}}
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