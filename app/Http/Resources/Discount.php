<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Discount extends JsonResource
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
        return array_merge(
            parent::toArray($request),
            [// this part will overwrite any same existing array name above
                'discount_name' => trim($this->discount_name),
                'discount_keylabel' => trim($this->discount_keylabel),
            ]
        );
    }
}
