<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashdrawDepartmentHistory extends Model
{
    use HasFactory;

    protected $table = 'CDrawDept_History';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'CDraw_Period_ID',
        'Department_ID',
        'CDrawDept_Qty_Sld',
        'CDrawDept_Val_Sld',
        'CDrawDept_Qty_Ref',
        'CDrawDept_Val_Ref',
        'CDrawDept_Qty_Disc',
        'CDrawDept_Val_Disc',
        'CDrawDept_Qty_Surc',
        'CDrawDept_Val_Surc',
    ];

    /**
     * Logics
     */
    public static function getCDrawDeptHistByCDPeriodID($cdraw_period_id){
        return static::select(DB::raw("CDrawDept_History.Department_ID, Dept_Name, CDrawDept_Qty_Sld, CDrawDept_Val_Sld, CDrawDept_Qty_Ref, CDrawDept_Val_Ref"))
            ->where('CDraw_Period_ID', $cdraw_period_id)
            ->leftJoin('Departments', 'Departments.Department_ID', 'CDrawDept_History.Department_ID')
            ->get();
    }
}
