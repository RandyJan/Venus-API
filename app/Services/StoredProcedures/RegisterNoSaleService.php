<?php

namespace App\Services\StoredProcedures;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterNoSaleService {

    public function execute( int $cashier_id) :array
    {
        try{

            DB::connection('enablerDb')
                ->update("EXEC SP_REGISTER_NO_SALE @CASHIER_ID=?",[
                    $cashier_id,
                ]);

            return [
                'success' => true,
                'message' => 'Success'
            ];

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

}
