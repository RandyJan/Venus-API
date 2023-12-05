<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StoredProcedures\RegisterNoSaleService;
use App\Traits\Response;
use Illuminate\Http\Request;

class RegisterNoSaleTransactionController extends Controller
{
    use Response;

    /**
     * @OA\Post(
     * path="/api/register-no-sale",
     * summary="Register No Sale",
     * description="Previous Route: /enablerAPI/lookUpCtrl/registerNoSale",
     * tags={"Transactions"},
     * @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       required={"cashier_id",},
     *       @OA\Property(property="cashier_id", type="number", format="number"),
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
        $service = new RegisterNoSaleService;

        $result = $service->execute($request->cashier_id);
        if(!$result['success']){
            return $this->response($result['message'], 0);
        }

        return $this->response($result['message']);
    }
}
