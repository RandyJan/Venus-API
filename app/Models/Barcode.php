<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;

    protected $table = 'Barcode';
    protected $primaryKey = 'Barcode';
    //protected $connection = 'enablerDb';

}
