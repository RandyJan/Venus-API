<?php

namespace App\Models;

use App\Http\Resources\TransactionVehicleTotalsReportCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'Transactions';
    protected $primaryKey = 'Transaction_ID';
    protected $connection = 'enablerDb';

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
        $items = DB::connection('enablerDb')
            ->select("
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
                WHERE Period_ID = {$period_id} AND BIR_Receipt_Type is not null
                GROUP BY DATEADD(hour, DATEDIFF(hour, 0, Transaction_Date), 0)
                ORDER BY TransactionHour ASC
            ");
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

}
