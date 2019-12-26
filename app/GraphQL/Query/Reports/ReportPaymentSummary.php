<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportPaymentSummary {

	public function run($args, Closure $getSelectFields) {
		$documents = TenantTable::parse('documents');
		$items = TenantTable::parse('items');

		$where = function ($query) use ($args, $documents) {
			if (isset($args['from']) && isset($args['to'])) {
				if ($args['from'] !== "" && $args['to'] !== "") {
					$from = $args['from'] . ' 00:00:00';
					$to = $args['to'] . ' 23:59:59';
					$query->whereBetween($documents . '.date', [$from, $to]);
				}
			}
		};

		$results = Item::join($documents, $documents . '.id', '=', $items . '.trxn_id')
			->where($items . '.type', 'payment')
			->where($where)
			->selectRaw('CASE WHEN item_id = 1 THEN "Cash"
                                      WHEN item_id = 2 THEN "Card"
                                      WHEN item_id = 3 THEN "Transfer"
                                      WHEN item_id = 4 THEN "Boost"
                                      ELSE "undefined" END as item_name, sum(total_amount) as total_amount, count(' . $items . '.id) as count, sum(refund_amount) as refund_amount, sum(total_amount) - sum(refund_amount) as net')
			->groupBy('item_id')
			->paginate($args['limit'], ['*'], 'page', $args['page']);

		return $results;

	}
}
