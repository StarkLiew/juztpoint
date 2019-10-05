<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Commission;

use App\Models\Setting;
use App\Orchid\Layouts\Commission\CommissionEditLayout;
use App\Orchid\Layouts\Commission\CommissionListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CommissionListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Commission';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered commissions';

	/**
	 * @var string
	 */
	public $permission = 'platform.systems';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'commissions' => Setting::filters()
				->where('type', '=', 'commission')
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
				->link(route('platform.systems.commissions.create')),
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
			CommissionListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					CommissionEditLayout::class,
				],
			])->async('asyncGetCommission'),
		];
	}

	/**
	 * @param Setting $commission
	 *
	 * @return array
	 */
	public function asyncGetCommission(Setting $commission): array
	{
		return [
			'commission' => $commission,
		];
	}

	/**
	 * @param Setting $commission
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveCommission(Setting $commission, Request $request) {
		$commission->fill($request->get('commission'));
		$commission->type = 'commission';
		$commission->save();

		Alert::info(__('Commission was saved.'));

		return back();
	}
}
