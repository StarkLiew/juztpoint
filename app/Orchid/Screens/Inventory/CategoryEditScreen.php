<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Inventory;

use App\Models\Setting;
use App\Orchid\Layouts\Inventory\CategoryEditLayout;
use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CategoryEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Category';

	/**
	 * @var bool
	 */
	private $exist = false;

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
	 * @param \App\Models\Setting $setting
	 *
	 * @return array
	 */
	public function query(Setting $setting): array
	{

		$this->exist = $setting->exists;

		return [
			'setting' => $setting,
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
			CategoryEditLayout::class,
		];
	}

	/**
	 * @param \App\Models\Account $account
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Setting $setting, Request $request) {
		$setting->type = 'category';
		$setting->user_id = Auth::id();
		$setting
			->fill($request->get('setting'))
			->save();

		Alert::info(__('Category was saved'));

		return redirect()->route('platform.categories');
	}

	/**
	 * @param Category $category
	 *d
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponsed
	 */
	public function remove(Setting $setting) {
		$setting->delete();

		Alert::info(__('Category was removed'));

		return redirect()->route('platform.categories');
	}

}
