<?php

declare (strict_types = 1);

namespace App\Orchid\Screens;

use App\Models\Item;
use App\Orchid\Layouts\Reports\ReportFiltersLayout;
use App\Orchid\Layouts\Reports\Staff\CommissionListLayout;
use DB;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class ReportScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Reports';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'Provides information, and sometimes analysis';

	/**
	 * Query data.
	 *
	 *
	 * @return array
	 */
	public function query(): array
	{

		$items = Item::with(['user', 'product', 'document'])
			->filters()
			->filtersApplySelection(ReportFiltersLayout::class)
			->select(['user_id', 'item_id', 'trxn_id', DB::raw('SUM(total_amount) as total_amount')])
			->where('type', '=', 'commission')
			->groupBy('user_id', 'item_id', 'trxn_id')
			->defaultSort('user_id', 'desc')
			->paginate();

		/* $users = User::filters()
				           ->defaultSort('name');

				   $mapped = array_map(($user, $item)=> {
			               return [$user->name => $item]
		*/

		return [
			'items' => $items,
		];
	}

	/**
	 * Button commands.
	 *
	 * @return Action[]
	 */
	public function commandBar(): array
	{
		return [

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
			ReportFiltersLayout::class,
			CommissionListLayout::class,

		];
	}
}
