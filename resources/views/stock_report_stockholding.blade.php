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
                            <h4 class="page-title">Stock Holding Report</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Reports</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Stock Report</a></li>
                                <li class="breadcrumb-item active">Stock Holding Report</li>
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
                                                    <form method="post" action="{{route('reportstockholdings')}}">
                                                        @csrf
                                                        <div class="row">
                                                            @if(session('loggindata')['loggeduserpermission']->viewreportstockholdingfilter=='Y')
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Select Store</label>
                                                                    <select id="testSelect1" name="store[]" multiple>
                                                                        @foreach($allstore as $allstores)
                                                                            @if($storeID!='')
                                                                                <option value="{{$allstores->store_id}}" @foreach($storeID as $selectedstoreid) @if($allstores->store_id == $selectedstoreid) SELECTED='SELECTED' @endif @endforeach>{{$allstores->store_name}}</option>
                                                                            @else
                                                                                <option value="{{$allstores->store_id}}">{{$allstores->store_name}}</option>
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
                                                                    <select id="testSelect2" name="supplier[]" multiple>
                                                                        @foreach($allsupplier as $supplier)
                                                                            @if($supplierID!='')
                                                                                <option value="{{$supplier->supplierID}}" @foreach($supplierID as $selectedsupplierid) @if($supplier->supplierID == $selectedsupplierid) SELECTED='SELECTED' @endif @endforeach>{{$supplier->suppliername}}</option>
                                                                            @else
                                                                                <option value="{{$supplier->supplierID}}">{{$supplier->suppliername}}</option>
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
                                                                <label>Select Category</label>
                                                                <select id="testSelect3" name="category[]" multiple>
                                                                    @foreach($allcategory as $category)
                                                                        @if($categoryID!='')
                                                                            <option value="{{$category->categoryID}}" @foreach($categoryID as $selectedcategoryid) @if($category->categoryID == $selectedcategoryid) SELECTED='SELECTED' @endif @endforeach>{{$category->categoryname}}</option>
                                                                        @else
                                                                            <option value="{{$category->categoryID}}">{{$category->categoryname}}</option>
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
                                                            <div class="col-md-3">
                                                                <label>Select Sub Category</label>
                                                                <select id="testSelect8" multiple name="subcategory[]">
                                                                    @foreach($allsubcategory as $subcategory)
                                                                        @if(!empty($subcategoryID))
                                                                            <option value="{{$subcategory->subcategoryID}}" @foreach($subcategoryID as $selectedsubcategories) @if($subcategory->subcategoryID == $selectedsubcategories) SELECTED='SELECTED' @endif @endforeach>{{$subcategory->subcategoryname}}</option>
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
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit" class="btn btn-success">Apply Filter</button>
                                                                
                                                            </div>
                                                            @endif
                                                            
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
                                                <th>Store</th>
                                                <th>Barcode</th>
                                                <th data-priority="1">Product Name</th>
                                                <th data-priority="1">IMEI/Serial</th>
                                                <th data-priority="1">Supplier</th>
                                                <th data-priority="1">Category</th>
                                                <th data-priority="1">Brand</th>
                                                <th data-priority="1">Model</th>
                                                <th data-priority="1">Colour</th>
                                                <th data-priority="1">Quantity</th>
                                                <th data-priority="1">Avg. Purchase Price<br>(Ex. GST)</th>
                                                <th data-priority="1">Avg. Purchase Price Tax</th>
                                                <th data-priority="1">Avg. Purchase Price<br>(Inc. GST)</th>
                                                <th>Total Purchase Price <br>(Ex. GST)</th>
                                                <th>Total Purchase Price <br>(Inc. GST)</th>
                                                <th>RRP <br>(Ex. GST)</th>
                                                <th>RRP Tax</th>
                                                <th>RRP <br>(Inc. GST)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($getstockholding as $stockholding)
                                            <tr>
                                                <td>{{$stockholding->store_name}}</td>
                                                <td>{{$stockholding->barcode}}</td>
                                                <td>{{$stockholding->productname}}</td>
                                                <td>{{$stockholding->productimei}}</td>
                                                <td>{{$stockholding->suppliername}}</td>
                                                <td>{{$stockholding->categoryname}}</td>
                                                <td>{{$stockholding->brandname}}</td>
                                                <td>{{$stockholding->modelname}}</td>
                                                <td>{{$stockholding->colourname}}</td>
                                                <td>
                                                    {{$stockholding->productquantity}}
                                                </td>
                                                <td>{{$stockholding->ppexgst}}</td>
                                                <td>{{$stockholding->ppexgst * $stockholding->pptax / 100}}</td>
                                                <td>{{$stockholding->ppingst}}</td>
                                                <td>
                                                    {{$stockholding->ppexgst * $stockholding->productquantity}}
                                                </td>
                                                <td>{{$stockholding->ppingst * $stockholding->productquantity}}</td>
                                                <td>{{$stockholding->spexgst}}</td>
                                                <td>{{$stockholding->spexgst * $stockholding->spgst / 100}}</td>
                                                <td>{{$stockholding->spingst}}</td>
                                            </tr>
                                            @endforeach
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
                                title: 'StockHoldingReport',
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