<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finalisation extends Model
{
    use HasFactory;

    protected $table = 'Finalisations';
    protected $primaryKey = 'MOP_ID';
    protected $connection = 'enablerDb';

    protected $fillable = [
        'MOP_ID',
        'MOP_Name',
        'Price_Level',
        'MOP_Cash_Draw',
        'MOP_Safedrop_Level',
        'MOP_Safedrop_Alarm',
        'MOP_Tender',
        'MOP_Partial_Tender',
        'MOP_Change',
        'MOP_KeyNumber',
        'MOP_KeyLabel',
        'MOP_Final_Msg_L1',
        'MOP_Final_Msg_L2',
        'MOP_Final_Msg_L3',
        'MOP_Type',
        'MOP_PO',
        'MOP_Ref',
        'MOP_ReceiptCopyCount',
    ];

    protected $appends = [
        'safedrop_level',
        'safedrop_alarm',
    ];

    /**
     * Accessors
     */
    public function getSafedropLevelAttribute(){
        return $this->attributes['MOP_Safedrop_Level'];
    }

    public function getSafedropAlarmAttribute(){
        return $this->attributes['MOP_Safedrop_Alarm'];
    }
}
