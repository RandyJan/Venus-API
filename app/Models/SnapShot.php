<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class SnapShot extends Model
{
    use HasFactory;

    public function callSaveLastDeltaGTReportDetailsSP($posID)
    {
        $result = DB::statement("EXEC Save_Last_Delta_GT_Report_Details @POS_ID= ?", [$posID]);
        if(!$result)
        {
            return false;
        }
        return "Success";
    }
    
    public function callSaveDeltaGTReportSnapshotSP($posID)
    {
        $result = DB::statement("EXEC Save_Delta_GT_Report_Snapshot @POS_ID=?", [$posID]);
        if(!$result)
        {
            return false;
        }
        return "Success";

    }
}
