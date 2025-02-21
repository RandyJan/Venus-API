<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class TransactionItem extends Model
{
    use HasFactory;

    protected $table = 'Transaction_Items';

    public $timestamps = false;
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
    public static function callLogAccountTransaction($accountID,$subAccID,$Amount){
        $result = DB::statement('EXEC SP_LOG_ACCOUNT_TRANSACTION ?,?,?',[$accountID,$subAccID,$Amount]);
        return $result;

    }
    public static function callTransItemsSP($TRANS_ID,$ITEM_NUMBER,$ITEM_TAX_ID,$ITEM_TYPE,$ITEM_DESC,$ITEM_PRICE, $ITEM_QTY,
     $ITEM_VALUE, $ITEM_ID, $ITEM_TAX_AMOUNT, $DELIVERY_ID, $original_item_value_pre_tax_change, $is_tax_exempt_item, $is_zero_rated_tax_item, 
     $posID, $itemDiscTotal, $itemDiscCodeType, $itemDBPrice){
        $result = DB::statement('EXEC SP_LOG_TRANSACTION_ITEM ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',[$TRANS_ID,$ITEM_NUMBER,$ITEM_TAX_ID,$ITEM_TYPE,$ITEM_DESC,$ITEM_PRICE, $ITEM_QTY,
        $ITEM_VALUE, $ITEM_ID, $ITEM_TAX_AMOUNT, $DELIVERY_ID, $original_item_value_pre_tax_change, $is_tax_exempt_item, $is_zero_rated_tax_item, 
        $posID, $itemDiscTotal, $itemDiscCodeType, $itemDBPrice]);

        return $result;
    }

    public static function getRefundItems($transactionID)
    {
       	$result = static::select(DB::raw("Item_number, RTRIM(Item_Description) as Item_Description, ABS(Item_Value) as Value, Item_Type"))
        ->where("Transaction_ID", $transactionID)
        ->get();

        if(!$result)
        {
            return "No transaction item for this refund";
        }

        return $result;
    }
}
