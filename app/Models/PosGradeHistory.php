<?php

namespace App\Models;

use App\Http\Resources\GradeTaxExemptHistoryCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosGradeHistory extends Model
{
    use HasFactory;

    protected $table = 'pos_grade_history';
    protected $connection = 'enablerDb';

    /**
     * Logics
     */
    public static function getGradeTaxExempt($period_id){
        return new GradeTaxExemptHistoryCollection(
            static::where('period_id', $period_id)
            ->leftJoin('Grades', 'Grades.Grade_ID', 'pos_grade_history.grade_id')
            ->get()
        );
    }
}
