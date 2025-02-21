<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class PosTerminal extends Model
{
    use HasFactory;

    protected $table = 'POS_Terminal';
    protected $primaryKey = 'POS_ID';
    //protected $connection = 'enablerDb';

    /**
     * Logics
     */
    public static function getMasterPOS(){
        return static::where('POS_Master', 1)->first();
    }
    public static function getTransNumber($posID){
       return static::where('POS_ID', $posID)->get();
    }
    // public function getNonBirTransNumber($posID){
    //     return static::select('Non_BIR_Trans_Number')->where()
    // }
    public static function updatePOSTransNum($transNum,$posID){
        $result = static::where('POS_ID',$posID)->update(['POS_Trans_Number'=>$transNum
        ]);

                    if($result){
                        return true;
                    }
                    return false;
    }
    public static function incrementBirResetCounter($posID,$val = 0,$i = 1){
        $result =  static::where('POS_ID',$posID)->update([
            "BIR_Non_Fuel_Trans_Number"=>0,
            "BIR_Reset_Counter"=>$nval =$val+$i,
            
        ]);
        return $nval;
    }
    public static function updateBIRFuelTransNum($transNum, $posID)
{
    $result = static::where( 'POS_ID',$posID)->update(['BIR_Fuel_Trans_Number'=>$transNum]);
    if($result){
        return true;
    }
    return false;
}
public static function updateBIRNonFuelTransNum($transNum,$posID){
    $result = static::where('POS_ID',$posID)->update(['BIR_Non_Fuel_Trans_Number'=>$transNum]);
        if($result){
            return true;
        }
        return false;
}
public static function incrementNonBirTransNumber($posID,$val = 0,$i=1){
    $result = static::where('POS_ID',$posID)->update(['Non_BIR_Trans_Number'=> $nval = $val+$i]);

    return $nval;
}

public static function incrementNonBirResetCounter($posID, $val=0, $i = 1)
{
    static::where('POS_ID',$posID)->update(['Non_BIR_Trans_Number'=> 1, 'Non_BIR_Trans_Number_Reset_Counter' => $nval = $val + $i]);
    return $nval;
}

public function getAccReportPOSData($posID)
{
    $result = static::select(DB::raw("gt_open_date as startDate, LTRIM(RTRIM(last_bir_non_fuel_trans_number)) as begSalesInvoice, LTRIM(RTRIM(last_bir_resetter)) as begSalesResetter,
     LTRIM(RTRIM(temp_bir_non_fuel_trans_number)) as endSalesInvoice, LTRIM(RTRIM(temp_bir_resetter)) as endSalesResetter,  ZCounter"))
     ->where('POS_ID', $posID)
     ->get();

     if(!$result)
     {
        return false;
     }
     return $result;
}

    public $timestamps = false;
}
