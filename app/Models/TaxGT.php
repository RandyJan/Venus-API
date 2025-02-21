<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TaxGT extends Model
{
    use HasFactory;
    protected $table = 'Tax_GT';

    public function getTaxGTByPosID($posID)
    {
        // $result = static::select(DB::raw())
        // ->where()
        // ->get()
        
        $result = static::select(DB::raw("LTRIM(RTRIM(TaxGT_Name)) as taxName, ISNULL(TaxGT_Taxable_Sale_Value,0) as taxSaleVal, pos_id"))
        ->where('pos_id', $posID)
        ->orderby("TaxGT_Id", "asc")
        ->get();

        if(!$result)
        {
            return false;
        }
        return $result;
    }

    public function getTaxDiscTotalByPosID($posID)
    {
        $result = static::select(DB::raw("LTRIM(RTRIM(TaxGT_Name)) as taxName, ((ISNULL(TaxGT_Taxable_Discount_Value,0) - (ISNULL(TaxGT_SeniorDisc_Discount_Value,0) - ISNULL(TaxGT_SeniorDisc_Refund_Value,0))) - ISNULL(TaxGT_TaxableDiscount_Refund, 0)) as discSaleVal, ISNULL(TaxGT_Discount_Tax_Value,0) as discTaxVal, pos_id"))
        ->where('pos_id', $posID)
        ->orderby("TaxGT_Id", "asc")
        ->get();
        
        if(!$result)
        {
            return false;
        }
        return $result;
    }
    
    public function getSaleTotal($posID)
    {
        $result = static::select(DB::raw("LTRIM(RTRIM(TaxGT_Name)) as taxName, ISNULL(TaxGT_Taxable_Sale_Value,0) as saleVal, ISNULL(TaxGT_Sale_Tax_Value,0) as saleTaxVal, pos_id"))
        ->where('pos_id', $posID)
        ->orderby("TaxGT_Id", "asc")
        ->get();

        if(!$result)
        {
            return false;
        }
        return $result;
    }
    
    public function getTaxSCPWDDiscTotalByPosID($posID)
    {
        $result = static::select(DB::raw("LTRIM(RTRIM(TaxGT_Name)) as taxName, ISNULL(TaxGT_SeniorDisc_Discount_Value,0) - ISNULL(TaxGT_SeniorDisc_Refund_Value,0) as scpwdDiscVal, ISNULL(TaxGT_SeniorDisc_Discount_Tax_Value,0) as scowdTaxVal, pos_id"))
        ->where('pos_id', $posID)
        ->orderby("TaxGT_Id", "asc")
        ->get();

        if(!$result)
        {
            return false;
        }
        return $result;
    }
    
    
    public function getPrevAccumTotalSalesRaw($posID)
    {
        $result = static::select(DB::raw("ISNULL(SUM(Last_Taxable_Sale_value),0) -
        ISNULL(SUM(Last_Taxable_Refund_value),0) -
        ISNULL(SUM(Last_Taxable_Discount_value),0) -
        ISNULL(SUM(Last_TaxableDiscount_Refund),0) as Previous_Accumulating_Total_Sales"))
        ->where('pos_id', $posID)
        ->get();

        if(!$result)
        {
            return false;
        }
        return $result;
    }
    
    public function getCurrAccumTotalSalesRaw($posID)
    {
       
        $result = static::select(DB::raw("ISNULL(SUM(Temp_Taxable_Sale_value),0) -
        ISNULL(SUM(Temp_Taxable_Refund_value),0) -
        ISNULL(SUM(Temp_Taxable_Discount_Value),0) -
        ISNULL(SUM(Temp_TaxableDiscount_Refund),0) as Current_Accumulating_Total_Sales"))
        ->where('pos_id', $posID)
        ->get();

        if(!$result)
        {
            return false;
        }
        return $result;
    }

    public function getTotalNetSales($posID)
    {
        $result = static::select(DB::raw("LTRIM(RTRIM(TAXGT_name)) as taxName, 
            ISNULL(Temp_Taxable_Sale_value,0) -
    CASE
        WHEN( ISNULL(Temp_Taxable_Sale_value,0) = 0  AND (ISNULL(Temp_Taxable_Discount_Value,0) - ISNULL(Temp_TaxableDiscount_Refund,0)) != 0 ) THEN 0 ELSE (ISNULL(Temp_Taxable_Discount_Value,0) - ISNULL(Temp_TaxableDiscount_Refund,0))
    END
    - ISNULL(Temp_Taxable_Refund_Value,0) as saleValue,
            (ISNULL(Temp_Sale_Tax_Value,0) - ISNULL(Temp_Refund_Tax_Value,0) - ISNULL(Temp_Discount_Tax_Value,0)) as vatAmount,
            (Temp_NonTaxable_Discount_Value - Temp_NonTaxable_Discount_Refund) as NonVatDiscount"))
            ->where('pos_id', $posID)
            ->orderby("TaxGT_Id", "asc")
            ->get();
            
            if(!$result){
                return false;
            }
            return $result;

    }
}
