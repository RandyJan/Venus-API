<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class PosPeriodBirTransactionNumber extends Model
{
    use HasFactory;

    protected $table = 'pos_periods_bir_trans_num';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'pos_id',
        'Period_ID',
        'BeginningSI',
        'EndingSI',
        'BeginningResetter',
        'EndingResetter',
    ];

    /**
     * Logics
     */
    public static function nsactionNumbers($period_id){
        return static::where('Period_ID', $period_id)->get();
    }
    public static function updateBegTransNumPerTrans($periodID,$posID,$transNum,$transNumResetter){
            $result = static::where('Period_ID',$periodID)
            ->where('pos_id',$posID)
            ->where('BeginningSI',0)
            ->update(['BeginningSI' =>$transNum,
                        'BeginningResetter'=>$transNumResetter]);

                        if($result){

                            return true;
                        }
                        return false;
    }
    public static function updateEndingTransNumPerTrans($periodID,$posID,$transNum,$transNumResetter)
    {
        $result = static::where('Period_ID',$periodID)
        ->where('pos_id',$posID)
        ->update(['EndingSI' =>$transNum,
                'EndingResetter'=>$transNumResetter]);

                    if($result){

                        return true;
                    }
                    return false;
    }

    public static function getTransactionNumbers($periodID)
    {
        $query = static::select(DB::raw('LTRIM(RTRIM(EndingSI)) as EndingSI,LTRIM(RTRIM(EndingResetter)) as EndingResetter, LTRIM(RTRIM(BeginningSI)) as BeginningSI,
         LTRIM(RTRIM(BeginningResetter)) as BeginningResetter, pos_id as pos_id'))
        ->where("Period_ID", $periodID)
        ->get();
        return $query;
    }
    public $timestamps = false;
}
