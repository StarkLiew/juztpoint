<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportStockMovementSummary {

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

		$item1 = Item::join(DB::raw($products . ' as product'), 'product.id', '=', $items . '.item_id')
			->where(function ($query) use ($items) {
				$query->where($items . '.type', 'ritem')
					->orWhere($items . '.type', 'open')
					->orWhere($items . '.type', 'credit');
			})
			->where('product.type', '=', 'product')
			->where($where)->selectRaw('product.name as name, ' . $items . '.type as type, sum(qty) as qty, 0 as price')->groupBy('product.name')->groupBy($items . '.type');

		$item2 = Item::join(DB::raw($products . ' as product'), 'product.id', '=', $items . '.item_id')
			->where(function ($query) use ($items) {
				$query->where($items . '.type', 'item');
			})
			->where('product.type', '=', 'product')
			->where($where)->selectRaw('product.name as name, ' . $items . '.type as type, sum(qty) as qty, 0 as price')->groupBy('product.name')->groupBy($items . '.type');

		$item3 = Item::join(DB::raw($products . ' as product'), 'product.id', '=', $items . '.item_id')
			->where(function ($query) use ($items) {
				$query->where($items . '.type', 'ritem')
					->orWhere($items . '.type', 'open')
					->orWhere($items . '.type', 'credit');
			})
			->where('product.type', '=', 'product')
			->where($where)->selectRaw('product.name as name, "cost" as type, 0 as qty, AVG(DISTINCT price) as price')->groupBy('product.name');

		$union = $item1->union($item2)->union($item3);

		$sub = DB::table(DB::raw("({$union->toSql()}) as prod"))
			->selectRaw('prod.name as name, prod.type as type, SUM(prod.qty) as qty, SUM(price) as cost')->groupBy('prod.name')->groupBy('prod.type');

		$results = DB::table(DB::raw("({$sub->toSql()}) as mix"))
			->mergeBindings($union->getQuery())
			->selectRaw('IFNULL(mix.name, "Total") as item_name, mix.type, SUM(if( mix.type="open", qty, 0 )) as opening, SUM(if( mix.type="ritem", qty, 0 )) as received, ABS(SUM(if( mix.type="item", qty, 0 ))) as sold, SUM(if( mix.type="credit", qty, 0 )) as "return", SUM(qty) as balance, SUM(qty) * SUM(mix.cost) as total_amount')->groupBy(DB::raw('mix.name'))->paginate($args['limit'], ['*'], 'page', $args['page']);

		$count = 0;
		$subSum = DB::table(DB::raw("({$sub->toSql()}) as mix"))->mergeBindings($union->getQuery())->selectRaw(DB::raw('SUM(mix.qty) * SUM(mix.cost) as aggregate'))->groupBy('mix.name');

		$sum = DB::table(DB::raw("({$subSum->toSql()}) as sub"))
			->mergeBindings($union->getQuery())
			->sum('aggregate');

		return ['summary' => ['count' => $count, 'sum' => $sum], 'data' => $results];

	}
}
