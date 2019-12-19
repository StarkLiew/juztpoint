<?php
namespace App\GraphQL\Query;

use App\Helpers\TenantTable;
use App\Models\Document;
use App\Models\Item;
use Closure;
use DB;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportsQuery extends Query {
	protected $attributes = [
		'name' => 'SalesReport',
		'description' => 'A query of sales',
	];

	public function type(): Type {

		return GraphQL::type('report');
	}
	// arguments to filter query
	public function args(): array{
		return [
			'name' => [
				'type' => Type::string(),
			],
			'from' => [
				'type' => Type::string(),
			],
			'to' => [
				'type' => Type::string(),
			],
			'store' => [
				'type' => Type::int(),
			],
			'terminal' => [
				'type' => Type::int(),
			],
			'user' => [
				'type' => Type::int(),
			],
			'limit' => [
				'type' => Type::int(),
			],
			'page' => [
				'type' => Type::int(),
			],
			'sort' => [
				'type' => Type::string(),
			],
			'desc' => [
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields) {

		$fields = $getSelectFields();
		$fieldList = $fields->getSelect();
		// unset($fieldList[array_search('sum', $fieldList)]);
		// unset($fieldList[array_search('count', $fieldList)]);

		if (isset($args['name'])) {
			$func = $args['name'];
			return $this->$func($args, $getSelectFields);
		}

	}

	public function payment_summary($args) {

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

	public function commission_daily_summary($args) {

		$documents = TenantTable::parse('documents');
		$settings = TenantTable::parse('settings');
		$items = TenantTable::parse('items');

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

		$item = Item::join($documents, $documents . '.id', '=', $items . '.trxn_id')
			->join('users', 'users.id', '=', $items . '.user_id')
			->where($items . '.type', 'commission')
			->where($where);

		$results = Item::join($documents, $documents . '.id', '=', $items . '.trxn_id')
			->join('users', 'users.id', '=', $items . '.user_id')
			->where($items . '.type', 'commission')
			->where($where)
			->selectRaw('DATE(' . $documents . '.date) as item_date, users.name as item_name, sum(total_amount) as total_amount')
			->groupBy(DB::raw('DATE(' . $documents . '.date)'))
			->groupBy('users.name')
			->paginate($args['limit'], ['*'], 'page', $args['page']);

		return ['summary' => ['count' => $item->count('trxn_id'), 'sum' => $item->sum('total_amount')], 'data' => $results];

	}

	public function receipts($args, Closure $getSelectFields) {

		$documents = TenantTable::parse('documents');
		$settings = TenantTable::parse('settings');
		$items = TenantTable::parse('items');

		$where = function ($query) use ($args, $documents, $items, $settings) {
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
		$fields = $getSelectFields();

		$results = Document::with(array_keys($fields->getRelations()))
			->withTrashed()
			->where('type', 'receipt')
			->where($where)
			->orderBy('date', 'desc')
			->paginate($args['limit'], ['*'], 'page', $args['page']);
		return ['summary' => ['count' => 0, 'sum' => 0], 'data' => $results];

	}
	public function purchase($args, Closure $getSelectFields) {

		$documents = TenantTable::parse('documents');
		$settings = TenantTable::parse('settings');
		$items = TenantTable::parse('items');

		$where = function ($query) use ($args, $documents, $items, $settings) {
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
		$fields = $getSelectFields();

		$results = Document::with(array_keys($fields->getRelations()))
			->where('type', 'po')
			->where($where)
			->orderBy('id', 'desc')
			->paginate($args['limit'], ['*'], 'page', $args['page']);
		return ['summary' => ['count' => 0, 'sum' => 0], 'data' => $results];

	}

	public function sales_six($args, Closure $getSelectFields) {

		/* $results = Document::select(DB::raw('YEAR(`date`) AS year, MONTH(`date`) AS month, SUM(`charge`) AS total_amount'))->where('date', '>', DB::raw('DATE_SUB(now(), INTERVAL 12 MONTH)'))
			            ->groupBy(DB::raw('YEAR(`date`)'))
			            ->groupBy(DB::raw('MONTH(`date`)'))
		*/

		$documents = TenantTable::parse('documents');

		$sql = <<<EOT
           SELECT t1.mth, t1.md, coalesce(SUM(t1.total_amount+t2.total_amount), 0) AS total_amount from (SELECT DATE_FORMAT(a.Date,"%b") as mth, DATE_FORMAT(a.Date, "%Y-%m") as md, 0 as total_amount from (select curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c) a where a.Date <= NOW() and a.Date >= Date_add(Now(),interval - 11 month)group by md)t1 left join (SELECT DATE_FORMAT(date, "%b") AS mth, SUM(charge)as total_amount ,DATE_FORMAT(date, "%Y-%m") as md FROM user_1_documents where date <= NOW() and date >= Date_add(Now(),interval - 11 month) GROUP BY date, md)t2 on t2.md = t1.md group by t1.md, t1.mth order by t1.md
           EOT;

		$results = DB::select($sql);

		$totalCount = count($results);

		$paginator = new \Illuminate\Pagination\LengthAwarePaginator($results, $totalCount, 12, 1);

		/* if ($count < 12) {
			            $last = end($results->items());
			            dd($last);
			            $remain = 12 - $count;
			            for ($i = 1; $i <= $remain; $i++) {
			                $mth = $last - 1;
			                $yr = $last['year'];
			                if ($mth === 0) {
			                    $mth = 12;
			                    $yr -= 1;
			                }
			                // array_unshift($results->items(), ['year' => $yr, 'month' => $mth, 'total_amount' => 0]);
			            }

		*/

		return ['summary' => ['count' => 0, 'sum' => 0], 'data' => $paginator];

	}

}
