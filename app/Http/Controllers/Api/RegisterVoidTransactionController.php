<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StoredProcedures\RegisterVoidService;
use App\Traits\Response;
use Illuminate\Http\Request;
use DB;

class RegisterVoidTransactionController extends Controller
{
    use Response;

    /**
     * @OA\Post(
     * path="/api/register-void",
     * summary="Register Void",
     * description="Previous Route: /enablerAPI/lookUpCtrl/registerVoid",
     * tags={"Transactions"},
     * @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       required={"cashier_id", "amount"},
     *       @OA\Property(property="cashier_id", type="number", format="number"),
     *       @OA\Property(property="amount", type="number", format="number"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Unsuccessful"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     * @param Request $request
     * @return Response
     */
    public function register(Request $request){
        $service = new RegisterVoidService;

        $result = $service->execute($request->cashier_id, $request->amount);
        if(!$result['success']){
            return $this->response($result['message'], 0);
        }

        return $this->response($result['message']);
    }

    public function callVoidSP(Request $request)
    {
        $result = $this->voidPerItem($request->posID, $request->cashierID, $request->itemID, $request->itemType, $request->itemDesc, $request->itemPrice, $request->itemQty, $request->itemValue);
        
        if(!$result)
        {
            return response(['status'=> '0'], 401);
        }
        else
        {
            return response($result);
        }
    }

    public function voidPerItem($posID, $cashierID, $itemID, $itemType, $itemDesc, $itemPrice, $itemQty, $itemValue)
    {
        $result = DB::statement('EXEC sp_register_void_cv ?,?,?,?,?,?,?,?', [$cashierID, $itemValue, $posID, $itemType, $itemID,
        $itemDesc, $itemPrice, $itemQty]);
        if(!$result)
        {
            return $result;
        }
        else
        {
            return $result;
        }
    }

    public function callMultipleVoidSP(Request $request)
    {
       //return response($request);
        foreach($request->item as $voidItem)
        {
            $result = DB::statement('EXEC sp_register_void_cv ?,?,?,?,?,?,?,?', [$voidItem['cashierID'], $voidItem['itemValue'], $voidItem['posID'], $voidItem['itemType'], $voidItem['itemID'],
            $voidItem['itemDesc'], $voidItem['itemPrice'], $voidItem['itemQty']]);

            if(!$result)
            {
                return response(['status'=>'0'], 401);
            }
        }

        return response(['status'=>'1']);
    }
}
