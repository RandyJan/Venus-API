<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\items;
use App\Traits\Response;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use App\Models\transactionDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Tax;
use App\Models\PosTerminal;
use App\Http\Resources\TransactionItemCollection;
use App\Models\Receipt;
use App\Models\CashdrawPeriod;
use App\Models\Period;
use App\Models\PosPeriodBirTransactionNumber as Posperiodtrans;
use App\Models\CashierHistory;
use App\Models\DepartmentHistory;
use App\Models\Refund_GT as Refgt;

class TransactionController extends Controller
{
    use Response;

    /**
     * @OA\Post(
     * path="/api/get-transaction",
     * summary="Get Transaction Data",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getTransactionData",
     * tags={"Transactions"},
     * @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       required={"birTransNum"},
     *       @OA\Property(property="birTransNum", type="integer", format="number"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example=""),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transactionData(Request $request){
        // return $request->all();
        try{
            $transaction = Transaction::where('BIR_Trans_Number', $request->birTransNum)->first();
            
            if(!$transaction){
                throw new Exception('Not Found');
            }

            return $this->response(
                'Success',
                1,
                [
                    'header'    => $transaction,
                    'items'     => new TransactionItemCollection(
                        TransactionItem::getByTransactionId($transaction->Transaction_ID)
                    ),
                ]
            );

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return $this->response($e->getMessage(), 0 );
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
         Log::info($request->all());
        // return response("test");
       $birTransNum = null;
       $bir_trans_resetter = 0;
        $datajson = $request->json();
        $data = json_encode($datajson);
        $datab = json_decode($data);
        // $Cashier_ID = $jsondata['Cashier_ID'];
        // $POS_ID = $jsondata['POS_ID'];
        // // $Transaction_Number = $jsondata['Transaction_Number'];
        // $Transaction_Date = $jsondata['Transaction_Date'];
        // $Period_ID = $jsondata['Period_ID'];
        // $Tax_Total = $jsondata['Tax_Total'];
        // $Sale_Total = $jsondata['Sale_Total'];

            // $transactionDate = Carbon::now();
             $postransnum = PosTerminal::getTransNumber($request->posID);
           
             $num = $postransnum[0]->POS_Trans_Number + 1;
            $nonBirTransNumber = $postransnum[0]->Non_BIR_Trans_Number;
            $nonBirTransNumberResetter = $postransnum[0]->Non_BIR_Trans_Number_Reset_Counter;
            // Log::info( $nonBirTransNumber);
            // Log::info( $nonBirTransNumberResetter);
            // Log::info($postransnum);
            // return;
            $cdrawPeriodID = CashdrawPeriod::getActiveCdrwPeriod($request->cashier_ID,$request->posID);
            
          //$try = json_encode($cdrawPeriodID['data']);
            $date = Carbon::now();
            
            if($request->isNormalTrans){
                $birTransNum = $postransnum[0]->BIR_Non_Fuel_Trans_Number + 1;
                $bir_trans_resetter = $postransnum[0]->BIR_Reset_Counter;
            }
            else{
                $cdrawPeriodID = CashdrawPeriod::getActiveCdrwPeriod($request->cashier_ID,$request->posID);
                $safedropCount = 1;
                $cdrawDetails = null;
                $cdrawtest = $cdrawPeriodID['data']['CDraw_Period_ID'];
                
                if($cdrawPeriodID["result"]){
                    $cdrawDetails = CashdrawPeriod::getCashdrawSafedropDetails( $cdrawPeriodID['data']['CDraw_Period_ID']);
                    //return response($cdrawDetails);
                    
                }
                if($cdrawDetails["result"]){
                    $birTransNum = (int) $cdrawDetails['data'][0]['CDraw_Num_Safedrop'] + (int)1;
                }
                else{
                    $birTransNum = $safedropCount;
                }
                

            }

        $periodID =  Period::getActiveShiftPeriod();
        // var_dump($periodID);
        $transID = null;
        if(!$periodID){
            return false;
        }
     $transID = Transaction::addNewTransaction($request->cashierID,$request->subAccID,$request->posID,$num,$date,$periodID,$request->taxTotal,$request->saleTotal,$request->birReceiptType,$birTransNum,$request->poNum,$request->plateNum,$request->vehicleTypeID,$request->odometer,$request->isManual,$request->isZeroRated,$request->isRefund,$request->transaction_type, $request->attendantID);
            // $maxtransid = Transaction::max('Transaction_Number');
            
            if($request->accountID && $request->subAccID){
                
                $subaccRes = TransactionItem::callLogAccountTransaction($request->accountID,$request->subAccID,$request->subAccAmt);
            }
            if(!$transID){
                return response("transId is null");
            }
            $posTNRet = PosTerminal::updatePOSTransNum($num,$request->posID);
            if(!$posTNRet){
                return response('postnret is null');
            }
            if($request->isNormalTrans){
             
                if($birTransNum > 999999999){
                    $birTransNum = 1;
                    $bir_trans_resetter = PosTerminal::incrementBirResetCounter($request->posID,$bir_trans_resetter);
                }
               
                $activePeriods = Period::getAllActivePeriod();
                if(!$activePeriods){
                    return response("No Active Periods");
                }
                for($i = 0;$i < count($activePeriods);$i++){
                    Posperiodtrans::updateBegTransNumPerTrans($activePeriods[$i]->periodID,$request->posID,$birTransNum,$bir_trans_resetter);
                    Posperiodtrans::updateEndingTransNumPerTrans($activePeriods[$i]->periodID,$request->posID,$birTransNum,$bir_trans_resetter);
                }
                if($request->birReceiptType ==1){
                    $fuelTNRet = PosTerminal::updateBIRFuelTransNum($num,$request->posID);
                    if(!$fuelTNRet){
                        return response('Error fuelTNRet');
                    }
                }
                if($request->birReceiptType == 2){
                    $nFuelTNRet = PosTerminal::updateBIRNonFuelTransNum($birTransNum,$request->posID);
                    if(!$nFuelTNRet){
                        return response("Error nFuelTNRet");
                    }
                }
                $chTransRet = CashierHistory::updateCashierHistTrans($request->cashierID,$request->saleTotal);
                if(!$chTransRet){
                    return response("Error chTransRet");
                }

            }
            if($request->transRefund > 0){
                $chRefRet = CashierHistory::updateCashierHistRefund($transID,$request->transRefund,$request->grossRefund);
                if(!$chRefRet){
                    return response("Error chRefret");
                }
            }

            if($request->customerName != null || $request->address != null || $request->TIN != null || $request->businessStyle != null || $request->cardNumber != null || 
            $request->approvalCode != null || $request->bankCode != null || $request->type != null)
            {
                transactionDetails::insert([
                    'Transaction_ID' =>  $transID,
                    'CustomerName'=>$request->customerName,
                    'Address'=>$request->address,
                    'TIN'=>$request->TIN,
                    'BusinessStyle'=>$request->businessStyle,
                    'CardNumber'=>$request->cardNumber,
                    'ApprovalCode'=>$request->approvalCode,
                    'BankCode'=>$request->bankCode,
                    'Type'=>$request->type,
                ]);
            }

            // $itemb = json_encode($data->items);
            // $item = json_decode($itemb); // decode as an array
            // $item = $request->items;
             $itemcount = array($request->items);
            // Log::info(count($itemcount));
            // return $request->items;
            
            //     $test = $request->items;
            // return $test[0]->itemDesc;
// Log::info($item);
$count = 0;
// var_dump($request->items);
foreach($request->items as $items){   
 $items = (object)$items;
$itemTaxID = $items->itemTaxID ?? null;
$orignalItemValuePreTaxChange = $items->originalItemValuePreTaxChange ?? null;
$itemDiscTotal = $items->itemDiscTotal ?? null;
$itemDiscCodeType = $items->itemDiscCodeType ?? null;
$itemDBPrice = $items->itemDBPrice ?? null;
$res = TransactionItem::callTransItemsSP($transID,$items->itemNumber,$itemTaxID,$items->itemType,$items->itemDesc,$items->itemPrice,$items->itemQTY
,$items->itemValue,$items->itemID,$items->itemTaxAmount,$items->deliveryID,$orignalItemValuePreTaxChange,$items->isTaxExemptItem,$items->isZeroRatedTaxItem,
$request->posID,$itemDiscTotal,$itemDiscCodeType,$itemDBPrice);

if($itemDiscTotal != null && abs($itemDiscTotal)>0){
    $taxRate = Tax::getTaxRate($itemTaxID)/100;

    $taxMul = 1 + $taxRate;
    $taxDisc =  (($items->itemValue/$taxMul) * $taxRate) - ((($items->itemValue + $itemDiscTotal)/$taxMul)*$taxRate);
    if($request->isRefund){
        if($items->itemType == 14){
            DepartmentHistory::logDepDiscountSP($transID,abs($items->itemQTY)*-1,abs($itemDiscTotal)*-1,1,$items->departmentID,$taxDisc * -1);
        }
            else if($items->itemType==2){
                DepartmentHistory::logDepDiscountSP($transID,abs($items->itemQTY)*-1,abs($itemDiscTotal)*-1,2,$items->itemID,$taxDisc * -1);
                
            }
            else if($items->itemType==53){
                DepartmentHistory::logDepDiscountSP($transID,abs($items->itemQTY)*-1,abs($itemDiscTotal)*-1,3,$items->itemID,$taxDisc * -1);
                
            }

            
    }
    else{
        if($items->itemType == 14){
            DepartmentHistory::logDepDiscountSP($transID,$items->itemQTY,abs($itemDiscTotal),1,$items->departmentID,$taxDisc);
        }
        else if($items->itemType == 2){
            DepartmentHistory::logDepDiscountSP($transID,$items->itemQTY,abs($itemDiscTotal),2,$items->itemID,$taxDisc);
        }
        
        else if($items->itemType == 53){
            DepartmentHistory::logDepDiscountSP($transID,$items->itemQTY,abs($itemDiscTotal),3,$items->itemID,$taxDisc);
        }
    }


}
if($request->isRefund){
    if($itemTaxID==1)
{
    $netRefund = abs($items->itemValue)-abs($itemDiscTotal);
    Refgt::incrementVatableByPosId($request->posID,$netRefund);
}
if($itemTaxID ==2){
    if($items->itemDesc != "SC Disc 5%" && $items->itemDesc != "PWD Disc 5%"){
        $netRefund = abs($items->itemValue)-(abs($itemDiscTotal)*2);
        Refgt::incrementVatExemptByPosId($request->posID,$netRefund);
    }

}
if($itemTaxID == 3){
    Refgt::incrementZeroRatedByPosId($request->posID,$items->itemValue);

}

}

    if($res){
        $count++;
    }
}

if($count != count($request->items)){
    return response("count is not equals to itemcount");
}
if($request->isRefund){
    
    if($nonBirTransNumber < 999999999){
     
        $nonBirTransNumber = PosTerminal::incrementNonBirTransNumber($request->posID,$nonBirTransNumber);
    }
    else{
        $nonBirTransNumber = 1;
        $nonBirTransNumberResetter = PosTerminal::incrementNonBirResetCounter($request->posID,$nonBirTransNumberResetter);
    }
    
    if((int)$request->transaction_type == 4){
        Transaction::updateTransactionResetter($transID,$nonBirTransNumberResetter);
    }
    Transaction::updateTransactionNumberReference($transID,$request->isRefundOrigTransNum,$request->transaction_resetter);
    Transaction::updateBirTransNum($transID,$nonBirTransNumber);
    // $test = ['resetter'=>$nonBirTransNumberResetter,
    // 'or_num'=>$nonBirTransNumber,
    // 'transID'=>$transID];
    
    return response()->json(['resetter'=>$nonBirTransNumberResetter,
            'or_num'=>$nonBirTransNumber,
            'transID'=>$transID]);
}

if((int)$request->transaction_type == 2){
    Transaction::updateTransactionResetter($transID,null);
}
if((int)$request->transaction_type == 1 || (int)$request->transaction_type == 3 ){
    Transaction::updateTransactionResetter($transID,$bir_trans_resetter);
}
                    return response()->json(['resetter'=>$bir_trans_resetter,
                                                'or_num'=>$birTransNum,
                                                'transID'=>$transID]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getItems(Request $request)
    {
            $response = items::where('Transaction_ID',$request->trans_ID)->get();
            return response()->json([$response]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receipt_sample(Request $request){
        $receipt = receipt::where('Receipt_ID', $request->posID)->first();
        Log::info($receipt);
    return response()->json($receipt);
    }
    public function activeTransaction(){
        $response = TransactionItem::max('Transaction_ID');
        return response($response);
    }
    public function receiptItems(Request $request){

        $response = TransactionItem::where('Transaction_ID', $request->transNumber)
        ->get();

        if(!$response){
            return response()->json([
                'StatusCode'=> 404,
                'message'=>'Items not found',
                'data'=>$response

            ]);
        }
        return response()->json(
            $response
        );
    }
    public function test($posID){
        $POSID = $posID;
        $ret = PosTerminal::getTransNumber($POSID);
        return response()->json(["data"=>$ret]);
    }

    public function GetTransactionForRefund(Request $request)
    {
        $transaction = Transaction::where('BIR_Trans_Number', $request->birTransNum)->first();
        $transactionDetails = transactionDetails::where('Transaction_ID', $transaction->Transaction_ID)->first();
        $transactionItems = TransactionItem::where('Transaction_ID', $transaction->Transaction_ID)->get();
        return response()->json([
            'transaction' => $transaction,
            'transactionDetails' => $transactionDetails,
            'transactionItems' => $transactionItems
        ]);
    }
}
