<?php

namespace App\Services;

use App\Http\Resources\GiftCertificate as ResourcesGiftCertificate;
use App\Models\GiftCertificate;

class GiftCertificateVerificationService {

    protected $gift_certificate_number;
    protected $amount;

    public function __construct($gift_certificate_number, $amount = 0)
    {
        $this->gift_certificate_number = $gift_certificate_number;
        $this->amount = $amount;
    }

    public function execute(){
        $gc = GiftCertificate::where('giftcert_number', $this->gift_certificate_number)->first();

        if( !$gc ){
            return [
                'success' => false,
                'message' => 'No gift cert found'
            ];
        }

        if( $gc->status != 0){
            return [
                'success' => false,
                'message' => 'Gift cert already in use'
            ];
        }

        if($gc->denomination < $this->amount){
            return [
                'success' => false,
                'message' => 'Amount is bigger than gitf cert'
            ];
        }

        return [
            'success' => true,
            'message' => 'Gift cert found',
            'data' => new ResourcesGiftCertificate($gc)
        ];
    }
}
