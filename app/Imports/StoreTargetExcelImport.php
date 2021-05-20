<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\storetarget;
use App\mastercomission;

class StoreTargetExcelImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection, $row)
    {
        //return $row;
    }

    public function model(array $row)
    {
        //dd($row['target_category']);
        foreach($row as $key => $rows)
        {
            $showcomission = mastercomission::select('comossioncategory')->where('comossioncategory', $key)->first();
            
            if($showcomission != "")
            {
                $data = [
                    'storeID'=> $row['id'],
                    'targetcategory'=>$showcomission->comossioncategory,
                    'target'=>$rows,
                    'month'=> $row['month'], 
                    'year'=> $row['year'],
                ];
            }
        }
        storetarget::insert($data);
    }
}
