<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManualHoseHistory extends Model
{
    use HasFactory;

    protected $table = 'Manual_Hose_History';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'Hose_ID',
        'Period_ID',
        'Postpay_Quantity',
        'Postpay_Value',
        'Postpay_Volume',
        'hose_vol_disc',
        'hose_val_disc',
        'hose_qty_disc',
    ];


    /**
     * Logics
     */
    public static function getFuelSales($period_id){
        return static::select(DB::raw('LTRIM(RTRIM(Grade_Name)) as Grade_Name, SUM(Postpay_Quantity) as postPayQty, SUM(Postpay_Volume) as postPayVol, SUM(Postpay_Value) as postPayVal'))
            ->where('Period_ID', $period_id)
            ->leftJoin('Hoses', 'Hoses.Hose_ID', 'Manual_Hose_History.Hose_ID')
            ->leftJoin('Grades', 'Grades.Grade_ID', 'Hoses.Grade_ID')
            ->groupBy(DB::raw('Hoses.Grade_ID, Grade_Name'))
            ->get();
    }

    public static function getFuelDiscount($period_id){
        return static::select(DB::raw("LTRIM(RTRIM(Grade_Name)) as Grade_Name, SUM(hose_vol_disc) as hoseVolDisc, SUM(hose_val_disc) as hoseValDisc, abs(sum(hose_qty_disc)) as hoseQtyDisc"))
        ->where('Period_ID', $period_id)
        ->leftJoin('Hoses', 'Hoses.Hose_ID', 'Manual_Hose_History.Hose_ID')
        ->leftJoin('Grades', 'Grades.Grade_ID', 'Hoses.Grade_ID')
        ->groupBy(DB::raw('Hoses.Grade_ID, Grade_Name'))
        ->get();
    }
}
