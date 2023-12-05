<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Receipt extends JsonResource
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
            'id ' => $this->Receipt_ID,
			'name ' => trim($this->Receipt_Name),
			'headerL1 ' => trim($this->Receipt_Header_L1),
			'headerL2 ' => trim($this->Receipt_Header_L2),
			'headerL3 ' => trim($this->Receipt_Header_L3),
			'headerL4 ' => trim($this->Receipt_Header_L4),
			'headerL5 ' => trim($this->Receipt_Header_L5),
			'footerL1 ' => trim($this->Receipt_Footer_L1),
			'footerL2 ' => trim($this->Receipt_Footer_L2),
			'footerL3 ' => trim($this->Receipt_Footer_L3),
			'footerL4 ' => trim($this->Receipt_Footer_L4),
			'footerL5 ' => trim($this->Receipt_Footer_L5),
        ];
    }
}
