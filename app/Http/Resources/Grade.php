<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Grade extends JsonResource
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
            'id ' => $this->Grade_ID,
			'name ' => trim($this->Grade_Name),
			'taxLink ' => $this->Tax_Link,
			'rate ' => $this->Tax_rate,
			'price ' => $this->Grade_Price,
        ];
    }
}
