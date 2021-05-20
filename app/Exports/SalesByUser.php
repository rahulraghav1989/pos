<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\product;
use App\orderdetail;
use App\orderitem;
use App\orderpayments;
use App\paymentoptions;
use App\refundorderdetail;
use App\refundorderitem;
use App\refundorderpayments;

class SalesByUser implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $getusersales = orderitem::leftJoin('orderdetail','orderdetail.orderID', '=', 'orderitem.orderID')
            ->leftJoin('products', 'products.productID', '=', 'orderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->whereBetween('orderdetail.orderDate', [$firstday, $lastday])
            ->where('orderdetail.userID', $userID)
            ->where('orderdetail.orderstatus', '1')
            ->whereNull('orderitem.planID')
            ->whereNull('orderitem.planOrderID')
            ->where('orderdetail.storeID', 'LIKE', '%'.$storeID.'%')
            ->get(array('orderdetail.orderID', 'orderdetail.orderDate', 'orderdetail.created_at', 'products.barcode', 'products.productname', 'orderitem.quantity', 'orderitem.spingst', 'orderitem.salePrice', 'orderitem.discountedAmount', 'orderitem.subTotal', 'mastersupplier.suppliername', 'mastercolour.colourname', 'masterbrand.brandname', 'mastermodel.modelname', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname'));

            $getuserrefund = refundorderitem::leftJoin('refundorderdetail','refundorderdetail.refundInvoiceID', '=', 'refundorderitem.refundInvoiceID')
            ->leftJoin('products', 'products.productID', '=', 'refundorderitem.productID')
            ->leftJoin('mastercategory', 'mastercategory.categoryID', '=', 'products.categories')
            ->leftJoin('mastersubcategory', 'mastersubcategory.subcategoryID', '=', 'products.subcategory')
            ->leftJoin('masterbrand', 'masterbrand.brandID', '=', 'products.brand')
            ->leftJoin('mastercolour', 'mastercolour.colourID', '=', 'products.colour')
            ->leftJoin('mastermodel', 'mastermodel.modelID', '=', 'products.model')
            ->leftJoin('mastersupplier', 'mastersupplier.supplierID', '=', 'products.supplierID')
            ->whereBetween('refundorderdetail.refundDate', [$firstday, $lastday])
            ->where('refundorderdetail.refundBy', $userID)
            ->where('refundorderdetail.refundStatus', '1')
            ->whereNull('refundorderitem.planID')
            ->whereNull('refundorderitem.planOrderID')
            ->where('refundorderdetail.storeID', 'LIKE', '%'.$storeID.'%')
            ->get(array('refundorderdetail.refundInvoiceID', 'refundorderdetail.refundDate', 'refundorderdetail.created_at', 'products.barcode', 'products.productname', 'refundorderitem.quantity', 'refundorderitem.spingst', 'refundorderitem.salePrice', 'refundorderitem.discountedAmount', 'refundorderitem.subTotal', 'mastersupplier.suppliername', 'mastercolour.colourname', 'masterbrand.brandname', 'mastermodel.modelname', 'mastercategory.categoryname', 'mastersubcategory.subcategoryname'));

            $with = array(
                'getusersales'=>$getusersales,
                'getuserrefund'=>$getuserrefund
            );

            return $with;
    }
}
