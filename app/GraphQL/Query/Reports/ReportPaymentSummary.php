<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportPaymentSummary {

	public function run($args, Closure $getSelectFields) {
		$documents = TenantTable::parse('documents');
		$items = TenantTable::parse('items');
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
			->leftJoin(DB::raw($accounts . ' as accounts'), $documents . '.account_id', '=', 'accounts.uid')
			->where($items . '.type', 'payment')
			->where($where);
		$sum = $item->sum(DB::raw('total_amount - IF(item_id=1,' . $documents . '.change, 0) - refund_amount'));
		$count = $item->count('item_id');

		$query = $item->selectRaw('CASE WHEN item_id = 1 THEN "Cash"
                                      WHEN item_id = 2 THEN "Card"
                                      WHEN item_id = 3 THEN "Transfer"
                                      WHEN item_id = 4 THEN "Boost"
                                      ELSE "undefined" END as item_name, sum(total_amount - IF(item_id=1,' . $documents . '.change, 0)) as total_amount, accounts.name as customer_name, count(' . $items . '.id) as count, sum(refund_amount) as refund_amount, sum(total_amount  - IF(item_id=1,' . $documents . '.change, 0)) - sum(refund_amount) as net')
			->groupBy('accounts.name')
			->groupBy('item_id');

		if (isset($args['sort']) && isset($args['desc'])) {

			if (isset($args['desc']) && $args['desc'] === 'desc') {
				$query->orderBy($args['sort'], 'desc');
			} else {
				if ($args['sort'] !== '') {
					$query->orderBy($args['sort']);
				} else {
					$query->orderBy('customer_name');
				}

			}

		}
		$results = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

		return ['summary' => ['count' => $count, 'sum' => $sum], 'data' => $results];

	}
}
