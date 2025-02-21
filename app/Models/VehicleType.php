<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $table = 'VehicleType';
    protected $primaryKey = 'vehicleTypeID';
    //protected $connection = 'enablerDb';

    protected $fillable = [
        'vehicleTypeID',
        'vehicleTypeName'
    ];
}
