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
use PdfReport;

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
		$title = 'Staff Daily Commission'; // Report title
		$from = $this->request->get('from') . ' 00:00:00';
		$to = $this->request->get('to') . ' 23:59:59';
		$user = $this->request->get('user');

		$meta = [ // For displaying filters description on header
			'Date from' => $from . ' to ' . $to,
			'Staff' => $user,
		];

		$queryBuilder = Item::select(['user_id', DB::raw('DATE(date) as trxn_date'), DB::raw('SUM(total_amount) as total_amount')])
			->join(TenantTable::parse('documents'), TenantTable::parse('items') . '.trxn_id', '=', TenantTable::parse('documents') . '.id')
			->where(TenantTable::parse('items') . '.type', '=', 'commission')
			->whereBetween(TenantTable::parse('documents') . '.date', [$from, $to])
			->where(TenantTable::parse('items') . '.user_id', '=', $user)
			->groupBy('user_id', DB::raw('DATE(date)'));

		$columns = [ // Set Column to be displayed

			'Date' => 'trxn_date', // if no column_name specified, this will automatically seach for snake_case of column name (will be registered_at) column from query result
			'Amount' => 'total_amount',
		];

		// Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
		return PdfReport::of($title, $meta, $queryBuilder, $columns)
			->editColumn('Date', [ // Change column class or manipulate its data for displaying to report
				'displayAs' => function ($result) {
					return $result->registered_at->format('d M Y');
				},
				'class' => 'left',
			])
			->editColumns(['Amount'], [ // Mass edit column
				'class' => 'right bold',
			])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Total Balance' => 'point', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
			])
			->limit(20) // Limit record to be showed
			->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
	}

}
