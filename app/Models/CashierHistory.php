<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashierHistory extends Model
{
    use HasFactory;

    protected $table = 'Cashier_History';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'Cashier_ID',
        'Period_ID',
        'Cshr_Num_Trs',
        'Cshr_Val_Trs',
        'Cshr_Num_Void',
        'Cshr_Val_Void',
        'Cshr_Num_NoSales',
        'Cshr_Num_Refund',
        'Cshr_Val_Refund',
    ];


    /**
     * Logics
     */
    public static function getCashierTotalbyPeriod($period_id){
        return static::select(DB::raw('CASHIER_HISTORY.Cashier_ID,  Cashiers.Cashier_Name, Period_ID, Cshr_Num_Trs, Cshr_Val_Trs, Cshr_Num_Void, Cshr_Val_Void, Cshr_Num_NoSales, Cshr_Num_Refund, Cshr_Val_Refund'))
            ->where('Period_ID', $period_id)
            ->leftJoin('Cashiers', 'Cashiers.Cashier_ID', 'CASHIER_HISTORY.Cashier_ID')
            ->get();
    }

    public static function updateCashierHistTrans($cashierID,$transVal){
       $add = 0;
       if($transVal > 0){
        $add = 1;
       }
       
       
        $result = DB::table('CASHIER_HISTORY')
    ->join('PERIODS', 'CASHIER_HISTORY.PERIOD_ID', '=', 'PERIODS.PERIOD_ID')
    ->where('CASHIER_HISTORY.CASHIER_ID', $cashierID)
    ->where('PERIODS.PERIOD_STATE', 1)
    ->update([
        'CSHR_NUM_TRS' => DB::raw('CSHR_NUM_TRS + '.$add),
        'CSHR_VAL_TRS' => DB::raw('CSHR_VAL_TRS + '.$transVal)
    ]);
    if($result){
        return true;
    }
    return false;
    }
    public static function updateCashierHistRefund($transID,$transRefund,$grossRefund){
      $result =  DB::table('CASHIER_HISTORY')
    ->join('PERIODS as P', 'CASHIER_HISTORY.PERIOD_ID', '=', 'P.PERIOD_ID')
    ->join('TRANSACTIONS as T', 'CASHIER_HISTORY.CASHIER_ID', '=', 'T.CASHIER_ID')
    ->where('P.PERIOD_STATE', 1)
    ->where('T.TRANSACTION_ID', $transID)
    ->update([
        'Cshr_Num_Trs' => DB::raw('Cshr_Num_Trs - 1'),
        'Cshr_Val_Trs' => DB::raw('Cshr_Val_Trs - '.$transRefund),
        'CSHR_NUM_REFUND' => DB::raw('CSHR_NUM_REFUND + 1'),
        'CSHR_VAL_REFUND' => DB::raw('CSHR_VAL_REFUND + '.$grossRefund)
    ]);
        if($result)
        {
            return true;
        }
        return false;
    }
}
