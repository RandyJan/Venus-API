<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EJournal extends Model
{
    use HasFactory;

    protected $connection = 'enablerDb';
    protected $table = 'electric_journal';
    protected $fillable =
    [
        'Transaction_ID',
        'pos_id',
        'Transaction_Date',
        'si_number',
        'data',
        'print_count'
    ];

    public $timestamps = false;
}
