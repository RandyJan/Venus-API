<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentSalesHistory extends JsonResource
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
            'depName' => trim($this->Dept_Name),
            'qty' => $this->Dept_Qty_Item_Sold,
            'val' => $this->Dept_Val_Item_Sold
        ];
    }
}
