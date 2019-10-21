<?php
namespace App\GraphQL\Query;

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
			'query' => [
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
			'limit' => [
				'type' => Type::int(),
			],
			'page' => [
				'type' => Type::int(),
			],
			'sort' => [
				'type' => Type::string(),
			],
			'sort_desc' => [
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields) {
		$where = function ($query) use ($args) {

		};
		if (isset($args['query'])) {
			switch ($args['query']) {
			case 'payment_summary':
				$fields = $getSelectFields();
				$results = Item::with(array_keys($fields->getRelations()))
					->where('type', 'payment')
					->where($where)
					->groupBy('item_id')
					->groupBy('date')
					->select($fields->getSelect())->sum('total_amount')
					->paginate($args['limit'], ['*'], 'page', $args['page']);
				break;

			}
		}

		return $results;
	}
}