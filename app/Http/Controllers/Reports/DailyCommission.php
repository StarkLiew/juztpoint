<?php
namespace App\Http\Controllers\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PdfReport;

class DailyCommission {

	public static function show(Request $request) {
		$title = 'Staff Daily Commission'; // Report title
		$from = $request->get('from') . ' 00:00:00';
		$to = $request->get('to') . ' 23:59:59';
		$user = $request->get('user');

		$meta = [ // For displaying filters description on header
			'Date from' => $from . ' to ' . $to,
			'Staff' => $user,
		];

		$columns = [ // Set Column to be displayed

			'Date' => 'trxn_date', // if no column_name specified, this will automatically seach for snake_case of column name (will be registered_at) column from query result
			'Employee Name' => 'user_id',
			'Amount' => 'total_amount',
		];

		$queryBuilder = Item::with(['user'])->select(['user_id', DB::raw('DATE(date) as trxn_date'), DB::raw('SUM(total_amount) as total_amount')])
			->join(TenantTable::parse('documents'), TenantTable::parse('items') . '.trxn_id', '=', TenantTable::parse('documents') . '.id')
			->where(TenantTable::parse('items') . '.type', '=', 'commission')
			->whereBetween(TenantTable::parse('documents') . '.date', [$from, $to]);

		if (!empty($user)) {
			$queryBuilder = $queryBuilder->where(TenantTable::parse('items') . '.user_id', '=', $user);
		}

		$queryBuilder = $queryBuilder->groupBy('user_id', DB::raw('DATE(date)'));

		return PdfReport::of($title, $meta, $queryBuilder, $columns)
			->editColumn('Date', [ // Change column class or manipulate its data for displaying to report
				'displayAs' => function ($result) {
					return Carbon::parse($result->trxn_date)->format('d/m/Y');
				},
				'class' => 'left',
			])
			->editColumn('Employee Name', [ // Change column class or manipulate its data for displaying to report
				'displayAs' => function ($result) {
					return $result->user->name;
				},
				'class' => 'left',
			])
			->editColumn('Amount', [
				'class' => 'right bold',
				'displayAs' => function ($result) {
					return number_format($result->total_amount, 2, '.', ',');

				},

			])
			->groupBy('Employee Name')
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Amount' => 'point', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
			])
			->limit(20) // Limit record to be showed
			->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
	}

}