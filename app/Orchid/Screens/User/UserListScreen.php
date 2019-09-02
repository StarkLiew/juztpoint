<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserFiltersLayout;
use App\Orchid\Layouts\User\UserListLayout;
use App\Orchid\Models\User;
use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class UserListScreen extends Screen {
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
	 * @return array
	 */
	public function query(): array
	{
		$authUser = Auth::user();

		$tenant_id = $authUser->id;

		if (!empty($authUser->tenant)) {
			$tenant_id = $authUser->tenant;
		}

		return [
			'users' => User::with('roles')
				->filters()
				->where('tenant', '=', $tenant_id)
				->orWhere('id', '=', $tenant_id)
				->filtersApplySelection(UserFiltersLayout::class)
				->defaultSort('id', 'desc')
				->paginate(),
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
			Link::name(__('Add'))
				->icon('icon-plus')
				->link(route('platform.systems.users.create')),
		];
	}

	/**
	 * Views.
	 *
	 * @return Layout[]
	 */
	public function layout(): array
	{
		return [
			UserFiltersLayout::class,
			UserListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					UserEditLayout::class,
				],
			])->async('asyncGetUser'),
		];
	}

	/**
	 * @param User $user
	 *
	 * @return array
	 */
	public function asyncGetUser(User $user): array
	{
		return [
			'user' => $user,
		];
	}

	/**
	 * @param User    $user
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveUser(User $user, Request $request) {
		$input = $request->get('user');
		$auth = Auth::user();

		$input['tenant'] = $auth->id;
		$input['password'] = uniqid();

		$user->fill($input)
			->save();

		Alert::info(__('User was saved.'));

		return back();
	}
}
