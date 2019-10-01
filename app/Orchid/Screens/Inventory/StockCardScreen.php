<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Inventory;

use App\Helpers\TenantTable;
use App\Models\Item;
use App\Orchid\Layouts\Reports\Inventory\StockCardFiltersLayout;
use App\Orchid\Layouts\Reports\Inventory\StockCardListLayout;
use DB;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;

class StockCardScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Stock Card';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'Intel on current Stock Status';

	/**
	 * @var string
	 */
	public $permission = 'platform.inventory';

	/**
	 * Query data.
	 *
	 *
	 * @return array
	 */
	public function query(): array
	{

		$products = Item::with(['user', 'document', 'product'])
			->filters()
			->filtersApplySelection(StockCardFiltersLayout::class)
			->select(['trxn_id', 'item_id', DB::raw(TenantTable::parse('items') . '.type as type'), DB::raw('DATE(date) as trxn_date'), DB::raw('SUM(qty) as qty')])
			->join(TenantTable::parse('products'), TenantTable::parse('items') . '.item_id', '=', TenantTable::parse('products') . '.id')
			->rightJoin(TenantTable::parse('documents'), TenantTable::parse('items') . '.trxn_id', '=', TenantTable::parse('documents') . '.id')
			->where(TenantTable::parse('products') . '.type', '=', 'product')
			->where(TenantTable::parse('items') . '.type', '=', 'open')
			->orWhere(TenantTable::parse('items') . '.type', '=', 'item')
			->orWhere(TenantTable::parse('items') . '.type', '=', 'receive')
			->groupBy('trxn_id', 'item_id', DB::raw(TenantTable::parse('items') . '.type'), DB::raw('DATE(date)'))
			->defaultSort('item_id', 'desc')
			->paginate();

		/* $users = User::filters()
				           ->defaultSort('name');

				   $mapped = array_map(($user, $item)=> {
			               return [$user->name => $item]
		*/

		// $dates['count'] = $count;

		return [
			'products' => $products,

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
				->link(route('report', ['report' => 'DailyCommission']) . '?' . $_SERVER['QUERY_STRING']),
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
			StockCardFiltersLayout::class,

			Layout::tabs([
				'Stock Card' => StockCardListLayout::class,

			]),

		];
	}

	public function print() {

	}
}