<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ChartTopProduct {

	public function run($args, Closure $getSelectFields) {

		$items = TenantTable::parse('items');
		$products = TenantTable::parse('products');

		$fields = $getSelectFields();

		$results = Item::with(array_keys($fields->getRelations()))
			->select(DB::raw('item_id, ABS(SUM(qty)) as qty'))
			->join($products, $items . '.item_id', '=', $products . '.id')
			->where($items . '.type', 'item')
			->where($products . '.type', 'product')
			->groupBy('item_id')
			->paginate(0, ['*'], 'page', 1);

		return ['summary' => ['count' => 0, 'sum' => 0], 'data' => $results];

	}
}
