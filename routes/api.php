<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UnifiedPmsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // Master setup
    Route::post('branches', [UnifiedPmsController::class, 'createBranch']);
    Route::post('projects', [UnifiedPmsController::class, 'createProject']);
    Route::post('blocks', [UnifiedPmsController::class, 'createBlock']);
    Route::post('plots', [UnifiedPmsController::class, 'createPlot']);
    Route::post('customers', [UnifiedPmsController::class, 'createCustomer']);
    Route::post('sellers', [UnifiedPmsController::class, 'createSeller']);
    Route::post('agents', [UnifiedPmsController::class, 'createAgent']);

    // Sales and collection
    Route::post('sales', [UnifiedPmsController::class, 'createSale']);
    Route::post('sales/{saleId}/charges', [UnifiedPmsController::class, 'addSaleCharge']);
    Route::post('sales/{saleId}/payments', [UnifiedPmsController::class, 'recordSalePayment']);
    Route::post('sales/{saleId}/status', [UnifiedPmsController::class, 'updateSaleStatus']);
    Route::get('sales/{saleId}/statement', [UnifiedPmsController::class, 'saleStatement']);
    Route::post('control-numbers', [UnifiedPmsController::class, 'createControlNumber']);

    // Portal and feedback
    Route::get('portal/listings', [UnifiedPmsController::class, 'availableListings']);
    Route::post('portal/feedback', [UnifiedPmsController::class, 'submitFeedback']);

    // Utility charges and maintenance
    Route::post('utility-bills', [UnifiedPmsController::class, 'createUtilityBill']);
    Route::post('utility-bills/{billId}/mark-paid', [UnifiedPmsController::class, 'markUtilityBillPaid']);
    Route::post('maintenance-schedules', [UnifiedPmsController::class, 'createMaintenanceSchedule']);

    // Assets and depreciation
    Route::post('assets', [UnifiedPmsController::class, 'createAsset']);
    Route::post('assets/{assetId}/depreciate', [UnifiedPmsController::class, 'depreciateAsset']);

    // Email inbox and reply
    Route::post('email/threads', [UnifiedPmsController::class, 'createEmailThread']);
    Route::post('email/threads/{threadId}/messages', [UnifiedPmsController::class, 'addEmailMessage']);
    Route::get('email/threads/{threadId}', [UnifiedPmsController::class, 'showEmailThread']);

    // Currency and reporting
    Route::post('currency-rates', [UnifiedPmsController::class, 'addCurrencyRate']);
    Route::get('reports/currency-valuation', [UnifiedPmsController::class, 'currencyValuationReport']);
    Route::get('reports/summary', [UnifiedPmsController::class, 'reportSummary']);
    Route::post('notifications/reminders/generate', [UnifiedPmsController::class, 'generateReminders']);
    Route::post('notifications', [UnifiedPmsController::class, 'createNotification']);
});
