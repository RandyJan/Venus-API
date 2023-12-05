<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $table = 'Transaction_Items';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'Transaction_ID',
        'Item_Number',
        'Item_Type',
        'Tax_ID',
        'Item_Description',
        'Item_Price',
        'Item_Quantity',
        'Item_Value',
        'Item_ID',
        'Item_Tax_Amount',
        'Item_Discount_Total',
        'is_tax_exempt_item',
        'is_zero_rated_tax_item',
    ];

    /**
     * Logics
     */
    public static function getByTransactionId($id){
        return static::where('Transaction_ID', $id)->get();
    }
}
