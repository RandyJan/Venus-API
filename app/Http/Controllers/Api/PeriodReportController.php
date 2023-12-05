<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Reports\CashDrawHistoryService;
use App\Services\Reports\ChargeAccountTransactionPostPay;
use App\Services\Reports\PeriodOnCloseService;
use App\Services\Reports\PeriodService;
use App\Traits\Response;
use Illuminate\Http\Request;

class PeriodReportController extends Controller
{
    use Response;

    /**
     * @OA\Get(
     * path="/api/reports/period",
     * summary="Get Report by Period",
     * description="Previous Route: /enablerAPI/reportsCtrl/getPeriodReport",
     * tags={"Reports"},
     * @OA\Parameter(
     *      name="posID",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number",
     *      ),
     * ),
     * @OA\Parameter(
     *      name="periodID",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number",
     *      ),
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
     *       @OA\Property(property="statusDescription", type="string", example="Not master pos | Period details not foun | Failed to close"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function periodReport(Request $request){
        $service = new PeriodService;
        $result = $service->execute($request->posID, $request->periodID);

        if(!$result['success']){
            return $this->response(
                $result['message'],
                1
            );
        }

        return $this->response(
            $result['message'],
            0,
            $result['data']
        );

    }

    /**
     * @OA\Get(
     * path="/api/reports/period-on-close",
     * summary="Get Report Period on close",
     * description="Previous Route: /enablerAPI/reportsCtrl/getPeriodReportOnClose",
     * tags={"Reports"},
     * @OA\Parameter(
     *      name="posID",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number",
     *      ),
     * ),
     * @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number",
     *      ),
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
     *       @OA\Property(property="statusDescription", type="string", example="No active period found"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function periodOnClose(Request $request){
        $service = new PeriodOnCloseService;
        $result = $service->execute($request->posID, $request->type);

        if(!$result['success']){
            return $this->response(
                $result['message'],
                1
            );
        }

        return $this->response(
            $result['message'],
            0,
            $result['data']
        );

    }

    /**
     * @OA\Get(
     * path="/api/reports/cdraw-history-by-cdraw-period",
     * summary="Get Report Period on close",
     * description="Previous Route: /enablerAPI/reportsCtrl/getCDrawHistByCDPeriod",
     * tags={"Reports"},
     * @OA\Parameter(
     *      name="cdrawPeriodID",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number",
     *      ),
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
     *       @OA\Property(property="statusDescription", type="string", example="No active period found"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function cdrawHistoryByCdrawPeriod(Request $request){
        $service = new CashDrawHistoryService;
        $result = $service->execute($request->cdrawPeriodID);

        if(!$result['success']){
            return $this->response(
                $result['message'],
                1
            );
        }

        return $this->response(
            $result['message'],
            0,
            $result['data']
        );
    }


    /**
     * @OA\Get(
     * path="/api/reports/charge-account-transaction-post-pay",
     * summary="Get Charge Account Transaction Report Post Pay",
     * description="Previous Route: /enablerAPI/reportsCtrl/getChargeAccTransReportPostPay",
     * tags={"Reports"},
     * @OA\Parameter(
     *      name="periodID",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number",
     *      ),
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
     *       @OA\Property(property="statusDescription", type="string", example="No active period found"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function getChargeAccountTransactionReportPostPay(Request $request){
        $service = new ChargeAccountTransactionPostPay;
        $result = $service->execute($request->periodID);

        if(!$result['success']){
            return $this->response(
                $result['message'],
                1
            );
        }

        return $this->response(
            $result['message'],
            0,
            $result['data']
        );
    }
}
