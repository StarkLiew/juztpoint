<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Payment;

use App\Models\Setting;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CustomerListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Customer';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered customers';

	/**
	 * @var string
	 */
	public $permission = 'platform.customers';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'accounts' => Setting::filters()
				->where('type', '=', 'customer')
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
				->link(route('platform.customers.create')),
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
			CustomerListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					CustomerEditLayout::class,
				],
			])->async('asyncGetCustomer'),
		];
	}

	/**
	 * @param User $user
	 *
	 * @return array
	 */
	public function asyncGetCustomer(Account $account): array
	{
		return [
			'account' => $account,
		];
	}

	/**
	 * @param Account $account
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveAccount(Account $account, Request $request) {
		$account->fill($request->get('account'));
		$account->type = 'account';
		$account->save();

		Alert::info(__('Customer was saved.'));

		return back();
	}
}
