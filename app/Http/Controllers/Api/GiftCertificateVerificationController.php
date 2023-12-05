<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GiftCertificateVerificationService;
use App\Traits\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GiftCertificateVerificationController extends Controller
{
    use Response;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Post(
     * path="/api/verify-gift-certificate",
     * summary="Verify Gift Certificate",
     * description="Previous Route: /enablerAPI/lookUpCtrl/verifyGC",
     * tags={"Transactions"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass GC Number and Amount",
     *    @OA\JsonContent(
     *       required={"giftCertNum", "amount"},
     *       @OA\Property(property="giftCertNum", type="integer", format="number"),
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
     *       @OA\Property(property="statusDescription", type="string", example=""),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        try{

            $service = new GiftCertificateVerificationService($request->giftCertNum, $request->amount);
            $result = $service->execute();
            if(!$result['success']){
                return $this->response($result['message'], 0 );
            }

            return $this->response( $result['message'], 1, $result['data'], );

        }catch(Exception $e){
            Log::error($e->getMessage());
            return $this->response($e->getMessage(), 0 );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
