<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CheckSafeDropAlamLevelService;
use App\Traits\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SafeDropAlarmLevelController extends Controller
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
     * path="/api/check-safe-drop-alarm-level",
     * summary="Verify Gift Certificate",
     * description="Previous Route: /enablerAPI/lookUpCtrl/checkSafeDropAlarmLevel",
     * tags={"Transactions"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass mopID",
     *    @OA\JsonContent(
     *       required={"mopID"},
     *       @OA\Property(property="mopID", type="integer", format="number"),
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
    public function check(Request $request)
    {
        try{

            $service = new CheckSafeDropAlamLevelService($request->mopID);
            $result = $service->execute();
            if(!$result['success']){
                return $this->response($result['message'], 0 );
            }

            return $this->response( $result['message'], 1, $result['data'] );

        }catch(Exception $e){
            Log::error($e->getMessage());
            return $this->response($e->getMessage(), 0 );
        }
    }

    /**
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
