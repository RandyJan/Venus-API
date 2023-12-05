<?php

namespace App\Services;

use App\Models\Period;

class GetCashierActiveShiftPeriodService {
    protected $cashier_id;

    public function __construct($cashier_id)
    {
        $this->cashier_id = $cashier_id;
    }

    public function execute(){

        $result = Period::select('Periods.Period_ID', 'Shift_Number')
            ->leftJoin('Cashier_History', 'Cashier_History.Period_ID', '=', 'Periods.Period_ID')
            ->where('Cashier_ID', $this->cashier_id)
            ->where('Period_Type', 1)
            ->where('Period_State', 1)
            ->get();

        if( count($result) == 0 ){
            return [
                'success' => false,
                'message' => 'Failed to retrieve period ID'
            ];
        }

        return [
            'success' => true,
            'message' => 'Success',
            'data' => $result
        ];
    }
}
