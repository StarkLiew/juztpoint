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
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
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
				$query->where('name', $args['name']);
			}
			if (isset($args['email'])) {
				$query->where('email', $args['email']);
			}

			if (isset($args['status'])) {
				$query->where('status', $args['status']);
			}
		};
		$auth = Auth::user();
		$tid = $auth->id;
		if ($auth->tenant) {
			$tid = $auth->tenant;
		}

		$fields = $getSelectFields();
		$q = User::with(array_keys($fields->getRelations()))
			->where(function ($query) use ($tid) {
				$query->where('tenant', '=', $tid);
				$query->orWhere('id', '=', $tid);
			})
			->where($where)

			->select($fields->getSelect());

		if (isset($args['sort']) && isset($args['desc'])) {

			if (isset($args['desc']) && $args['desc'] === 'desc') {
				$q->orderBy($args['sort'], 'desc');
			} else {
				$q->orderBy($args['sort']);
			}

		}

		if ($args['limit'] > 0) {
			$results = $q->paginate($args['limit'], ['*'], 'page', $args['page']);
		} else {
			$count = $q->count();
			$results = $q->paginate($count, ['*'], 'page', 1);
		}
		return $results;
	}
}