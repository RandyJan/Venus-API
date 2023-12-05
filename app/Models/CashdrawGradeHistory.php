<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashdrawGradeHistory extends Model
{
    use HasFactory;

    protected $table = 'CDrawGrade_History';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'CDraw_Period_ID',
        'Grade_ID',
        'CDrawGrade_Trs',
        'CDrawGrade_Vol',
        'CDrawGrade_Val',
        'CDrawGrade_Vol_Disc',
        'CDrawGrade_Val_Disc',
        'CDrawGrade_Qty_Surc',
        'CDrawGrade_Val_Surc',
    ];

    /**
     * Logics
     */
    public static function getCDrawGradeHistByCDPeriodID($cdraw_period_id){
        return static::select(DB::raw("CDrawGrade_History.Grade_ID, Grade_Name, CDrawGrade_Trs, CDrawGrade_Vol, CDrawGrade_Val"))
            ->where('CDraw_Period_ID', $cdraw_period_id)
            ->leftJoin('Grades', 'Grades.Grade_ID', 'CDrawGrade_History.Grade_ID')
            ->get();
    }
}
