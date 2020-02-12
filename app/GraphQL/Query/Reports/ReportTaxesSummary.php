<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportTaxesSummary {

	public function run($args, Closure $getSelectFields) {
		$documents = TenantTable::parse('documents');
		$items = TenantTable::parse('items');
		$settings = TenantTable::parse('settings');
		$accounts = TenantTable::parse('accounts');

		$where = function ($query) use ($args, $documents) {
			if (isset($args['from']) && isset($args['to'])) {
				if ($args['from'] !== "" && $args['to'] !== "") {
					$from = $args['from'] . ' 00:00:00';
					$to = $args['to'] . ' 23:59:59';
					$query->whereBetween($documents . '.date', [$from, $to]);
				}
			}
			if (isset($args['store'])) {
				$query->where($documents . '.store_id', $args['store']);
			}
			if (isset($args['terminal'])) {
				$query->where($documents . '.terminal_id', $args['terminal']);
			}
			if (isset($args['user'])) {
				$query->where($documents . '.transact_by', $args['user']);
			}

		};

		$item = Item::join($documents, $documents . '.id', '=', $items . '.trxn_id')
			->join(DB::raw($settings . ' as tax'), 'tax.id', '=', $items . '.tax_id')
			->join(DB::raw($settings . ' as store'), 'store.id', '=', $items . '.store_id')
			->leftJoin(DB::raw($accounts . ' as accounts'), $documents . '.account_id', '=', 'accounts.uid')
			->where($items . '.type', 'item')
			->where($where);

		$sum = $item->sum($items . '.tax_amount');
		$count = $item->count('item_id');

		$query = $item->selectRaw('tax.name as item_name, store.name as store_name, accounts.name as customer_name, count(' . $items . '.id) as count,  sum(' . $items . '.tax_amount) as total_amount')
			->groupBy('tax.name')
			->groupBy('accounts.name')
			->groupBy('store.name');

		if (isset($args['sort']) && isset($args['desc'])) {

			if (isset($args['desc']) && $args['desc'] === 'desc') {
				$query->orderBy($args['sort'], 'desc');
			} else {
				if ($args['sort'] !== '') {
					$query->orderBy($args['sort']);
				} else {
					$query->orderBy($documents . '.reference', 'desc');
				}

			}

		}
		$results = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
		return ['summary' => ['count' => $count, 'sum' => $sum], 'data' => $results];

	}
}
