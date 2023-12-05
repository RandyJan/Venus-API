<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCertificate extends Model
{
    use HasFactory;

    protected $table = 'gift_cert';
    protected $primaryKey = 'giftcert_id';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'giftcert_id',
        'giftcert_number',
        'serial_number',
        'denomination',
        'status',
        'pos_trans_no',
        'date_created'
    ];
}
