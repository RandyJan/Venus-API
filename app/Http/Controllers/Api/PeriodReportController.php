<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Reports\CashDrawHistoryService;
use App\Services\Reports\ChargeAccountTransactionPostPay;
use App\Services\Reports\PeriodOnCloseService;
use App\Services\Reports\PeriodService;
use App\Models\PosTerminal;
use App\Models\Period;
use App\Models\Delivery_GT;
use App\Models\DepartmentGT;
use App\Models\MOPGT;
use App\Models\TaxGT;
use App\Models\SnapShot;
use App\Models\Miscellaneous;
use App\Models\Refund_GT;
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
        return response($result);

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
        return $result;
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

    public function getAllActivePeriod(Request $request)
    {
        $posMaster = PosTerminal::getMasterPOS();

        if(!$posMaster){
            return [
                'success' => false,
                'message' => 'Master POS ID not found!'
            ];
        }

        if( $posMaster->POS_ID != $request->posID){
            return [
                'success' => false,
                'message' => 'Not master POS!'
            ];
        }

        $activerPeriods = Period::getActivePeriods($request->type);
        
        if(!$activerPeriods)
        {
            return response("There's nothing in here",401);
        }

        return $activerPeriods;

    }

    public function getAccumulatingSales(Request $request)
    {
        $gradeHistSum = Delivery_GT::getDeliveryGTByPosID($request->posID);
        $deptHistSum = DepartmentGT::getDepartmentGTByPosID($request->posID);
        $mopHistSum = MOPGT::getMopGTByPosID($request->posID);
        $taxHistSum = TaxGT::getTaxGTByPosID($request->posID);
        $discTotal = TaxGT::getTaxDiscTotalByPosID($request->posID);
        $saleTotal = TaxGT::getSaleTotal($request->posID);
        $scpwdDiscTotal = TaxGT::getTaxSCPWDDiscTotalByPosID($request->posID);
        //return response($gradeHistSum);
        SnapShot::callSaveLastDeltaGTReportDetailsSP($request->posID);
        SnapShot::callSaveDeltaGTReportSnapshotSP($request->posID);
        
        $prevTotalSales = TaxGT::getPrevAccumTotalSalesRaw($request->posID);
        $currTotalSales = TaxGT::getCurrAccumTotalSalesRaw($request->posID);
        $netSales = TaxGT::getTotalNetSales($request->posID);
        
        $details = [];
        
        $misc = Miscellaneous::getAccReportMiscData();
        $AccReportPOSData = PosTerminal::getAccReportPOSData($request->posID);
        
        array_push($details, $misc[0]);
        array_push($details, $AccReportPOSData[0]);
        array_push($details, $prevTotalSales[0]);
        array_push($details, $currTotalSales[0]);
        
        $refundHistSum = Refund_GT::getByPosId($request->posID);

        $data = array(
			"gradeHistSum" => $gradeHistSum, 
			"deptHistSum" => $deptHistSum, 
			"mopHistSum" => $mopHistSum, 
			"taxHistSum" => $taxHistSum, 
			"discTotal" => $discTotal, 
			"saleTotal" => $saleTotal, 
			"scpwdDiscTotal" => $scpwdDiscTotal, 
			"details" => $details, 
			"netSales" => $netSales,
			'refundHistSum' => $refundHistSum
		);

        return response(["result" => 1, "message" => "success", "data" => $data]);
    }
}
