<?php
namespace App\GraphQL\Query;
use App\Models\Account;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class AccountsQuery extends Query {
	protected $attributes = [
		'name' => 'accounts',
		'description' => 'A query of accounts',
	];

	public function type() {
		return Type::listOf(GraphQL::type('accounts'));
	}
	// arguments to filter query
	public function args() {
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
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
			'properties' => [
				'name' => 'properties',
				'type' => Type::string(),
			],
		];
	}
	public function resolve($root, $args, SelectFields $fields) {
		$where = function ($query) use ($args) {
			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['type'])) {
				$query->where('type', $args['type']);
			}
			if (isset($args['status'])) {
				$query->where('status', $args['status']);
			}
		};
		$results = Account::with(array_keys($fields->getRelations()))
			->where($where)
			->select($fields->getSelect())
			->get();
		return $results;
	}
}