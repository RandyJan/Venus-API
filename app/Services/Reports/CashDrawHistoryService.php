<?php

namespace App\Services\Reports;

use App\Http\Resources\CashdrawDetails;
use App\Models\CashdrawDepartmentHistory;
use App\Models\CashdrawGradeHistory;
use App\Models\CashdrawHistory;
use App\Models\CashdrawPeriod;
use App\Models\ManualCashdrawHistory;

class CashDrawHistoryService {

    public function execute($cdraw_period_id){
        $cdraw_history = CashdrawHistory::getCdrawHistoryByCdrawPeriod($cdraw_period_id);

        if(count($cdraw_history) == 0){
            return [
                'success' => false,
                'message' => 'No available cash draw history'
            ];
        }

        $manual_cdraw_history = ManualCashdrawHistory::getManualCDrawGradeHistByCDPeriodID($cdraw_period_id);
        if(count($manual_cdraw_history) == 0){
            return [
                'success' => false,
                'message' => 'No available manual cash draw history'
            ];
        }

        $cdraw_grade_history = CashdrawGradeHistory::getCDrawGradeHistByCDPeriodID($cdraw_period_id);
        if(count($cdraw_grade_history) == 0){
            return [
                'success' => false,
                'message' => 'No available cash draw grade history'
            ];
        }

        $cdraw_department_history = CashdrawDepartmentHistory::getCDrawDeptHistByCDPeriodID($cdraw_period_id);
        if(count($cdraw_department_history) == 0){
            return [
                'success' => false,
                'message' => 'No available cash draw department history'
            ];
        }

        $cdraw_information = CashdrawPeriod::getCashDrawDetails($cdraw_period_id);
        if(!$cdraw_information){
            return [
                'success' => false,
                'message' => 'No available cash draw information'
            ];
        }


        return [
            'success' => true,
            'message' => 'Success',
            'data' => [
                'cdrawFinHistory' => $cdraw_history,
                'manualCdrawGradeHist' => $manual_cdraw_history,
                'cdrawGradeHist' => $cdraw_grade_history,
                'cdrawDeptHist' => $cdraw_department_history,
                'cdrawDetails' => new CashdrawDetails($cdraw_information),
            ]
        ];
    }
}
