<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class DepartmentGT extends Model
{
    use HasFactory;
    protected $table = "Department_GT";

    public function getDepartmentGTByPosID($posID)
    {
        $result = static::select(DB::raw("Department_GT.department_id, LTRIM(RTRIM(Dept_Name)) as Dept_Name, pos_id, ISNULL(dept_qty_item_sold,0) as dept_qty_item_sold, ISNULL(dept_val_item_sold,0) as dept_val_item_sold"))
        ->where('pos_id', $posID)
        ->leftjoin("Departments", "Departments.department_id", "Department_GT.department_id")
        ->get();
        if(!$result)
        {
            return null;
        }
        return $result;
    }
}
