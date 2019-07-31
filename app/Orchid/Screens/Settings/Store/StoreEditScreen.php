<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Store;

use App\Models\Setting;
use App\Orchid\Layouts\Store\StoreEditLayout;
use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class StoreEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Store';

	/**
	 * @var bool
	 */
	private $exist = false;

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
	 * @param \Orchid\Platform\Models\User $user
	 *
	 * @return array
	 */
	public function query(Setting $store): array
	{

		$this->exist = $store->exists;

		return [
			'store' => $store,
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
			StoreEditLayout::class,
		];
	}

	/**
	 * @param \Orchid\Platform\Models\User $user
	 * @param \Illuminate\Http\Request     $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Setting $store, Request $request) {
		$store->type = 'store';
		$store->user_id = Auth::id();
		$input = $request->get('store');
		$store->properties = $input['properties'];
		$store
			->fill($request->get('store'))
			->save();

		Alert::info(__('Store was saved'));

		return redirect()->route('platform.systems.stores');
	}

	/**
	 * @param User $user
	 *
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remove(Setting $store) {
		$store->delete();

		Alert::info(__('Store was removed'));

		return redirect()->route('platform.systems.stores');
	}

}
