<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashdrawHistory extends Model
{
    use HasFactory;

    protected $table = 'CashDraw_History';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'MOP_ID',
        'CDraw_Period_ID',
        'CDraw_Tot_Amount',
        'CDraw_Float',
        'CDraw_Num_Safedrop',
        'CDraw_Tot_Safedrop',
        'CDraw_Num_Payin',
        'CDraw_Tot_Payin',
        'CDraw_Num_CashOut',
        'CDraw_Tot_CashOut',
    ];

    protected $appends = [
        'total_amount',
        'total_safedrop',
    ];

    /**
     * Accessors
     */
    public function getTotalAmountAttribute(){
        return (double)$this->attributes['CDraw_Tot_Amount'];
    }
    public function getTotalSafedropAttribute(){
        return (double)$this->attributes['CDraw_Tot_Safedrop'];
    }

    /**
     * Logics
     */
    public static function getCurrentCashDrawRecord(){
        return static::leftJoin('CashDraw_Periods', 'CashDraw_Periods.CDraw_Period_ID', '=', 'CashDraw_History.CDraw_Period_ID')
            ->where('CDraw_Period_state', 1)
            ->first();
    }

    public static function getCdrawHistoryByCdrawPeriod($Cdraw_period_id){
        return static::select(DB::raw("cashdraw_history.MOP_ID, finalisations.MOP_Name, CDraw_Tot_Amount, CDraw_Float, CDraw_Num_Safedrop, CDraw_Tot_Safedrop, CDraw_Num_Payin, CDraw_Tot_Payin, CDraw_Num_CashOut, CDraw_Tot_CashOut"))
            ->where('CDraw_Period_ID', $Cdraw_period_id)
            ->leftJoin('Finalisations', 'Finalisations.MOP_ID', 'CashDraw_History.MOP_ID')
            ->get();
    }
     public function getCashdrawSafedropDetails($cashdrawPeriodID){
        $result = static::select("CDraw_Num_Safedrop","CDraw_Tot_Safedrop","CDraw_Tot_Amount")
        ->where("CDraw_Period_ID",$cashdrawPeriodID)
        ->where("MOP_ID",1)
        ->get();
        // if(is_null($result)){
        //     return false;
        // }
        if($result){
            return $result;
        }
        return false;
     }

     public function getCDrawHistByCDPeriod($cashdrawPeriodID)
     {
        $result = static::select(DB::raw("CashDraw_History.MOP_ID, Finalisations.MOP_Name, CDraw_Tot_Amount, CDraw_Float, CDraw_Num_Safedrop,
         CDraw_Tot_Safedrop, CDraw_Num_Payin, CDraw_Tot_Payin, CDraw_Num_CashOut, CDraw_Tot_CashOut, CDraw_Tot_Refund"))
        ->where('CDraw_Period_ID', $cashdrawPeriodID)
        ->leftjoin("Finalisations", "Finalisations.MOP_ID","CashDraw_History.MOP_ID")
        ->get();

        if(is_null($result)){
            return false;
        }

        if($result){
              return $result;
        }

        return false;
        
     }
}
