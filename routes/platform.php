<?php

declare (strict_types = 1);

use App\Orchid\Screens\Account\CustomerEditScreen;
use App\Orchid\Screens\Account\CustomerListScreen;
use App\Orchid\Screens\Account\VendorEditScreen;
use App\Orchid\Screens\Account\VendorListScreen;
use App\Orchid\Screens\ExampleScreen;
use App\Orchid\Screens\Inventory\CategoryEditScreen;
use App\Orchid\Screens\Inventory\CategoryListScreen;
use App\Orchid\Screens\Inventory\ProductEditScreen;
use App\Orchid\Screens\Inventory\ProductListScreen;
use App\Orchid\Screens\Inventory\ServiceEditScreen;
use App\Orchid\Screens\Inventory\ServiceListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Settings\Commission\CommissionEditScreen;
use App\Orchid\Screens\Settings\Commission\CommissionListScreen;
use App\Orchid\Screens\Settings\Company\CompanyEditScreen;
use App\Orchid\Screens\Settings\Payment\PaymentEditScreen;
use App\Orchid\Screens\Settings\Payment\PaymentListScreen;
use App\Orchid\Screens\Settings\Store\StoreEditScreen;
use App\Orchid\Screens\Settings\Store\StoreListScreen;
use App\Orchid\Screens\Settings\Tax\TaxEditScreen;
use App\Orchid\Screens\Settings\Tax\TaxListScreen;
use App\Orchid\Screens\Settings\Terminal\TerminalEditScreen;
use App\Orchid\Screens\Settings\Terminal\TerminalListScreen;
use App\Orchid\Screens\User\UserAddScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
 */

// Main
$this->router->screen('/main', PlatformScreen::class)->name('platform.main');

// Company...
$this->router->screen('systems/company', CompanyEditScreen::class)->name('platform.systems.company');

// Stores...
$this->router->screen('stores/{stores}/edit', StoreEditScreen::class)->name('platform.systems.stores.edit');
$this->router->screen('stores/create', StoreEditScreen::class)->name('platform.systems.stores.create');
$this->router->screen('stores', StoreListScreen::class)->name('platform.systems.stores');

// Stores...
$this->router->screen('taxes/{stores}/edit', TaxEditScreen::class)->name('platform.systems.taxes.edit');
$this->router->screen('taxes/create', TaxEditScreen::class)->name('platform.systems.taxes.create');
$this->router->screen('taxes', TaxListScreen::class)->name('platform.systems.taxes');

// Stores...
$this->router->screen('terminals/{terminals}/edit', TerminalEditScreen::class)->name('platform.systems.terminals.edit');
$this->router->screen('terminals/create', TerminalEditScreen::class)->name('platform.systems.terminals.create');
$this->router->screen('terminals', TerminalListScreen::class)->name('platform.systems.terminals');

// Stores...
$this->router->screen('commissions/{stores}/edit', CommissionEditScreen::class)->name('platform.systems.commissions.edit');
$this->router->screen('commissions/create', CommissionEditScreen::class)->name('platform.systems.commissions.create');
$this->router->screen('commissions', CommissionListScreen::class)->name('platform.systems.commissions');

// Payments...
$this->router->screen('payments/{stores}/edit', PaymentEditScreen::class)->name('platform.systems.payments.edit');
$this->router->screen('payments/create', PaymentEditScreen::class)->name('platform.systems.payments.create');
$this->router->screen('payments', PaymentListScreen::class)->name('platform.systems.payments');

// Users...
$this->router->screen('users/{users}/edit', UserEditScreen::class)->name('platform.systems.users.edit');
$this->router->screen('users/create', UserAddScreen::class)->name('platform.systems.users.create');
$this->router->screen('users', UserListScreen::class)->name('platform.systems.users');

// Roles...
$this->router->screen('roles/{roles}/edit', RoleEditScreen::class)->name('platform.systems.roles.edit');
$this->router->screen('roles/create', RoleEditScreen::class)->name('platform.systems.roles.create');
$this->router->screen('roles', RoleListScreen::class)->name('platform.systems.roles');

// Example...
$this->router->screen('example', ExampleScreen::class)->name('platform.example');
//Route::screen('/dashboard/screen/idea', 'Idea::class','platform.screens.idea');

// Customers...
$this->router->screen('customers/{accounts}/edit', CustomerEditScreen::class)->name('platform.customers.edit');
$this->router->screen('customers/create', CustomerEditScreen::class)->name('platform.customers.create');
$this->router->screen('customers', CustomerListScreen::class)->name('platform.customers');

// Vendors...
$this->router->screen('vendors/{accounts}/edit', VendorEditScreen::class)->name('platform.vendors.edit');
$this->router->screen('vendors/create', VendorEditScreen::class)->name('platform.vendors.create');
$this->router->screen('vendors', VendorListScreen::class)->name('platform.vendors');

// Categories...
$this->router->screen('categories/{settings}/edit', CategoryEditScreen::class)->name('platform.categories.edit');
$this->router->screen('categories/create', CategoryEditScreen::class)->name('platform.categories.create');
$this->router->screen('categories', CategoryListScreen::class)->name('platform.categories');

// Products...
$this->router->screen('products/{products}/edit', ProductEditScreen::class)->name('platform.products.edit');
$this->router->screen('products/create', ProductEditScreen::class)->name('platform.products.create');
$this->router->screen('products', ProductListScreen::class)->name('platform.products');

// Services...
$this->router->screen('services/{services}/edit', ServiceEditScreen::class)->name('platform.services.edit');
$this->router->screen('services/create', ServiceEditScreen::class)->name('platform.services.create');
$this->router->screen('services', ServiceListScreen::class)->name('platform.services');