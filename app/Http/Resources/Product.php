<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request),
        return [
            'id' => $this->Product_ID,
			'taxID' => $this->Tax_ID,
			'depID' => $this->Department_ID,
			'desc' => trim($this->Product_Desc),
			'price' => $this->Product_Price,
			'quickCode' => trim($this->Product_Quick_Code),
			'scVat' => trim($this->SCVat),
			'discRate' => trim($this->Discount_Rate),
            'barcode'=> $this->when(isset($this->Barcode), trim($this->Barcode)),
        ];
    }
}
