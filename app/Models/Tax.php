<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'Taxes';
    protected $primaryKey = 'Tax_ID';
   // protected $connection = 'enablerDb';

    protected $appends = [
        'tax_name',
        'tax_legend'
    ];

    /**
     * Accessors
     */
    public function getTaxNameAttribute(){
        return trim($this->attributes['Tax_Name']);
    }

    public function getTaxLegendAttribute(){
        return trim($this->attributes['Tax_Legend']);
    }
    public static function getTaxRate($taxID){
        $result = static::select('Tax_Rate')
        ->where('Tax_ID',$taxID)
        ->first();
        if($result){
            return $result->Tax_rate;
        }
        return false;
    }
}
