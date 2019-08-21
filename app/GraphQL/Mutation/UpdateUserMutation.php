<?php
namespace App\GraphQL\Mutation;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateUserMutation extends Mutation {
	protected $attributes = [
		'name' => 'UpdateUser',
	];
	public function type(): Type {
		return GraphQL::type('user');
	}
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::nonNull(Type::int()),
			],
			'name' => [
				'name' => 'name',
				'type' => Type::nonNull(Type::string()),
			],
		];
	}
	public function resolve($root, $args) {
		$user = User::find($args['id']);
		if (!$user) {
			return null;
		}
		$user->name = $args['name'];
		$user->save();
		return $user;
	}
}