<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Inventory;

use App\Models\Setting;
use App\Orchid\Layouts\Inventory\CategoryEditLayout;
use App\Orchid\Layouts\Inventory\CategoryListLayout;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CategoryListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Category';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered categories';

	/**
	 * @var string
	 */
	public $permission = 'platform.categories';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'settings' => Setting::filters()
				->where('type', '=', 'category')
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
				->link(route('platform.categories.create')),
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
			CategoryListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					CategoryEditLayout::class,
				],
			])->async('asyncGetCategory'),
		];
	}

	/**
	 * @param User $user
	 *
	 * @return array
	 */
	public function asyncGetCategory(Setting $setting): array
	{
		return [
			'setting' => $setting,
		];
	}

	/**
	 * @param Account $account
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveCategory(Setting $setting, Request $request) {
		$account->fill($request->get('setting'));
		$account->type = 'category';
		$account->save();

		Alert::info(__('Category was saved.'));

		return back();
	}
}
