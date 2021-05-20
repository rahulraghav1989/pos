<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\mastercomission;
use App\masterplancategory;
use App\masterplanpropositiontype;
use App\mastercategory;
use App\store;

class StoreTargetExcelExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$activestore= store::select('store_id', 'store_name')->where('storestatus', '1')->get();
    	return $activestore;
    }

    public function headings(): array
    {
    	
        //
        $showcomission = mastercomission::select('comossioncategory')->where('comissioncategoryview', '1')->get();

        $headings = ['ID', 'Store Name', 'Month', 'Year',];
        foreach($showcomission as $value)
        {
        	array_push($headings, $value->comossioncategory);
        }

        return [
        	$headings
        ];
    }
}
