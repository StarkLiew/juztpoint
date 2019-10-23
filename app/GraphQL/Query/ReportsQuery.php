<?php
namespace App\GraphQL\Query;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
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

		return GraphQL::paginate('reports');
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
			'location' => [
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
			return $this->$func($args);
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
}