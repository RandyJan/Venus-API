<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PosTerminal;
use App\Models\Period;
use App\Services\Reports\PeriodOnCloseService;
use App\Models\CashDraw_Periods_DB;
use App\Models\CashdrawPeriod;
use App\Models\CashdrawHistory;
use App\Models\ManualCashdrawHistory;
use App\Models\CashdrawGradeHistory;
use App\Models\CashdrawDepartmentHistory;


class PeriodController extends Controller
{
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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


    public function closePeriod(Request $request)
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

        $activePeriod = Period::getActivePeriodByType($request->periodType);
        
        if(is_null($activePeriod)){
			return response(["result" => 0, "message" => "no active period found"]);
		}

		if(!$activePeriod){
			return response(["result" => 0, "message" => "no active period found"]);
		}

        $closePeriod = Period::closePeriodSP($request->periodType);
        if(!$closePeriod)
        {
            return response(["result" => 0, "message" => "failed to close period"]);
        }
        return response($activePeriod);

        $service = new PeriodOnCloseService;
        $returnData = $service->execute($activePeriod, $request->posID);
        return response($returnData);
    }

    public function closeCDrawPeriod(Request $request)
    {
        $cdrawPeriodID = CashDraw_Periods_DB::getActiveCdrwPeriod($request->cashierID, $request->posID);
        if(!$cdrawPeriodID){
			return response(["result" => 0, "message" => "no active cashdrawer found"]);
		}
        
        $result = CashDraw_Periods_DB::closeCDrawPeriodSP($request->cashierID, $request->posID);
        if(!$result){
			return response(["result" => 0, "message" => "failed to close period"]);
		}

        $output = $this->getCDrawHistByCDPeriod($cdrawPeriodID->CDraw_Period_ID);
        return response(["result" => 1, "message" => "Success", "data" => $output]);
        
    }

    public function getCDrawHistByCDPeriod($cdrawPeriodID)
    {
        $cdrawHist = CashDrawHistory::getCDrawHistByCDPeriod($cdrawPeriodID);
        
        if(!$cdrawHist)
        {
            return response(["result" => 0, "message" => "No available cash draw history"]);
		}

        $manualHist = ManualCashdrawHistory::getManualCDrawGradeHistByCDPeriodID($cdrawPeriodID);
        
        if(!$manualHist)
        {
			return response(["result" => 0, "message" => "No available manual cash draw history"]);
		}

        $cdrawGradeHist = CashdrawGradeHistory::getCDrawGradeHistByCDPeriodID($cdrawPeriodID);

        if(!$cdrawGradeHist)
        {
			return response(["result" => 0, "message" => "No available cash draw grade history"]);
		}

        $cdrawDeptHist = CashdrawDepartmentHistory::getCDrawDeptHistByCDPeriodID($cdrawPeriodID);

		if(!$cdrawDeptHist){
			return response(["result" => 0, "message" => "No available cash draw department history"]);
		}

        $cdrawDetails = CashdrawPeriod::getCashDrawDetails($cdrawPeriodID);

		if(!$cdrawDetails){
			return response(["result" => 0, "message" => "No available cash draw information"]);
		}
        
        $data = array(
            "cdrawFinHistory" => $cdrawHist,
            "cdrawGradeHist" => $cdrawGradeHist, 
            "cdrawDeptHist" => $cdrawDeptHist, 
            "cdrawDetails" => $cdrawDetails, 
            "manualCdrawGradeHist" => $manualHist);
        return $data;
    }
}
