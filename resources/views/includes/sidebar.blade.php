<div class="left side-menu">
            <div class="slimscroll-menu" id="remove-scroll">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu" id="side-menu">
                        <li class="menu-title">Menu</li>
                        <li>
                            <a href="{{ route('home') }}" class="waves-effect">
                                <i class="icon-accelerator"></i><span> Dashboard </span>
                            </a>
                        </li>
                        {{--@foreach(session('loggindata')['loggedinsubmenu'] as $mainmenu)
                            @if($mainmenu->mainmenuType =='single')
                            <li>
                                <a href="{{ route($mainmenu->mainmenuUri) }}" class="waves-effect"><i class="icon-mail-open"></i><span> {{$mainmenu->mainmenuName}} </span></a>
                            </li>
                            @endif
                            @if($mainmenu->mainmenuType=='brakeline')
                            <li class="menu-title">{{$mainmenu->mainmenuName}}</li>
                            @endif
                            @if($mainmenu->mainmenuType=='dropdown')
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> {{$mainmenu->mainmenuName}} <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                                <ul class="submenu">
                                    @foreach($mainmenu->submenu as $submenu)
                                        <li><a href="{{ route($submenu->submenuUri) }}">{{$submenu->submenuName}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                        @endforeach--}}
                        @if(session('loggindata')['loggeduserpermission']->searchproducts=='Y')
                        <li>
                            <a href="{{ route('searchproduct') }}" class="waves-effect"><i class="icon-mail-open"></i><span> Search Products</span></a>
                        </li>
                        @endif
                        @if(session('loggindata')['loggeduserpermission']->newsale=='Y')
                        <li>
                            <a href="{{ route('startnewsale') }}" class="waves-effect"><i class="icon-mail-open"></i><span> New Sale</span></a>
                        </li>
                        @endif
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Sales <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewsalehistory=='Y')
                                <li><a href="{{ route('salehistory') }}">Sale History</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->reportEOD=='Y')
                                <li><a href="{{ route('endofday') }}">EOD</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->storeeodreport=='Y')
                                <li><a href="{{ route('storeeodreport') }}">Store EOD Report</a></li>
                                @endif
                            </ul>
                        </li>
                        @if(session('loggindata')['loggeduserpermission']->viewcustomer=='Y')
                        <li>
                            <a href="{{ route('customer') }}" class="waves-effect"><i class="icon-mail-open"></i><span> Customer</span></a>
                        </li>
                        @endif
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Tracker <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewtracker=='Y')
                                <li><a href="{{ route('tracker') }}">Personal Trackers</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewstoretracker=='Y')
                                <li><a href="{{ route('storetracker') }}">Store Tracker</a></li>
                                @endif
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Products/Plans <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewproducts=='Y')
                                <li><a href="{{ route('products') }}">Products</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewplans=='Y')
                                <li><a href="{{ route('plans') }}">Plans</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->livestocktake=='Y')
                                <li><a href="{{ route('livestocktake') }}">Live Stock Take</a></li>
                                @endif
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Stock Transfer <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewstocktransferout=='Y')
                                <li><a href="{{ route('stocktransfer') }}">Outgoing Transfer</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewstocktransferin=='Y')
                                <li><a href="{{ route('incomingtransfer') }}">Incoming Transfer</a></li>
                                @endif
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Purchase Order <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewpurchaseorder=='Y')
                                <li><a href="{{ route('productpurchaseorder') }}">Purchase Order</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->receivepurchaseorder=='Y')
                                <li><a href="{{ route('productreceiveorder') }}">Receive Order</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewdemoreceive=='Y')
                                <li><a href="{{ route('demoreceive') }}">Demo Receive</a></li>
                                @endif
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('stockreturn') }}" class="waves-effect"><i class="icon-mail-open"></i><span> Stock Return</span></a>
                        </li>
                        <li class="menu-title">Advance</li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Users <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->vieweuser=='Y')
                                <li><a href="{{ route('usermaster') }}">Users</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewusergroup=='Y')
                                <li><a href="{{ route('usergroups') }}">User Group</a></li>
                                @endif
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Roster <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewtimesheet=='Y')
                                <li><a href="{{ route('timesheet') }}">Timesheet</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewrostermanager=='Y')
                                <li><a href="{{ route('rostermanager') }}">Roster Manager</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->reportroster=='Y')
                                <li><a href="{{ route('rosterreport') }}">Roster Report</a></li>
                                @endif
                            </ul>
                        </li>
                        @if(session('loggindata')['loggeduserpermission']->viewmasters=='Y')
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Masters <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                <li><a href="{{ route('addbrand') }}">Brand Master</a></li>
                                <li><a href="{{ route('colourmaster') }}">Colour Master</a></li>
                                <li><a href="{{ route('modelmaster') }}">Model Master</a></li>
                                <li><a href="{{ route('suppliermaster') }}">Supplier Master</a></li>
                                <li><a href="{{ route('storemaster') }}">Store Master</a></li>
                                <li><a href="{{ route('stockgroupmaster') }}">Stock Group Master</a></li>
                                <li><a href="{{ route('categorymaster') }}">Product Category Master</a></li>
                                <li><a href="{{ route('plancategorymaster') }}">Plan Category Master</a></li>
                                <li><a href="{{ route('plantypemaster') }}">Plan Type Master</a></li>
                                <li><a href="{{ route('plantermmaster') }}">Plan Term Master</a></li>
                                <li><a href="{{ route('producttypemaster') }}">Product Type Master</a></li>
                                <li><a href="{{ route('planpropositiontype') }}">Plan Proposition Type Master</a></li>
                                <li><a href="{{ route('planhandsetterm') }}">Plan Handset Term Master</a></li>
                                <li><a href="{{ route('paymentmaster') }}">Payment Master</a></li>
                                <li><a href="{{ route('comissionmaster') }}">Comission Master</a></li>
                                <li><a href="{{ route('plancomission') }}">Plan Comission Master</a></li>
                            </ul>
                        </li>
                        @endif
                        <li class="menu-title">Report</li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Sales Report <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewreportsalesbyuser=='Y')
                                <li><a href="{{ route('salesbyuser') }}">Sales By User</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportsalespaymentmethod=='Y')
                                <li><a href="{{ route('salebypaymentmethod') }}">Sales By Payment Method</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportsalesmaster=='Y')
                                <li><a href="{{ route('salesmasterreport') }}">Sales Master Outright</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportsalesmastercombin=='Y')
                                <li><a href="{{ route('salescombinemasterreport') }}">Sales Master Combine</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportsalesconnection=='Y')
                                <li><a href="{{ route('salesconnection') }}">Sales Connection</a></li>
                                @endif
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Stock Report <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewinstock=='Y')
                                <li><a href="{{ route('instock') }}">In-Stock Report</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportstockhistory=='Y')
                                <li><a href="{{ route('stockhistory') }}">Stock History</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportproductreceive=='Y')
                                <li><a href="{{ route('productreceived') }}">Product Received</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportstocktransfer=='Y')
                                <li><a href="{{ route('reportstocktransfer') }}">Stock Transfer</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewstockreturn=='Y')
                                <li><a href="{{ route('stockreturnreport') }}">Stock Return</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportstockholding=='Y')
                                <li><a href="{{ route('reportstockholdings') }}">Stock Holdings</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewdemostock=='Y')
                                <li><a href="{{ route('demostock') }}">Demo Stock</a></li>
                                @endif
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Profit Report <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                @if(session('loggindata')['loggeduserpermission']->viewreportprofitbyuser=='Y')
                                <li><a href="{{ route('profitbyuser') }}">Profit By User</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportprofitbyconnection=='Y')
                                <li><a href="{{ route('profitbyconnection') }}">Profit By Connection</a></li>
                                @endif
                                @if(session('loggindata')['loggeduserpermission']->viewreportprofitbycategory=='Y')
                                <li><a href="{{ route('profitbycategory') }}">Profit By Category</a></li>
                                @endif
                            </ul>
                        </li>
                        @if(session('loggindata')['loggeduserpermission']->upfrontreport=='Y')
                        <li>
                            <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Upfront  <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="submenu">
                                <li><a href="{{ route('upfrontdashboard') }}">Upfront Dashboard</a></li>
                                <li><a href="{{ route('upfrontdetailedreport') }}">Upfront Detailed Report</a></li>
                                <li><a href="{{ route('upfrontstoresummary') }}">Upfront Store Summary</a></li>
                            </ul>
                        </li>
                        @endif
                    </ul>

                </div>
                <!-- Sidebar -->
                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>