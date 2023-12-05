<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\items;
use App\Traits\Response;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionItemCollection;

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
    {        $datajson = $request->getContent();
        $data = json_decode($datajson, true);

        // $Cashier_ID = $jsondata['Cashier_ID'];
        // $POS_ID = $jsondata['POS_ID'];
        // // $Transaction_Number = $jsondata['Transaction_Number'];
        // $Transaction_Date = $jsondata['Transaction_Date'];
        // $Period_ID = $jsondata['Period_ID'];
        // $Tax_Total = $jsondata['Tax_Total'];
        // $Sale_Total = $jsondata['Sale_Total'];

            $transactionDate = Carbon::now();
                $maxtransid = Transaction::max('Transaction_Number');
                $nexttransid = $maxtransid + 1;
                    Transaction::insert([
                        'cashier_ID' => $data['cashierID'],
                        'Period_ID'=>2,
                        'sub_Account_ID' => '',
                        'POS_ID'=> $data['posID'],
                        'Transaction_Number'=>$nexttransid,
                        'Transaction_Date'=> $transactionDate,
                        'Tax_Total'=> $data['taxTotal'],
                        'Sale_Total'=>$data['saleTotal'],
                        'BIR_Receipt_Type'=>'',
                        'PO_Number'=>'',
                        'vehicleTypeID' => $data['vehicleTypeID'],
                        'isManual' => $data['isManual'],
                        'isZeroRated' => $data['isZeroRated'],
                        'Discount_Total'=>'',
                        'isRefund' => $data['isRefund'],
              ]);
$itemb = json_encode($data['items']);
$item = json_decode($itemb);


// foreach($item as $items){
//     $transactionIdMax = items::max('Transaction_ID');
//     $transactionId = $transactionIdMax + 1;
//     $itemNumberMax = items::max('Item_Number');
//     $itemNumber= $itemNumberMax + 1;
//     $items = items::insert([
//         'Transaction_ID'=>$transactionId,
//         'Item_Number'=>$itemNumber,
//         'Item_Type'=>$items->itemType,
//         'Item_Description'=>$items->itemDesc,
//         'Item_price'=>$items->itemPrice,
//         'Item_Quantity'=>$items->itemQTY,
//         'Item_Value'=>$items->itemValue,
//         'Item_ID'=>$items->itemID,
//         'Item_Tax_Amount'=>$items->itemTaxAmount
//     ]);
// }
foreach($item as $items){
    $transid =  $nexttransid;
    $itemnumbermax = items::max('Item_Number');
    $itemnumber = $itemnumbermax + 1;
    $itemTaxIdmax = items::max('Tax_ID');
    $itemTaxId = $itemTaxIdmax = 1;
    $itemType = $items->itemType;
    $itemDescription = $items->itemDesc;
    $itemPrice = $items->itemPrice;
    $itemQty = $items->itemQTY;
    $itemValue = $items->itemValue;
    $itemId = $items->itemID;
    $itemTaxAmount = $items->itemTaxAmount;
    $deliveryId = '';
    $originalValuePreTaxChange='';
    $isTaxExempt ='';
    $isZeroRatedTax = '';
    $posId = 2;
    $itemDisc = '';
    $discCode = '';
    $itemDbPrice='';
    // Log::info();

    // $insertitems = DB::exec('SP_LOG_TRANSACTION_ITEM @TRANS_ID, @ITEM_NUMBER,
    // @ITEM_TAX_ID, @ITEM_TYPE, @ITEM_DESC, @ITEM_PRICE, @ITEM_QTY,
    //  @ITEM_VALUE, @ITEM_ID, @ITEM_TAX_AMOUNT, @DELIVERY_ID, @original_item_value_pre_tax_change, @is_tax_exempt_item,
    //  @is_zero_rated_tax_item,@pos_id,@ITEM_DISCOUNT_TOTAL, @discount_code_type, @item_DB_Price',[$transid, $itemnumber, $itemTaxId,$itemType,
    //  $itemDescription, $itemPrice,$itemQty,$itemValue,$itemId,$itemTaxAmount,$deliveryId,
    //  $originalValuePreTaxChange,$isTaxExempt,$isZeroRatedTax,$posId,$itemDisc,$discCode,$itemDbPrice]);
  $insertitems =  DB::statement('EXEC SP_LOG_TRANSACTION_ITEM ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
        $transid, $itemnumber, $itemTaxId, $itemType, $itemDescription, $itemPrice, $itemQty, $itemValue,
        $itemId, $itemTaxAmount, $deliveryId, $originalValuePreTaxChange, $isTaxExempt, $isZeroRatedTax,
        $posId, $itemDisc, $discCode, $itemDbPrice
    ]);


}

               // 'cashierID' => $data['cashierID'],
                    // 'subAccID' => '',
                    // 'accountID' => '',
                    // 'posID' => $data['posID'],
                    // 'taxTotal' => $data['taxTotal'],
                    // 'saleTotal' => $data['saleTotal'],
                    // 'isManual' => $data['isManual'],
                    // 'isZeroRated' => $data['isZeroRated'],
                    // 'customerName' => '',
                    // 'address' => '',
                    // 'TIN' => '',
                    // 'businessStyle' => '',
                    // 'cardNumber' => '',
                    // 'approvalCode' => '',
                    // 'bankCode' => '',
                    // 'type' => '',
                    // 'isRefund' => $data['isRefund'],
                    // 'transaction_type' => $data['transaction_type'],
                    // 'isRefundOrigTransNum' => '',
                    // 'transaction_resetter' => '',
                    // 'birReceiptType' => '',
                    // 'poNum' => '',
                    // 'plateNum' =>'',
                    // 'odometer' => '',
                    // 'transRefund' => $data['transRefund'],
                    // 'grossRefund' => $data['grossRefund'],
                    // 'subAccPmt' => '',
                    // 'vehicleTypeID' => $data['vehicleTypeID'],
                    // 'isNormalTrans' => $data['isNormalTrans'],
                    // Log::info($insertitems);

                    return response()->json([$insertitems]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
