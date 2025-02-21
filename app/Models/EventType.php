<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EventType extends Model
{
    use HasFactory;
    protected $table = "Event_type";

    public static function GetEventInfo()
    {
        $result = static::select(DB::raw("Event_Type, RTRIM(Event_name) as Event_name"))
        ->where("Device_Type", 15)
        ->get();
        if(!$result)
        {
            return false;
        }
        return $result;
    }
}
