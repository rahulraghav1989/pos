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
                                <h4 class="page-title">Group Permission</h4>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advance</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">User Group</a></li>
                                    <li class="breadcrumb-item active">Group Permission</li>
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
                                                        <input type="hidden" name="groupid" id="groupid" value="{{$getgrouppermission->usertypeID}}">
                                                        <td>
                                                            <select name="changestore" class="permission">
                                                                <option value="Y" @if($getgrouppermission->changestore == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                                <option value="N" @if($getgrouppermission->changestore == 'N' || $getgrouppermission->changestore == '') SELECTED='SELECTED' @endif>No</option>
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
                                                                <option value="Y" @if($getgrouppermission->newsale == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                                <option value="N" @if($getgrouppermission->newsale == 'N' || $getgrouppermission->newsale == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->refund == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->refund == 'N' || $getgrouppermission->refund == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewmasters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewmasters == 'N' || $getgrouppermission->viewmasters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addmasters" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addmasters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addmasters == 'N' || $getgrouppermission->addmasters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editmasters" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editmasters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editmasters == 'N' || $getgrouppermission->editmasters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deletemaster" class="permission">
                                                            <option value="Y" @if($getgrouppermission->deletemaster == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->deletemaster == 'N' || $getgrouppermission->deletemaster == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->vieweuser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->vieweuser == 'N' || $getgrouppermission->vieweuser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="adduser" class="permission">
                                                            <option value="Y" @if($getgrouppermission->adduser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->adduser == 'N' || $getgrouppermission->adduser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="edituser" class="permission">
                                                            <option value="Y" @if($getgrouppermission->edituser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->edituser == 'N' || $getgrouppermission->edituser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deleteuser" class="permission">
                                                            <option value="Y" @if($getgrouppermission->deleteuser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->deleteuser == 'N' || $getgrouppermission->deleteuser == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        Empty
                                                    </td>
                                                    <td>
                                                        Edit Permission:
                                                        <select name="editpermission" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editpermission == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editpermission == 'N' || $getgrouppermission->editpermission == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewusergroup == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewusergroup == 'N' || $getgrouppermission->viewusergroup == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addusergroup" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addusergroup == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addusergroup == 'N' || $getgrouppermission->addusergroup == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editusergroup" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editusergroup == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editusergroup == 'N' || $getgrouppermission->editusergroup == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->editusergrouppermission == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editusergrouppermission == 'N' || $getgrouppermission->editusergrouppermission == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewproducts == 'N' || $getgrouppermission->viewproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addproducts" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addproducts == 'N' || $getgrouppermission->addproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editproducts" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editproducts == 'N' || $getgrouppermission->editproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deleteproducts" class="permission">
                                                            <option value="Y" @if($getgrouppermission->deleteproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->deleteproducts == 'N' || $getgrouppermission->deleteproducts == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="viewproductfilters" class="permission">
                                                            <option value="Y" @if($getgrouppermission->viewproductfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewproductfilters == 'N' || $getgrouppermission->viewproductfilters == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewplans == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewplans == 'N' || $getgrouppermission->viewplans == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addplans" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addplans == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addplans == 'N' || $getgrouppermission->addplans == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editplans" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editplans == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editplans == 'N' || $getgrouppermission->editplans == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deleteplans" class="permission">
                                                            <option value="Y" @if($getgrouppermission->deleteplans == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->deleteplans == 'N' || $getgrouppermission->deleteplans == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    
                                                    <td>
                                                        <select name="viewplansfilters" class="permission">
                                                            <option value="Y" @if($getgrouppermission->viewplansfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewplansfilters == 'N' || $getgrouppermission->viewplansfilters == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->searchproducts == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->searchproducts == 'N' || $getgrouppermission->searchproducts == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->searchproductsbystore == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->searchproductsbystore == 'N' || $getgrouppermission->searchproductsbystore == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewpurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewpurchaseorder == 'N' || $getgrouppermission->viewpurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addpurchaseorder" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addpurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addpurchaseorder == 'N' || $getgrouppermission->addpurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editpurchaseorder" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editpurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editpurchaseorder == 'N' || $getgrouppermission->editpurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="deletepurchaseorder" class="permission">
                                                            <option value="Y" @if($getgrouppermission->deletepurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->deletepurchaseorder == 'N' || $getgrouppermission->deletepurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    
                                                    <td>
                                                        <label>PO Filters: </label>
                                                        <select name="viewpurchaseorderfilters" class="permission">
                                                            <option value="Y" @if($getgrouppermission->viewpurchaseorderfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewpurchaseorderfilters == 'N' || $getgrouppermission->viewpurchaseorderfilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Receive Filters: </label>
                                                        <select name="viewpurchaseorderreceivefilters" class="permission">
                                                            <option value="Y" @if($getgrouppermission->viewpurchaseorderreceivefilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewpurchaseorderreceivefilters == 'N' || $getgrouppermission->viewpurchaseorderreceivefilters == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <label>Edit PO Item: </label>
                                                        <select name="editpurchaseorderitem" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editpurchaseorderitem == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editpurchaseorderitem == 'N' || $getgrouppermission->editpurchaseorderitem == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Del PO Item: </label>
                                                        <select name="deletepurchaseorderitem" class="permission">
                                                            <option value="Y" @if($getgrouppermission->deletepurchaseorderitem == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->deletepurchaseorderitem == 'N' || $getgrouppermission->deletepurchaseorderitem == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>View Item Price: </label>
                                                        <select name="viewpurchaseorderprice" class="permission">
                                                            <option value="Y" @if($getgrouppermission->viewpurchaseorderprice == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewpurchaseorderprice == 'N' || $getgrouppermission->viewpurchaseorderprice == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Receive PO: </label>
                                                        <select name="receivepurchaseorder" class="permission">
                                                            <option value="Y" @if($getgrouppermission->receivepurchaseorder == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->receivepurchaseorder == 'N' || $getgrouppermission->receivepurchaseorder == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewtimesheet == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewtimesheet == 'N' || $getgrouppermission->viewtimesheet == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addtimesheet" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addtimesheet == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addtimesheet == 'N' || $getgrouppermission->addtimesheet == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewrostermanager == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewrostermanager == 'N' || $getgrouppermission->viewrostermanager == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->rostermanagerpay == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->rostermanagerpay == 'N' || $getgrouppermission->rostermanagerpay == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->reportroster == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->reportroster == 'N' || $getgrouppermission->reportroster == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->reportrosterfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->reportrosterfilter == 'N' || $getgrouppermission->reportrosterfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewstocktransferout == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewstocktransferout == 'N' || $getgrouppermission->viewstocktransferout == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>IN: </label>
                                                        <select name="viewstocktransferin" class="permission">
                                                            <option value="Y" @if($getgrouppermission->viewstocktransferin == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewstocktransferin == 'N' || $getgrouppermission->viewstocktransferin == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addstocktransfer" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addstocktransfer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addstocktransfer == 'N' || $getgrouppermission->addstocktransfer == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewstocktransferfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewstocktransferfilters == 'N' || $getgrouppermission->viewstocktransferfilters == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewcustomer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewcustomer == 'N' || $getgrouppermission->viewcustomer == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addcustomer" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addcustomer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addcustomer == 'N' || $getgrouppermission->addcustomer == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="editcustomer" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editcustomer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editcustomer == 'N' || $getgrouppermission->editcustomer == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewtracker == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewtracker == 'N' || $getgrouppermission->viewtracker == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addpersonaltarget" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addpersonaltarget == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addpersonaltarget == 'N' || $getgrouppermission->addpersonaltarget == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewtrackerfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewtrackerfilter == 'N' || $getgrouppermission->viewtrackerfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                       <label>View Bonus:</label>
                                                       <select name="viewtrackerbonus" class="permission">
                                                            <option value="Y" @if($getgrouppermission->viewtrackerbonus == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewtrackerbonus == 'N' || $getgrouppermission->viewtrackerbonus == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewstoretracker == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewstoretracker == 'N' || $getgrouppermission->viewstoretracker == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addstoretarget" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addstoretarget == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addstoretarget == 'N' || $getgrouppermission->addstoretarget == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewstoretrackerfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewstoretrackerfilter == 'N' || $getgrouppermission->viewstoretrackerfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                       <label>View Bonus:</label>
                                                       <select name="viewstoretrackerbonus" class="permission">
                                                            <option value="Y" @if($getgrouppermission->viewstoretrackerbonus == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewstoretrackerbonus == 'N' || $getgrouppermission->viewstoretrackerbonus == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewdemoreceive == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewdemoreceive == 'N' || $getgrouppermission->viewdemoreceive == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="adddemoreceive" class="permission">
                                                            <option value="Y" @if($getgrouppermission->adddemoreceive == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->adddemoreceive == 'N' || $getgrouppermission->adddemoreceive == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewstockreturn == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewstockreturn == 'N' || $getgrouppermission->viewstockreturn == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addstockreturn" class="permission">
                                                            <option value="Y" @if($getgrouppermission->addstockreturn == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->addstockreturn == 'N' || $getgrouppermission->addstockreturn == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewstockreturnfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewstockreturnfilter == 'N' || $getgrouppermission->viewstockreturnfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <label>Edit Items:</label>
                                                        <select name="editstockreturnitem" class="permission">
                                                            <option value="Y" @if($getgrouppermission->editstockreturnitem == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->editstockreturnitem == 'N' || $getgrouppermission->editstockreturnitem == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Delete Items:</label>
                                                        <select name="deletestockreturnitem" class="permission">
                                                            <option value="Y" @if($getgrouppermission->deletestockreturnitem == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->deletestockreturnitem == 'N' || $getgrouppermission->deletestockreturnitem == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                        <br>
                                                        <label>Return Approval:</label>
                                                        <select name="stockreturnAdminAprroval" class="permission">
                                                            <option value="Y" @if($getgrouppermission->stockreturnAdminAprroval == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->stockreturnAdminAprroval == 'N' || $getgrouppermission->stockreturnAdminAprroval == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->bulk_appacccomission == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->bulk_appacccomission == 'N' || $getgrouppermission->bulk_appacccomission == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->livestocktake == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->livestocktake == 'N' || $getgrouppermission->livestocktake == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewsalehistory == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewsalehistory == 'N' || $getgrouppermission->viewsalehistory == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewsalehistoryfilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewsalehistoryfilters == 'N' || $getgrouppermission->viewsalehistoryfilters == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->reportEOD == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->reportEOD == 'N' || $getgrouppermission->reportEOD == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->reportEODfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->reportEODfilter == 'N' || $getgrouppermission->reportEODfilter == '') SELECTED='SELECTED' @endif>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <label>EOD Till</label>
                                                        <select name="reportEODtill" class="permission">
                                                            <option value="Y" @if($getgrouppermission->reportEODtill == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->reportEODtill == 'N' || $getgrouppermission->reportEODtill == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->storeeodreport == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->storeeodreport == 'N' || $getgrouppermission->storeeodreport == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->storeeodreportfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->storeeodreportfilter == 'N' || $getgrouppermission->storeeodreportfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalesbyuser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalesbyuser == 'N' || $getgrouppermission->viewreportsalesbyuser == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalesbyuserfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalesbyuserfilter == 'N' || $getgrouppermission->viewreportsalesbyuserfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalespaymentmethod == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalespaymentmethod == 'N' || $getgrouppermission->viewreportsalespaymentmethod == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalespaymentmethodfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalespaymentmethodfilter == 'N' || $getgrouppermission->viewreportsalespaymentmethodfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalesmaster == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalesmaster == 'N' || $getgrouppermission->viewreportsalesmaster == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalesmasterfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalesmasterfilter == 'N' || $getgrouppermission->viewreportsalesmasterfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalesmastercombin == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalesmastercombin == 'N' || $getgrouppermission->viewreportsalesmastercombin == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalesmastercombinefilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalesmastercombinefilter == 'N' || $getgrouppermission->viewreportsalesmastercombinefilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalesconnection == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalesconnection == 'N' || $getgrouppermission->viewreportsalesconnection == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportsalesconnectionfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportsalesconnectionfilter == 'N' || $getgrouppermission->viewreportsalesconnectionfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportprofitbyuser == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportprofitbyuser == 'N' || $getgrouppermission->viewreportprofitbyuser == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportprofitbyuserfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportprofitbyuserfilter == 'N' || $getgrouppermission->viewreportprofitbyuserfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportprofitbycategory == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportprofitbycategory == 'N' || $getgrouppermission->viewreportprofitbycategory == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportprofitbycategoryfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportprofitbycategoryfilter == 'N' || $getgrouppermission->viewreportprofitbycategoryfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportprofitbyconnection == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportprofitbyconnection == 'N' || $getgrouppermission->viewreportprofitbyconnection == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportprofitbyconnectionfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportprofitbyconnectionfilter == 'N' || $getgrouppermission->viewreportprofitbyconnectionfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewinstock == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewinstock == 'N' || $getgrouppermission->viewinstock == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportstockhistory == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportstockhistory == 'N' || $getgrouppermission->viewreportstockhistory == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportstockhistoryfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportstockhistoryfilter == 'N' || $getgrouppermission->viewreportstockhistoryfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportstockholding == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportstockholding == 'N' || $getgrouppermission->viewreportstockholding == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportstockholdingfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportstockholdingfilter == 'N' || $getgrouppermission->viewreportstockholdingfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportstocktransfer == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportstocktransfer == 'N' || $getgrouppermission->viewreportstocktransfer == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportstocktransferfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportstocktransferfilter == 'N' || $getgrouppermission->viewreportstocktransferfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportstockreturn == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportstockreturn == 'N' || $getgrouppermission->viewreportstockreturn == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportstockreturnfilter == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportstockreturnfilter == 'N' || $getgrouppermission->viewreportstockreturnfilter == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewdemostock == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewdemostock == 'N' || $getgrouppermission->viewdemostock == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportproductreceive == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportproductreceive == 'N' || $getgrouppermission->viewreportproductreceive == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->viewreportproductreceivefilters == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->viewreportproductreceivefilters == 'N' || $getgrouppermission->viewreportproductreceivefilters == '') SELECTED='SELECTED' @endif>No</option>
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
                                                            <option value="Y" @if($getgrouppermission->upfrontreport == 'Y') SELECTED='SELECTED' @endif>Yes</option>
                                                            <option value="N" @if($getgrouppermission->upfrontreport == 'N' || $getgrouppermission->upfrontreport == '') SELECTED='SELECTED' @endif>No</option>
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
                        var groupid = $('#groupid').val();
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                        url:"{{ route('ajaxupdategrouppermission') }}",
                        method:"POST",
                        data:{column:column, option:option, groupid:groupid, _token:_token},
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
        