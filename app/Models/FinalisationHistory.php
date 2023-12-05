<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FinalisationHistory extends Model
{
    use HasFactory;

    protected $table = 'Finalisation_History';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'MOP_ID',
        'Period_ID',
        'MOP_Net_Qty',
        'MOP_Net_Value',
        'MOP_Num_Safedrop',
        'MOP_Val_Safedrop',
        'MOP_Num_Payin',
        'MOP_Val_Payin',
        'MOP_Num_Cashout',
        'MOP_Val_Cashout',
        'MOP_val_CashAdj',
    ];

    /**
     * Logics
     */
    public static function getAccountDiscountSales($period_id){
        return static::select(DB::raw('SUM(MOP_Val_Cashout) as accDisc'))
            ->where('Period_ID', $period_id)
            ->first();
    }

    public static function getFinalisationTotals($period_id){
        return static::select(DB::raw('Finalisation_History.MOP_ID, MOP_Name, MOP_Net_Qty, MOP_Net_Value, MOP_Num_Safedrop, MOP_Val_Safedrop, MOP_Num_Cashout, MOP_Val_Cashout'))
            ->where('Period_ID', $period_id)
            ->leftJoin('Finalisations', 'Finalisations.MOP_ID', 'Finalisation_History.MOP_ID')
            ->get();
    }
}
