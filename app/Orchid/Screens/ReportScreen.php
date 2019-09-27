<?php

declare (strict_types = 1);

namespace App\Orchid\Screens;

use App\Helpers\TenantTable;
use App\Models\Item;
use App\Orchid\Layouts\Reports\ReportFiltersLayout;
use App\Orchid\Layouts\Reports\Staff\CommissionListLayout;
use App\Orchid\Layouts\Reports\Staff\SummaryByDateListLayout;
use App\Orchid\Layouts\Reports\Staff\SummaryListLayout;
use DB;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
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

		$summary = Item::with(['user'])
			->filters()
			->filtersApplySelection(ReportFiltersLayout::class)
			->select(['user_id', DB::raw('SUM(total_amount) as total_amount')])
			->where('type', '=', 'commission')
			->groupBy('user_id')
			->defaultSort('user_id', 'desc')
			->paginate();

		$dates = Item::with(['user', 'document'])
			->filters()
			->filtersApplySelection(ReportFiltersLayout::class)
			->select(['user_id', DB::raw('DATE(date) as trxn_date'), DB::raw('SUM(total_amount) as total_amount')])
			->join(TenantTable::parse('documents'), TenantTable::parse('items') . '.trxn_id', '=', TenantTable::parse('documents') . '.id')
			->where(TenantTable::parse('items') . '.type', '=', 'commission')
			->groupBy('user_id', DB::raw('DATE(date)'))
			->defaultSort('user_id', 'desc')
			->paginate();

		$sum = collect(['sum' => Item::filters()
				->filtersApplySelection(ReportFiltersLayout::class)
				->where('type', '=', 'commission')->sum('total_amount')]);
		/* $users = User::filters()
				           ->defaultSort('name');

				   $mapped = array_map(($user, $item)=> {
			               return [$user->name => $item]
		*/

		// $dates['count'] = $count;

		return [
			'items' => $items,
			'summary' => $summary,
			'dates' => $dates,
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

			Link::name(__('Print'))
				->icon('icon-printer')
				->method('print'),
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

			Layout::tabs([
				'Detail' => CommissionListLayout::class,
				'Summary By Staff' => SummaryListLayout::class,
				'Summary By Date' => SummaryByDateListLayout::class,
			]),

		];
	}

	public function print() {

	}

}
