<?php

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
}); */
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

/*Route::middleware('auth:api')->get('/test', function (Request $request) {
return $request->user();
});*/

//Route::middleware('cors')->get('masters', 'SyncController@all');
//Route::group(['middleware' => ['cors']], function () {
//authToken

//});

Route::post('user/login', 'Auth\LoginController@login');
Route::post('user/register', 'Model\UserController@register');

Route::middleware('auth:api')->group(function () {

	//Owner
	Route::put('owner_change_password', 'Model\UserController@changePassword');

	Route::middleware('permission:create-users')->post('users', 'Model\UserController@create');
	Route::middleware('permission:view-users')->get('users', 'Model\UserController@all');
	Route::middleware('permission:view-users')->get('users/{id}', 'Model\UserController@detail');
	Route::middleware('permission:edit-users')->put('users/{id}', 'Model\UserController@update');
	Route::middleware('permission:remove-users')->delete('users/{id}', 'Model\UserController@trash');

	Route::middleware('permission:create-products')->post('products', 'Model\ProductController@create');
	Route::middleware('permission:view-products')->get('products', 'Model\ProductController@all');
	Route::middleware('permission:view-products')->get('products/{id}', 'Model\ProductController@detail');
	Route::middleware('permission:edit-products')->put('products/{id}', 'Model\ProductController@update');
	Route::middleware('permission:remove-products')->delete('products/{id}', 'Model\ProductController@trash');

	Route::middleware('permission:create-services')->post('services', 'Model\ProductController@create');
	Route::middleware('permission:view-services')->get('services', 'Model\ProductController@all');
	Route::middleware('permission:view-services')->get('services/{id}', 'Model\ProductController@detail');
	Route::middleware('permission:edit-services')->put('services/{id}', 'Model\ProductController@update');
	Route::middleware('permission:remove-services')->delete('services/{id}', 'Model\ProductController@trash');

	Route::middleware('permission:create-customers')->post('customers', 'Model\AccountController@create');
	Route::middleware('permission:view-customers')->get('customers', 'Model\AccountController@all');
	Route::middleware('permission:view-customers')->get('customers/{id}', 'Model\AccountController@detail');
	Route::middleware('permission:edit-customers')->put('customers/{id}', 'Model\AccountController@update');
	Route::middleware('permission:remove-customers')->delete('customers/{id}', 'Model\AccountController@trash');

	Route::middleware('permission:create-suppliers')->post('suppliers', 'Model\AccountController@create');
	Route::middleware('permission:view-suppliers')->get('suppliers', 'Model\AccountController@all');
	Route::middleware('permission:view-suppliers')->get('suppliers/{id}', 'Model\AccountController@detail');
	Route::middleware('permission:edit-suppliers')->put('suppliers/{id}', 'Model\AccountController@update');
	Route::middleware('permission:remove-suppliers')->delete('suppliers/{id}', 'Model\AccountController@trash');

	//CONFIGURATION
	Route::middleware('permission:setup-account')->get('companies', 'Model\CompanyController@detail');
	Route::middleware('permission:setup-account')->get('companies/{id}', 'Model\CompanyController@detail');
	Route::middleware('permission:setup-account')->put('companies/{id}', 'Model\CompanyController@update');

	Route::middleware('permission:setup-account')->get('stores', 'Model\StoreController@all');
	Route::middleware('permission:setup-account')->get('stores/{id}', 'Model\StoreController@detail');
	Route::middleware('permission:setup-account')->post('stores', 'Model\StoreController@create');
	Route::middleware('permission:setup-account')->put('stores/{id}', 'Model\StoreController@update');
	Route::middleware('permission:setup-account')->delete('stores/{id}', 'Model\StoreController@trash');

	Route::middleware('permission:setup-account')->get('config_bookings', 'Model\ConfigBookingController@detail');
	Route::middleware('permission:setup-account')->get('config_bookings/{id}', 'Model\ConfigBookingController@detail');
	Route::middleware('permission:setup-account')->put('config_bookings/{id}', 'Model\ConfigBookingController@update');

	Route::middleware('permission:setup-staff')->get('config_staff_notifications', 'Model\ConfigStaffNotificationController@detail');
	Route::middleware('permission:setup-staff')->get('config_staff_notifications/{id}', 'Model\ConfigStaffNotificationController@detail');
	Route::middleware('permission:setup-staff')->put('config_staff_notifications/{id}', 'Model\ConfigStaffNotificationController@update');

	Route::middleware('permission:setup-customer')->get('config_notifications', 'Model\ConfigNotificationController@detail');
	Route::middleware('permission:setup-customer')->get('config_notifications/{id}', 'Model\ConfigNotificationController@detail');
	Route::middleware('permission:setup-customer')->put('config_notifications/{id}', 'Model\ConfigNotificationController@update');

	Route::get('permission_role', 'Model\PermissionRoleController@all');
	Route::put('permission_role', 'Model\PermissionRoleController@update');

	Route::middleware('permission:setup-staff')->post('commission_rates', 'Model\CommissionRateController@create');
	Route::middleware('permission:setup-staff')->get('commission_rates', 'Model\CommissionRateController@all');
	Route::middleware('permission:setup-staff')->get('commission_rates/{id}', 'Model\CommissionRateController@detail');
	Route::middleware('permission:setup-staff')->put('commission_rates/{id}', 'Model\CommissionRateController@update');
	Route::middleware('permission:setup-staff')->delete('commission_rates/{id}', 'Model\CommissionRateController@trash');

	Route::middleware('permission:setup-product')->post('service_categories', 'Model\CategoryController@create');
	Route::middleware('permission:setup-product')->get('service_categories', 'Model\CategoryController@all');
	Route::middleware('permission:setup-product')->get('service_categories/{id}', 'Model\CategoryController@detail');
	Route::middleware('permission:setup-product')->put('service_categories/{id}', 'Model\CategoryController@update');
	Route::middleware('permission:setup-product')->delete('service_categories/{id}', 'Model\CategoryController@trash');

	Route::middleware('permission:setup-product')->post('product_categories', 'Model\CategoryController@create');
	Route::middleware('permission:setup-product')->get('product_categories', 'Model\CategoryController@all');
	Route::middleware('permission:setup-product')->get('product_categories/{id}', 'Model\CategoryController@detail');
	Route::middleware('permission:setup-product')->put('product_categories/{id}', 'Model\CategoryController@update');
	Route::middleware('permission:setup-product')->delete('product_categories/{id}', 'Model\CategoryController@trash');

	Route::middleware('permission:setup-customer')->post('referral_sources', 'Model\ReferralSourceController@create');
	Route::middleware('permission:setup-customer')->get('referral_sources', 'Model\ReferralSourceController@all');
	Route::middleware('permission:setup-customer')->get('referral_sources/{id}', 'Model\ReferralSourceController@detail');
	Route::middleware('permission:setup-customer')->put('referral_sources/{id}', 'Model\ReferralSourceController@update');
	Route::middleware('permission:setup-customer')->delete('referral_sources/{id}', 'Model\ReferralSourceController@trash');

	Route::middleware('permission:setup-customer')->post('cancellation_reasons', 'Model\CancellationReasonController@create');
	Route::middleware('permission:setup-customer')->get('cancellation_reasons', 'Model\CancellationReasonController@all');
	Route::middleware('permission:setup-customer')->get('cancellation_reasons/{id}', 'Model\CancellationReasonController@detail');
	Route::middleware('permission:setup-customer')->put('cancellation_reasons/{id}', 'Model\CancellationReasonController@update');
	Route::middleware('permission:setup-customer')->delete('cancellation_reasons/{id}', 'Model\CancellationReasonController@trash');

	Route::middleware('permission:setup-sales')->post('payment_types', 'Model\PaymentTypeController@create');
	Route::middleware('permission:setup-sales')->get('payment_types', 'Model\PaymentTypeController@all');
	Route::middleware('permission:setup-sales')->get('payment_types/{id}', 'Model\PaymentTypeController@detail');
	Route::middleware('permission:setup-sales')->put('payment_types/{id}', 'Model\PaymentTypeController@update');
	Route::middleware('permission:setup-sales')->delete('payment_types/{id}', 'Model\PaymentTypeController@trash');

	Route::middleware('permission:setup-sales')->post('tax_rates', 'Model\TaxRateController@create');
	Route::middleware('permission:setup-sales')->get('tax_rates', 'Model\TaxRateController@all');
	Route::middleware('permission:setup-sales')->get('tax_rates/{id}', 'Model\TaxRateController@detail');
	Route::middleware('permission:setup-sales')->put('tax_rates/{id}', 'Model\TaxRateController@update');
	Route::middleware('permission:setup-sales')->delete('tax_rates/{id}', 'Model\TaxRateController@trash');

	Route::middleware('permission:setup-sales')->post('discount_types', 'Model\DiscountTypeController@create');
	Route::middleware('permission:setup-sales')->get('discount_types', 'Model\DiscountTypeController@all');
	Route::middleware('permission:setup-sales')->get('discount_types/{id}', 'Model\DiscountTypeController@detail');
	Route::middleware('permission:setup-sales')->put('discount_types/{id}', 'Model\DiscountTypeController@update');
	Route::middleware('permission:setup-sales')->delete('discount_types/{id}', 'Model\DiscountTypeController@trash');

	Route::middleware('permission:setup-sales')->get('config_sales', 'Model\ConfigSaleController@detail');
	Route::middleware('permission:setup-sales')->get('config_sales/{id}', 'Model\ConfigSaleController@detail');
	Route::middleware('permission:setup-sales')->put('config_sales/{id}', 'Model\ConfigSaleController@update');

	Route::middleware('permission:create-appointments')->post('appointments', 'Model\AppointmentController@create');
	Route::middleware('permission:view-appointments')->get('appointments', 'Model\AppointmentController@all');
	Route::middleware('permission:view-appointments')->get('appointments/{id}', 'Model\AppointmentController@detail');
	Route::middleware('permission:edit-appointments')->put('appointments/{id}', 'Model\AppointmentController@update');
	Route::middleware('permission:remove-appointments')->delete('appointments/{id}', 'Model\AppointmentController@trash');

	Route::middleware('permission:view-invoices')->get('uninvoices', 'Model\AppointmentController@uninvoices');

	Route::middleware('permission:create-invoices')->post('invoices', 'Model\InvoiceController@create');
	Route::middleware('permission:view-invoices')->get('invoices', 'Model\InvoiceController@all');
	Route::middleware('permission:view-invoices')->get('invoices/{id}', 'Model\InvoiceController@detail');
	Route::middleware('permission:edit-invoices')->put('invoices/{id}', 'Model\InvoiceController@update');
	Route::middleware('permission:remove-invoices')->delete('invoices/{id}', 'Model\InvoiceController@trash');
	Route::middleware('permission:view-reports')->get('invoices/sum/monthly', 'Model\InvoiceController@getMonthlySum');

	Route::middleware('permission:create-receives')->post('receives', 'Model\ReceiveController@create');
	Route::middleware('permission:view-receives')->get('receives', 'Model\ReceiveController@all');
	Route::middleware('permission:view-receives')->get('receives/{id}', 'Model\ReceiveController@detail');
	Route::middleware('permission:edit-receives')->put('receives/{id}', 'Model\ReceiveController@update');
	Route::middleware('permission:remove-receives')->delete('receives/{id}', 'Model\ReceiveController@trash');

	Route::middleware('permission:create-payments')->post('payments', 'Model\PaymentController@create');
	Route::middleware('permission:view-payments')->get('payments', 'Model\PaymentController@all');
	Route::middleware('permission:view-payments')->get('payments/{id}', 'Model\PaymentController@detail');
	Route::middleware('permission:edit-payments')->put('payments/{id}', 'Model\PaymentController@update');
	Route::middleware('permission:remove-payments')->delete('payments/{id}', 'Model\PaymentController@trash');
	// Route::middleware('permission:view-payments')->get('receipt/{id}', 'Report\ReportController@generateReceipt');

	Route::middleware('permission:view-reports')->get('commissions', 'Model\CommissionController@all');
	Route::middleware('permission:view-reports')->get('commissions/{id}', 'Model\CommissionController@detail');

	// Route::get('/reports/{name}', "ReportController@index");

});