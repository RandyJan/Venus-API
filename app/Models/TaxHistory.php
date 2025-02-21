<?php

namespace App\Models;

use App\Http\Resources\TaxHistoryCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxHistory extends Model
{
    use HasFactory;

    protected $table = 'Tax_History';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'Tax_ID',
        'Period_ID',
        'Tax_Tot_Value',
        'Tax_Sale_Value',
        'Tax_Discount_Value',
        'Tax_Refund_Value',
        'Tax_SeniorPWDDisc_Sale_Value',
        'Tax_SeniorPWDDisc_Discount_Value',
        'Tax_SeniorPWDDisc_Refund_Value',
        'Tax_NonTaxable_Discount',
    ];

    public static function getTaxHistoryByPeriodID($period_id){
        return new TaxHistoryCollection(
            static::where('Period_ID', $period_id)
            ->leftJoin('Taxes', 'Taxes.Tax_ID','Tax_History.Tax_ID')
            ->get()
        );
    }

}
