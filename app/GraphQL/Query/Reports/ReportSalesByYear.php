<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportSalesByYear {

	public function run($args, Closure $getSelectFields) {
		$documents = TenantTable::parse('documents');
		$settings = TenantTable::parse('settings');
		$accounts = TenantTable::parse('accounts');
		$items = TenantTable::parse('items');
		$products = TenantTable::parse('products');

		$where = function ($query) use ($args, $documents, $items, $settings) {
			if (isset($args['from']) && isset($args['to'])) {
				if ($args['from'] !== "" && $args['to'] !== "") {
					$from = $args['from'] . ' 00:00:00';
					$to = $args['to'] . ' 23:59:59';
					$query->whereBetween($documents . '.date', [$from, $to]);
				}
			}
			if (isset($args['store'])) {
				$query->where($items . '.store_id', $args['store']);
			}
			if (isset($args['terminal'])) {
				$query->where($items . '.terminal_id', $args['terminal']);
			}
			if (isset($args['user'])) {
				$query->where($items . '.user_id', $args['user']);
			}

		};

		$item = Item::join($documents . ' as documents', 'documents.id', '=', $items . '.trxn_id')
			->join('users', 'users.id', '=', $items . '.user_id')
			->join($products . ' as products', 'products.id', '=', $items . '.item_id')
			->where($items . '.type', 'item')
			->whereNull('documents.deleted_at')
			->where($where);
		$sum = $item->sum('total_amount');
		$count = $item->count('trxn_id');

		$query = $item->selectRaw('YEAR(date) as item_date, sum(IF(products.type LIKE  "%product%", total_amount, 0)) as product_amount, sum(IF(products.type LIKE  "%service%", total_amount, 0)) as service_amount, sum(total_amount) as total_amount')
			->groupBy(DB::raw('YEAR(documents.date)'));

		if (isset($args['sort']) && isset($args['desc'])) {

			if (isset($args['desc']) && $args['desc'] === 'desc') {
				$query->orderBy($args['sort'], 'desc');
			} else {
				if ($args['sort'] !== '') {
					$query->orderBy($args['sort']);
				} else {
					$query->orderBy('documents.date');
				}

			}

		}

		$results = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

		return ['summary' => ['count' => $count, 'sum' => $sum], 'data' => $results];

	}
}
