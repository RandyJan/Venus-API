<?php

namespace App\Services\Reports;

use App\Http\Resources\AccountDiscountSalesReport;
use App\Http\Resources\DepartmentSalesHistoryCollection;
use App\Http\Resources\DepartmentTaxExemptHistoryCollection;
use App\Http\Resources\FinalisationTotalSalesReportCollection;
use App\Http\Resources\FuelSaleReportCollection;
use App\Http\Resources\ManualFuelSaleReportCollection;
use App\Http\Resources\PosPeriodBirTransactionNumberCollection;
use App\Models\CashierHistory;
use App\Models\DepartmentHistory;
use App\Models\DiscountHistory;
use App\Models\FinalisationHistory;
use App\Models\HoseHistory;
use App\Models\ManualHoseHistory;
use App\Models\Period;
use App\Models\PosGradeHistory;
use App\Models\PosPeriodBirTransactionNumber;
use App\Models\PosTerminal;
use App\Models\TankHistory;
use App\Models\TaxHistory;
use App\Models\Transaction;

class PeriodService {
    public function execute($pos_id, $period_id){
        $posMaster = PosTerminal::getMasterPOS();

        if(!$posMaster){
            return [
                'success' => false,
                'message' => 'Master POS ID not found!'
            ];
        }

        if( $posMaster->POS_ID != $pos_id){
            return [
                'success' => false,
                'message' => 'Not master POS!'
            ];
        }

        $periodDetails = Period::getPeriodDetails($period_id);
        
        if(!$periodDetails){
            return [
                'success' => false,
                'message' => 'Period Details not found!'
            ];
        }

        $departmentSales = DepartmentHistory::departmentSales($period_id);
        $fuelSales = HoseHistory::getFuelSales($period_id);
        $manualFuelSales = ManualHoseHistory::getFuelSales($period_id);
        $accountDiscountSales = FinalisationHistory::getAccountDiscountSales($period_id);
        $finalisationHistory = FinalisationHistory::getFinalisationTotals($period_id);
        $cashiersTotal = CashierHistory::getCashierTotalbyPeriod($period_id);
        $transNums = PosPeriodBirTransactionNumber::getTransactionNumbers($period_id);
        //return new PosPeriodBirTransactionNumberCollection($transNums);
        $depTaxExempt = DepartmentHistory::getDepartmentTaxExempt($period_id);
        $gradeTaxExempt = PosGradeHistory::getGradeTaxExempt($period_id);
        $taxHistory = TaxHistory::getTaxHistoryByPeriodID($period_id);
        $deptDiscounts = DepartmentHistory::getDepartmentDiscounts($period_id);
        $manualFuelSalesDisc = ManualHoseHistory::getFuelDiscount($period_id);
        $fuelSalesDisc = HoseHistory::getFuelSalesDisc($period_id);
        $discountTotal = DiscountHistory::getDiscountTotal($period_id);
        $depRefund = DepartmentHistory::getDepartmentRefund($period_id);
        $transHourData = Transaction::getTransactionHistoryData($period_id);
        $hoseHistData = HoseHistory::getHoseHistoryData($period_id);
        $vehicleHistData = Transaction::getVehicleTotalsByPeriod($period_id);
        $tankHistData = TankHistory::getTankHistoryByPeriod($period_id);
        //return $tankHistData;
        
        return [
            'success' => true,
            'message' => 'Success',
            'data' => [
                'depSales' => new DepartmentSalesHistoryCollection($departmentSales),
                'fuelSales' => new FuelSaleReportCollection($fuelSales),
                'manualFuelSales' => new ManualFuelSaleReportCollection($manualFuelSales),
                'accDiscSales' => new AccountDiscountSalesReport($accountDiscountSales),
                'finHistory' => new FinalisationTotalSalesReportCollection($finalisationHistory),
                'cashiersTotal' => $cashiersTotal,
                'transNums' => new PosPeriodBirTransactionNumberCollection($transNums),
                'deptTaxExempt' => new DepartmentTaxExemptHistoryCollection($depTaxExempt),
                'deliveryTaxExempt' => $gradeTaxExempt,
                'taxHistory' => $taxHistory,
                'deptDiscounts' => $deptDiscounts,
                'manualFuelSalesDisc' => $manualFuelSalesDisc,
                'fuelSalesDisc' => $fuelSalesDisc,
                'discountTotal' => $discountTotal,
                'depRefund' => $depRefund,
                'transHourData' => $transHourData,
                'hoseHistData' => $hoseHistData,
                'vehicleTotals' => $vehicleHistData,
                'tankHistData' => $tankHistData,
            ]
        ];
    }
}
