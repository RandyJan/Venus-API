<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FinalisationTotalSalesReport extends JsonResource
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
            'id' => $this->MOP_ID,
            'name' => trim($this->MOP_Name),
            'netQty' => $this->MOP_Net_Qty,
            'netValue' => $this->MOP_Net_Value,
            'numSafeDrop' => $this->MOP_Num_Safedrop,
            'valSafeDrop' => $this->MOP_Val_Safedrop,
            'numCashout' => $this->MOP_Num_Cashout,
            'valCashout' => $this->valCashout,
        ];
    }
}
