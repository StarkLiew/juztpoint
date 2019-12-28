<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ChartTopEmployee {

	public function run($args, Closure $getSelectFields) {

		$documents = TenantTable::parse('documents');
		$settings = TenantTable::parse('settings');
		$items = TenantTable::parse('items');

		$results = Item::join($documents, $documents . '.id', '=', $items . '.trxn_id')
			->join('users', 'users.id', '=', $items . '.user_id')
			->where($items . '.type', 'commission')
			->where($documents . '.date', '<=', DB::raw('NOW()'))
			->where($documents . '.date', '>=', DB::raw('Date_add(Now(),interval - 11 month)'))
			->selectRaw('DATE_FORMAT(' . $documents . '.date, "%Y-%m") as md, users.name as item_name, sum(total_amount) as total_amount')
			->groupBy(DB::raw('DATE_FORMAT(' . $documents . '.date, "%Y-%m")'))
			->groupBy('users.name')
			->paginate(0, ['*'], 'page', 1);

		return ['summary' => ['count' => 0, 'sum' => 0], 'data' => $results];

	}
}
