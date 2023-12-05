<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosPeriodBirTransactionNumber extends JsonResource
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
            'endingTransNum' => trim($this->EndingSI),
            'endingTransReset' => trim($this->EndingResetter),
            'startingTransNum' => trim($this->BeginningSI),
            'startingTransReset' => trim($this->BeginningResetter),
            'pos_id' => $this->pos_id,
            'period_id' => $this->Period_ID,
        ];
    }
}
