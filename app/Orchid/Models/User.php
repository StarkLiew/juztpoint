<?php

declare (strict_types = 1);

namespace App\Orchid\Models;

use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Orchid\Access\UserAccess;
use Orchid\Access\UserInterface;
use Orchid\Filters\Filterable;
use Orchid\Platform\Notifications\DashboardNotification;
use Orchid\Platform\Notifications\ResetPassword;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Dashboard;

class User extends Authenticatable implements UserInterface {
	use Notifiable, UserAccess, AsSource, Filterable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'pin',
		'tenant',
		'initial',
		'last_login',
		'permissions',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * @var array
	 */
	protected $casts = [
		'permissions' => 'array',
		'email_verified_at' => 'datetime',
		'last_login' => 'datetime',
	];

	/**
	 * @var array
	 */
	protected $allowedFilters = [
		'id',
		'name',
		'email',
		'permissions',
	];

	/**
	 * @var array
	 */
	protected $allowedSorts = [
		'id',
		'name',
		'email',
		'last_login',
		'updated_at',
		'created_at',
	];

	/**
	 * Send the password reset notification.
	 *
	 * @param string $token
	 *
	 * @return void
	 */
	public function sendPasswordResetNotification($token) {
		$this->notify(new ResetPassword($token));
	}

	/**
	 * Display name.
	 *
	 * @return string
	 */
	public function getNameTitle(): string {
		return $this->name;
	}

	/**
	 * Display sub.
	 *
	 * @return string
	 */
	public function getSubTitle(): string {
		return 'Administrator';
	}

	/**
	 * @param string $name
	 * @param string $email
	 * @param string $password
	 *
	 * @throws Exception
	 */
	public static function createAdmin(string $name, string $email, string $password) {
		if (static::where('email', $email)->exists()) {
			throw new Exception('User exist');
		}

		$permissions = collect();

		Dashboard::getPermission()
			->collapse()
			->each(function ($item) use ($permissions) {
				$permissions->put($item['slug'], true);
			});

		$user = static::create([
			'name' => $name,
			'email' => $email,
			'password' => Hash::make($password),
			'permissions' => $permissions,
		]);

		$user->notify(new DashboardNotification([
			'title' => "Welcome {$name}",
			'message' => 'You can find the latest news of the project on the website',
			'action' => 'https://orchid.software/',
			'type' => DashboardNotification::INFO,
		]));
	}

	/**
	 *@throws Exception
	 *
	 * @return string
	 */
	public function getAvatar() {
		$hash = md5(strtolower(trim($this->email)));

		return "https://www.gravatar.com/avatar/$hash";
	}

	/**
	 * @return Collection
	 */
	public function getStatusPermission(): Collection{
		$permissions = $this->permissions ?? [];

		return Dashboard::getPermission()
			->sort()
			->transform(function ($group) use ($permissions) {
				return collect($group)->sortBy('description')
					->map(function ($value) use ($permissions) {
						$slug = $value['slug'];
						$value['active'] = array_key_exists($slug, $permissions) && (bool) $permissions[$slug];

						return $value;
					});
			});
	}
}
