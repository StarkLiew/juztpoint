<?php

declare (strict_types = 1);

use App\Orchid\Screens\ExampleScreen;
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
$this->router->screen('commissions/{stores}/edit', CommissionEditScreen::class)->name('platform.systems.commissions.edit');
$this->router->screen('commissions/create', CommissionEditScreen::class)->name('platform.systems.commissions.create');
$this->router->screen('commissions', CommissionListScreen::class)->name('platform.systems.commissions');

// Payments...
$this->router->screen('payments/{stores}/edit', PaymentEditScreen::class)->name('platform.systems.payments.edit');
$this->router->screen('payments/create', PaymentEditScreen::class)->name('platform.systems.payments.create');
$this->router->screen('payments', PaymentListScreen::class)->name('platform.systems.payments');

// Users...
$this->router->screen('users/{users}/edit', UserEditScreen::class)->name('platform.systems.users.edit');
$this->router->screen('users', UserListScreen::class)->name('platform.systems.users');

// Roles...
$this->router->screen('roles/{roles}/edit', RoleEditScreen::class)->name('platform.systems.roles.edit');
$this->router->screen('roles/create', RoleEditScreen::class)->name('platform.systems.roles.create');
$this->router->screen('roles', RoleListScreen::class)->name('platform.systems.roles');

// Example...
$this->router->screen('example', ExampleScreen::class)->name('platform.example');
//Route::screen('/dashboard/screen/idea', 'Idea::class','platform.screens.idea');