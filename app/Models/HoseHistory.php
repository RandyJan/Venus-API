<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HoseHistory extends Model
{
    use HasFactory;

    protected $table = 'Hose_History';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'Hose_ID',
        'Period_ID',
        'Open_Meter_Value',
        'Close_Meter_Value',
        'Postpay_Quantity',
        'Open_Meter_Volume',
        'Postpay_Value',
        'Close_Meter_Volume',
        'Postpay_Volume',
        'Postpay_Cost',
        'Prepay_Quantity',
        'Prepay_Value',
        'Prepay_Volume',
        'Prepay_Cost',
        'Prepay_Refund_Qty',
        'Prepay_Refund_Val',
        'Preauth_Quantity',
        'Prepay_Rfd_Lst_Qty',
        'Preauth_Value',
        'Prepay_Rfd_Lst_Val',
        'Preauth_Volume',
        'Preauth_Cost',
        'Monitor_Quantity',
        'Monitor_Value',
        'Monitor_Volume',
        'Monitor_Cost',
        'Driveoffs_Quantity',
        'Driveoffs_Value',
        'Driveoffs_Volume',
        'Driveoffs_Cost',
        'Test_Del_Quantity',
        'Test_Del_Volume',
        'Offline_Quantity',
        'Offline_Volume',
        'Offline_Value',
        'Offline_Cost',
        'Open_Mech_Volume',
        'Close_Mech_Volume',
        'Open_Volume_Turnover_Correction',
        'Open_Money_Turnover_Correction',
        'Close_Volume_Turnover_Correction',
        'Close_Money_Turnover_Correction',
        'Open_Volume_Turnover_Correction2',
        'Close_Volume_Turnover_Correction2',
        'hose_vol_disc',
        'hose_val_disc',
        'hose_qty_disc',
    ];

    /**
     * Reports
     */
    public static function getFuelSales($period_id){
        return static::select(DB::raw('Grade_Name, SUM(Postpay_Quantity) as postPayQty, SUM(Postpay_Volume) as postPayVol, SUM(Postpay_Value) as postPayVal, SUM(Test_Del_Quantity) as testDelQty, SUM(Test_Del_Volume) as testDelVol, SUM(Offline_Quantity) as offQty, SUM(Offline_Volume) as offVol, SUM(Offline_Value) as offVal, SUM(Driveoffs_Quantity) as driveOffQty, SUM(Driveoffs_Value) as driveOffVal, SUM(Driveoffs_Volume) as driveOffVol, SUM(Driveoffs_Cost) as driveOffCost'))
            ->where('Period_ID', $period_id)
            ->leftJoin('Hoses', 'Hoses.Hose_ID', 'Hose_History.Hose_ID')
            ->leftJoin('Grades', 'Grades.Grade_ID', 'Hoses.Grade_ID')
            ->groupBy(DB::raw('Hoses.Grade_ID, Grade_Name'))
            ->get();
    }

    public static function getFuelSalesDisc($period_id){
        return static::where('Period_ID', $period_id)
            ->select(DB::raw("LTRIM(RTRIM(Grade_Name)) as Grade_Name, abs(SUM(hose_vol_disc)) as hoseVolDisc, SUM(hose_val_disc) as hoseValDisc, SUM(hose_qty_disc) as hoseQtyDisc"))
            ->leftJoin('Hoses', 'Hoses.Hose_ID', 'Hose_History.Hose_ID')
            ->leftJoin('Grades', 'Grades.Grade_ID', 'Hoses.Grade_ID')
            ->groupBy(DB::raw('Hoses.Grade_ID, Grade_Name'))
            ->get();
    }

    public static function getHoseHistoryData($period_id){  //refactor
        return static::select(DB::raw("
            Hose_History.Hose_ID as hoseID,
            Hose_number as hoseNum,
            LTRIM(RTRIM(Grade_Name)) as gradeName,
            Hoses.Pump_ID as pumpID,
            LTRIM(RTRIM(Pump_Name)) as pumpName,
            Logical_Number as logicalNum,
            Period_ID as periodID,
            Open_Meter_Value as openMeterVal,
            Open_Meter_Volume as openMeterVol,
            Close_Meter_Value as closeMeterVal,
            Close_Meter_Volume as closeMeterVol,
            (Postpay_Value + Driveoffs_Value) as postpayVal,
            (Postpay_Volume + Driveoffs_Volume + Test_Del_Volume) as postpayVol,
            Driveoffs_Value as driveoffVal,
            Driveoffs_Volume as driveoffVol,
            Test_Del_Volume as testDelVol
        "))->where('Period_ID', $period_id)
        ->leftJoin('Hoses', 'Hoses.Hose_ID', 'Hose_History.Hose_ID')
        ->leftJoin('Pumps', 'Pumps.Pump_ID', 'hoses.Pump_ID')
        ->leftJoin('Grades', 'Grades.Grade_ID', 'Hoses.Grade_ID')
        ->get();
    }
}
