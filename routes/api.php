<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/reports/cdraw-history-by-cdraw-period', [App\Http\Controllers\Api\PeriodReportController::class, 'cdrawHistoryByCdrawPeriod']);
Route::get('/reports/charge-account-transaction-post-pay', [App\Http\Controllers\Api\PeriodReportController::class, 'getChargeAccountTransactionReportPostPay']);
Route::post('/addnewTransaction', [App\Http\Controllers\Api\TransactionController::class, 'store'] );
