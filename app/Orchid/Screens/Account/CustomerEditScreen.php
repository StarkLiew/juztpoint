<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Payment;

use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CustomerEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Customer';

	/**
	 * @var bool
	 */
	private $exist = false;

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
	 * @param \Orchid\Platform\Models\User $user
	 *
	 * @return array
	 */
	public function query(Account $account): array
	{

		$this->exist = $account->exists;

		return [
			'account' => $account,
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

			Link::name(__('Save'))
				->icon('icon-check')
				->method('save'),

			Link::name(__('Remove'))
				->icon('icon-trash')
				->method('remove')
				->canSee($this->exist),
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
			CustomerEditLayout::class,
		];
	}

	/**
	 * @param \Orchid\Platform\Models\Account $account
	 * @param \Illuminate\Http\Request     $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Account $account, Request $request) {
		$account->type = 'customer';
		$account->user_id = Auth::id();
		$account = $request->get('account');

		$account
			->fill($request->get('account'))
			->save();

		Alert::info(__('Customer was saved'));

		return redirect()->route('platform.customers');
	}

	/**
	 * @param Account $account
	 *
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remove(Account $account) {
		$account->delete();

		Alert::info(__('Customer was removed'));

		return redirect()->route('platform.customers');
	}

}
