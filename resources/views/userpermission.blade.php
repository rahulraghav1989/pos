@extends('main')

@section('content')
    @include('includes.topbar')

    @include('includes.sidebar')
    <link href="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <style type="text/css">
        .btn-toolbar{
            display: none !important;
        }
    </style>
    <style>
    /* width */
    .scrolltab::-webkit-scrollbar {
      width: 3px;
    }

    /* Track */
    .scrolltab::-webkit-scrollbar-track {
      box-shadow: inset 0 0 5px grey; 
      border-radius: 10px;
    }
     
    /* Handle */
    .scrolltab::-webkit-scrollbar-thumb {
      background: #131a3e; 
      border-radius: 10px;
    }

    /* Handle on hover */
    .scrolltab::-webkit-scrollbar-thumb:hover {
      background: #30419b; 
    }
    table th {
        color: #FFF;
    }
    </style>
    <div id="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">User Permission</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>
                                    <li class="breadcrumb-item active">User Permission</li>
                                </ol>
                            </div>
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
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
                                <div class="card-body">
                                    <p style="color: red;">You are editing permission of <span style="font-weight: 600; font-size: 16px;">{{$getuserdata->name}}</span></p>
                                    <div class="scrolltab" style="float: left; width: 23%; height: 400px; overflow-x: auto;">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                          <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Switch Between Stores</a>
                                          <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">New Sale</a>
                                          <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Making Refund</a>
                                          <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">All Masters</a>
                                          <a class="nav-link" id="users-tab" data-toggle="pill" href="#users" role="tab" aria-controls="users" aria-selected="false">Users</a>
                                          <a class="nav-link" id="products-tab" data-toggle="pill" href="#products" role="tab" aria-controls="products" aria-selected="false">Products & Plans</a>
                                          <a class="nav-link" id="searchproduct-tab" data-toggle="pill" href="#searchproduct" role="tab" aria-controls="searchproduct" aria-selected="false">Search Product</a>
                                          <a class="nav-link" id="purchaseorder-tab" data-toggle="pill" href="#purchaseorder" role="tab" aria-controls="purchaseorder" aria-selected="false">Purchase Order</a>
                                          <a class="nav-link" id="timesheet-tab" data-toggle="pill" href="#timesheet" role="tab" aria-controls="timesheet" aria-selected="false">Roster</a>
                                          <a class="nav-link" id="stocktransfer-tab" data-toggle="pill" href="#stocktransfer" role="tab" aria-controls="stocktransfer" aria-selected="false">Stock Transfer</a>
                                          <a class="nav-link" id="customer-tab" data-toggle="pill" href="#customer" role="tab" aria-controls="customer" aria-selected="false">Customer</a>
                                          <a class="nav-link" id="personaltarget-tab" data-toggle="pill" href="#personaltarget" role="tab" aria-controls="personaltarget" aria-selected="false">Targets</a>
                                          <a class="nav-link" id="demoproducts-tab" data-toggle="pill" href="#demoproducts" role="tab" aria-controls="demoproducts" aria-selected="false">Demo Product</a>
                                          <a class="nav-link" id="stockreturn-tab" data-toggle="pill" href="#stockreturn" role="tab" aria-controls="stockreturn" aria-selected="false">Stock Return</a>
                                          <a class="nav-link" id="bulkcomission-tab" data-toggle="pill" href="#bulkcomission" role="tab" aria-controls="bulkcomission" aria-selected="false">Bulk Comission</a>
                                          <a class="nav-link" id="livestoretake-tab" data-toggle="pill" href="#livestoretake" role="tab" aria-controls="livestoretake" aria-selected="false">Live Stock Take</a>
                                          <a class="nav-link" id="salehistory-tab" data-toggle="pill" href="#salehistory" role="tab" aria-controls="salehistory" aria-selected="false">Sales History</a>
                                          <a class="nav-link" id="eod-tab" data-toggle="pill" href="#eod" role="tab" aria-controls="eod" aria-selected="false">EOD</a>
                                          <a class="nav-link" id="salesbyuser-tab" data-toggle="pill" href="#salesbyuser" role="tab" aria-controls="salesbyuser" aria-selected="false">Sales Report</a>
                                          <a class="nav-link" id="profitbyuser-tab" data-toggle="pill" href="#profitbyuser" role="tab" aria-controls="profitbyuser" aria-selected="false">Profit Report</a>
                                          <a class="nav-link" id="stockhistory-tab" data-toggle="pill" href="#stockhistory" role="tab" aria-controls="stockhistory" aria-selected="false">Stock Report</a>
                                          <a class="nav-link" id="upfrontreport-tab" data-toggle="pill" href="#upfrontreport" role="tab" aria-controls="upfrontreport" aria-selected="false">Upfront Report</a>   
                                        </div>
                                    </div>
                                    <div class="tab-content" id="v-pills-tabContent" style="float: left; width: 75%; margin-left: 2%;">
                                      <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <div>
                                            <h5>Switch Between Store</h5>
                                            <table id="tech-companies-1" class="table  table-striped">
                                                <thead>
                                                <tr style="background-color: #30419b;">
                                                    <th>Allowed</th>
                                                    <th data-priority="1">View</th>
                                                    <th data-priority="3">Add</th>
                                                    <th data-priority="1">Edit</th>
                                                    <th data-priority="3">Delete</th>
                                                    <th data-priority="3">Filters</th>
                                                    <th data-priority="3">Additional Permissions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        @csrf
                                                        <input type="hidden" name="userid" id="userid" value="{{$getuserdata->id}}">
                                                        <td>
                                                            <select name="changestore" class="permission">
                                                                <option value="Y" @if($getuserdata->changestore == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                                <option value="N" @if($getuserdata->changestore == 'N' || $getuserdata->changestore == '') SELECTED='SELECTED' @endif>No</option>
                                                            </select>
                                                        </td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                     </div>
                                      <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <div>
                                            <h5>New Sale</h5>
                                            <table id="tech-companies-1" class="table  table-striped">
                                                <thead>
                                                <tr style="background-color: #30419b;">
                                                    <th>Allowed</th>
                                                    <th data-priority="1">View</th>
                                                    <th data-priority="3">Add</th>
                                                    <th data-priority="1">Edit</th>
                                                    <th data-priority="3">Delete</th>
                                                    <th data-priority="3">Filters</th>
                                                    <th data-priority="3">Additional Permissions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select name="newsale" class="permission">
                                                                <option value="Y" @if($getuserdata->newsale == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                                <option value="N" @if($getuserdata->newsale == 'N' || $getuserdata->newsale == '') SELECTED='SELECTED' @endif>No</option>
                                                            </select>
                                                        </td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                        <td>No</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                      </div>
                                      <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                        <h5>Making Refund</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="refund" class="permission">
                                                            <option value="Y" @if($getuserdata->refund == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->refund == 'N' || $getuserdata->refund == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>No</td>
                                                    <td>No</td>
                                                    <td>No</td>
                                                    <td>No</td>
                                                    <td>No</td>
                                                    <td>No</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>All Masters</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewmasters" class="permission">
                                                            <option value="Y" @if($getuserdata->viewmasters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewmasters == 'N' || $getuserdata->viewmasters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addmasters" class="permission">
                                                            <option value="Y" @if($getuserdata->addmasters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addmasters == 'N' || $getuserdata->addmasters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editmasters" class="permission">
                                                            <option value="Y" @if($getuserdata->editmasters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editmasters == 'N' || $getuserdata->editmasters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deletemaster" class="permission">
                                                            <option value="Y" @if($getuserdata->deletemaster == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->deletemaster == 'N' || $getuserdata->deletemaster == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>Empty</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Users</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="vieweuser" class="permission">
                                                            <option value="Y" @if($getuserdata->vieweuser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->vieweuser == 'N' || $getuserdata->vieweuser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="adduser" class="permission">
                                                            <option value="Y" @if($getuserdata->adduser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->adduser == 'N' || $getuserdata->adduser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="edituser" class="permission">
                                                            <option value="Y" @if($getuserdata->edituser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->edituser == 'N' || $getuserdata->edituser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deleteuser" class="permission">
                                                            <option value="Y" @if($getuserdata->deleteuser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->deleteuser == 'N' || $getuserdata->deleteuser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Edit Permission:
                                                        <select name="editpermission" class="permission">
                                                            <option value="Y" @if($getuserdata->editpermission == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editpermission == 'N' || $getuserdata->editpermission == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>User Group</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewusergroup" class="permission">
                                                            <option value="Y" @if($getuserdata->viewusergroup == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewusergroup == 'N' || $getuserdata->viewusergroup == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addusergroup" class="permission">
                                                            <option value="Y" @if($getuserdata->addusergroup == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addusergroup == 'N' || $getuserdata->addusergroup == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editusergroup" class="permission">
                                                            <option value="Y" @if($getuserdata->editusergroup == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editusergroup == 'N' || $getuserdata->editusergroup == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Edit permission:
                                                        <select name="editusergrouppermission" class="permission">
                                                            <option value="Y" @if($getuserdata->editusergrouppermission == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editusergrouppermission == 'N' || $getuserdata->editusergrouppermission == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Products</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewproducts" class="permission">
                                                            <option value="Y" @if($getuserdata->viewproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewproducts == 'N' || $getuserdata->viewproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addproducts" class="permission">
                                                            <option value="Y" @if($getuserdata->addproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addproducts == 'N' || $getuserdata->addproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editproducts" class="permission">
                                                            <option value="Y" @if($getuserdata->editproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editproducts == 'N' || $getuserdata->editproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deleteproducts" class="permission">
                                                            <option value="Y" @if($getuserdata->deleteproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->deleteproducts == 'N' || $getuserdata->deleteproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="viewproductfilters" class="permission">
                                                            <option value="Y" @if($getuserdata->viewproductfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewproductfilters == 'N' || $getuserdata->viewproductfilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Plans</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewplans" class="permission">
                                                            <option value="Y" @if($getuserdata->viewplans == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewplans == 'N' || $getuserdata->viewplans == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addplans" class="permission">
                                                            <option value="Y" @if($getuserdata->addplans == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addplans == 'N' || $getuserdata->addplans == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editplans" class="permission">
                                                            <option value="Y" @if($getuserdata->editplans == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editplans == 'N' || $getuserdata->editplans == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deleteplans" class="permission">
                                                            <option value="Y" @if($getuserdata->deleteplans == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->deleteplans == 'N' || $getuserdata->deleteplans == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    
                                                    <td>
                                                        <select name="viewplansfilters" class="permission">
                                                            <option value="Y" @if($getuserdata->viewplansfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewplansfilters == 'N' || $getuserdata->viewplansfilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                       Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="searchproduct" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Search Products</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="searchproducts" class="permission">
                                                            <option value="Y" @if($getuserdata->searchproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->searchproducts == 'N' || $getuserdata->searchproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Search Products By Store</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="searchproductsbystore" class="permission">
                                                            <option value="Y" @if($getuserdata->searchproductsbystore == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->searchproductsbystore == 'N' || $getuserdata->searchproductsbystore == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="purchaseorder" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Purchase Order</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewpurchaseorder" class="permission">
                                                            <option value="Y" @if($getuserdata->viewpurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewpurchaseorder == 'N' || $getuserdata->viewpurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addpurchaseorder" class="permission">
                                                            <option value="Y" @if($getuserdata->addpurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addpurchaseorder == 'N' || $getuserdata->addpurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editpurchaseorder" class="permission">
                                                            <option value="Y" @if($getuserdata->editpurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editpurchaseorder == 'N' || $getuserdata->editpurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deletepurchaseorder" class="permission">
                                                            <option value="Y" @if($getuserdata->deletepurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->deletepurchaseorder == 'N' || $getuserdata->deletepurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    
                                                    <td>
                                                        <label>PO Filters: </label>
                                                        <select name="viewpurchaseorderfilters" class="permission">
                                                            <option value="Y" @if($getuserdata->viewpurchaseorderfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewpurchaseorderfilters == 'N' || $getuserdata->viewpurchaseorderfilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Receive Filters: </label>
                                                        <select name="viewpurchaseorderreceivefilters" class="permission">
                                                            <option value="Y" @if($getuserdata->viewpurchaseorderreceivefilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewpurchaseorderreceivefilters == 'N' || $getuserdata->viewpurchaseorderreceivefilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <label>Edit PO Item: </label>
                                                        <select name="editpurchaseorderitem" class="permission">
                                                            <option value="Y" @if($getuserdata->editpurchaseorderitem == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editpurchaseorderitem == 'N' || $getuserdata->editpurchaseorderitem == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Del PO Item: </label>
                                                        <select name="deletepurchaseorderitem" class="permission">
                                                            <option value="Y" @if($getuserdata->deletepurchaseorderitem == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->deletepurchaseorderitem == 'N' || $getuserdata->deletepurchaseorderitem == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>View Item Price: </label>
                                                        <select name="viewpurchaseorderprice" class="permission">
                                                            <option value="Y" @if($getuserdata->viewpurchaseorderprice == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewpurchaseorderprice == 'N' || $getuserdata->viewpurchaseorderprice == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Receive PO: </label>
                                                        <select name="receivepurchaseorder" class="permission">
                                                            <option value="Y" @if($getuserdata->receivepurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->receivepurchaseorder == 'N' || $getuserdata->receivepurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="timesheet" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Time Sheet</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewtimesheet" class="permission">
                                                            <option value="Y" @if($getuserdata->viewtimesheet == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewtimesheet == 'N' || $getuserdata->viewtimesheet == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addtimesheet" class="permission">
                                                            <option value="Y" @if($getuserdata->addtimesheet == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addtimesheet == 'N' || $getuserdata->addtimesheet == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                       Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Roster Manager</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewrostermanager" class="permission">
                                                            <option value="Y" @if($getuserdata->viewrostermanager == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewrostermanager == 'N' || $getuserdata->viewrostermanager == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                       <label>Salary Payment</label>
                                                        <select name="rostermanagerpay" class="permission">
                                                            <option value="Y" @if($getuserdata->rostermanagerpay == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->rostermanagerpay == 'N' || $getuserdata->rostermanagerpay == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Roster Report</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="reportroster" class="permission">
                                                            <option value="Y" @if($getuserdata->reportroster == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->reportroster == 'N' || $getuserdata->reportroster == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="reportrosterfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->reportrosterfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->reportrosterfilter == 'N' || $getuserdata->reportrosterfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="stocktransfer" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Stock Transfer</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <label>OUT: </label>
                                                        <select name="viewstocktransferout" class="permission">
                                                            <option value="Y" @if($getuserdata->viewstocktransferout == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewstocktransferout == 'N' || $getuserdata->viewstocktransferout == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>IN: </label>
                                                        <select name="viewstocktransferin" class="permission">
                                                            <option value="Y" @if($getuserdata->viewstocktransferin == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewstocktransferin == 'N' || $getuserdata->viewstocktransferin == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addstocktransfer" class="permission">
                                                            <option value="Y" @if($getuserdata->addstocktransfer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addstocktransfer == 'N' || $getuserdata->addstocktransfer == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    
                                                    <td>
                                                        <select name="viewstocktransferfilters" class="permission">
                                                            <option value="Y" @if($getuserdata->viewstocktransferfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewstocktransferfilters == 'N' || $getuserdata->viewstocktransferfilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                       Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="customer" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Customer</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewcustomer" class="permission">
                                                            <option value="Y" @if($getuserdata->viewcustomer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewcustomer == 'N' || $getuserdata->viewcustomer == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addcustomer" class="permission">
                                                            <option value="Y" @if($getuserdata->addcustomer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addcustomer == 'N' || $getuserdata->addcustomer == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editcustomer" class="permission">
                                                            <option value="Y" @if($getuserdata->editcustomer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editcustomer == 'N' || $getuserdata->editcustomer == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                       Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="personaltarget" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Personal Target</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewtracker" class="permission">
                                                            <option value="Y" @if($getuserdata->viewtracker == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewtracker == 'N' || $getuserdata->viewtracker == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addpersonaltarget" class="permission">
                                                            <option value="Y" @if($getuserdata->addpersonaltarget == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addpersonaltarget == 'N' || $getuserdata->addpersonaltarget == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewtrackerfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewtrackerfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewtrackerfilter == 'N' || $getuserdata->viewtrackerfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                       <label>View Bonus:</label>
                                                       <select name="viewtrackerbonus" class="permission">
                                                            <option value="Y" @if($getuserdata->viewtrackerbonus == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewtrackerbonus == 'N' || $getuserdata->viewtrackerbonus == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Store Target</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewstoretracker" class="permission">
                                                            <option value="Y" @if($getuserdata->viewstoretracker == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewstoretracker == 'N' || $getuserdata->viewstoretracker == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addstoretarget" class="permission">
                                                            <option value="Y" @if($getuserdata->addstoretarget == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addstoretarget == 'N' || $getuserdata->addstoretarget == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewstoretrackerfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewstoretrackerfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewstoretrackerfilter == 'N' || $getuserdata->viewstoretrackerfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                       <label>View Bonus:</label>
                                                       <select name="viewstoretrackerbonus" class="permission">
                                                            <option value="Y" @if($getuserdata->viewstoretrackerbonus == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewstoretrackerbonus == 'N' || $getuserdata->viewstoretrackerbonus == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="demoproducts" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Demo Product</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewdemoreceive" class="permission">
                                                            <option value="Y" @if($getuserdata->viewdemoreceive == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewdemoreceive == 'N' || $getuserdata->viewdemoreceive == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="adddemoreceive" class="permission">
                                                            <option value="Y" @if($getuserdata->adddemoreceive == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->adddemoreceive == 'N' || $getuserdata->adddemoreceive == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                       Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="stockreturn" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Stock Return</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewstockreturn" class="permission">
                                                            <option value="Y" @if($getuserdata->viewstockreturn == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewstockreturn == 'N' || $getuserdata->viewstockreturn == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addstockreturn" class="permission">
                                                            <option value="Y" @if($getuserdata->addstockreturn == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->addstockreturn == 'N' || $getuserdata->addstockreturn == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewstockreturnfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewstockreturnfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewstockreturnfilter == 'N' || $getuserdata->viewstockreturnfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <label>Edit Items:</label>
                                                        <select name="editstockreturnitem" class="permission">
                                                            <option value="Y" @if($getuserdata->editstockreturnitem == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->editstockreturnitem == 'N' || $getuserdata->editstockreturnitem == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Delete Items:</label>
                                                        <select name="deletestockreturnitem" class="permission">
                                                            <option value="Y" @if($getuserdata->deletestockreturnitem == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->deletestockreturnitem == 'N' || $getuserdata->deletestockreturnitem == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Return Approval:</label>
                                                        <select name="stockreturnAdminAprroval" class="permission">
                                                            <option value="Y" @if($getuserdata->stockreturnAdminAprroval == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->stockreturnAdminAprroval == 'N' || $getuserdata->stockreturnAdminAprroval == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="bulkcomission" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Bulk Comission</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="bulk_appacccomission" class="permission">
                                                            <option value="Y" @if($getuserdata->bulk_appacccomission == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->bulk_appacccomission == 'N' || $getuserdata->bulk_appacccomission == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="livestoretake" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Live Stock Take</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="livestocktake" class="permission">
                                                            <option value="Y" @if($getuserdata->livestocktake == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->livestocktake == 'N' || $getuserdata->livestocktake == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="salehistory" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Sales History</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewsalehistory" class="permission">
                                                            <option value="Y" @if($getuserdata->viewsalehistory == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewsalehistory == 'N' || $getuserdata->viewsalehistory == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewsalehistoryfilters" class="permission">
                                                            <option value="Y" @if($getuserdata->viewsalehistoryfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewsalehistoryfilters == 'N' || $getuserdata->viewsalehistoryfilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="eod" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>EOD</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="reportEOD" class="permission">
                                                            <option value="Y" @if($getuserdata->reportEOD == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->reportEOD == 'N' || $getuserdata->reportEOD == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="reportEODfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->reportEODfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->reportEODfilter == 'N' || $getuserdata->reportEODfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <label>EOD Till</label>
                                                        <select name="reportEODtill" class="permission">
                                                            <option value="Y" @if($getuserdata->reportEODtill == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->reportEODtill == 'N' || $getuserdata->reportEODtill == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>EOD Report</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="storeeodreport" class="permission">
                                                            <option value="Y" @if($getuserdata->storeeodreport == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->storeeodreport == 'N' || $getuserdata->storeeodreport == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="storeeodreportfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->storeeodreportfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->storeeodreportfilter == 'N' || $getuserdata->storeeodreportfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="salesbyuser" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Sales By User</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalesbyuser" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalesbyuser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalesbyuser == 'N' || $getuserdata->viewreportsalesbyuser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalesbyuserfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalesbyuserfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalesbyuserfilter == 'N' || $getuserdata->viewreportsalesbyuserfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Sales By Payment Method</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalespaymentmethod" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalespaymentmethod == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalespaymentmethod == 'N' || $getuserdata->viewreportsalespaymentmethod == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalespaymentmethodfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalespaymentmethodfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalespaymentmethodfilter == 'N' || $getuserdata->viewreportsalespaymentmethodfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Sales Master Outright</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalesmaster" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalesmaster == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalesmaster == 'N' || $getuserdata->viewreportsalesmaster == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalesmasterfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalesmasterfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalesmasterfilter == 'N' || $getuserdata->viewreportsalesmasterfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Sales Master Combine</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalesmastercombin" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalesmastercombin == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalesmastercombin == 'N' || $getuserdata->viewreportsalesmastercombin == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalesmastercombinefilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalesmastercombinefilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalesmastercombinefilter == 'N' || $getuserdata->viewreportsalesmastercombinefilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Sales By Connection</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalesconnection" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalesconnection == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalesconnection == 'N' || $getuserdata->viewreportsalesconnection == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportsalesconnectionfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportsalesconnectionfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportsalesconnectionfilter == 'N' || $getuserdata->viewreportsalesconnectionfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="profitbyuser" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>Profit By User</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportprofitbyuser" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportprofitbyuser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportprofitbyuser == 'N' || $getuserdata->viewreportprofitbyuser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportprofitbyuserfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportprofitbyuserfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportprofitbyuserfilter == 'N' || $getuserdata->viewreportprofitbyuserfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Profit By Category</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportprofitbycategory" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportprofitbycategory == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportprofitbycategory == 'N' || $getuserdata->viewreportprofitbycategory == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportprofitbycategoryfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportprofitbycategoryfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportprofitbycategoryfilter == 'N' || $getuserdata->viewreportprofitbycategoryfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Profit By Connection</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportprofitbyconnection" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportprofitbyconnection == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportprofitbyconnection == 'N' || $getuserdata->viewreportprofitbyconnection == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportprofitbyconnectionfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportprofitbyconnectionfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportprofitbyconnectionfilter == 'N' || $getuserdata->viewreportprofitbyconnectionfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="stockhistory" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <h5>In Stock</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewinstock" class="permission">
                                                            <option value="Y" @if($getuserdata->viewinstock == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewinstock == 'N' || $getuserdata->viewinstock == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Stock History</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportstockhistory" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportstockhistory == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportstockhistory == 'N' || $getuserdata->viewreportstockhistory == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportstockhistoryfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportstockhistoryfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportstockhistoryfilter == 'N' || $getuserdata->viewreportstockhistoryfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Stock Holding</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportstockholding" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportstockholding == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportstockholding == 'N' || $getuserdata->viewreportstockholding == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportstockholdingfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportstockholdingfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportstockholdingfilter == 'N' || $getuserdata->viewreportstockholdingfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Stock Transfer</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportstocktransfer" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportstocktransfer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportstocktransfer == 'N' || $getuserdata->viewreportstocktransfer == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportstocktransferfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportstocktransferfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportstocktransferfilter == 'N' || $getuserdata->viewreportstocktransferfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Stock Return</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportstockreturn" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportstockreturn == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportstockreturn == 'N' || $getuserdata->viewreportstockreturn == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportstockreturnfilter" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportstockreturnfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportstockreturnfilter == 'N' || $getuserdata->viewreportstockreturnfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Demo Stock</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewdemostock" class="permission">
                                                            <option value="Y" @if($getuserdata->viewdemostock == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewdemostock == 'N' || $getuserdata->viewdemostock == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h5>Product Receive</h5>
                                        <table id="tech-companies-1" class="table  table-striped">
                                            <thead>
                                            <tr style="background-color: #30419b;">
                                                <th>Allowed</th>
                                                <th data-priority="1">View</th>
                                                <th data-priority="3">Add</th>
                                                <th data-priority="1">Edit</th>
                                                <th data-priority="3">Delete</th>
                                                <th data-priority="3">Filters</th>
                                                <th data-priority="3">Additional Permissions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportproductreceive" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportproductreceive == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportproductreceive == 'N' || $getuserdata->viewreportproductreceive == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        <select name="viewreportproductreceivefilters" class="permission">
                                                            <option value="Y" @if($getuserdata->viewreportproductreceivefilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getuserdata->viewreportproductreceivefilters == 'N' || $getuserdata->viewreportproductreceivefilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="tab-pane fade" id="upfrontreport" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                            <h5>Upfront Report</h5>
                                            <table id="tech-companies-1" class="table  table-striped">
                                                <thead>
                                                <tr style="background-color: #30419b;">
                                                    <th>Allowed</th>
                                                    <th data-priority="1">View</th>
                                                    <th data-priority="3">Add</th>
                                                    <th data-priority="1">Edit</th>
                                                    <th data-priority="3">Delete</th>
                                                    <th data-priority="3">Filters</th>
                                                    <th data-priority="3">Additional Permissions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            Empty
                                                        </td>
                                                        <td>
                                                            <select name="upfrontreport" class="permission">
                                                                <option value="Y" @if($getuserdata->upfrontreport == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                                <option value="N" @if($getuserdata->upfrontreport == 'N' || $getuserdata->upfrontreport == '') SELECTED='SELECTED' @endif>No</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            Empty
                                                        </td>
                                                        <td>
                                                            Empty
                                                        </td>
                                                        <td>
                                                            Empty
                                                        </td>
                                                        <td>
                                                            Empty
                                                        </td>
                                                        <td>
                                                            Empty
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
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
            <script src="{{ asset('posview') }}/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>
            <script>
                $(function() {
                    $('.table-responsive').responsiveTable({
                        addDisplayAllBtn: 'btn btn-secondary'
                    });
                });
            </script>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('.permission').on('change', function(){
                        var column= $(this).attr("name");
                        var option= $(this).val();
                        var userid = $('#userid').val();
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                        url:"{{ route('ajaxupdateuserpermission') }}",
                        method:"POST",
                        data:{column:column, option:option, userid:userid, _token:_token},
                        success:function(result)
                        {
                         
                        }
                       })
                    });

                });
            </script>
        </div>
    </div>
@endsection
        