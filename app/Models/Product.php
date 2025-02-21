<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'Products';
    protected $primaryKey = 'Product_ID';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'Product_ID',
        'Tax_ID',
        'Department_ID',
        'Product_Desc',
        'Product_Price',
        'Product_Quick_Code',
        'Dept_Tax_Exempt_Qty',
        'SCVat',
        'Discount_Rate',
    ];

    public static function getProductByDesc($key){
        $result =static::select('Products.Product_ID as id, LTRIM(RTRIM(Product_Desc)) as "desc", Product_Price as price, Barcode.Barcode as barcode,
        LTRIM(RTRIM(Product_Quick_Code)) as quickCode, Tax_ID as taxID, Department_ID as depID, SCVat as scVat, Discount_Rate as discRate')
        ->where('Product_Desc','like',$key)
        ->join("Barcode","Barcode.Product_ID = Products.Product_ID", "left")
        ->get();

   
    }

}
