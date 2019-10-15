<?php
namespace App\GraphQL\Mutation\User;
use App\Models\User;
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
				'type' => Type::int(),
				'rules' => ['required'],
			],
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
				'rules' => ['required'],
			],
			'properties' => [
				'name' => 'properties',
				'type' => Type::string(),
			],
		];
	}

	public function validationErrorMessages(array $args = []): array
	{
		return [
			'name.required' => 'Please enter your full name',
			'name.string' => 'Your name must be a valid string',
		];
	}

	public function resolve($root, $args) {

		if (isset($args['properties'])) {
			$args['properties'] = json_decode($args['properties']);
		}

		$user = User::find($args['id']);

		if (!$user->save($args)) {
			return null;
		}
		return $user;
	}
}