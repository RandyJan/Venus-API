<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    use HasFactory;

    protected $table = 'Sub_Accounts';
    protected $primaryKey = 'Sub_Account_ID';
    protected $connection = 'enablerDb';

    protected $fillabe = [
        'Sub_Account_ID',
        'Account_ID',
        'SubAcc_Number',
        'SubAcc_Name',
        'SubAcc_Blocked',
        'SubAcc_Balance',
        'SubAcc_Limit',
        'SubAcc_Vehicle_ID',
        'SubAcc_Disct',
        'Sub_Account_Discount_Counter',
    ];
}
