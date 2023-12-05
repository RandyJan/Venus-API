<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosTerminal extends Model
{
    use HasFactory;

    protected $table = 'POS_Terminal';
    protected $primaryKey = 'POS_ID';
    protected $connection = 'enablerDb';

    /**
     * Logics
     */
    public static function getMasterPOS(){
        return static::where('POS_Master', 1)->first();
    }
}
