<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosPeriodBirTransactionNumber extends Model
{
    use HasFactory;

    protected $table = 'pos_periods_bir_trans_num';
    protected $connection = 'enablerDb';

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
    public static function getTransactionNumbers($period_id){
        return static::where('Period_ID', $period_id)->get();
    }
}
