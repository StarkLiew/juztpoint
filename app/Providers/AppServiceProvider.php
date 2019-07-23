<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Orchestra\Support\Facades\Tenanti;

//NEW: Import Schema

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		Schema::defaultStringLength(191);

		User::observe(new UserObserver);

		Tenanti::connection('tenants', function (User $entity, array $config) {
			$config['database'] = "user_{$entity->id}";

			return $config;
		});

	}
}
