<?php

namespace App\Models;

use App\Http\Resources\TransactionVehicleTotalsReportCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TransactionItem;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'Transactions';
    protected $primaryKey = 'Transaction_ID';
   // protected $connection = 'enablerDb';

    protected $fillable = [
        'Cashier_ID',
        'Sub_Account_ID',
        'POS_ID',
        'Transaction_Number',
        'Transaction_Date',
        'Period_ID',
        'Tax_Total',
        'Sale_Total',
        'BIR_Receipt_Type',
        'BIR_Trans_Number',
        'PO_Number',
        'Plate_Number',
        'VehicleTypeID',
        'Odometer',
        'isManual',
        'isZeroRated',
        'Discount_Total',
        'isSeniorPWD',
        'isRefund',
        'Attendant_ID'
        // 'Transaction_Type',
        // 'transaction_resetter',
        // 'transaction_number_reference',
        // 'transaction_resetter_reference',
    ];

    /**
     * Scopes
     */
    public function scopeRefundChecker($query, $transaction_number, $transaction_resetter_reference){
        return $query->where('transaction_number_reference', $transaction_number)
            ->where('transaction_resetter_reference', $transaction_resetter_reference);
    }

    /**
     * Relationships
     */
    public function items(){
        return $this->hasMany(TransactionItem::class, 'Transaction_ID', 'Transaction_ID');
    }

    /**
     * Reports
     */
    public static function getTransactionHistoryData($period_id){   //refactor
        $items =  DB::select("
                WITH C AS ( SELECT Transaction_ID, Transaction_Date, Period_ID, Sale_Total, BIR_Receipt_Type, isRefund,
                    Quantity = (SELECT SUM(TI.Item_Quantity) FROM Transaction_Items TI WHERE (TI.Item_Type = 2 OR TI.Item_Type = 53) AND TI.Transaction_ID = T.Transaction_ID)
                FROM Transactions T
                )
                SELECT
                DATEADD(hour, DATEDIFF(hour, 0, Transaction_Date), 0) AS TransactionHour,
                        count(Transaction_ID)
                        as transCount,
                        sum(sale_total)
                        as saleTotal,
                        sum(Quantity) as Quantity
                FROM C
                WHERE Period_ID = ? AND BIR_Receipt_Type is not null
                GROUP BY DATEADD(hour, DATEDIFF(hour, 0, Transaction_Date), 0)
                ORDER BY TransactionHour ASC
            ", [$period_id]);

        // $items = static::with(['TransactionItem' => function ($query) {
        //     $query->where(function ($q) {
        //         $q->where('Item_Type', 2)
        //           ->orWhere('Item_Type', 53);
        //     })->selectRaw('SUM(Item_Quantity) as totalQuantity');
        // }])->get();

        //return $items;
        
        // ->select(
        //     DB::raw('CONCAT(DATE(Transaction_Date), " ", LPAD(HOUR(Transaction_Date), 2, "0"), ":00:00") AS TransactionHour'),
        //     DB::raw('COUNT(Transaction_ID) as transCount'),
        //     DB::raw('SUM(Sale_Total) as saleTotal'),
        // )
        // ->where('Period_ID', $period_id)
        // ->whereNotNull('BIR_Receipt_Type')
        // ->groupBy('TransactionHour')
        // ->orderBy('TransactionHour', 'ASC')
        // ->get();
        $ttid = TransactionItem::All();
        $items = [];
        foreach($ttid as $test)
        {
          $item = TransactionItem::selectRaw('SUM(Item_Quantity) as totalQuantity')->where(function($q){$q->where("Item_Type", 2)->orwhere("Item_Type", 53);})->where("Transaction_ID", $test->Transaction_ID)->get();
          if($item)
          {
              array_push($items, $item);
          }  
        }
        return $items;
        // foreach($ttid as $item)
        // {

        //     $items = static::select('Transaction_ID', 'Transaction_Date', 'Period_ID', 'Sale_Total', 'BIR_Receipt_Type', 'isRefund')->where("Quantity", $item->qValue)->get();
        // }
        
            

        $transactionHistoryHourDataForRefunds = (new self)->getTransHistHourDataForRefund($period_id);


        for($i = 0; $i < count($items); $i++){
            for($ii = 0; $ii < count($transactionHistoryHourDataForRefunds); $ii++){
                if($items[$i]["TransactionHour"] == $transactionHistoryHourDataForRefunds[$ii]["TransactionHour"]){
					$items[$i]["transCount"] = (int)($items[$i]["transCount"] - $transactionHistoryHourDataForRefunds[$ii]["transCount"]);
					$items[$i]["saleTotal"] = (string)($items[$i]["saleTotal"] - $transactionHistoryHourDataForRefunds[$ii]["saleTotal"]);
					$items[$i]["Quantity"] = (float)($items[$i]["Quantity"] - abs($transactionHistoryHourDataForRefunds[$ii]["Quantity"]));
                }
            }
		}

		return $items;
    }

    public function getTransHistHourDataForRefund($period_id){  //refactor
        return DB::connection('enablerDb')
        ->select("
            WITH C AS
            (
            SELECT Transaction_ID, Transaction_Date, Period_ID, Sale_Total, BIR_Receipt_Type, isRefund,
                Quantity = (SELECT SUM(TI.Item_Quantity) FROM Transaction_Items TI WHERE (TI.Item_Type = 2 OR TI.Item_Type = 53) AND TI.Transaction_ID = T.Transaction_ID)
            FROM Transactions T
            )
            SELECT
            DATEADD(hour, DATEDIFF(hour, 0, Transaction_Date), 0) AS TransactionHour,
                    count(Transaction_ID)
                    as transCount,
                    sum(sale_total)
                    as saleTotal,
                    sum(Quantity) as Quantity
            FROM C
            WHERE Period_ID = {$period_id} and isRefund = 1
            GROUP BY DATEADD(hour, DATEDIFF(hour, 0, Transaction_Date), 0)
            ORDER BY TransactionHour ASC
        ");
    }

    public static function getVehicleTotalsByPeriod($period_id){   //refactor
        return new TransactionVehicleTotalsReportCollection(
            static::select(DB::raw("
                VehicleType.vehicleTypeName,
                sum(case when Transactions.isRefund = 0 then 1 else -1 end) as COUNT
            "))
            ->where('Period_ID', $period_id)
            ->leftJoin('VehicleType', 'Transactions.VehicleTypeID', 'VehicleType.VehicleTypeID')
            ->groupBy(DB::raw('VehicleType.vehicleTypeName'))
            ->get()
        );
    }

    public static function getChargeAccTransReportPostPay($period_id){
        return static::selectRaw("Period_ID, BIR_Trans_Number, Transaction_Date, SubAcc_Number, LTRIM(RTRIM(SubAcc_Name)) as SubAcc_Name, LTRIM(RTRIM(Grade_Name)) as Grade_Name, LTRIM(RTRIM(PO_Number)) as PO_Number, LTRIM(RTRIM(Plate_Number)) as Plate_Number, Item_Price, Item_Quantity, Item_Value")
        ->where('Item_Type', 2)
        ->where('Period_ID', $period_id)
        ->leftJoin('Transaction_Items', 'Transaction_Items.Transaction_ID', 'Transactions.Transaction_ID')
        ->leftJoin('Transaction_Details', 'Transaction_Details.Transaction_ID', 'Transactions.Transaction_ID')
        ->leftJoin('Hose_Delivery', 'Hose_Delivery.Delivery_ID', 'Transaction_Items.Item_ID')
        ->leftJoin('Hoses', 'hoses.Hose_ID', 'Hose_Delivery.Hose_ID')
        ->leftJoin('Grades', 'Grades.Grade_ID', 'Hoses.Grade_ID')
        ->Join('Sub_Accounts', 'Sub_Accounts.Sub_Account_ID', 'Transactions.Sub_Account_ID')
        ->get();
    }

    public static function getChargeAccTransReportManualDel($period_id){
        return static::selectRaw("Period_ID, BIR_Trans_Number, Transaction_Date, SubAcc_Number, LTRIM(RTRIM(SubAcc_Name)) as SubAcc_Name, LTRIM(RTRIM(Grade_Name)) as Grade_Name, LTRIM(RTRIM(PO_Number)) as PO_Number, LTRIM(RTRIM(Plate_Number)) as Plate_Number, Item_Price, Item_Quantity, Item_Value")
            ->where('Item_Type', 53)
            ->where('Period_ID', $period_id)
            ->leftJoin('Transaction_Items', 'Transaction_Items.Transaction_ID', 'Transactions.Transaction_ID')
            ->leftJoin('Transaction_Details', 'Transaction_Details.Transaction_ID', 'Transactions.Transaction_ID')
            ->leftJoin('Hose_Delivery', 'Hose_Delivery.Delivery_ID', 'Transaction_Items.Item_ID')
            ->leftJoin('Hoses', 'hoses.Hose_ID', 'Hose_Delivery.Hose_ID')
            ->leftJoin('Grades', 'Grades.Grade_ID', 'Hoses.Grade_ID')
            ->Join('Sub_Accounts', 'Sub_Accounts.Sub_Account_ID', 'Transactions.Sub_Account_ID')
            ->get();

    }

    public static function addNewTransaction($cashierID,$subAccID,$posID,$num,$date,$periodID,$taxTotal,
    $saleTotal,$birReceiptType,$birTransNum,$poNum,$plateNum,$vehicleTypeID,$odometer,$isManual,$isZeroRated,$isRefund,$transaction_type, $attendantID){

       $result = static::insert([

            "Cashier_ID"=>$cashierID,
            "Sub_Account_ID" => $subAccID,
            "POS_ID"=>$posID,
            "Transaction_Number"=>$num,
            "Transaction_Date"=>$date,
            "Period_ID"=>$periodID,
            "Tax_Total"=>$taxTotal,
            "Sale_Total"=>$saleTotal,
            "BIR_Receipt_Type"=>$birReceiptType,
            "BIR_Trans_Number"=>$birTransNum,
            "PO_Number"=>$poNum,
            "Plate_Number"=>$plateNum,
            "VehicleTypeID"=>$vehicleTypeID,
            "Odometer"=>$odometer,
            "isManual"=>$isManual,
            "isZeroRated"=>$isZeroRated,
            "isRefund"=>$isRefund,
            "Transaction_Type"=>$transaction_type,
            "attendant_ID" => $attendantID
            
        ]);
        $id = static::max('Transaction_ID');
        if(!$id){
            return false;
        }
        return $id;

    }
    public static function updateTransactionResetter($transID,$val){
        $result = static::where('Transaction_ID',$transID)
        ->update(['transaction_resetter'=>$val]);
        if($result){
            return true;
        }
        return false;
    }
    public static function updateTransactionNumberReference($transID,$val,$transaction_resetter){

        $result = static::where('Transaction_ID',$transID)
        ->update(['transaction_number_reference'=>$val,
                    'transaction_resetter_reference'=>$transaction_resetter]);
                    if($result){
                        return true;
                    }
                    return false;
    }
    public static function updateBirTransNum($transID,$val){
        $result = static::where('Transaction_ID',$transID)
        ->update(['BIR_Trans_Number'=>$val]);
        if($result){
            return true;
        }
        return false; 

    }

    public static function getRefundTransactions($dateFrom, $dateTo)
    {     
        $result = static::select(DB::raw("Transactions.Transaction_ID, Transactions.Cashier_ID, Cashiers.Cashier_Name, Transactions.POS_ID, Transactions.Transaction_Date, Transactions.Sale_Total"))
        ->whereDate("Transaction_Date", ">=", $dateFrom)
        ->whereDate("Transaction_Date", "<=", $dateTo)
 //       ->whereBetween("Transaction_Date", [$dateFrom, $dateTo])
        ->where("isRefund", 1)
        ->leftjoin("Cashiers", "Transactions.Cashier_ID", "Cashiers.Cashier_ID")
        ->get();
        if(!$result)
        {
            return false;
        }

        
        return $result;
    }
   


public $timestamps = false;
}
