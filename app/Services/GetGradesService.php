<?php

namespace App\Services;

use App\Models\Grade;

class GetGradesService {

    public function execute(){
        $result = Grade::select('Grade_ID', 'Grade_Name', 'Tax_Link', 'Tax_rate', 'Grade_Price')
        ->leftJoin('Taxes', 'Taxes.Tax_ID', '=', 'Grades.Tax_Link')
        ->leftJoin('Price_Levels', 'Price_Levels.Price_Profile_ID', '=', 'Grades.Price_Profile_ID')
        ->leftJoin('Price_Level_Types', 'Price_Level_Types.Price_Level', '=', 'Price_Levels.Price_Level')
        ->where('Price_Levels.Price_Level', 1)
        ->get();

        if( count($result) == 0 ){
            return [
                'success' => false,
                'message' => 'Failed to retrieve grade list'
            ];
        }

        return [
            'success' => true,
            'message' => 'Success',
            'data' => $result
        ];
    }

}
