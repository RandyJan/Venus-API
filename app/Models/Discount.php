<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';
    protected $primaryKey = 'discount_id';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'discount_id',
        'discount_name',
        'discount_type',
        'discount_value',
        'discount_keynumber',
        'discount_keylabel',
        'discount_allowoverride',
        'discount_restricted',
        'discount_code_id',
        'discount_input_type',
        'discount_list_position',
        'item_limit_unit_type',
        'item_limit_min_value',
        'item_limit_max_value',
        'department_id',
        'discount_list_visible',
    ];

    /**
     * Relationships
     */
    public function presets(){
        return $this->hasMany(DiscountPreset::class, 'discount_id', 'discount_id');
    }

    /**
     * Scopes
     */
    public function scopeVisible($query){
        return $query->where('discount_list_visible', 1);
    }
}
