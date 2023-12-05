<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentHistory extends Model
{
    use HasFactory;

    protected $table = 'Department_History';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'Department_ID',
        'Period_ID',
        'Dept_Qty_Item_Sold',
        'Dept_Val_Item_Sold',
        'Dept_Qty_Item_Ref',
        'Dept_Val_Item_Ref',
        'Dept_Qty_Disc',
        'Dept_Val_Disc',
        'Dept_Tax_Item_Sold',
        'Dept_Tax_Item_Ref',
        'Dept_Tax_Disc',
        'Dept_Tax_Exempt_Value',
        'Dept_Tax_Exempt_Qty',
    ];

    /**
     * Logics
     */
    public static function departmentSales($period_id){
        return static::where('Period_ID', $period_id)
            ->leftJoin('Departments', 'Departments.Department_ID', 'Department_History.Department_ID')
            ->get();
    }

    public static function getDepartmentTaxExempt($period_id){
        return static::where('Period_ID', $period_id)
            ->leftJoin('Departments', 'Departments.Department_ID', 'Department_History.Department_ID')
            ->get();
    }

    public static function getDepartmentDiscounts($period_id){
        return static::where('Period_ID', $period_id)
        ->leftJoin('Departments', 'Departments.Department_ID', 'Department_History.Department_ID')
        ->get();
    }

    public static function getDepartmentRefund($period_id){
        return static::where('Period_ID', $period_id)
            ->leftJoin('Departments', 'Departments.Department_ID', 'Department_History.Department_ID')
            ->get();
    }
}
