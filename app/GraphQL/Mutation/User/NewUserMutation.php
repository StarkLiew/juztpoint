<?php
namespace App\GraphQL\Mutation\User;
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
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
				'rules' => ['required'],
			],
			'avatar' => [
				'name' => 'avatar',
				'type' => GraphQL::type('Upload'),
			],
			'email' => [
				'name' => 'email',
				'type' => Type::string(),
				'rules' => ['required', 'email', 'unique:users,email'],
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
			'email.required' => 'Please enter your email address',
			'email.email' => 'Please enter a valid email address',
			'email.exists' => 'Sorry, this email address is already in use',
		];
	}

	public function resolve($root, $args) {

		if (isset($args['properties'])) {
			$args['properties'] = json_decode($args['properties']);
		}
		$auth = Auth::user();

		$tid = $auth->id;
		if ($auth->tenant) {
			$tid = $auth->tenant;
		}
		$args['tenant'] = $tid;
		$args['password'] = bcrypt(uniqid());

		$user = User::create($args);

		if (!$user) {
			return null;
		}
		return $user;
	}
}