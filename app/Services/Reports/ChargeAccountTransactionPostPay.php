<?php

namespace App\Services\Reports;

use App\Models\Transaction;

class ChargeAccountTransactionPostPay {

    public function execute($period_id){

        $chargeAccPostPay = Transaction::getChargeAccTransReportPostPay($period_id);
        $chargeAccManualDel = Transaction::getChargeAccTransReportManualDel($period_id);

        return [
            'success' => true,
            'message' => 'Success',
            'data' => [
                'chargeAccPostPay' => $chargeAccPostPay,
                'chargeAccManualDel' => $chargeAccManualDel,
            ]
        ];
    }
}
