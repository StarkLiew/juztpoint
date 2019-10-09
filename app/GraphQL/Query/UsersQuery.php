<?php
namespace App\GraphQL\Query;

use App\Models\User;
use Auth;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class UsersQuery extends Query {
	protected $attributes = [
		'name' => 'users',
		'description' => 'A query of users',
	];

	public function type(): Type {
		return GraphQL::paginate('users');
	}
	// arguments to filter query
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
			],
			'email' => [
				'name' => 'email',
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
			if (isset($args['email'])) {
				$query->where('email', $args['email']);
			}
		};

		$id = Auth::id();

		$fields = $getSelectFields();

		$results = User::with(array_keys($fields->getRelations()))
			->where('tenant', '=', $id)
			->orWhere('id', '=', $id)
			->where($where)
			->select($fields->getSelect())
			->paginate($args['limit'], ['*'], 'page', $args['page']);

		return $results;
	}
}