<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Miscellaneous extends Model
{
    use HasFactory;

    protected $table = 'Miscellaneous';

    public function getAccReportMiscData()
    {
        $result = static::select(DB::raw("temp_delta_gt_report_date as endDate, last_delta_gt_report_date as lastReportDate"))
        ->get();

        if(!$result)
        {
            return false;
        }
        return $result;
    }
}
