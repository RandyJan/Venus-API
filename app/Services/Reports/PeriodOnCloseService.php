<?php

namespace App\Services\Reports;

use App\Models\Period;

class PeriodOnCloseService {

    public function execute($pos_id, $type){

        $result = Period::getActivePeriodByType($type);
        
        if(!$result){
            return [
                'success' => false,
                'message' => 'No active period found'
            ];
        }

        $periodService = new PeriodService;

        return $periodService->execute($pos_id, $result->Period_ID);
    }
}
