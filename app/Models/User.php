<?php

namespace App\Models;

use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Orchestra\Tenanti\Tenantor;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable {
	use SoftDeletes, Notifiable;
	/**
	 * Convert to tenantor.
	 *
	 * @return \Orchestra\Tenanti\Tenantor
	 */
	public function asTenantor(): Tenantor {
		return Tenantor::fromEloquent('pos', $this);
	}

	/**
	 * Make a tenantor.
	 *
	 * @return \Orchestra\Tenanti\Tenantor
	 */
	public static function makeTenantor($key, $connection = null): Tenantor {
		return Tenantor::make(
			'pos', $key, $connection ?: (new static())->getConnectionName()
		);
	}

	/**
	 * The "booting" method of the model.
	 */
	protected static function boot() {
		parent::boot();

		static::observe(new UserObserver);
	}

}
