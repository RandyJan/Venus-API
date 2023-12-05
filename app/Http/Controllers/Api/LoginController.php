<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cashier;
use App\Http\Resources\User as ResourcesUser;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Cashier Login",
     * description="Previous Route: /enablerAPI/cashierCtrl/loginCashier",
     * operationId="authLogin",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass cashier credentials",
     *    @OA\JsonContent(
     *       required={"number","password"},
     *       @OA\Property(property="number", type="integer", format="number"),
     *       @OA\Property(property="password", type="string", format="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="statusCode = 1",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusDescription", type="string", example="Login Success")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="statusCode = 0",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusDescription", type="string", example="Invalid Login")
     *    )
     *  ),
     * )
     */
    public function login(Request $request){
        $user = User::login($request->number, $request->password);

        return response()->json([
            'statusCode' => 1,
            'statusDescription' => 'Login Success',
            'data' => new Cashier($user)
        ]);
    }
}
