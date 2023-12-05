<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashierHistory extends Model
{
    use HasFactory;

    protected $table = 'Cashier_History';
    protected $connection = 'enablerDb';

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
}
