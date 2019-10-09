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
		'name' => 'receipts',
		'description' => 'A query of receipts',
	];

	public function type(): Type {
		return GraphQL::paginate('receipts');
	}
	// arguments to filter query
	public function args(): array{
		return [
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

		];
	}
	public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields) {
		$where = function ($query) use ($args) {
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
		$results = Document::with(array_keys($fields->getRelations()))
			->where('type', 'receipt')
			->where($where)
			->select($fields->getSelect())
			->paginate($args['limit'], ['*'], 'page', $args['page']);
		return $results;
	}
}