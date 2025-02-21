<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Delivery_GT extends Model
{
    use HasFactory;

    protected $table = 'Delivery_GT';

    public function getDeliveryGTByPosID($posID)
    {
        // Delivery_GT.grade_id, LTRIM(RTRIM(Grades.Grade_Name)) as Grade_Name, pos_id, delivery_type, 
        // grade_trs, grade_vol, grade_val
        
        $result = static::select(DB::raw('Delivery_GT.grade_id, LTRIM(RTRIM(Grades.Grade_Name)) as Grade_Name, pos_id, delivery_type,
         ISNULL(grade_trs,0) as grade_trs, ISNULL(grade_vol,0) as grade_vol, ISNULL(grade_val,0) as grade_val'))
        ->where('pos_id', $posID)
        ->leftjoin('Grades', 'Grades.Grade_ID','Delivery_GT.grade_id')
        ->get();

        if(!$result)
        {
            return null;
        }
        return $result;
    }
}
