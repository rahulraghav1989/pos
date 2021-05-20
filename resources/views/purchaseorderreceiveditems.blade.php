@extends('main')

@section('content')
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
                            <h4 class="page-title">Receive Purchase Order: #{{$allpurchaseorderitem[0]->ponumber}}</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stexo</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Purchase Order</a></li>
                                <li class="breadcrumb-item active">#{{$allpurchaseorderitem[0]->ponumber}}</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-30">
                            @if(session()->has('poreceivsuccess'))
                            <div class="card-body">
                                <div class="alert alert-success" role="alert" style="margin-top: 10px;">
                                    {{ session()->get('poreceivsuccess') }}
                                </div>
                            </div>
                            @endif
                            @if(session()->has('poreceiverror'))
                            <div class="card-body">
                                <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                    {{ session()->get('poreceiverror') }}
                                </div>
                            </div>
                            @endif
                            <div class="card-body">
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
                                <script>
                                    $(document).ready(function () {
                                        $(".submit").click(function () {

                                            var books = $('#docketnumber');
                                            if (books.val() === '') {
                                                $('#docketnumber').focus();

                                                return books;
                                            }
                                            else 
                                                $('.docketnumber').val(books.val());
                                                return books;
                                        });
                                    });
                                </script>
                                <div class="form-group">
                                    <label>Docket (Consignment Number)</label>
                                    <input type="text" id="docketnumber" placeholder="Type Here" class="form-control" value="{{$allpurchaseorderitem[0]->docketnumber}}" onkeyup="sync()">
                                </div>
                                <table id="datatable2" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Received Quantity</th>
                                        @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
                                        <th>PP<br>(Ex. GST)</th>
                                        <th>PP<br>(Inc. GST)</th>
                                        @endif
                                        <th>Status</th>
                                        @if(session('loggindata')['loggeduserpermission']->receivepurchaseorder=='Y')
                                        
                                        <th>
                                            Action
                                        </th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allpurchaseorderitem as $allpoitem)
                                        <tr>
                                            <td>
                                                {{$allpoitem->productname}}
                                            </td>
                                            <td>{{$allpoitem->poquantity}}</td>
                                            <td>{{$allpoitem->receivequantity}}</td>
                                            @if(session('loggindata')['loggeduserpermission']->viewpurchaseorderprice=='Y')
                                            <td>{{$allpoitem->popurchaseprice}}</td>
                                            <td>{{$allpoitem->poppingst}}</td>
                                            @endif
                                            <td>
                                                @if($allpoitem->poitemstatus=='0')
                                                Pending
                                                @elseif($allpoitem->poitemstatus=='1')
                                                Parital Received
                                                @elseif($allpoitem->poitemstatus=='2')
                                                Complete Received
                                                @endif
                                            </td>
                                            @if(session('loggindata')['loggeduserpermission']->receivepurchaseorder=='Y')
                                                <td>
                                                    @if($allpoitem->poitemstatus=='2')
                                                    Stock Received
                                                    @else
                                                    <form action="{{route('poreceivestep1')}}" method="post">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label>Recevied Quantity *</label>
                                                            <input type="text" name="receivedquantity" placeholder="Type Here" required="" class="form-control" style="width: 130px;">
                                                            <input type="hidden" name="docketnumber" placeholder="Type Here" class="form-control docketnumber">
                                                            <input type="hidden" name="poitemid" placeholder="Type Here" required="" class="form-control" value="{{$allpoitem->poitemID}}">
                                                            <input type="hidden" name="storeid" placeholder="Type Here" required="" class="form-control" value="{{$allpurchaseorderitem[0]->storeID}}">
                                                        </div>
                                                        <button type="submit" id="" class="btn btn-outline-success waves-effect waves-light submit">Receive</button>
                                                    </form>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div> <!-- end row -->      

                
            </div>
            <!-- container-fluid -->

        </div>
        @include('includes.footer')
        <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    </div>
</div>
@endsection