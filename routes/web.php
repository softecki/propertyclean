<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MaintainerController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoicePaymentController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class,'index'])->middleware(
    [

        'XSS',
    ]
);
Route::get('home', [HomeController::class,'index'])->name('home')->middleware(
    [

        'XSS',
    ]
);
Route::get('dashboard', [HomeController::class,'index'])->name('dashboard')->middleware(
    [

        'XSS',
    ]
);

//-------------------------------User-------------------------------------------

Route::resource('users', UserController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);


//-------------------------------Subscription-------------------------------------------



Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::resource('subscriptions', SubscriptionController::class);
    Route::get('coupons/history', [CouponController::class,'history'])->name('coupons.history');
    Route::delete('coupons/history/{id}/destroy', [CouponController::class,'historyDestroy'])->name('coupons.history.destroy');
    Route::get('coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
    Route::resource('coupons', CouponController::class);
    Route::get('subscription/transaction', [SubscriptionController::class,'transaction'])->name('subscription.transaction');
}
);

//-------------------------------Subscription Payment-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::post('subscription/{id}/stripe/payment', [SubscriptionController::class,'stripePayment'])->name('subscription.stripe.payment');
}
);
//-------------------------------Settings-------------------------------------------
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('settings/account', [SettingController::class,'account'])->name('setting.account');
    Route::post('settings/account', [SettingController::class,'accountData'])->name('setting.account');
    Route::delete('settings/account/delete', [SettingController::class,'accountDelete'])->name('setting.account.delete');

    Route::get('settings/password', [SettingController::class,'password'])->name('setting.password');
    Route::post('settings/password', [SettingController::class,'passwordData'])->name('setting.password');

    Route::get('settings/general', [SettingController::class,'general'])->name('setting.general');
    Route::post('settings/general', [SettingController::class,'generalData'])->name('setting.general');

    Route::get('settings/smtp', [SettingController::class,'smtp'])->name('setting.smtp');
    Route::post('settings/smtp', [SettingController::class,'smtpData'])->name('setting.smtp');

    Route::get('settings/payment', [SettingController::class,'payment'])->name('setting.payment');
    Route::post('settings/payment', [SettingController::class,'paymentData'])->name('setting.payment');

    Route::get('settings/company', [SettingController::class,'company'])->name('setting.company');
    Route::post('settings/company', [SettingController::class,'companyData'])->name('setting.company');

    Route::get('language/{lang}', [SettingController::class,'lanquageChange'])->name('language.change');
    Route::post('theme/settings', [SettingController::class,'themeSettings'])->name('theme.settings');

    Route::get('settings/site-seo', [SettingController::class,'siteSEO'])->name('setting.site.seo');
    Route::post('settings/site-seo', [SettingController::class,'siteSEOData'])->name('setting.site.seo');

    Route::get('settings/google-recaptcha', [SettingController::class,'googleRecaptcha'])->name('setting.google.recaptcha');
    Route::post('settings/google-recaptcha', [SettingController::class,'googleRecaptchaData'])->name('setting.google.recaptcha');
}
);


//-------------------------------Role & Permissions-------------------------------------------
Route::resource('permission', PermissionController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('role', RoleController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);




//-------------------------------Note-------------------------------------------
Route::resource('note', NoticeBoardController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::resource('feedback', NoticeBoardController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Contact-------------------------------------------
Route::resource('contact', ContactController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);




//-------------------------------logged History-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function () {

    Route::get('logged/history', [UserController::class,'loggedHistory'])->name('logged.history');
    Route::get('logged/{id}/history/show', [UserController::class,'loggedHistoryShow'])->name('logged.history.show');
    Route::delete('logged/{id}/history', [UserController::class,'loggedHistoryDestroy'])->name('logged.history.destroy');
});


//-------------------------------Property-------------------------------------------
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('property', PropertyController::class);
    Route::get('property/{pid}/unit/create', [PropertyController::class,'unitCreate'])->name('unit.create');
    Route::post('property/{pid}/unit/store', [PropertyController::class,'unitStore'])->name('unit.store');
    Route::get('property/{pid}/unit/{id}/edit', [PropertyController::class,'unitEdit'])->name('unit.edit');
    Route::get('units', [PropertyController::class,'units'])->name('unit.index');
    Route::put('property/{pid}/unit/{id}/update', [PropertyController::class,'unitUpdate'])->name('unit.update');
    Route::delete('property/{pid}/unit/{id}/destroy', [PropertyController::class,'unitDestroy'])->name('unit.destroy');
    Route::get('property/{pid}/unit', [PropertyController::class,'getPropertyUnit'])->name('property.unit');
}
);

//-------------------------------Tenant-------------------------------------------
Route::resource('tenant', TenantController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Tenant Application-------------------------------------------
Route::resource('tenantapplication', TenantController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Contract-------------------------------------------

Route::resource('contract', ContractController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Assets-------------------------------------------

Route::resource('asset', ContractController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);


//-------------------------------Type-------------------------------------------
Route::resource('type', TypeController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Invoice-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('invoice/{id}/payment/create', [InvoiceController::class,'invoicePaymentCreate'])->name('invoice.payment.create');
    Route::post('invoice/{id}/payment/store', [InvoiceController::class,'invoicePaymentStore'])->name('invoice.payment.store');
    Route::delete('invoice/{id}/payment/{pid}/destroy', [InvoiceController::class,'invoicePaymentDestroy'])->name('invoice.payment.destroy');
    Route::delete('invoice/type/destroy', [InvoiceController::class,'invoiceTypeDestroy'])->name('invoice.type.destroy');
    Route::resource('invoice', InvoiceController::class);
}
);
//report
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('report/{id}/payment/create', [InvoiceController::class,'invoicePaymentCreate'])->name('invoice.payment.create');
    Route::post('report/{id}/payment/store', [InvoiceController::class,'invoicePaymentStore'])->name('invoice.payment.store');
    Route::delete('report/{id}/payment/{pid}/destroy', [InvoiceController::class,'invoicePaymentDestroy'])->name('invoice.payment.destroy');
    Route::delete('report/type/destroy', [InvoiceController::class,'invoiceTypeDestroy'])->name('invoice.type.destroy');
    Route::resource('report', InvoiceController::class);
}
);

//-------------------------------Expense-------------------------------------------
Route::resource('expense', ExpenseController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Transactions-------------------------------------------
Route::resource('transactions', ExpenseController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Maintainer-------------------------------------------
Route::resource('maintainer', MaintainerController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Maintenance Request-------------------------------------------
Route::get('maintenance-request/pending', [MaintenanceRequestController::class,'pendingRequest'])->name('maintenance-request.pending');
Route::get('maintenance-request/in-progress', [MaintenanceRequestController::class,'inProgressRequest'])->name('maintenance-request.inprogress');
Route::get('maintenance-request/{id}/action', [MaintenanceRequestController::class,'action'])->name('maintenance-request.action');
Route::post('maintenance-request/{id}/action', [MaintenanceRequestController::class,'actionData'])->name('maintenance-request.action');
Route::resource('maintenance-request', MaintenanceRequestController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Plan Payment-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::post('subscription/{id}/bank-transfer', [PaymentController::class, 'subscriptionBankTransfer'])->name('subscription.bank.transfer');
    Route::get('subscription/{id}/bank-transfer/action/{status}', [PaymentController::class, 'subscriptionBankTransferAction'])->name('subscription.bank.transfer.action');
    Route::post('subscription/{id}/paypal', [PaymentController::class, 'subscriptionPaypal'])->name('subscription.paypal');
    Route::get('subscription/{id}/paypal/{status}', [PaymentController::class, 'subscriptionPaypalStatus'])->name('subscription.paypal.status');
}
);

//-------------------------------Invoice Payment-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::post('invoice/{id}/banktransfer/payment',[InvoicePaymentController::class,'banktransferPayment'])->name('invoice.banktransfer.payment');
    Route::post('invoice/{id}/stripe/payment', [InvoicePaymentController::class,'stripePayment'])->name('invoice.stripe.payment');
    Route::post('invoice/{id}/paypal', [InvoicePaymentController::class, 'invoicePaypal'])->name('invoice.paypal');
    Route::get('invoice/{id}/paypal/{status}', [InvoicePaymentController::class, 'invoicePaypalStatus'])->name('invoice.paypal.status');
}
);
