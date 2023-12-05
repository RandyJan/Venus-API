<?php

namespace App\Services;

use App\Models\Receipt;

class GetReceiptLayoutService {

    protected $pos_id;
    public function __construct($pos_id)
    {
        $this->pos_id = $pos_id;
    }

    public function execute(){
        $result = Receipt::select(
            'Receipt.Receipt_ID', 'Receipt_Name', 'Receipt_Header_L1',
            'Receipt_Header_L2', 'Receipt_Header_L3', 'Receipt_Header_L4',
            'Receipt_Header_L5', 'Receipt_Footer_L1', 'Receipt_Footer_L2',
            'Receipt_Footer_L3', 'Receipt_Footer_L4', 'Receipt_Footer_L5',
        )->leftJoin('POS_Terminal', 'POS_Terminal.Receipt_ID', '=', 'Receipt.Receipt_ID')
        ->where('POS_ID', $this->pos_id)
        ->get();

        if( count($result) == 0 ){
            return [
                'success' => false,
                'message' => 'Failed to retrieve receipt layout'
            ];
        }

        return [
            'success' => true,
            'message' => 'Success',
            'data' => $result
        ];
    }
}
