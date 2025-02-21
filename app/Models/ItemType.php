<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;

    protected $table = 'Item_Types';
    protected $primaryKey = 'Item_Type';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'Item_Type',
        'Item_Type_Descr'
    ];
}
