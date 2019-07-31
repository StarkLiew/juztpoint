<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Tax;

use App\Models\Setting;
use App\Orchid\Layouts\Tax\TaxEditLayout;
use App\Orchid\Layouts\Tax\TaxListLayout;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class TaxListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Tax';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered taxes';

	/**
	 * @var string
	 */
	public $permission = 'platform.systems.taxes';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'taxes' => Setting::filters()
				->where('type', '=', 'tax')
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
				->link(route('platform.systems.taxes.create')),
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
			TaxListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					TaxEditLayout::class,
				],
			])->async('asyncGetTax'),
		];
	}

	/**
	 * @param User $user
	 *
	 * @return array
	 */
	public function asyncGetTax(Setting $tax): array
	{
		return [
			'tax' => $tax,
		];
	}

	/**
	 * @param User    $user
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveStore(Setting $tax, Request $request) {
		$tax->fill($request->get('tax'));
		$tax->type = 'tax';
		$tax->save();

		Alert::info(__('Tax was saved.'));

		return back();
	}
}
