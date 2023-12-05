<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Cashier extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->Cashier_ID,
            'number' => $this->Cashier_Number,
            'name' => $this->cashier_name,
            'sellFuel' => $this->Cashier_Sell_Fuel,
            'sellDry' => $this->Cashier_Sell_Dry,
            'openDraw' => $this->Cashier_Open_CDraw,
            'refunds' => $this->Cashier_Refunds,
            'delItem' => $this->Cashier_Del_Item,
            'driveOff' => $this->Cashier_Drive_Off,
            'testDel' => $this->Cashier_Test_Del,
            'clsShift' => $this->Cashier_Cls_Shift,
            'clsDay' => $this->Cashier_Cls_Day,
            'clsMonth' => $this->Cashier_Cls_Month,
            'bof' => $this->Cashier_BOF,
            'overRide' => $this->Cashier_Override,
            'gm' => $this->Cashier_GM,
            'printRep' => $this->Cashier_Print_Rep
        ];
    }
}
