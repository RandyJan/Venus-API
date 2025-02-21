<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class MOPGT extends Model
{
    use HasFactory;
    protected $table = 'Mop_GT';

    public function getMopGTByPosID($posID)
    {
        $result = static::select(DB::raw("Mop_ID, LTRIM(RTRIM(Mop_Name)) as Mop_Name, pos_id, ISNULL(Mop_Net_Sale_Value,0) as Mop_Net_Sale_Value"))
        ->where('pos_ID', $posID)
        ->get();
        if(!$result)
        {
            return false;
        }
        return $result;
    }
}
