<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManualCashdrawHistory extends Model
{
    use HasFactory;

    protected $table = 'Manual_CDrawGrade_History';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'CDraw_Period_ID',
        'Grade_ID',
        'CDrawGrade_Trs',
        'CDrawGrade_Vol',
        'CDrawGrade_Val',
        'CDrawGrade_Vol_Disc',
        'CDrawGrade_Val_Disc',
    ];

    /**
     * Logics
     */
    public static function getManualCDrawGradeHistByCDPeriodID($cdraw_period_id){
        return static::select(DB::raw("Manual_CDrawGrade_History.Grade_ID, Grade_Name, CDrawGrade_Trs, CDrawGrade_Vol, CDrawGrade_Val"))
            ->where('CDraw_Period_ID', $cdraw_period_id)
            ->leftJoin('Grades', 'Grades.Grade_ID', 'Manual_CDrawGrade_History.Grade_ID')
            ->get();
    }
}
