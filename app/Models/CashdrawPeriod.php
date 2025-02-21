<?php

namespace App\Models;

use App\Http\Resources\CashdrawDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CashDraw_Periods_DB as cashierperiod;
use App\Models\CashdrawHistory;
use PhpParser\Node\Stmt\Static_;
use DB;
class CashdrawPeriod extends Model
{
    use HasFactory;

    protected $table = 'CashDraw_Periods';
    protected $primaryKey = 'CDraw_Period_ID';
    //protected $connection = 'enablerDb';

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
        return static::select(DB::raw("CashDraw_Periods.CDraw_Period_ID, cashiers.Cashier_Name, CDraw_Open_Date, CDraw_Close_Date"))
            ->where('CDraw_Period_ID', $cdraw_period_id)
            ->leftJoin('cashiers', 'cashiers.Cashier_ID', 'CashDraw_Periods.Cashier_ID')
            ->first();
    }

    public static function getActiveCdrwPeriod($cashierID, $posID){
        $result =  cashierperiod::getActiveCdrwPeriod($cashierID,$posID);    
        
        if($result){
            return array(
                "result"=> 1,
                "message"=>"success",
                "data"=>$result
            );
        }


            return array(
                "result"=> 0,
                "message"=>"No active cashdraw found",
                    );
 
        
    }
    public Static function getCashDrawSafedropDetails($cashdrawPeriodID){
        $retData = CashdrawHistory::getCashdrawSafedropDetails($cashdrawPeriodID);
        if($retData){
            return array(
                "result"=> 1,
                "message"=>"success",
                "data"=>$retData
            );
        }
        return array(
            "result"=> 1,
            "message"=>"No safedrop details available",
           
        );
    }

    public function GetAllCashdrawByPosID($posID)
    {
        $return = static::select(DB::raw("CDraw_Period_ID, LTRIM(RTRIM(Cashiers.Cashier_Name)) as Cashier_Name, CDraw_CLose_Date"))
        ->where("POS_ID", $posID)
        ->leftJoin("Cashiers", "Cashiers.Cashier_ID", "CashDraw_Periods.Cashier_ID")
        ->get();

        return $return;
    }
}
