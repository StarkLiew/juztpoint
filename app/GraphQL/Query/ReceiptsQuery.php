<?php
namespace App\GraphQL\Query;

use App\Models\Document;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReceiptsQuery extends Query {
	protected $attributes = [
		'name' => 'reports',
		'description' => 'A query of reports',
	];

	public function type(): Type {
		return GraphQL::paginate('receipts');
	}
	// arguments to filter query
	public function args(): array{
		return [
			'from' => [
				'type' => Type::string(),
			],
			'to' => [
				'type' => Type::string(),
			],

			'id' => [
				'name' => 'id',
				'type' => Type::int(),
			],
			'account_id' => [
				'name' => 'id',
				'type' => Type::int(),
			],
			'reference' => [
				'name' => 'reference',
				'type' => Type::string(),
			],
			'status' => [
				'name' => 'status',
				'type' => Type::string(),
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
			'search' => [
				'name' => 'search',
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields) {
		$where = function ($query) use ($args) {

			if (isset($args['from']) && isset($args['to'])) {
				if ($args['from'] !== "" && $args['to'] !== "") {
					$from = $args['from'] . ' 00:00:00';
					$to = $args['to'] . ' 23:59:59';
					$query->whereBetween($documents . '.date', [$from, $to]);
				}
			}

			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['account_id'])) {
				$query->where('account_id', $args['account_id']);
			}
			if (isset($args['status'])) {
				$query->where('status', $args['status']);
			}
			if (isset($args['reference'])) {
				$query->where('reference', $args['reference']);
			}
		};

		$fields = $getSelectFields();
		$selectFields = $fields->getSelect();

		$results = Document::with(array_keys($fields->getRelations()))
			->where('type', 'receipt')
			->where($where)
			->select($selectFields)
			->paginate($args['limit'], ['*'], 'page', $args['page']);

		return $results;
	}
}