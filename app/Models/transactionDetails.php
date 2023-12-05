<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactionDetails extends Model
{
    use HasFactory;

    protected $table = 'Transaction_Details';

    protected $fillable = [
        'Transaction_ID',
        'CustomerName',
        'Address',
        'TIN',
        'BusinessStyle',
        'CardNumber',
        'ApprovalCode',
        'BankCode',
        'Type'

    ];
}
