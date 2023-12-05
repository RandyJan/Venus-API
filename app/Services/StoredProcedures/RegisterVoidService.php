<?php

namespace App\Services\StoredProcedures;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterVoidService {

    public function execute( int $cashier_id, float $amount) :array
    {
        try{

            DB::connection('enablerDb')
                ->update("EXEC SP_REGISTER_VOID @CASHIER_ID=?, @VOID_VALUE=?",[
                    $cashier_id,
                    $amount,
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
