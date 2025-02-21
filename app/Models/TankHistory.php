<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TankHistory extends Model
{
    use HasFactory;

    protected $table = 'Tank_History';
    //protected $connection = 'enablerDb';

    /**
     * Reports
     */
    public static function getTankHistoryByPeriod($period_id){
        return static::select(DB::raw("LTRIM(RTRIM(Tanks.Tank_Name)) as tankName, Tank_History.Open_Gauge_Volume as openVolume, Tank_History.Close_Gauge_Volume as closeVolume, Tank_History.Tank_Del_Volume as addedVolume, Tank_History.Hose_Del_Volume as soldVolume"))
        ->where('Period_ID', $period_id)
        ->leftJoin('Tanks', 'Tank_History.Tank_ID', 'Tanks.Tank_ID')
        ->get();
    }
}
