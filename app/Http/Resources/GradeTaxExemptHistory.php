<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GradeTaxExemptHistory extends JsonResource
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
            'Grade_Name' => trim($this->Grade_Name),
            'gradeTaxExemptValue' => $this->grade_tax_exempt_value,
            'gradeTaxExemptQty' => $this->grade_tax_exempt_qty,
        ];
    }
}
