<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Store;

use App\Models\Setting;
use App\Orchid\Layouts\Store\StoreEditLayout;
use App\Orchid\Layouts\Store\StoreListLayout;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class StoreListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Store';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered stores';

	/**
	 * @var string
	 */
	public $permission = 'platform.systems.stores';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'stores' => Setting::filters()
				->where('type', '=', 'store')
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
				->link(route('platform.systems.stores.create')),
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
			StoreListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					StoreEditLayout::class,
				],
			])->async('asyncGetStore'),
		];
	}

	/**
	 * @param User $user
	 *
	 * @return array
	 */
	public function asyncGetStore(Setting $store): array
	{
		return [
			'store' => $store,
		];
	}

	/**
	 * @param User    $user
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveStore(Setting $store, Request $request) {
		$store->fill($request->get('store'));
		$store->type = 'store';
		$store->save();

		Alert::info(__('Store was saved.'));

		return back();
	}
}
