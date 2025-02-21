<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EventJournal extends Model
{
    use HasFactory;

    protected $table = "Event_Journal";

    public static function SaveLogEvent($deviceType, $deviceID, $deviceNumber, $eventType, $eventDesc)
    {
        // @id int OUTPUT ,         -- ##PARAM @ID            ID of the event record created.
        // @device_type smallint ,  -- ##PARAM @Device_Type   Value indicating the type of device the event occurred at.
        // @device_id int ,         -- ##PARAM @Device_ID     Where relevant the ID of the device generating the event.
        // @device_number int ,     -- ##PARAM @Device_Number The logical number of the device.
        // @event_type int ,        -- ##PARAM @Event_Type    The Enabler internal Event type
        // @desc nvarchar(320)    
        $eventID = static::max("Event_ID") + 1;
        $triggerLogSaving = DB::statement("EXEC sp_Log_Event ?,?,?,?,?,?", [$eventID, $deviceType, $deviceID, $deviceNumber, $eventType, $eventDesc]);
        if(!$triggerLogSaving)
        {
            return false;
        }
        return $triggerLogSaving;
    }
}
