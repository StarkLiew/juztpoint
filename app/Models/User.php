<?php

namespace App\Models;

use App\Notifications\PasswordReset;
use App\Observers\UserObserver;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use Orchestra\Tenanti\Tenantor;

class User extends Authenticatable {
	use SoftDeletes, Notifiable, HasApiTokens, Billable;

	protected $appends = array('company_name');
	protected $company_name;

	/**
	 * @var array
	 */
	protected $casts = [
		'properties' => 'array',
		'permissions' => 'array',
	];

	protected $fillable = [
		'name',
		'avatar',
		'email',
		'password',
		'pin',
		'level',
		'tenant',
		'initial',
		'last_login',
		'permissions',
		'properties',
	];

	/**
	 * Convert to tenantor.
	 *
	 * @return \Orchestra\Tenanti\Tenantor
	 */
	public function asTenantor(): Tenantor {
		return Tenantor::fromEloquent('user', $this);
	}

	/**
	 * Make a tenantor.
	 *
	 * @return \Orchestra\Tenanti\Tenantor
	 */
	public static function makeTenantor($key, $connection = null): Tenantor {
		return Tenantor::make(
			'user', $key, $connection ?: (new static())->getConnectionName()
		);
	}

	/**
	 * The "booting" method of the model.
	 */
	protected static function boot() {
		parent::boot();

		static::addGlobalScope(new TenantScope);
		static::observe(new UserObserver);

	}

	/**
	 * Set the user's first name.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function getCompanyNameAttribute() {
		return $this->company_name;
	}

	/**
	 * Set the user's first name.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setCompanyNameAttribute($value) {
		$this->company_name = $value;
	}

	public static function getAvatarAttribute($value) {
		if (!$value) {
			return null;
		}

		return 'data:image/jpeg;base64,' . base64_encode($value);
	}
	public function sendPasswordResetNotification($token) {

		$this->notify(new PasswordReset($token));
	}

}
