<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/changestore', 'HomeController@changestore')->name('changestore');

Route::get('/addbrand', 'HomeController@addbrandview')->name('addbrand');
Route::post('/addbrandvalue', 'HomeController@addbrandvalue')->name('addbrandvalue');
Route::post('/editbrand', 'HomeController@editbrand')->name('editbrand');
Route::post('/editbrandstatus', 'HomeController@editbrandstatus')->name('editbrandstatus');
Route::get('/colourmaster', 'HomeController@colourmasterview')->name('colourmaster');
Route::post('/addcolour', 'HomeController@addcolour')->name('addcolour');
Route::post('/editcolour', 'HomeController@editcolour')->name('editcolour');
Route::post('/editcolourstatus', 'HomeController@editcolourstatus')->name('editcolourstatus');
Route::get('/modelmaster', 'HomeController@modelmasterview')->name('modelmaster');
Route::post('/addmodel', 'HomeController@addmodel')->name('addmodel');
Route::post('/editmodel', 'HomeController@editmodel')->name('editmodel');
Route::post('/editmodelstatus', 'HomeController@editmodelstatus')->name('editmodelstatus');
Route::get('/suppliermaster', 'HomeController@suppliermasterview')->name('suppliermaster');
Route::post('/addsupplier', 'HomeController@addsupplier')->name('addsupplier');
Route::post('/editsupplier', 'HomeController@editsupplier')->name('editsupplier');
Route::post('/editsupplierstatus', 'HomeController@editsupplierstatus')->name('editsupplierstatus');
Route::get('/storemaster', 'HomeController@storemasterview')->name('storemaster');
Route::post('/addstore', 'HomeController@addstore')->name('addstore');
Route::post('/editstore', 'HomeController@editstore')->name('editstore');
Route::post('/editstorestatus', 'HomeController@editstorestatus')->name('editstorestatus');
Route::get('/stockgroupmaster', 'HomeController@stockgroupmasterview')->name('stockgroupmaster');
Route::post('/addstockgroup', 'HomeController@addstockgroup')->name('addstockgroup');
Route::post('/editstockgroup', 'HomeController@editstockgroup')->name('editstockgroup');
Route::post('/editstockgroupstatus', 'HomeController@editstockgroupstatus')->name('editstockgroupstatus');
Route::get('/categorymaster', 'HomeController@categorymasterview')->name('categorymaster');
Route::post('/addcategory', 'HomeController@addcategory')->name('addcategory');
Route::post('/editcategory', 'HomeController@editcategory')->name('editcategory');
Route::post('/editcategorystatus', 'HomeController@editcategorystatus')->name('editcategorystatus');
Route::post('/editsubcategory', 'HomeController@editsubcategory')->name('editsubcategory');
Route::post('/editsubcategorystatus', 'HomeController@editsubcategorystatus')->name('editsubcategorystatus');
Route::get('/plancategorymaster', 'HomeController@plancategorymasterview')->name('plancategorymaster');
Route::post('/addplancategory', 'HomeController@addplancategory')->name('addplancategory');
Route::post('/editplancategory', 'HomeController@editplancategory')->name('editplancategory');
Route::post('/editplancategorystatus', 'HomeController@editplancategorystatus')->name('editplancategorystatus');
Route::get('/plantypemaster', 'HomeController@plantypemasterview')->name('plantypemaster');
Route::post('/addplantype', 'HomeController@addplantype')->name('addplantype');
Route::post('/editplantype', 'HomeController@editplantype')->name('editplantype');
Route::post('/editplantypestatus', 'HomeController@editplantypestatus')->name('editplantypestatus');
Route::get('/plantermmaster', 'HomeController@plantermmasterview')->name('plantermmaster');
Route::post('/addplanterm', 'HomeController@addplanterm')->name('addplanterm');
Route::post('/editplanterm', 'HomeController@editplanterm')->name('editplanterm');
Route::post('/editplantermstatus', 'HomeController@editplantermstatus')->name('editplantermstatus');
Route::get('/producttypemaster', 'HomeController@producttypemasterview')->name('producttypemaster');
Route::post('/addproducttype', 'HomeController@addproducttype')->name('addproducttype');
Route::post('/editproducttype', 'HomeController@editproducttype')->name('editproducttype');
Route::post('/editproducttypestatus', 'HomeController@editproducttypestatus')->name('editproducttypestatus');
Route::get('/planpropositiontype', 'HomeController@planpropositiontypeview')->name('planpropositiontype');
Route::post('/addplanpropositiontype', 'HomeController@addplanpropositiontype')->name('addplanpropositiontype');
Route::post('/editplanpropositiontype', 'HomeController@editplanpropositiontype')->name('editplanpropositiontype');
Route::post('/editplanpropositionstatus', 'HomeController@editplanpropositionstatus')->name('editplanpropositionstatus');
Route::get('/planhandsetterm', 'HomeController@planhandsettermview')->name('planhandsetterm');
Route::post('/addplanhandsetterm', 'HomeController@addplanhandsetterm')->name('addplanhandsetterm');
Route::post('/editplanhandsetterm', 'HomeController@editplanhandsetterm')->name('editplanhandsetterm');
Route::post('/editplanhandsettermstatus', 'HomeController@editplanhandsettermstatus')->name('editplanhandsettermstatus');
Route::get('/paymentmaster', 'HomeController@paymentmasterview')->name('paymentmaster');
Route::post('/addpaymentoption', 'HomeController@addpaymentoption')->name('addpaymentoption');
Route::post('/editpaymentoption', 'HomeController@editpaymentoption')->name('editpaymentoption');
Route::post('/editpaymentoptionstatus', 'HomeController@editpaymentoptionstatus')->name('editpaymentoptionstatus');
Route::get('/comissionmaster', 'HomeController@comissionmasterview')->name('comissionmaster');
Route::post('/addcomissionmaster', 'HomeController@addcomissionmaster')->name('addcomissionmaster');
Route::get('/plancomission', 'HomeController@plancomission')->name('plancomission');
Route::post('/plancomissionadd', 'HomeController@plancomissionadd')->name('plancomissionadd');
Route::post('/addplancomtax', 'HomeController@addplancomtax')->name('addplancomtax');
Route::post('/ajaxdeleteplancomission', 'HomeController@ajaxdeleteplancomission')->name('ajaxdeleteplancomission');


/***********Products**************/
Route::get('/products', 'productcontroller@productsview')->name('products');
Route::get('/productaddpage', 'productcontroller@productaddpageview')->name('productaddpage');
Route::post('/productadd', 'productcontroller@productadd')->name('productadd');
Route::post('/editproductstatus', 'productcontroller@editproductstatus')->name('editproductstatus');
Route::get('/changeproduct/{id}', 'productcontroller@changeproductview')->name('changeproduct');
Route::post('/editproduct', 'productcontroller@editproduct')->name('editproduct');
Route::post('/ajaxdeletestockgroup', 'productcontroller@ajaxdeletestockgroup')->name('ajaxdeletestockgroup');
Route::post('/ajaxcheckbarcode', 'productcontroller@ajaxcheckbarcode')->name('ajaxcheckbarcode');
Route::post('/ajaxaddcolour', 'productcontroller@ajaxaddcolour')->name('ajaxaddcolour');
Route::post('/ajaxaddmodel', 'productcontroller@ajaxaddmodel')->name('ajaxaddmodel');
Route::post('/ajaxaddbrand', 'productcontroller@ajaxaddbrand')->name('ajaxaddbrand');
Route::post('/ajaxaddsupplier', 'productcontroller@ajaxaddsupplier')->name('ajaxaddsupplier');

/***********Stock Return*******/
Route::get('/stockreturn', 'stockreturnController@stockreturnview')->name('stockreturn');
Route::post('/createstockreturn', 'stockreturnController@createstockreturn')->name('createstockreturn');
Route::get('/stockreturncreation/{sid}', 'stockreturnController@stockreturncreationview')->name('stockreturncreation');
Route::post('/ajaxupdateranumber', 'stockreturnController@ajaxupdateranumber')->name('ajaxupdateranumber');
Route::post('/ajaxupdatenote', 'stockreturnController@ajaxupdatenote')->name('ajaxupdatenote');
Route::post('/ajaxupdatedate', 'stockreturnController@ajaxupdatedate')->name('ajaxupdatedate');
Route::post('/ajaxupdatesupplier', 'stockreturnController@ajaxupdatesupplier')->name('ajaxupdatesupplier');
Route::post('/stockreturnbybarcode', 'stockreturnController@stockreturnbybarcode')->name('stockreturnbybarcode');
Route::post('/stockreturnaddallbyproductid', 'stockreturnController@stockreturnaddallbyproductid')->name('stockreturnaddallbyproductid');
Route::post('/stockreturnaddimeinumber', 'stockreturnController@stockreturnaddimeinumber')->name('stockreturnaddimeinumber');
Route::post('/stockreturnaddfaultyproduct', 'stockreturnController@stockreturnaddfaultyproduct')->name('stockreturnaddfaultyproduct');
Route::post('/stockreturnadddemoproduct', 'stockreturnController@stockreturnadddemoproduct')->name('stockreturnadddemoproduct');
Route::post('/stockreturnconfirm', 'stockreturnController@stockreturnconfirm')->name('stockreturnconfirm');
Route::post('/stockreturncreditamount', 'stockreturnController@stockreturncreditamount')->name('stockreturncreditamount');
Route::post('/editstockreturnitem', 'stockreturnController@editstockreturnitem')->name('editstockreturnitem');
Route::post('/deletestockreturnitem', 'stockreturnController@deletestockreturnitem')->name('deletestockreturnitem');
Route::post('/cancelstockreturn', 'stockreturnController@cancelstockreturn')->name('cancelstockreturn');
Route::post('/adminapproval', 'stockreturnController@adminapproval')->name('adminapproval');

/***********Purchase Order*******/
Route::get('/productpurchaseorder', 'purchaseorder@productpurchaseorderview')->name('productpurchaseorder');
Route::post('/createpostepfirst', 'purchaseorder@createpostepfirst')->name('createpostepfirst');
Route::get('/purchaseordercreation/{id}', 'purchaseorder@purchaseordercreationview')->name('purchaseordercreation');
Route::post('/addpositem', 'purchaseorder@addpositem')->name('addpositem');
Route::post('/editpoitem', 'purchaseorder@editpoitem')->name('editpoitem');
Route::post('/deletepoitem', 'purchaseorder@deletepoitem')->name('deletepoitem');
Route::post('/finalposubmission', 'purchaseorder@finalposubmission')->name('finalposubmission');
Route::get('/purchaseorderincomplete', 'purchaseorder@purchaseorderincompleteview')->name('purchaseorderincomplete');
Route::get('/productreceiveorder', 'purchaseorder@productreceiveorderview')->name('productreceiveorder');
Route::post('/poreceivestep1', 'purchaseorder@poreceivestep1')->name('poreceivestep1');
Route::post('/addmultiproduct', 'purchaseorder@addmultiproduct')->name('addmultiproduct');
Route::post('/editpostatus', 'purchaseorder@editpostatus')->name('editpostatus');
Route::get('/purchaseorderreceiveitem/{id}', 'purchaseorder@purchaseorderreceiveitemview')->name('purchaseorderreceiveitem');
Route::post('/ajaxupdateporeference', 'purchaseorder@ajaxupdateporeference')->name('ajaxupdateporeference');
Route::post('/ajaxupdateponote', 'purchaseorder@ajaxupdateponote')->name('ajaxupdateponote');
Route::get('/demoreceive', 'purchaseorder@demoreceiveview')->name('demoreceive');
Route::post('/adddemoreceive', 'purchaseorder@adddemoreceive')->name('adddemoreceive');
Route::get('/demoordercreation/{id}', 'purchaseorder@demoordercreationview')->name('demoordercreation');
Route::post('/ajaxupdatedrreference', 'purchaseorder@ajaxupdatedrreference')->name('ajaxupdatedrreference');
Route::post('/ajaxupdatedrnote', 'purchaseorder@ajaxupdatedrnote')->name('ajaxupdatedrnote');
Route::post('/adddritem', 'purchaseorder@adddritem')->name('adddritem');
Route::post('/finaldrsubmission', 'purchaseorder@finaldrsubmission')->name('finaldrsubmission');
Route::get('/demoreceiveitem/{id}', 'purchaseorder@demoreceiveitemview')->name('demoreceiveitem');
Route::post('/drreceivestep1', 'purchaseorder@drreceivestep1')->name('drreceivestep1');
Route::post('/addmultidemo', 'purchaseorder@addmultidemo')->name('addmultidemo');
Route::post('/ajaxcheckimei', 'purchaseorder@ajaxcheckimei')->name('ajaxcheckimei');
Route::post('/ajaxchecksim', 'purchaseorder@ajaxchecksim')->name('ajaxchecksim');
Route::post('/partialpo', 'purchaseorder@partialpo')->name('partialpo');
Route::post('/deletedemopoitem', 'purchaseorder@deletedemopoitem')->name('deletedemopoitem');
Route::post('/editdemoitem', 'purchaseorder@editdemoitem')->name('editdemoitem');
Route::post('/poaddbyproductid', 'purchaseorder@poaddbyproductid')->name('poaddbyproductid');

/************Plans***********/
Route::get('/plans', 'planscontroller@plansview')->name('plans');
Route::post('/planadd', 'planscontroller@planadd')->name('planadd');
Route::post('/editplanstatus', 'planscontroller@editplanstatus')->name('editplanstatus');
Route::get('/changeplan/{id}', 'planscontroller@changeplanview')->name('changeplan');
Route::post('/planedit', 'planscontroller@planedit')->name('planedit');

/**********New Sale************/
Route::get('/startnewsale', 'newsalecontroller@startnewsale')->name('startnewsale');
Route::get('/newsalestorechange', 'newsalecontroller@newsalestorechangeview')->name('newsalestorechange');
Route::get('/newsale/{id}', 'newsalecontroller@newsaleview')->name('newsale');
Route::post('/cancelorder', 'newsalecontroller@cancelorder')->name('cancelorder');
Route::post('/customeradd', 'newsalecontroller@customeradd')->name('customeradd');
Route::post('/addbybarcode', 'newsalecontroller@addbybarcode')->name('addbybarcode');
Route::post('/addbyproductid', 'newsalecontroller@addbyproductid')->name('addbyproductid');
Route::post('/addallbyproductid', 'newsalecontroller@addallbyproductid')->name('addallbyproductid');
Route::post('/savecustomer', 'newsalecontroller@savecustomer')->name('savecustomer');
Route::post('/addstockbyid', 'newsalecontroller@addstockbyid')->name('addstockbyid');
Route::post('/searchplan', 'newsalecontroller@searchplan')->name('searchplan');
Route::post('/addbyplanid', 'newsalecontroller@addbyplanid')->name('addbyplanid');
Route::post('/addplandetail', 'newsalecontroller@addplandetail')->name('addplandetail');
Route::post('/addimeinumber', 'newsalecontroller@addimeinumber')->name('addimeinumber');
Route::post('/orderpayment', 'newsalecontroller@orderpayment')->name('orderpayment');
Route::post('/confirmfullpayment', 'newsalecontroller@confirmfullpayment')->name('confirmfullpayment');
Route::post('/ajaxupdatesalenote', 'newsalecontroller@ajaxupdatesalenote')->name('ajaxupdatesalenote');
Route::post('/calculatediscount', 'newsalecontroller@calculatediscount')->name('calculatediscount');
Route::post('/updatequantity', 'newsalecontroller@updatequantity')->name('updatequantity');
Route::post('/invoicedeleteitem', 'newsalecontroller@invoicedeleteitem')->name('invoicedeleteitem');
Route::post('/addrecharge', 'newsalecontroller@addrecharge')->name('addrecharge');
Route::post('/invoicedeleteplan', 'newsalecontroller@invoicedeleteplan')->name('invoicedeleteplan');
Route::post('/changesaleprice', 'newsalecontroller@changesaleprice')->name('changesaleprice');

/*********Refund**************/
Route::post('/refunditem', 'refundController@refunditem')->name('refunditem');
Route::get('/refund/{id}', 'refundController@refundview')->name('refund');
Route::post('/ajaxupdaterefundnote', 'refundController@ajaxupdaterefundnote')->name('ajaxupdaterefundnote');
Route::post('/updaterefundquantity', 'refundController@updaterefundquantity')->name('updaterefundquantity');
Route::post('/refundinvoiceitemdelete', 'refundController@refundinvoiceitemdelete')->name('refundinvoiceitemdelete');
Route::post('/cancelrefund', 'refundController@cancelrefund')->name('cancelrefund');
Route::post('/refundorderpayment', 'refundController@refundorderpayment')->name('refundorderpayment');
Route::post('/refundconfirmfullpayment', 'refundController@refundconfirmfullpayment')->name('refundconfirmfullpayment');
Route::get('/refundinvoice/{id}', 'refundController@refundinvoiceview')->name('refundinvoice');

/**********Reports************/
Route::get('/instock', 'reportController@instockview')->name('instock');
Route::get('/salehistory', 'reportController@salehistoryview')->name('salehistory');
Route::get('/endofday', 'reportController@endofdayview')->name('endofday');

Route::match(array('GET','POST'),'eodreport', 'reportController@eodreportview')->name('eodreport');

Route::match(array('GET','POST'),'storeeodreport', 'reportController@storeeodreportview')->name('storeeodreport');
//Route::get('/todayendofday', 'reportController@todayendofdayview')->name('todayendofday');
Route::match(array('GET','POST'),'todayendofday', 'reportController@todayendofdayview')->name('todayendofday');

Route::post('/storecashin', 'reportController@storecashin')->name('storecashin');
Route::post('/storecashout', 'reportController@storecashout')->name('storecashout');
Route::get('/reportpaymenttally/{date}/{storeid}', 'reportController@reportpaymenttally')->name('reportpaymenttally');

Route::get('/sale/{id}', 'reportController@salehistorydetailview')->name('sale');
Route::get('/print_sale/{id}', 'reportController@printsale')->name('print_sale');

Route::match(array('GET','POST'),'timesheet', 'reportController@timesheet')->name('timesheet');

Route::post('/addtimesheet', 'reportController@addtimesheet')->name('addtimesheet');
Route::post('/edittimesheet', 'reportController@edittimesheet')->name('edittimesheet');
Route::match(array('GET','POST'),'rostermanager', 'reportController@rostermanagerview')->name('rostermanager');

Route::post('/paysalary', 'reportController@paysalary')->name('paysalary');
Route::get('/salereport', 'reportController@salereportview')->name('salereport');
Route::post('/eodrecon', 'reportController@eodrecon')->name('eodrecon');
Route::get('/tracker', 'reportController@trackerview')->name('tracker');
Route::get('/storetracker', 'reportController@storetrackerview')->name('storetracker');
Route::get('/setpersonaltarget', 'reportController@setpersonaltargetview')->name('setpersonaltarget');
Route::post('/addpersonaltarget', 'reportController@addpersonaltarget')->name('addpersonaltarget');
Route::get('/setstoretarget', 'reportController@setstoretargetview')->name('setstoretarget');
Route::post('/addstoretarget', 'reportController@addstoretarget')->name('addstoretarget');
//Route::get('/salebypaymentmethod', 'reportController@salebypaymentmethodview')->name('salebypaymentmethod');
Route::match(array('GET','POST'),'salebypaymentmethod', 'reportController@salebypaymentmethodview')->name('salebypaymentmethod');

//Route::get('/salesmasterreport', 'reportController@salesmasterreportview')->name('salesmasterreport');
Route::match(array('GET','POST'),'salesmasterreport', 'reportController@salesmasterreportview')->name('salesmasterreport');

Route::match(array('GET','POST'),'salescombinemasterreport', 'reportController@salescombinemasterreportview')->name('salescombinemasterreport');

//Route::get('/salesconnection', 'reportController@salesconnectionview')->name('salesconnection');
Route::match(array('GET','POST'),'salesconnection', 'reportController@salesconnectionview')->name('salesconnection');

//Route::get('/profitbyuser', 'reportController@profitbyuserview')->name('profitbyuser');
Route::match(array('GET','POST'),'profitbyuser', 'reportController@profitbyuserview')->name('profitbyuser');

//Route::get('/profitbycategory', 'reportController@profitbycategoryview')->name('profitbycategory');
Route::match(array('GET','POST'),'profitbycategory', 'reportController@profitbycategoryview')->name('profitbycategory');

//Route::get('/profitbyconnection', 'reportController@profitbyconnectionview')->name('profitbyconnection');
Route::match(array('GET','POST'),'profitbyconnection', 'reportController@profitbyconnectionview')->name('profitbyconnection');

//Route::get('/salesbyuser', 'reportController@salesbyuserview')->name('salesbyuser');
Route::match(array('GET','POST'),'salesbyuser', 'reportController@salesbyuserview')->name('salesbyuser');

Route::post('/salesbyuserexcelexport', 'reportController@salesbyuserexcelexport')->name('salesbyuserexcelexport');

//Route::get('/stockhistory', 'reportController@stockhistoryview')->name('stockhistory');
Route::match(array('GET','POST'),'stockhistory', 'reportController@stockhistoryview')->name('stockhistory');

//Route::post('/stockhistory', 'reportController@stockhistoryfilterview')->name('stockhistoryfilter');

//Route::get('/productreceived', 'reportController@productreceivedview')->name('productreceived');
Route::match(array('GET','POST'),'productreceived', 'reportController@productreceivedview')->name('productreceived');

Route::get('/productreceived/{id}', 'reportController@productreceived_detail')->name('productreceived_detail');
Route::get('/eodtodayprint', 'reportController@eodtodayprintview')->name('eodtodayprint');

//Route::get('/reportstocktransfer', 'reportController@reportstocktransferview')->name('reportstocktransfer');
Route::match(array('GET','POST'),'reportstocktransfer', 'reportController@reportstocktransferview')->name('reportstocktransfer');

//Route::get('/stockreturnreport', 'reportController@stockreturnreportview')->name('stockreturnreport');
Route::match(array('GET','POST'),'stockreturnreport', 'reportController@stockreturnreportview')->name('stockreturnreport');

//Route::get('/reportstockholdings', 'reportController@reportstockholdingsview')->name('reportstockholdings');
Route::match(array('GET','POST'),'reportstockholdings', 'reportController@reportstockholdingsview')->name('reportstockholdings');
Route::match(array('GET','POST'),'rosterreport', 'reportController@rosterreportview')->name('rosterreport');

Route::match(array('GET','POST'),'upfrontdashboard', 'reportController@upfrontdashboardview')->name('upfrontdashboard');
Route::match(array('GET','POST'),'upfrontdetailedreport', 'reportController@upfrontdetailedreportview')->name('upfrontdetailedreport');
Route::match(array('GET','POST'),'upfrontstoresummary', 'reportController@upfrontstoresummaryview')->name('upfrontstoresummary');

Route::get('demostock', 'reportController@demostockview')->name('demostock');

Route::post('/exceldownloadpersonaltarget', 'reportController@exceldownloadpersonaltarget')->name('exceldownloadpersonaltarget');
Route::post('/exceldownloadstoretarget', 'reportController@exceldownloadstoretarget')->name('exceldownloadstoretarget');
Route::post('/importpersonaldata', 'reportController@excelimportpersonaltarget')->name('importpersonaldata');
Route::post('/importstoredata', 'reportController@excelimportstoretarget')->name('importstoredata');

/**********Filters************/
Route::post('/pofilter', 'filterscontroller@pofilter')->name('pofilter');
Route::post('/poreceivefilter', 'filterscontroller@poreceivefilter')->name('poreceivefilter');
Route::post('/poincomfilter', 'filterscontroller@poincomfilter')->name('poincomfilter');
Route::post('/cleartimesheetfilter', 'filterscontroller@cleartimesheetfilter')->name('cleartimesheetfilter');
Route::post('/usertrackerfilter', 'filterscontroller@usertrackerfilter')->name('usertrackerfilter');
Route::post('/storetrackerfilter', 'filterscontroller@storetrackerfilter')->name('storetrackerfilter');
Route::post('/personaltargetfilter', 'filterscontroller@personaltargetfilter')->name('personaltargetfilter');
Route::post('/storetargetfilter', 'filterscontroller@storetargetfilter')->name('storetargetfilter');
Route::post('/salesbyuserfilter', 'filterscontroller@salesbyuserfilter')->name('salesbyuserfilter');
Route::post('/salesbypaymentmethodfilter', 'filterscontroller@salesbypaymentmethodfilter')->name('salesbypaymentmethodfilter');
Route::post('/salesmasterfilter', 'filterscontroller@salesmasterfilter')->name('salesmasterfilter');
Route::post('/salesconnectionfilter', 'filterscontroller@salesconnectionfilter')->name('salesconnectionfilter');
Route::post('/userprofitfilter', 'filterscontroller@userprofitfilter')->name('userprofitfilter');
Route::post('/categoryprofitfilter', 'filterscontroller@categoryprofitfilter')->name('categoryprofitfilter');
Route::post('/connectionprofitfilter', 'filterscontroller@connectionprofitfilter')->name('connectionprofitfilter');
Route::post('/stockhistoryfilter', 'filterscontroller@stockhistoryfilter')->name('stockhistoryfilter');
Route::post('/reportstocktransferfilter', 'filterscontroller@reportstocktransferfilter')->name('reportstocktransferfilter');
Route::post('/stockreturnfilter', 'filterscontroller@stockreturnfilter')->name('stockreturnfilter');
Route::post('/reportstockreturnfilter', 'filterscontroller@reportstockreturnfilter')->name('reportstockreturnfilter');
Route::post('/reportstockholdingfilter', 'filterscontroller@reportstockholdingfilter')->name('reportstockholdingfilter');
Route::post('/salehistoryfilter', 'filterscontroller@salehistoryfilter')->name('salehistoryfilter');
Route::post('/productsfilters', 'filterscontroller@productsfilters')->name('productsfilters');
Route::post('/planfilters', 'filterscontroller@planfilters')->name('planfilters');
Route::post('/stocktransferoutfilters', 'filterscontroller@stocktransferoutfilters')->name('stocktransferoutfilters');
Route::post('/bulkcomissionfilter', 'filterscontroller@bulkcomissionfilter')->name('bulkcomissionfilter');
Route::post('/eodfilter', 'filterscontroller@eodfilter')->name('eodfilter');

/**********Users************/
Route::get('/usermaster', 'userController@usermasterview')->name('usermaster');
Route::get('/usergroups', 'userController@usergroupsview')->name('usergroups');
Route::post('/adduser', 'userController@adduser')->name('adduser');
Route::post('/ajaxcheckusername', 'userController@ajaxcheckusername')->name('ajaxcheckusername');
Route::post('/edituser', 'userController@edituser')->name('edituser');
Route::post('/edituserstatus', 'userController@edituserstatus')->name('edituserstatus');
Route::get('/userpermission/{id}', 'userController@userpermissionview')->name('userpermission');
Route::post('/ajaxupdateuserpermission', 'userController@ajaxupdateuserpermission')->name('ajaxupdateuserpermission');
Route::get('/usersstore/{id}', 'userController@usersstoreview')->name('usersstore');
Route::post('/addstoretouser', 'userController@addstoretouser')->name('addstoretouser');
Route::post('/removeuserstore', 'userController@removeuserstore')->name('removeuserstore');
Route::get('/usergrouppermission/{id}', 'userController@usergrouppermissionview')->name('usergrouppermission');
Route::post('/ajaxupdategrouppermission', 'userController@ajaxupdategrouppermission')->name('ajaxupdategrouppermission');
Route::get('/usergroupcreate/{id}', 'userController@usergroupcreateview')->name('usergroupcreate');
Route::post('/creategroup', 'userController@creategroup')->name('creategroup');
Route::post('/editusergroup', 'userController@editusergroup')->name('editusergroup');

/**********Stock Transfer************/
Route::get('/stocktransfer', 'stocktransferController@stocktransferview')->name('stocktransfer');
Route::get('/incomingtransfer', 'stocktransferController@incomingtransferview')->name('incomingtransfer');
Route::get('/startstocktransfer', 'stocktransferController@startstocktransfer')->name('startstocktransfer');
Route::get('/createstocktransfer/{id}', 'stocktransferController@createstocktransfer')->name('createstocktransfer');
Route::post('/addtransferstore', 'stocktransferController@addtransferstore')->name('addtransferstore');
Route::post('/ajaxupdateconsignmentnumber', 'stocktransferController@ajaxupdateconsignmentnumber')->name('ajaxupdateconsignmentnumber');
Route::post('/ajaxupdatetransfernote', 'stocktransferController@ajaxupdatetransfernote')->name('ajaxupdatetransfernote');
Route::post('/stbybarcode', 'stocktransferController@stbybarcode')->name('stbybarcode');
Route::post('/stbyproductid', 'stocktransferController@stbyproductid')->name('stbyproductid');
Route::post('/stbyimei', 'stocktransferController@stbyimei')->name('stbyimei');
Route::post('/stbyserial', 'stocktransferController@stbyserial')->name('stbyserial');
Route::post('/editquantity', 'stocktransferController@editquantity')->name('editquantity');
Route::post('/deleteitem', 'stocktransferController@deleteitem')->name('deleteitem');
Route::post('/proceedtransfer', 'stocktransferController@proceedtransfer')->name('proceedtransfer');
Route::get('/stocktransferinvoice/{id}', 'stocktransferController@stocktransferinvoice')->name('stocktransferinvoice');

Route::get('/stocktransferreceive/{id}', 'stocktransferController@stocktransferreceive')->name('stocktransferreceive');
Route::post('/receivestocktransfer', 'stocktransferController@receivestocktransfer')->name('receivestocktransfer');
Route::post('/cancelstocktransfer', 'stocktransferController@cancelstocktransfer')->name('cancelstocktransfer');

/**************Customer******************/
Route::get('/customer', 'customerController@customerview')->name('customer');
Route::post('/addcustomer', 'customerController@addcustomer')->name('addcustomer');
Route::get('/customerdetail/{id}', 'customerController@customerdetailview')->name('customerdetail');
Route::post('/editcustomer', 'customerController@editcustomer')->name('editcustomer');

/**************Search Product**********/
Route::get('/searchproduct', 'searchproductController@searchproductview')->name('searchproduct');
Route::post('/searchentity', 'searchproductController@searchentity')->name('searchentity');
/**************Extra Routes************/
Route::get('/404', 'HomeController@page404view')->name('404');

/********Bulking Process***********/
Route::get('/bulkaccessoriescommission', 'bulkprocess@app_accesscomission')->name('bulkaccessoriescommission');
Route::post('/updatebulkappacccomission', 'bulkprocess@update_app_accesscomission')->name('updatebulkappacccomission');
/********Email Notiofications********/
Route::post('/sendcustomerinvoice', 'emailNotificationController@sendcustomerinvoice')->name('sendcustomerinvoice');
Route::post('/sendcustomerrefundinvoice', 'emailNotificationController@sendcustomerrefundinvoice')->name('sendcustomerrefundinvoice');
/*********Live Stock Take************/
Route::get('/livestocktake', 'LiveStockTakeController@livestocktake')->name('livestocktake');
Route::post('/startlivestocktake', 'LiveStockTakeController@startlivestocktake')->name('startlivestocktake');
Route::get('/livestocktakecreate/{id}', 'LiveStockTakeController@livestocktakecreate')->name('livestocktakecreate');
Route::post('/ajaxfindbarcode', 'LiveStockTakeController@ajaxfindbarcode')->name('ajaxfindbarcode');
Route::post('/ajaxfindimei', 'LiveStockTakeController@ajaxfindimei')->name('ajaxfindimei');
/*Route::match(array('GET','POST'),'livestocktake', 'LiveStockTakeController@livestocktake')->name('livestocktake');*/