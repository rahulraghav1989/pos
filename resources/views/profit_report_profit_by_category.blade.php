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
                            <h4 class="page-title">Profit By Category</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Profit Report</a></li>
                                <li class="breadcrumb-item active">Profit By Category</li>
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
                                            <div class="text-right">
                                                <a class="btn btn-primary mo-mb-2" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                    Filters
                                                </a>
                                            </div>
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body mt-3 mb-0">
                                                    <form method="post" action="{{route('profitbycategory')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportprofitbycategoryfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Select Store</label>
                                                                    <select id="testSelect1" name="store[]" multiple>
                                                                        @foreach($userprofitdata['allstore'] as $allstore)
                                                                            @if($userprofitdata['storeID']!='')
                                                                                <option value="{{$allstore->store_id}}" @foreach($userprofitdata['storeID'] as $selectedstoreid) @if($allstore->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstore->store_name}}</option>
                                                                            @else
                                                                                <option value="{{$allstore->store_id}}">{{$allstore->store_name}}</option>
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
                                                                    <label>Select User</label>
                                                                    <select id="testSelect2" name="user[]" multiple>
                                                                        @foreach($userprofitdata['allusers'] as $allusers)
                                                                            @if($userprofitdata['userID']!='')
                                                                                <option value="{{$allusers->id}}" @foreach($userprofitdata['userID'] as $selecteduserid) @if($allusers->id == $selecteduserid) SELECTED='SELECTED' @endif @endforeach>{{$allusers->name}}</option>
                                                                            @else
                                                                                <option value="{{$allusers->id}}">{{$allusers->name}}</option>
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
                                                                    <label>Select Category</label>
                                                                    <select id="testSelect3" name="category[]" multiple>
                                                                        @foreach($userprofitdata['allcategory'] as $allcategory)
                                                                            @if($userprofitdata['categoryID']!='')
                                                                                <option value="{{$allcategory->categoryID}}" @foreach($userprofitdata['categoryID'] as $selectedcategoryid) @if($allcategory->categoryID == $selectedcategoryid) SELECTED='SELECTED' @endif @endforeach>{{$allcategory->categoryname}}</option>
                                                                            @else
                                                                                <option value="{{$allcategory->categoryID}}">{{$allcategory->categoryname}}</option>
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
                                                                <label>Select Sub Category</label>
                                                                <select id="testSelect8" multiple name="subcategory[]">
                                                                    @foreach($userprofitdata['allsubcategory'] as $subcategory)
                                                                        @if(!empty($userprofitdata['subcategoryID']))
                                                                            <option value="{{$subcategory->subcategoryID}}" @foreach($userprofitdata['subcategoryID'] as $selectedsubcategories) @if($subcategory->subcategoryID == $selectedsubcategories) SELECTED='SELECTED' @endif @endforeach>{{$subcategory->subcategoryname}}</option>
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
                                                            @endif
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Date Range</label>
                                                                    <div>
                                                                        <div class="input-daterange input-group" id="date-range">
                                                                            <input type="text" class="form-control" name="startdate" placeholder="Start Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($userprofitdata['firstday'])) @endphp" />
                                                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" autocomplete="off" value="@php echo date('d-m-Y', strtotime($userprofitdata['lastday'])) @endphp" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit" class="btn btn-success">Apply Filter</button>
                                                                
                                                            </div>
                                                        </div>
                                                    </form>
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
                                                <th>Sale Rep.</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Supplier</th>
                                                <th>Category</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Colour</th>
                                                <th>PP (Inc. GST)</th>
                                                <th>SP (Inc. GST)</th>
                                                <th>Discount</th>
                                                <th>Sold Price (Inc. GST)</th>
                                                <th>Quantity</th>
                                                <th>Profit (inc. GST)</th>
                                                <th>Total Price (Inc. GST)</th>
                                                <th>Total Profit (Inc. GST)</th>
                                            </tr>
                                            </thead>
                                            @php
                                            $salepp = 0;
                                            $salesp = 0;
                                            $salediscount = 0;
                                            $salesoldprice = 0;
                                            $saleprofit = 0;
                                            $saletotalprice = 0;
                                            $saletotalprofit = 0;
                                            $refundpp= 0;
                                            $refundsp = 0;
                                            $refunddiscount = 0;
                                            $refundsoldprice = 0;
                                            $refundprofit = 0;
                                            $refundtotalprice = 0;
                                            $refundtotalrefund = 0;
                                            @endphp
                                            <tbody>
                                            @foreach($userprofitdata['userprofit'] as $userprofittab)
                                                <tr>
                                                    <td>@php echo date('d-m-Y H:i:s', strtotime($userprofittab->created_at)) @endphp</td>
                                                    <th>
                                                        <a href="sale/{{$userprofittab->orderID}}" style="color: #007bff;">{{$userprofittab->orderID}}</a>
                                                    </th>
                                                    <td>{{$userprofittab->store_name}}</td>
                                                    <td>{{$userprofittab->name}}</td>
                                                    <td>{{$userprofittab->barcode}}</td>
                                                    <td>{{$userprofittab->productname}}</td>
                                                    <td>{{$userprofittab->suppliername}}</td>
                                                    <td>
                                                        @if($userprofittab->subcategoryname != "")
                                                        {{$userprofittab->subcategoryname}}
                                                        @else
                                                        {{$userprofittab->categoryname}}
                                                        @endif
                                                    </td>
                                                    <td>{{$userprofittab->brandname}}</td>
                                                    <td>{{$userprofittab->modelname}}</td>
                                                    <td>{{$userprofittab->colourname}}</td>
                                                    <td>
                                                        {{$userprofittab->ppingst}}
                                                        @php
                                                        $salepp += $userprofittab->ppingst;
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        {{$userprofittab->spingst}}
                                                        @php
                                                        $salesp += $userprofittab->spingst
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @if($userprofittab->discountedAmount == '')
                                                        0.00
                                                        @else
                                                        {{$userprofittab->discountedAmount}}
                                                            @php
                                                            $salediscount += $userprofittab->discountedAmount;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$userprofittab->salePrice}}
                                                        @php
                                                        $salesoldprice += $userprofittab->salePrice;
                                                        @endphp
                                                    </td>
                                                    <td>{{$userprofittab->quantity}}</td>
                                                    <td>
                                                        {{$userprofittab->salePrice - $userprofittab->ppingst}}
                                                        @php
                                                        $saleprofit += ($userprofittab->salePrice - $userprofittab->ppingst);
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        {{$userprofittab->subTotal}}
                                                        @php
                                                        $saletotalprice += $userprofittab->subTotal;
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        {{($userprofittab->salePrice - $userprofittab->ppingst) * $userprofittab->quantity}}
                                                        @php
                                                        $saletotalprofit += (($userprofittab->salePrice - $userprofittab->ppingst) * $userprofittab->quantity);
                                                        @endphp
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @foreach($userprofitdata['userrefund'] as $userrefund)
                                                <tr style="background-color: #f1525247;">
                                                    <td>@php echo date('d-m-Y H:i:s', strtotime($userrefund->created_at)) @endphp</td>
                                                    <th>
                                                        <a href="refundsale/{{$userrefund->refundInvoiceID}}" style="color: #007bff;">{{$userrefund->refundInvoiceID}}</a>
                                                    </th>
                                                    <td>{{$userrefund->store_name}}</td>
                                                    <td>{{$userrefund->name}}</td>
                                                    <td>{{$userrefund->barcode}}</td>
                                                    <td>{{$userrefund->productname}}</td>
                                                    <td>{{$userrefund->suppliername}}</td>
                                                    <td>
                                                        @if($userrefund->subcategoryname != "")
                                                        {{$userrefund->subcategoryname}}
                                                        @else
                                                        {{$userrefund->categoryname}}
                                                        @endif
                                                    </td>
                                                    <td>{{$userrefund->brandname}}</td>
                                                    <td>{{$userrefund->modelname}}</td>
                                                    <td>{{$userrefund->colourname}}</td>
                                                    <td>
                                                        -{{$userrefund->ppingst}}
                                                        @php
                                                        $refundpp += $userrefund->ppingst;
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        -{{$userrefund->spingst}}
                                                        @php
                                                        $refundsp += $userrefund->spingst;
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @if($userrefund->discountedAmount == '')
                                                        -0.00
                                                        @else
                                                        -{{$userrefund->discountedAmount}}
                                                            @php
                                                            $refunddiscount += $userrefund->discountedAmount;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td>
                                                        -{{$userrefund->salePrice}}
                                                        @php
                                                        $refundsoldprice += $userrefund->salePrice;
                                                        @endphp
                                                    </td>
                                                    <td>{{$userrefund->quantity}}</td>
                                                    <td>
                                                        @if(($userrefund->salePrice - $userrefund->ppingst) < 0)
                                                        {{$userrefund->salePrice - $userrefund->ppingst}}
                                                        @else
                                                        -{{$userrefund->salePrice - $userrefund->ppingst}}
                                                        @endif
                                                        @php
                                                        $refundprofit += ($userrefund->salePrice - $userrefund->ppingst);
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        -{{$userrefund->subTotal}}
                                                        @php
                                                        $refundtotalprice += $userrefund->subTotal;
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @if((($userrefund->salePrice - $userrefund->ppingst)*$userrefund->quantity) < 0)
                                                        {{($userrefund->salePrice - $userrefund->ppingst) * $userrefund->quantity}}
                                                        @else
                                                        -{{($userrefund->salePrice - $userrefund->ppingst) * $userrefund->quantity}}
                                                        @endif
                                                        @php
                                                        $refundtotalrefund += (($userrefund->salePrice - $userrefund->ppingst) * $userrefund->quantity);
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
                                                    <td style="font-size: 1.2em; font-weight: 600;">Total</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">{{$salepp - $refundpp}}</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">{{$salesp - $refundsp}}</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">{{$salediscount - $refunddiscount}}</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">{{$salesoldprice - $refundsoldprice}}</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;"></td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">{{$saleprofit - $refundprofit}}</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">{{$saletotalprice - $refundtotalprice}}</td>
                                                    <td style="font-size: 1.2em; font-weight: 600;">
                                                        {{$saletotalprofit - $refundtotalrefund}}<br>
                                                        @if(count($userprofitdata['userprofit']) != 0)
                                                        Profitability
                                                        {{round(($saletotalprofit - $refundtotalrefund * 100)/count($userprofitdata['userprofit']),2)}}
                                                        @endif
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
                    buttons: [
                            {
                                extend: 'excel',
                                text : 'Export Excel',
                                title: 'ProfitByCategoryReport',
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