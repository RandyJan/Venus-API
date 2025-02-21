<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventType;
use App\Models\EventJournal;

class LoggingController extends Controller
{
    public function GetLoggingInfo()
    {
        $result = EventType::GetEventInfo();
        if(!$result)
        {
            return response("There's no event type.", 401);
        }
        return response($result);
    }

    public function SaveLog(Request $request)
    {
        $result = EventJournal::SaveLogEvent($request->deviceType, $request->deviceID, $request->deviceNumber, $request->eventType, $request->eventDescription);
        if(!$result)
        {
            return response('Saving failed', 401);
        }
        return response($result);
    }
}
