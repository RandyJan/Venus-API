<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubAccount extends JsonResource
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
            'Sub_Account_ID' => $this->Sub_Account_ID,
            'Account_ID' => $this->Account_ID,
            'SubAcc_Number' => $this->SubAcc_Number,
            'SubAcc_Name' => trim($this->SubAcc_Name),
            'SubAcc_Blocked' => $this->SubAcc_Blocked,
            'SubAcc_Balance' => $this->SubAcc_Balance,
            'SubAcc_Limit' => $this->SubAcc_Limit,
            'SubAcc_Vehicle_ID' => trim($this->SubAcc_Vehicle_ID),
            'SubAcc_Disct' => trim($this->SubAcc_Disct),
            'Sub_Account_Discount_Counter' => $this->Sub_Account_Discount_Counter,
        ];
    }
}
