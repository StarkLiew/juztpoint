<?php
namespace App\GraphQL\Mutation\Account;
use App\Models\Account;
use Auth;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class NewAccountMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewAccount',
	];
	public function type(): Type {
		return GraphQL::type('account');
	}
	public function args(): array{
		return [
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
				'rules' => ['required'],
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

	public function validationErrorMessages(array $args = []): array
	{
		return [
			'name.required' => 'Please enter your full name',
		];
	}
	public function resolve($root, $args) {

		$args['properties'] = json_decode($args['properties']);
		$args['uid'] = uniqid();
		$args['user_id'] = Auth::id();
		$account = Account::create($args);

		if (!$account) {
			return null;
		}
		return $account;
	}
}
