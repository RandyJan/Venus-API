<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrentPriceProfile extends JsonResource
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
            'Grade_ID ' => $this->Grade_ID,
			'Grade_Name ' => trim($this->Grade_Name),
			'Grade_Description ' => trim($this->Grade_Description),
			'Price_Profile_ID ' => $this->Price_Profile_ID,
            'Price_Level' => $this->Price_Level,
            'Level_Name' => trim($this->Level_Name),
            'Grade_Price' => $this->Grade_Price,
        ];

    }
}
