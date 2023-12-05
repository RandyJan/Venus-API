<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Tax extends JsonResource
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
            'id' => $this->Tax_ID,
            'name' => $this->tax_name,
            'rate' => $this->Tax_rate,
            'inclusive' => $this->Tax_Inclusive,
            'legend' => $this->tax_legend
        ];
    }
}
