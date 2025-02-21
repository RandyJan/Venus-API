<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VoidEvent extends Model
{
    use HasFactory;
    protected $table = "Void_Event";

    public static function getVoidData($dateFrom, $dateTo)
    {
        // $this->db->select("Void_Event.Void_Event_Date, Void_Event.POS_ID, Void_Event.Item_Description, Void_Event.Item_Value, Void_Event.Cashier_ID, Cashiers.Cashier_Name");
		// $this->db->where("Void_Event_Date >=", $startDate);
		// $this->db->where("Void_Event_Date <", $endDate);
		// $this->db->join("Cashiers", "Void_Event.Cashier_ID = Cashiers.Cashier_ID", "left");
		// $result = $this->db->get($this->table);

        $result = static::select(DB::raw("Void_Event.Void_Event_Date, Void_Event.POS_ID, Void_Event.Item_Description, Void_Event.Item_Value, Void_Event.Cashier_ID, RTRIM(Cashiers.Cashier_Name) as Cashier_Name"))
        ->whereDate("Void_Event_Date", ">=", $dateFrom)
        ->whereDate("Void_Event_Date", "<=", $dateTo)
 //       ->whereBetween("Void_Event_Date", [$dateFrom, $dateTo])
        ->leftjoin("Cashiers", "Void_Event.Cashier_ID", "Cashiers.Cashier_ID")
        ->get();

        if(!$result)
        {
            return "No void event data for from this date";
        }
        return $result;
    }
}
