<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'Products';
    protected $primaryKey = 'Product_ID';
    protected $connection = 'enablerDb';

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

}
