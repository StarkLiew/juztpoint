<?php

declare (strict_types = 1);

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

//Screens

// Platform > System > Users
Breadcrumbs::for ('platform.systems.users', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Users'), route('platform.systems.users'));
});

// Platform > System > Users > User
Breadcrumbs::for ('platform.systems.users.edit', function ($trail, $user) {
	$trail->parent('platform.systems.users');
	$trail->push(__('Edit'), route('platform.systems.users.edit', $user));
});

// Platform > System > Roles
Breadcrumbs::for ('platform.systems.roles', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Roles'), route('platform.systems.roles'));
});

// Platform > System > Roles > Create
Breadcrumbs::for ('platform.systems.roles.create', function ($trail) {
	$trail->parent('platform.systems.roles');
	$trail->push(__('Create'), route('platform.systems.roles.create'));
});

// Platform > System > Roles > Role
Breadcrumbs::for ('platform.systems.roles.edit', function ($trail, $role) {
	$trail->parent('platform.systems.roles');
	$trail->push(__('Role'), route('platform.systems.roles.edit', $role));
});

// Platform > System > Category
Breadcrumbs::for ('platform.systems.category', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Categories'), route('platform.systems.category'));
});

// Platform > System > Categories > Create
Breadcrumbs::for ('platform.systems.category.create', function ($trail) {
	$trail->parent('platform.systems.category');
	$trail->push(__('Create'), route('platform.systems.category.create'));
});

// Platform > Categories > Category
Breadcrumbs::for ('platform.systems.category.edit', function ($trail, $category) {
	$trail->parent('platform.systems.category');
	$trail->push(__('Category'), route('platform.systems.category.edit', $category));
});

// Platform > System > Comments
Breadcrumbs::for ('platform.systems.comments', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Comments'), route('platform.systems.comments'));
});

// Platform > System > Comments > Comment
Breadcrumbs::for ('platform.systems.comments.edit', function ($trail, $comment) {
	$trail->parent('platform.systems.comments');
	$trail->push(__('Comment'), route('platform.systems.comments.edit', $comment));
});

// Platform -> Example
Breadcrumbs::for ('platform.example', function ($trail) {
	$trail->parent('platform.index');
	$trail->push(__('Example'));
});

// Platform > System > Company
Breadcrumbs::for ('platform.systems.company', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Company'), route('platform.systems.company'));
});

// Platform > System > Stores
Breadcrumbs::for ('platform.systems.stores', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Stores'), route('platform.systems.stores'));
});

// Platform > System > Stores > Create
Breadcrumbs::for ('platform.systems.stores.create', function ($trail) {
	$trail->parent('platform.systems.stores');
	$trail->push(__('Create'), route('platform.systems.stores.create'));
});

// Platform > System > Stores > Store
Breadcrumbs::for ('platform.systems.stores.edit', function ($trail, $store) {
	$trail->parent('platform.systems.stores');
	$trail->push(__('Store'), route('platform.systems.stores.edit', $store));
});

// Platform > System > Taxes
Breadcrumbs::for ('platform.systems.taxes', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Taxes'), route('platform.systems.taxes'));
});

// Platform > System > Taxes > Create
Breadcrumbs::for ('platform.systems.taxes.create', function ($trail) {
	$trail->parent('platform.systems.taxes');
	$trail->push(__('Create'), route('platform.systems.taxes.create'));
});

// Platform > System > Taxes > Store
Breadcrumbs::for ('platform.systems.taxes.edit', function ($trail, $tax) {
	$trail->parent('platform.systems.taxes');
	$trail->push(__('Tax'), route('platform.systems.taxes.edit', $tax));
});

// Platform > System > Commissions
Breadcrumbs::for ('platform.systems.commissions', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Commissions'), route('platform.systems.commissions'));
});

// Platform > System > Commissions > Create
Breadcrumbs::for ('platform.systems.commissions.create', function ($trail) {
	$trail->parent('platform.systems.commissions');
	$trail->push(__('Create'), route('platform.systems.commissions.create'));
});

// Platform > System > Commissions > Store
Breadcrumbs::for ('platform.systems.commissions.edit', function ($trail, $commission) {
	$trail->parent('platform.systems.commissions');
	$trail->push(__('Commission'), route('platform.systems.commissions.edit', $commission));
});

// Platform > System > Payments
Breadcrumbs::for ('platform.systems.payments', function ($trail) {
	$trail->parent('platform.systems.index');
	$trail->push(__('Payments'), route('platform.systems.payments'));
});

// Platform > System > Payments > Create
Breadcrumbs::for ('platform.systems.payments.create', function ($trail) {
	$trail->parent('platform.systems.payments');
	$trail->push(__('Create'), route('platform.systems.payments.create'));
});

// Platform > System > Payments > Store
Breadcrumbs::for ('platform.systems.payments.edit', function ($trail, $payment) {
	$trail->parent('platform.systems.payments');
	$trail->push(__('Payment'), route('platform.systems.payments.edit', $payment));
});