<?php

namespace App\Services;

use App\Models\CashdrawHistory;
use App\Models\Finalisation;

class CheckSafeDropAlamLevelService {

    protected $mop_id;
    public function __construct($mop_id)
    {
        $this->mop_id = $mop_id;
    }

    public function execute(){
        $data = Finalisation::find($this->mop_id);

        if(!$data){
            return [
                'success' => false,
                'message' => 'No Safedrop Alarm Found',
                // 'test'=>$data
            ];
        }

        if($data->safedrop_level <= 0 && $data->safedrop_alarm <= 0){
            return [
                'success' => true,
                'message' => 'Success',
                'data' => 0
            ];
        }

        // currentCashdrawHistory
        $cch = CashdrawHistory::getCurrentCashDrawRecord();

        if(!$cch){
            return [
                'success' => false,
                'message' => 'No cashdraw total amount found',
            ];
        }

        if($cch->total_amount < $data->safedrop_alarm || $data->safedrop_alarm <= 0){
            return [
                'success' => true,
                'message' => 'Success',
                'data' => 0
            ];
        }

        if(
            ($cch->total_amount >= $data->safedrop_alarm && $cch->total_amount < $data->safedrop_level) ||
            ($cch->total_amount >= $data->safedrop_alarm && $data->safedrop_level <= 0)
        ){
            return [
                'success' => true,
                'message' => 'Success',
                'data' => 1
            ];
        }

        if($cch->total_amount > $data->safedrop_level){
            return [
                'success' => true,
                'message' => 'Success',
                'data' => 2,
            ];
        }

    }
}
