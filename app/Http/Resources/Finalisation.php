<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Finalisation extends JsonResource
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
            'id' => $this->MOP_ID,
			'name' => trim($this->MOP_Name),
			'priceLvl' => $this->Price_Level,
			'cashDraw' => $this->MOP_Cash_Draw,
			'safeDropLvl' => $this->MOP_Safedrop_Level,
			'safeDropAlarm' => $this->MOP_Safedrop_Alarm,
			'tender' => $this->MOP_Tender,
			'partialTender' => $this->MOP_Partial_Tender,
			'change' => $this->MOP_Change,
			'keyNum' => trim($this->MOP_KeyNumber),
			'keyLabel' => trim($this->MOP_KeyLabel),
			'finalMsgL1' => trim($this->MOP_Final_Msg_L1),
			'finalMsgL2' => trim($this->MOP_Final_Msg_L2),
			'finalMsgL3' => trim($this->MOP_Final_Msg_L3),
			'mopType' => $this->MOP_Type,
			'mopPO' => $this->MOP_PO,
			'mopRef' => $this->MOP_Ref,
			'mopReceiptCopyCount' => $this->MOP_ReceiptCopyCount,
			"withDiscount"=>$this->With_Discount
        ];
    }
}
