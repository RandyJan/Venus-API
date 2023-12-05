<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StoredProcedures\RegisterVoidService;
use App\Traits\Response;
use Illuminate\Http\Request;

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
}
