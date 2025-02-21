<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Refund_GT as Refgt;
class Refund_GT extends Model
{
    use HasFactory;
    protected $table = 'refund_gt';

    public static function incrementVatableByPosId($posID,$money){
        $pos = Refgt::getByPosId($posID);
        $result = static::where('pos_id',$posID)->update(['vatable'=>abs($money) + $pos->vatable]);

        return $result;
    }
    public static function getByPosId($posID){
        $result = static::where('pos_id',$posID)->get();
        if(!$result){
            return false;
        }
        return $result[0];
    }
    public static function incrementVatExemptByPosId($posID,$money){
        $pos = Refgt::getByPosId($posID);
        $result = static::where('pos_id',$posID)->update(['vat_exempt'=>abs($money)+$pos->vat_exempt]);

        return $result;
    }
    public static function incrementZeroRatedByPosId($posID,$money){
        $pos = Refgt::getByPosId($posID);
        $result = static::where('pos_id',$posID)->update(['zero_rated',abs($money)+$pos->zero_rated]);

        return $result;
    }
    public $timestamps = false;
}
