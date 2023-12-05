<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $table = 'Periods';
    protected $primaryKey = 'Period_ID';
    protected $connection = 'enablerDb';

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
}
