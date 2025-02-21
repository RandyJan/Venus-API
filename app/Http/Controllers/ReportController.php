<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\CashDrawPeriod;
use App\Models\VoidEvent;

class ReportController extends Controller
{
    //
    public function getVoidRefundReport(Request $request)
    {
        
        $refundData = Transaction::getRefundTransactions($request->dateFrom, $request->dateTo);
        if(!$refundData)
        {
            return response("No refund transaction from between the dates");
        }
        $refundArray = array();

		for($i = 0; $i < count($refundData); $i++){
			$trID = $refundData[$i]->Transaction_ID;
			$items = TransactionItem::getRefundItems($trID);

			$refunds = array(
				"transID" => $refundData[$i]->Transaction_ID,
    			"cashierID"=> $refundData[$i]->Cashier_ID,
    			"cashierName"=> trim($refundData[$i]->Cashier_Name),
			    "POSID"=> $refundData[$i]->POS_ID,
			    "transDate"=> $refundData[$i]->Transaction_Date,
			    "saleTotal"=> $refundData[$i]->Sale_Total,
			    "saleItems"=> $items,
			);
			array_push($refundArray, $refunds);
		}

		//return response(count($refundData));
        $voidData = VoidEvent::getVoidData($request->dateFrom, $request->dateTo);
		//return response(count($refundArray));
        if(count($refundArray) == 0 && count($voidData) == 0){
			return response(
				json_encode([
						"statusCode" => 0, 
						"statusDescription" => "Failed", 
						"data" => "No Data Available"])
				);
		}
		$returnData = [
            "refundData" => $refundArray,
			"voidData" => $voidData
        ];
		return response(json_encode([
			"statusCode" => 1, 
			"statusDescription" => "Success", 
			"data" => $returnData]));

        //return response($voidData);
    }

	public function getAllCashDrawByPosID(Request $request)
	{
		$data = CashDrawPeriod::GetAllCashdrawByPosID($request->posID);

		if(!$data)
		{
			return response([
				"result" => 0,
				"message" => "Failed to get cashdraw list"
			]);
		}
		return response(["result" => 1, "message" => "Success", "data"=> $data]);
	}
}
