<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EJournal;
use App\Models\Transaction;
use DB;
use Illuminate\Support\Facades\Log;

class ElectronicJournalController extends Controller
{
    //
    
    public function saveTransactionInfo(Request $request)
    {
        $data = str_replace("=", " ", $request->data);

        //return response($data);
        $date = date("Y-m-d H:i");
        $TransactionSaving = EJournal::insert([
            'Transaction_ID' => $request->Transaction_ID,
            'pos_id' => $request->pos_id,
            'Transaction_Date' => $date,
            'si_number' => $request->si_number,
            'data' => $data,
            'print_count' => 0
        ]);
        if(!$TransactionSaving)
        {
            return response("Error Saving", 401);
        }
        return response("Saving complete");
    }

    public function getTransJournalByDate(Request $request)
    {
        if(is_null($request->dateTo) || is_null($request->posID))
        {
            return response(["statusCode" => 0, "statusDescription" => "Missing parameter/s"]);
        }
        $transJourn = Transaction::select("Transaction_Date as transDate", "transaction_resetter as transactionResetter", "BIR_Trans_Number as birTransNum", "Transaction_Type as transactionType")
        ->where('pos_id', $request->posID)
        ->whereDate('Transaction_Date', $request->dateTo)
        ->get();
        //$transJourn = Transaction::getTransJournalByDate($request->dateTo, $request->posID);
        if(count($transJourn) <= 0)
        {
            return response(json_encode(array(
                "statusCode" => 0,
                "message" => "transaction journal not found"
            )));
        }
        return response(json_encode(array("statusCode" =>1 , "data"=> $transJourn)));
       
    }
    
    public function getReceiptForReprint(Request $request)
    {
        LOG::info($request);
        $receipt = EJournal::where('si_number', $request->SI_Number)
        ->get();
        
        if(count($receipt) <= 0)
        {
            return response(json_encode([
                "statusCode" => 0,
                "data" => "There's no receipt for this transaction"
            ]));
        }
        
        $newCount = $receipt[0]->print_count + 1;
        
        $update = EJournal::where('si_number', $request->SI_Number)
        ->update(['print_count'=> $newCount]);
        
      //  return response($update);
        return response(json_encode([
            "statusCode" => 1,
            "data" => $receipt[0]->Data
        ]));
    }

    
}
