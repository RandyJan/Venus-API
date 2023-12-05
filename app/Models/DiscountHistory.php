<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DiscountHistory extends Model
{
    use HasFactory;

    protected $table = 'Discount_History';
    protected $connection = 'enablerDb';

    /**
     * Logics
     */
    public static function getDiscountTotal($period_id){
        return static::select(DB::raw("LTRIM(RTRIM(discount_name)) as discount_name, abs(discount_qty) as discQty, discount_val as discVal"))
            ->where('Period_ID', $period_id)
            ->leftJoin('discounts', 'discounts.discount_id', 'Discount_History.discount_id')
            ->get();
    }
}
