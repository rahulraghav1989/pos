@extends('main')

@section('content')
<div id="wrapper">
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('posview') }}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ asset('posview') }}/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    
    <link href="{{ asset('posview') }}/multipleselector/styles/multiselect.css" rel="stylesheet" />
    <script src="{{ asset('posview') }}/multipleselector/scripts/multiselect.min.js"></script>
    @include('includes.topbar')

    @include('includes.sidebar')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="page-title">Sales Master Report</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Sales</a></li>
                                <li class="breadcrumb-item active">Sales Master Report</li>
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
                                                                <form method="post" action="{{route('salesmasterreport')}}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        @if(session('loggindata')['loggeduserpermission']->viewreportsalesmasterfilter=='Y')
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Store</label>
                                                                                <select id="testSelect2" name="store[]" multiple>
                                                                                    @foreach($masterdata['allstore'] as $allstore)
                                                                                        @if($masterdata['storeID']!='')
                                                                                            <option value="{{$allstore->store_id}}" @foreach($masterdata['storeID'] as $selectedstoreid) @if($allstore->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstore->store_name}}</option>
                                                                                        @else
                                                                                            <option value="{{$allstore->store_id}}">{{$allstore->store_name}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect2')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select User</label>
                                                                                <select id="testSelect1" name="user[]" multiple>
                                                                                    @foreach($masterdata['allusers'] as $allusers)
                                                                                        @if($masterdata['userID']!="")
                                                                                            <option value="{{$allusers->id}}" @foreach($masterdata['userID'] as $selecteduserid) @if($allusers->id == $selecteduserid) SELECTED='SELECTED' @endif @endforeach>{{$allusers->name}}</option>
                                                                                        @else
                                                                                            <option value="{{$allusers->id}}">{{$allusers->name}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect1')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Supplier</label>
                                                                                <select id="testSelect3" name="supplier[]" multiple>
                                                                                    @foreach($masterdata['allsupplier'] as $allsupplier)
                                                                                        @if($masterdata['supplier']!='')
                                                                                            <option value="{{$allsupplier->supplierID}}" @foreach($masterdata['supplier'] as $selectedsupplier) @if($allsupplier->supplierID == $selectedsupplier) SELECTED='SELECTED' @endif @endforeach>{{$allsupplier->suppliername}}</option>
                                                                                        @else
                                                                                            <option value="{{$allsupplier->supplierID}}">{{$allsupplier->suppliername}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect3')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Category</label>
                                                                                <select id="testSelect4" name="category[]" multiple>
                                                                                    @foreach($masterdata['allcategory'] as $allcategory)
                                                                                        @if($masterdata['category']!='')
                                                                                            <option value="{{$allcategory->categoryID}}" @foreach($masterdata['category'] as $selectedcategory) @if($allcategory->categoryID == $selectedcategory) SELECTED='SELECTED' @endif @endforeach>{{$allcategory->categoryname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allcategory->categoryID}}">{{$allcategory->categoryname}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect4')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>Select Sub Category</label>
                                                                            <select id="testSelect8" multiple name="subcategory[]">
                                                                                @foreach($masterdata['allsubcategory'] as $subcategory)
                                                                                    @if(!empty($masterdata['subcategorys']))
                                                                                        <option value="{{$subcategory->subcategoryID}}" @foreach($masterdata['subcategorys'] as $selectedsubcategories) @if($subcategory->subcategoryID == $selectedsubcategories) SELECTED='SELECTED' @endif @endforeach>{{$subcategory->subcategoryname}}</option>
                                                                                    @else
                                                                                        <option value="{{$subcategory->subcategoryID}}">{{$subcategory->subcategoryname}}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                document.multiselect('#testSelect8')
                                                                                    .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                        console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                    })
                                                                                    .setCheckBoxClick("1", function(target, args) {
                                                                                        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                    });
                                                                            </script>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Brand</label>
                                                                                <select id="testSelect5" name="brand[]" multiple>
                                                                                    @foreach($masterdata['allbrand'] as $allbrand)
                                                                                        @if($masterdata['brand']!='')
                                                                                            <option value="{{$allbrand->brandID}}" @foreach($masterdata['brand'] as $selectedbrand) @if($allbrand->brandID == $selectedbrand) SELECTED='SELECTED' @endif @endforeach>{{$allbrand->brandname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allbrand->brandID}}">{{$allbrand->brandname}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect5')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Model</label>
                                                                                <select id="testSelect6" name="model[]" multiple>
                                                                                    @foreach($masterdata['allmodel'] as $allmodel)
                                                                                        @if($masterdata['model']!='')
                                                                                            <option value="{{$allmodel->modelID}}" @foreach($masterdata['model'] as $selectedmodel) @if($allmodel->modelID == $selectedmodel) SELECTED='SELECTED' @endif @endforeach>{{$allmodel->modelname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allmodel->modelID}}">{{$allmodel->modelname}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect6')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label>Select Colour</label>
                                                                                <select id="testSelect7" name="colour" multiple>
                                                                                    @foreach($masterdata['allcolour'] as $allcolour)
                                                                                        @if($masterdata['colour']!='')
                                                                                            <option value="{{$allcolour->colourID}}" @foreach($masterdata['colour'] as $selectedcolour) @if($allcolour->colourID == $masterdata['colour']) SELECTED='SELECTED' @endif @endforeach>{{$allcolour->colourname}}</option>
                                                                                        @else
                                                                                            <option value="{{$allcolour->colourID}}">{{$allcolour->colourname}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <script>
                                                                                    document.multiselect('#testSelect7')
                                                                                        .setCheckBoxClick("checkboxAll", function(target, args) {
                                                                                            console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
                                                                                        })
                                                                                        .setCheckBoxClick("1", function(target, args) {
                                                                                            console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
                                                                                        });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Date Range</label>
                                                                                <div>
                                                                                    <div class="input-daterange input-group" id="date-range">
                                                                                        <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($masterdata['firstday'])) @endphp" />
                                                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($masterdata['lastday'])) @endphp" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 text-right">
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
                                        <table id="datatable-buttons" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Sale Invoice</th>
                                                <th>Store</th>
                                                <th data-priority="1">Customer</th>
                                                <th data-priority="1">Sale Rep.</th>
                                                <th data-priority="1">Barcode</th>
                                                <th data-priority="1">Product Name</th>
                                                <th data-priority="1">Quantity</th>
                                                <th data-priority="1">Supplier</th>
                                                <th data-priority="1">Category</th>
                                                <th data-priority="1">Brand</th>
                                                <th data-priority="1">Colour</th>
                                                <th data-priority="1">Model</th>
                                                <th data-priority="3">SP (Inc. GST)</th>
                                                <th data-priority="6">Discount</th>
                                                <th data-priority="6">Sold Price (Inc. GST)</th>
                                                <th data-priority="6">Total (Inc. GST)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                            $salesp = 0;
                                            $salediscount = 0;
                                            $salesoldprice = 0;
                                            $saletotal = 0;
                                            $refundsp = 0;
                                            $refunddiscount = 0;
                                            $refundsoldprice = 0;
                                            $refundtotal = 0;
                                            @endphp
                                            @foreach($masterdata['getsales'] as $sales)
                                            <tr>
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($sales->created_at)) @endphp</td>
                                                <th>
                                                    <a href="sale/{{$sales->orderID}}" style="color: #007bff;">{{$sales->orderID}}</a>
                                                </th>
                                                <td>{{$sales->store_name}}</td>
                                                <td>{{$sales->customerfirstname}} {{$sales->customerlastname}}</td>
                                                <td>{{$sales->name}}</td>
                                                <td>{{$sales->barcode}}</td>
                                                <td>{{$sales->productname}}</td>
                                                <td>{{$sales->quantity}}</td>
                                                <td>{{$sales->suppliername}}</td>
                                                <td>
                                                    @if($sales->subcategoryname != '')
                                                    {{$sales->subcategoryname}}
                                                    @else
                                                    {{$sales->categoryname}}
                                                    @endif
                                                </td>
                                                <td>{{$sales->brandname}}</td>
                                                <td>{{$sales->colourname}}</td>
                                                <td>{{$sales->modelname}}</td>
                                                <td>
                                                    {{$sales->spingst}}
                                                    @php
                                                    $salesp += $sales->spingst;
                                                    @endphp
                                                </td>
                                                <td>
                                                    @if($sales->discountedAmount != '')
                                                    {{$sales->discountedAmount}}
                                                    @php
                                                    $salediscount += $sales->discountedAmount;
                                                    @endphp
                                                    @else
                                                    0.00
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$sales->salePrice}}
                                                    @php
                                                    $salesoldprice += $sales->salePrice;
                                                    @endphp
                                                </td>
                                                <td>
                                                    {{$sales->subTotal}}
                                                    @php
                                                    $saletotal += $sales->subTotal;
                                                    @endphp
                                                </td>
                                            </tr>
                                            @endforeach
                                            @foreach($masterdata['getrefund'] as $refund)
                                            <tr style="background-color: #f1525247;">
                                                <td>@php echo date('d-m-Y H:i:s', strtotime($refund->created_at)) @endphp</td>
                                                <th>
                                                    <a href="refundinvoice/{{$refund->refundInvoiceID}}" style="color: #007bff;">{{$refund->refundInvoiceID}}</a>
                                                </th>
                                                <td>{{$refund->store_name}}</td>
                                                <td>{{$refund->customerfirstname}} {{$refund->customerlastname}}</td>
                                                <td>{{$refund->name}}</td>
                                                <td>{{$refund->barcode}}</td>
                                                <td>{{$refund->productname}}</td>
                                                <td>{{$refund->quantity}}</td>
                                                <td>{{$refund->suppliername}}</td>
                                                <td>
                                                    @if($refund->subcategoryname != '')
                                                    {{$refund->subcategoryname}}
                                                    @else
                                                    {{$refund->categoryname}}
                                                    @endif
                                                </td>
                                                <td>{{$refund->brandname}}</td>
                                                <td>{{$refund->colourname}}</td>
                                                <td>{{$refund->modelname}}</td>
                                                <td>
                                                    -{{$refund->spingst}}
                                                    @php
                                                    $refundsp += $refund->spingst;
                                                    @endphp
                                                </td>
                                                <td>
                                                    @if($refund->discountedAmount != '')
                                                    -{{$refund->discountedAmount}}
                                                    @php
                                                    $refunddiscount += $refund->discountedAmount;
                                                    @endphp
                                                    @else
                                                    -0.00
                                                    @endif
                                                </td>
                                                <td>
                                                    -{{$refund->salePrice}}
                                                    @php
                                                    $refundsoldprice += $refund->salePrice;
                                                    @endphp
                                                </td>
                                                <td>
                                                    -{{$refund->subTotal}}
                                                    @php
                                                    $refundtotal += $refund->subTotal;
                                                    @endphp
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
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
                                                    <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$salesp - $refundsp}}
                                                    </td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$salediscount - $refunddiscount}}
                                                    </td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$salesoldprice - $refundsoldprice}}
                                                    </td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$saletotal - $refundtotal}}
                                                    </td>
                                                </tr>
                                            </tfoot>
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
        
        <script src="{{ asset('posview') }}/assets/js/jquery.min.js"></script>

        <script src="{{ asset('posview') }}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="{{ asset('posview') }}/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="{{ asset('posview') }}/assets/pages/form-advanced.js"></script>
        <!-- Responsive-table-->
        <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>
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
                                title: 'SaleMasterReport',
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