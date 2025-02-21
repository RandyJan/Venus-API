<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccountDisc extends Model
{
    use HasFactory;

    protected $table = "Sub_Account_Disc";

    public function GetDiscounts($subAccountNumber)
    {
        $result = static::where('Sub_Account_ID', $subAccountNumber)->get();
        return $result;
    }
}
