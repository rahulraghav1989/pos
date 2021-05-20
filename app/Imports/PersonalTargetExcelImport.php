<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\personaltarget;
use App\mastercomission;

class PersonalTargetExcelImport implements ToModel, WithHeadingRow
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
        //dd($row);
        /*return new personaltarget([
            'userID'=> $row['userid'],
            'targetcategory'=>$row,
            'target'=>$row,
            'month'=> $row['month'], 
            'year'=> $row['year'],
        ]);*/
        foreach($row as $key => $rows)
        {
            $showcomission = mastercomission::select('comossioncategory')->where('comossioncategory', $key)->first();

            if($showcomission != "")
            {
                $data[] = [
                    'userID'=> $row['userid'],
                    'targetcategory'=>$showcomission->comossioncategory,
                    'target'=>$rows,
                    'month'=> $row['month'], 
                    'year'=> $row['year'],
                ];
            }
            /*return new personaltarget([
                'userID'=> $row['userid'],
                'targetcategory'=>$row,
                'target'=>$row,
                'month'=> $row['month'], 
                'year'=> $row['year'],
            ]);*/
            
            /*return new personaltarget([
            'userID'=> $row['userid'],
            'targetcategory'=>$row['target_category'],
            'target'=>$row['target'],
            'month'=> $row['month'], 
            'year'=> $row['year'],
        ]);*/
            /*$insertpersonal = new personaltarget;
            $insertpersonal->userID = $row['userid'];
            $insertpersonal->targetcategory = $row['target_category'];
            $insertpersonal->target = $row['target'];
            $insertpersonal->month = $row['month'];
            $insertpersonal->year = $row['year'];
            $insertpersonal->save();*/
        }
        personaltarget::insert($data);
    }
}
