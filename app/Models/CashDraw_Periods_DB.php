<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CashDraw_Periods_DB extends Model
{
    use HasFactory;
    protected $table = "CashDraw_Periods";




    public function getActiveCdrwPeriod($cashierID,$posID){
        
        $cdraw = static::select('CDraw_Period_ID')->where('POS_ID', $posID)
        ->where('CDraw_Period_state', 1)->first();

        return $cdraw;
    }

    public function closeCDrawPeriodSP($cashierID, $posID)
    {
        $result = DB::statement("EXEC SP_CLOSE_CDRAW_PERIOD @POS_ID= ? , @CASHIER_ID= ? ", [$posID, $cashierID]);
        return $result;
    }
    
}
