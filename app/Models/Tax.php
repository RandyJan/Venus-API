<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'Taxes';
    protected $primaryKey = 'Tax_ID';
    protected $connection = 'enablerDb';

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
}
