@extends('main')

@section('content')
<div id="wrapper">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
    <style type="text/css">
        .chosen-container{
            width: 100% !important;
            margin-bottom: 20px;
        }
        .chosen-choices{
            display: block !important;
            height: auto !important;
            padding: .375rem .75rem !important;
            font-size: 1rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            color: #495057 !important;
            background-color: #000 !important;
            background-clip: padding-box !important;
            border: 1px solid #ced4da !important;
            border-radius: .25rem !important;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    </style>
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
    
    
    @include('includes.topbar')

    @include('includes.sidebar')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Sales By User</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales Report</a></li>
                                <li class="breadcrumb-item active">Sales By User</li>
                            </ol>
                        </div>
                    </div> <!-- end row -->
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-30">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="text-right">
                                                        <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                            Filters
                                                        </a>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="collapse" id="collapseExample">
                                                            <div class="card card-body mt-3 mb-0">
                                                                <form method="post" action="{{route('salesbyuser')}}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        @if(session('loggindata')['loggeduserpermission']->viewreportsalesbyuserfilter=='Y')
                                                                        <div class="col-md-3">
                                                                            <select data-placeholder="SELECT STORES" multiple class="chosen-select" name="" style="width: 100%;">
                                                                                @foreach($allstore as $allstores)
                                                                                    @if(!empty($storeID))
                                                                                        @foreach($storeID as $selectedstoreid)
                                                                                            <option value="{{$allstores->store_id}}" @if($allstores->store_id == $selectedstoreid) SELECTED='SELECTED' @endif>{{$allstores->store_name}}</option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="{{$allstores->store_id}}">{{$allstores->store_name}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select data-placeholder="SELECT USERS" multiple class="chosen-select" name="user[]" style="width: 100%;">
                                                                                @foreach($alluser as $allusers)
                                                                                    @if(!empty($userID))
                                                                                        @foreach($userID as $selecteduserid)
                                                                                            <option value="{{$allusers->id}}" @if($allusers->id == $selecteduserid) SELECTED='SELECTED' @endif>{{$allusers->name}}</option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="{{$allusers->id}}">{{$allusers->name}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select data-placeholder="SELECT SUPPLIERS" multiple class="chosen-select" name="supplier[]" style="width: 100%;">
                                                                                @foreach($allsupplier as $supplier)
                                                                                    @if(!empty($suppliers))
                                                                                        @foreach($suppliers as $selectedsupplier)
                                                                                            <option value="{{$supplier->supplierID}}" @if($supplier->supplierID == $selectedsupplier) SELECTED='SELECTED' @endif>{{$supplier->suppliername}}</option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="{{$supplier->supplierID}}">{{$supplier->suppliername}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select data-placeholder="SELECT CATEGORIES" multiple class="chosen-select" name="category[]" style="width: 100%;">
                                                                                @foreach($allcategory as $category)
                                                                                    @if(!empty($categorys))
                                                                                        @foreach($categorys as $selectedcategories)
                                                                                            <option value="{{$category->categoryID}}" @if($category->categoryID == $selectedcategories) SELECTED='SELECTED' @endif>{{$category->categoryname}}</option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="{{$category->categoryID}}">{{$category->categoryname}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select data-placeholder="SELECT BRANDS" multiple class="chosen-select" name="brand[]" style="width: 100%;">
                                                                                @foreach($allbrand as $brand)
                                                                                    @if(!empty($brands))
                                                                                        @foreach($brands as $selectedbrands)
                                                                                            <option value="{{$brand->brandID}}" @if($brand->brandID == $selectedbrands) SELECTED='SELECTED' @endif>{{$brand->brandname}}</option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="{{$brand->brandID}}">{{$brand->brandname}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select data-placeholder="SELECT COLOURS" multiple class="chosen-select" name="colour[]" style="width: 100%;">
                                                                                @foreach($allcolour as $colour)
                                                                                    @if(!empty($colours))
                                                                                        @foreach($colours as $selectedcolours)
                                                                                            <option value="{{$colour->colourID}}" @if($colour->colourID == $selectedcolours) SELECTED='SELECTED' @endif>{{$colour->colourname}}</option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="{{$colour->colourID}}">{{$colour->colourname}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select data-placeholder="SELECT MODELS" multiple class="chosen-select" name="model[]" style="width: 100%;">
                                                                                @foreach($allmodel as $model)
                                                                                    @if(!empty($models))
                                                                                        @foreach($models as $selectedmodels)
                                                                                            <option value="{{$model->modelID}}" @if($model->modelID == $selectedmodels) SELECTED='SELECTED' @endif>{{$model->modelname}}</option>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <option value="{{$model->modelID}}">{{$model->modelname}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        @endif
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <div>
                                                                                    <div class="input-daterange input-group" id="date-range">
                                                                                        <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($firstday)) @endphp" />
                                                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($lastday)) @endphp" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 text-right">
                                                                            <input type="hidden" name="appliedfilter" value="yes">
                                                                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="datatable-buttons" class="table table-striped" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Supplier</th>
                                                <th>Category</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Colour</th>
                                                <th>RRP</th>
                                                <th>Discount per Qty</th>
                                                <th>Sale Price</th>
                                                <th>Quantity</th>
                                                <th>Total Discount</th>
                                                <th>Total Sale Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                            $totalrrp = 0;
                                            $totalsp = 0;
                                            $totalsubtotal = 0;
                                            $reftotalrrp = 0;
                                            $reftotalsp = 0;
                                            $reftotalsubtotal = 0;
                                            @endphp
                                            @foreach($getusersales as $usersales)
                                                <tr>
                                                    <td>@php echo date('d-m-Y H:i:s', strtotime($usersales->created_at)) @endphp</td>
                                                    <td><a href="sale/{{$usersales->orderID}}" style="color: #007bff;"> {{$usersales->orderID}} </a></td>
                                                    <td>{{$usersales->barcode}}</td>
                                                    <td>{{$usersales->productname}}</td>
                                                    <td>{{$usersales->suppliername}}</td>
                                                    <td>
                                                        @if($usersales->subcategoryname != '')
                                                        {{$usersales->subcategoryname}}
                                                        @else
                                                        {{$usersales->categoryname}}
                                                        @endif
                                                    </td>
                                                    <td>{{$usersales->brandname}}</td>
                                                    <td>{{$usersales->modelname}}</td>
                                                    <td>{{$usersales->colourname}}</td>
                                                    <td>
                                                        {{$usersales->spingst}}
                                                        @php
                                                        $totalrrp += $usersales->spingst;
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @if($usersales->discountedAmount != "")
                                                        {{$usersales->discountedAmount / $usersales->quantity}}
                                                        @else
                                                        0.00
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$usersales->salePrice}}
                                                        @php
                                                        $totalsp += $usersales->salePrice;
                                                        @endphp
                                                    </td>
                                                    <td>{{$usersales->quantity}}</td>
                                                    <td>
                                                        @if($usersales->discountedAmount != "")
                                                        {{$usersales->discountedAmount}}
                                                        @else
                                                        0.00
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$usersales->subTotal}}
                                                        @php
                                                        $totalsubtotal += $usersales->subTotal;
                                                        @endphp
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @foreach($getuserrefund as $userrefund)
                                                <tr style="background-color: #f1525247;">
                                                    <td>@php echo date('d-m-Y H:i:s', strtotime($userrefund->created_at)) @endphp</td>
                                                    <td><a href="refundinvoice/{{$userrefund->refundInvoiceID}}" style="color: #007bff;"> {{$userrefund->refundInvoiceID}} </a></td>
                                                    <td>{{$userrefund->barcode}}</td>
                                                    <td>{{$userrefund->productname}}</td>
                                                    <td>{{$userrefund->suppliername}}</td>
                                                    <td>
                                                        @if($userrefund->subcategoryname != '')
                                                        {{$userrefund->subcategoryname}}
                                                        @else
                                                        {{$userrefund->categoryname}}
                                                        @endif
                                                    </td>
                                                    <td>{{$userrefund->brandname}}</td>
                                                    <td>{{$userrefund->modelname}}</td>
                                                    <td>{{$userrefund->colourname}}</td>
                                                    <td>
                                                        -{{$userrefund->spingst}}
                                                        @php
                                                        $reftotalrrp += $userrefund->spingst;
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @if($userrefund->discountedAmount != "")
                                                        {{$userrefund->discountedAmount / $userrefund->quantity}}
                                                        @else
                                                        0.00
                                                        @endif
                                                    </td>
                                                    <td>
                                                        -{{$userrefund->salePrice}}
                                                        @php
                                                        $reftotalsp += $userrefund->salePrice;
                                                        @endphp
                                                    </td>
                                                    <td>{{$userrefund->quantity}}</td>
                                                    <td>
                                                        @if($userrefund->discountedAmount != "")
                                                        {{$userrefund->discountedAmount}}
                                                        @else
                                                        0.00
                                                        @endif
                                                    </td>
                                                    <td>
                                                        -{{$userrefund->subTotal}}
                                                        @php
                                                        $reftotalsubtotal += $userrefund->subTotal;
                                                        @endphp
                                                    </td>
                                                </tr>
                                            @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Total</b></td>
                                                    <td>{{$totalrrp - $reftotalrrp}}</td>
                                                    <td></td>
                                                    <td>{{$totalsp - $reftotalsp}}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{$totalsubtotal - $reftotalsubtotal}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
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
        
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>

        <script src="{{ asset('posview') }}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="{{ asset('posview') }}/assets/pages/form-advanced.js"></script>
        <!-- Responsive-table-->
        <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>
        <!-----multi select-->
        <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
        
        <script type="text/javascript">
            $(".chosen-select").chosen({
              no_results_text: "Oops, nothing found!"
            })
        </script>


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
                                title: 'Report Sales By User ',
                            }
                        ]
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            } );
        </script>
    </div>
</div>
@endsection