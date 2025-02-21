<?php

use App\Http\Controllers\Api\LookupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ElectronicJournalController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [App\Http\Controllers\Api\LoginController::class, 'login']);
Route::post('/check-test-del-stat', [App\Http\Controllers\Api\LookupController::class, 'checkTestDelStat']);
Route::post('/check-fuel-sale', [App\Http\Controllers\Api\LookupController::class, 'checkFuelSale']);
Route::post('/cashier-active-shift-period', [App\Http\Controllers\Api\LookupController::class, 'getCashierActiveShiftPeriod']);
Route::get('/taxes', [App\Http\Controllers\Api\LookupController::class, 'taxes']);
Route::get('/finalisations', [App\Http\Controllers\Api\LookupController::class, 'finalisations']);
Route::get('/grades', [App\Http\Controllers\Api\LookupController::class, 'grades']);
Route::post('/receipt-layout', [App\Http\Controllers\Api\LookupController::class, 'receiptLayout']);
Route::post('/pos-terminal', [App\Http\Controllers\Api\LookupController::class, 'receiptLayout']);
Route::get('/item-types', [App\Http\Controllers\Api\LookupController::class, 'itemTypes']);
Route::get('/non-fuel-products', [App\Http\Controllers\Api\LookupController::class, 'nonFuelProducts']);
Route::get('/products', [App\Http\Controllers\Api\LookupController::class, 'products']);
Route::get('/vehicle-types', [App\Http\Controllers\Api\LookupController::class, 'vehicleTypes']);
Route::post('/sub-account-details', [App\Http\Controllers\Api\LookupController::class, 'subAccountDetails']);
Route::post('/check-price-profile-exist', [App\Http\Controllers\Api\LookupController::class, 'checkPriceProfileExist']);
Route::post('/check-gift-certificate', [App\Http\Controllers\Api\LookupController::class, 'checkGiftCertificate']);
Route::post('/refund-checker', [App\Http\Controllers\Api\LookupController::class, 'refundChecker']);
Route::get('/discounts', [App\Http\Controllers\Api\LookupController::class, 'discounts']);
Route::get('/current-price-profile', [App\Http\Controllers\Api\LookupController::class, 'currentPriceProfile']);
Route::post('/verify-gift-certificate', [App\Http\Controllers\Api\GiftCertificateVerificationController::class, 'verify']);
Route::post('/check-safe-drop-alarm-level', [App\Http\Controllers\Api\SafeDropAlarmLevelController::class, 'check']);
Route::post('/get-transaction', [App\Http\Controllers\Api\TransactionController::class, 'transactionData']);
Route::post('/register-void', [App\Http\Controllers\Api\RegisterVoidTransactionController::class, 'register']);
Route::post('/register-no-sale', [App\Http\Controllers\Api\RegisterNoSaleTransactionController::class, 'register']);
Route::get('/reports/period', [App\Http\Controllers\Api\PeriodReportController::class, 'periodReport']);
Route::get('/reports/period-on-close', [App\Http\Controllers\Api\PeriodReportController::class, 'periodOnclose']);
Route::post('/reports/period-on-close', [App\Http\Controllers\Api\PeriodReportController::class, 'periodOnclose']);
Route::get('/reports/cdraw-history-by-cdraw-period', [App\Http\Controllers\Api\PeriodReportController::class, 'cdrawHistoryByCdrawPeriod']);
Route::post('/reports/cdraw-history-by-cdraw-period', [App\Http\Controllers\Api\PeriodReportController::class, 'cdrawHistoryByCdrawPeriod']);
Route::get('/reports/charge-account-transaction-post-pay', [App\Http\Controllers\Api\PeriodReportController::class, 'getChargeAccountTransactionReportPostPay']);
Route::get('/test/{id}',[TransactionController::class,'test']);






Route::post('/addnewTransaction', [TransactionController::class, 'store'] );
Route::post('/receipt-sample', [TransactionController::class,'receipt_sample']);
Route::get('/getItems',[TransactionController::class,'getItems']);
Route::get('/getTransId',[TransactionController::class, 'activeTransaction']);
Route::get('/gasProducts', [LookupController::class, 'gasProducts']);
Route::get('/finalisationstest',[LookupController::class, 'finalisationstest']);
Route::post('/receiptItems', [TransactionController::class, 'receiptItems']);

Route::post('/testing',[TransactionController::class,'testingpurpose']);

Route::post('/SaveToEJournal', [App\Http\Controllers\ElectronicJournalController::class, 'saveTransactionInfo']);
Route::post('/getRefundTransaction',[TransactionController::class, 'GetTransactionForRefund']);
Route::post('/reports/getAllActivePeriod', [App\Http\Controllers\Api\PeriodReportController::class, 'getAllActivePeriod']);
Route::post('/reports/closePeriod', [App\Http\Controllers\PeriodController::class, 'closePeriod']);
Route::post('/reports/getAccumulatingSales', [App\Http\Controllers\Api\PeriodReportController::class, 'getAccumulatingSales']);
Route::post('/reports/closeCDrawPeriod', [App\Http\Controllers\PeriodController::class, 'closeCDrawPeriod']);
Route::post('/safedropAmountChecker', [App\Http\Controllers\Api\SafeDropAlarmLevelController::class, 'checkSafedropAmount']);
Route::post('/getTransJournalByDate', [App\Http\Controllers\ElectronicJournalController::class, 'getTransJournalByDate']);
Route::post('/getReceiptForReprint', [App\Http\Controllers\ElectronicJournalController::class, 'getReceiptForReprint']);
Route::get('/getSubAccounts', [App\Http\Controllers\Api\LookupController::class, 'getSubAccountList']);
Route::get('/getAttendants', [App\Http\Controllers\Api\LookupController::class, 'getAttendant']);
Route::post('/voidItem', [App\Http\Controllers\Api\RegisterVoidTransactionController::class, 'callVoidSP']);
Route::post('/voidMultipleItem', [App\Http\Controllers\Api\RegisterVoidTransactionController::class, 'callMultipleVoidSP']);
Route::post('/getVoidRefundReport', [App\Http\Controllers\ReportController::class, 'getVoidRefundReport']);
Route::get('/getLoggingInfo', [App\Http\Controllers\LoggingController::class, 'GetLoggingInfo']);
Route::post('/saveToLog', [App\Http\Controllers\LoggingController::class, 'SaveLog']);
Route::post('/reports/getAllCashDrawByPosID', [App\Http\Controllers\ReportController::class, 'getAllCashDrawByPosID']);