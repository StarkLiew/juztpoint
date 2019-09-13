<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Orchid\Access\UserSwitch;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class UserEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'User';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered users';

	/**
	 * @var string
	 */
	public $permission = 'platform.systems.users';

	/**
	 * Query data.
	 *
	 * @param \Orchid\Platform\Models\User $user
	 *
	 * @return array
	 */
	public function query(User $user): array
	{
		$user->load(['roles']);

		return [
			'user' => $user,
			'permission' => $user->getStatusPermission(),
		];
	}

	/**
	 * Button commands.
	 *
	 * @return Link[]
	 */
	public function commandBar(): array
	{
		return [

			Link::name(__('Settings'))
				->icon('icon-open')
				->group([
					Link::name(__('Login as user'))
						->icon('icon-login')
						->method('loginAs'),

					Link::name(__('Change Password'))
						->icon('icon-lock-open')
						->title(__('Change Password'))
						->method('changePassword')
						->modal('password'),
				]),

			Link::name(__('Save'))
				->icon('icon-check')
				->method('save'),

			Link::name(__('Remove'))
				->icon('icon-trash')
				->method('remove'),
		];
	}

	/**
	 * @throws \Throwable
	 *
	 * @return array
	 */
	public function layout(): array
	{
		return [
			UserEditLayout::class,
			// UserRoleLayout::class,

			Layout::modal('password', [
				Layout::rows([
					Password::make('password')
						->title(__('Password'))
						->required()
						->placeholder(__('Enter your password')),
				]),
			]),
		];
	}

	/**
	 * @param \Orchid\Platform\Models\User $user
	 * @param \Illuminate\Http\Request     $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(User $user, Request $request) {

		$authUser = Auth::user();

		$tenant_id = $authUser->id;
		if (!empty($authUser->tenant)) {
			$tenant_id = $authUser->tenant;
		}

		$input = $request->get('user');

		$hasPin = User::where('pin', $input['pin'])->where('id', '<>', $user->id)->where(function ($query) use ($tenant_id) {
			$query->where('tenant', $tenant_id)
				->orWhere('id', $tenant_id);
		})->count();
		if ($hasPin > 0) {
			Alert::info(__('User pin exist. Try diffirent pin.'));
			return back();
		}

		$permissions = [];

		if (array_key_exists('backoffice', $input['properties'])) {

			$permissions = json_decode('{"platform.systems.announcement":"1","platform.systems.attachment":"1","platform.systems.commissions":"1","platform.systems.company":"1","platform.systems.payments":"1","platform.systems.roles":"1","platform.systems.stores":"1","platform.systems.taxes":"1","platform.systems.users":"1","platform.categories":"1","platform.customers":"1","platform.index":"1","platform.products":"1","platform.services":"1","platform.systems.index":"1","platform.systems":"1","platform.vendors":"1"}');

		}

		// $permissions = $request->get('permissions', []);
		$roles = $request->input('user.roles', []);

		/* foreach ($permissions as $key => $value) {
			unset($permissions[$key]);
			$permissions[base64_decode($key)] = $value;
		} */

		$user
			->fill($request->get('user'))
			->replaceRoles($roles)
			->fill([
				'permissions' => $permissions,
			])->save();

		Alert::info(__('User was saved'));

		return redirect()->route('platform.systems.users');
	}

	/**
	 * @param User $user
	 *
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remove(User $user) {
		$user->delete();

		Alert::info(__('User was removed'));

		return redirect()->route('platform.systems.users');
	}

	/**
	 * @param User $user
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function loginAs(User $user) {
		UserSwitch::loginAs($user);

		return redirect()->route(config('platform.index'));
	}

	/**
	 * @param User    $user
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function changePassword(User $user, Request $request) {
		$user->password = Hash::make($request->get('password'));
		$user->save();

		Alert::info(__('User was saved'));

		return back();
	}
}
