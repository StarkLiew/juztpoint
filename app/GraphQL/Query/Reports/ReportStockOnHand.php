<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportStockOnHand {

	public function run($args, Closure $getSelectFields) {
		$documents = TenantTable::parse('documents');
		$items = TenantTable::parse('items');
		$products = TenantTable::parse('products');

		$where = function ($query) use ($args, $documents) {
			if (isset($args['from']) && isset($args['to'])) {
				if ($args['from'] !== "" && $args['to'] !== "") {
					$from = $args['from'] . ' 00:00:00';
					$to = $args['to'] . ' 23:59:59';
					$query->where($documents . '.date', '<=', $to);
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

		$item = Item::join(DB::raw($products . ' as product'), 'product.id', '=', $items . '.item_id')
			->where(function ($query) use ($items) {
				$query->where($items . '.type', 'item')
					->orWhere($items . '.type', 'ritem')
					->orWhere($items . '.type', 'open')
					->orWhere($items . '.type', 'credit');
			})
			->where('product.type', '=', 'product')
			->where($where);

		$sum = $item->selectRaw('SUM(qty * price) as amount')->first();
		$count = $item->count('item_id');

		$results = $item->selectRaw('product.name as item_name, sum(qty) as onhand, avg(price) as cost, sum(qty) * avg(price) as total_amount')->groupBy('product.name')->paginate($args['limit'], ['*'], 'page', $args['page']);

		return ['summary' => ['count' => $count, 'sum' => $sum->amount], 'data' => $results];

	}
}
