<?php

namespace App\Models;

use App\Http\Resources\CashdrawDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashdrawPeriod extends Model
{
    use HasFactory;

    protected $table = 'CashDraw_Periods';
    protected $primaryKey = 'CDraw_Period_ID';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'CDraw_Period_ID',
      'Cashier_ID',
      'POS_ID',
      'CDraw_Open_Date',
      'CDraw_Close_Date',
      'CDraw_Period_state',
    ];

    /**
     *  Logics
     */
    public static function getCashDrawDetails($cdraw_period_id){
        return static::selectRaw('CDraw_Period_ID, Cashier_Name, CDraw_Open_Date, CDraw_Close_Date')
            ->where('CDraw_Period_ID', $cdraw_period_id)
            ->leftJoin('cashiers', 'cashiers.Cashier_ID', 'CashDraw_Periods.Cashier_ID')
            ->first();
    }
}
