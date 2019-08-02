<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Account;

use App\Models\Account;
use App\Orchid\Layouts\Account\VendorEditLayout;
use App\Orchid\Layouts\Account\VendorListLayout;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class VendorListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Vendor';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered vendors';

	/**
	 * @var string
	 */
	public $permission = 'platform.vendors';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'accounts' => Account::filters()
				->where('type', '=', 'vendor')
				->defaultSort('name', 'desc')
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
				->link(route('platform.vendors.create')),
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
			VendorListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					VendorEditLayout::class,
				],
			])->async('asyncGetVendor'),
		];
	}

	/**
	 * @param User $user
	 *
	 * @return array
	 */
	public function asyncGetVendor(Account $account): array
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
		$account->type = 'vendor';
		$account->save();

		Alert::info(__('Vendor was saved.'));

		return back();
	}
}
