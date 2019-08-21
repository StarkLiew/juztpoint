<?php
namespace App\GraphQL\Mutation;
use App\Models\User;
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
			'name' => [
				'name' => 'name',
				'type' => Type::nonNull(Type::string()),
			],
			'email' => [
				'name' => 'email',
				'type' => Type::nonNull(Type::string()),
			],
			'password' => [
				'name' => 'password',
				'type' => Type::nonNull(Type::string()),
			],
		];
	}
	public function resolve($root, $args) {
		$args['password'] = bcrypt($args['password']);
		$user = User::create($args);
		if (!$user) {
			return null;
		}
		return $user;
	}
}