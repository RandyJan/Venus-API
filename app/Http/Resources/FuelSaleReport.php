<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FuelSaleReport extends JsonResource
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
            "Grade_Name" => trim($this->Grade_Name),
            "postPayQty" => $this->postPayQty,
            "postPayVol" => $this->postPayVol,
            "postPayVal" => $this->postPayVal,
            "testDelQty" => $this->testDelQty,
            "testDelVol" => $this->testDelVol,
            "offQty" => $this->offQty,
            "offVol" => $this->offVol,
            "offVal" => $this->offVal,
            "driveOffQty" => $this->driveOffQty,
            "driveOffVal" => $this->driveOffVal,
            "driveOffVol" => $this->driveOffVol,
            "driveOffCost" => $this->driveOffCost,
        ];
    }
}
