<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Period extends Model
{
    use HasFactory;

    protected $table = 'Periods';
    protected $primaryKey = 'Period_ID';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'Period_ID',
        'Period_Create_TS',
        'Period_Type',
        'Period_Close_DT',
        'Period_State',
        'Period_Name',
        'Period_Number',
        'Shift_number',
        'Tank_Dips_Entered',
        'Tank_Drops_Entered',
        'Pump_Meter_Entered',
        'Exported',
        'Export_Required',
        'WetStock_Out_Of_Variance',
        'WetStock_Approval_ID',
        'BeginningSI',
        'EndingSI',
    ];

    /**
     * Logics
     */
    public static function getPeriodDetails($period_id){
        return static::where('Period_ID', $period_id)->first();
    }

    public static function getActivePeriodByType($type){
        return static::where('Period_Type', $type)
            ->where('Period_State', 1)
            ->first();
    }
    public static function getActiveShiftPeriod(){
        // $data = array("Period_Type" => 1,
        //                 "Period_State"=>1);
                        $result = static::select('Period_ID')
                        ->where("Period_Type", 1)
                        ->where("Period_State", 1)
                        ->first();

                        if($result){
                            return $result->Period_ID;
                        }

                        return false;
    }
    public static function getAllActivePeriod(){
          $result =   static::select("Period_ID as periodID")
            ->where('Period_State',1)
            ->get();

            if($result)
            {
                return $result;
            }
            return false;

    }

    public static function getActivePeriods()
    {
        $result = static::where('Period_State', 1)->get();
        if(!$result)
        {
            return false;
        }
        return $result;
    }

    public static function closePeriodSP($periodType)
    {
        $result = DB::statement("EXEC SP_CLOSE_POS_PERIOD @period_type= ?", [$periodType]);
        if(!$result)
        {
            return false;
        }
        return $result;
    }
}
