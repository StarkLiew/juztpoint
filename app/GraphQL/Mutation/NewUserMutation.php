<?php
namespace App\GraphQL\Mutation;
use App\Models\User;
use Auth;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class NewUserMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewUser',
	];
	public function type(): Type {
		return GraphQL::type('user');
	}
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
			'password' => [
				'name' => 'password',
				'type' => Type::string(),
			],
			'pin' => [
				'name' => 'pin',
				'type' => Type::string(),
			],
			'properties' => [
				'name' => 'properties',
				'type' => Type::string(),
			],
			'action' => [
				'name' => 'action',
				'type' => Type::string(),
			],
		];
	}
	public function resolve($root, $args) {

		if (isset($args['action']) && $args['action'] === 'delete') {
			$account = Account::where('id', $args['id'])->delete();
			return null;
		}
		if (isset($args['properties'])) {
			$args['properties'] = json_decode($args['properties']);
		}
		$auth = Auth::user();
		$tid = $auth->id;
		if ($auth->tenant) {
			$tid = $auth->tenant;
		}

		if (isset($args['id'])) {
			if (isset($args['pin'])) {
				$countPin = User::where('pin', $args['pin'])->where('id', '<>', $$args['id'])->where('tenant', '=', $tid)->orWhere('id', '=', $tid)->count();
				if ($countPin > 0) {
					return null;
				}
			}
			if (isset($args['password'])) {
				$args['password'] = bcrypt($args['password']);
			}
			$user = User::find($args['id']);
			$user->update($args);

		} else {
			$args['password'] = bcrypt($args['password']);
			$user = User::create($args);
		}

		if (!$user) {
			return null;
		}
		return $user;
	}
}