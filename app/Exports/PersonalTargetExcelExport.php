<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;
use App\mastercomission;
use App\masterplancategory;
use App\masterplanpropositiontype;
use App\mastercategory;
use App\loggeduser;

class PersonalTargetExcelExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        /*$showcomission = mastercomission::select('comossioncategory')->where('comissioncategoryview', '1')->get();*/

        $activeusers= loggeduser::select('id', 'name')->where('userstatus', '1')->get();
    	return $activeusers;
    }

    public function headings(): array
    {
    	
        //
        $showcomission = mastercomission::select('comossioncategory')->where('comissioncategoryview', '1')->get();

        $headings = ['userID', 'Name', 'month', 'year', ];
        foreach($showcomission as $value)
        {
        	array_push($headings, $value->comossioncategory);
        }

        return [
        	$headings
        ];
    }
}
