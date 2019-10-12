<?php
namespace App\GraphQL\Mutation\User;
use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class TrashUserMutation extends Mutation {
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
		];
	}

	public function resolve($root, $args) {

		$user = User::where('id', $args['id'])->delete();

		if (!$user) {
			return null;
		}
		return $user;
	}
}