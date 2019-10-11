<?php
namespace App\GraphQL\Query;

use App\Models\Account;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class AccountsQuery extends Query {
	protected $attributes = [
		'name' => 'accounts',
		'description' => 'A query of accounts',
	];

	public function type(): Type {

		return GraphQL::paginate('accounts');

	}
	// arguments to filter query
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
			],
			'uid' => [
				'name' => 'uid',
				'type' => Type::string(),
			],
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
			],
			'description' => [
				'name' => 'description',
				'type' => Type::string(),
			],
			'type' => [
				'name' => 'type',
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
			if (isset($args['type'])) {
				$query->where('type', $args['type']);
			}
			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['search'])) {
				$query->where(function ($query) use ($args) {
					$query->orWhere('name', 'LIKE', '%' . $args['search'] . '%');
					$query->orWhere('properties->mobile', 'LIKE', '%' . $args['search'] . '%');
					$query->orWhere('properties->email', 'LIKE', '%' . $args['search'] . '%');
				});

			}

			if (isset($args['name'])) {
				$query->where('name', 'like', '%' . $args['name'] . '%');
			}

			if (isset($args['status'])) {
				$query->where('status', $args['status']);
			}
		};

		$fields = $getSelectFields();
		$query = Account::with(array_keys($fields->getRelations()))
			->where($where)
			->select($fields->getSelect());

		if (isset($args['sort']) && isset($args['desc'])) {

			if (isset($args['desc']) && $args['desc'] === 'desc') {
				$query->orderBy($args['sort'], 'desc');
			} else {
				$query->orderBy($args['sort']);
			}

		}

		$results = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

		return $results;
	}
}