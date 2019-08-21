<?php
namespace App\GraphQL\Mutation;
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
				'type' => Type::nonNull(Type::string()),
			],
			'type' => [
				'name' => 'type',
				'type' => Type::nonNull(Type::string()),
			],
			'status' => [
				'name' => 'status',
				'type' => Type::nonNull(Type::string()),
			],
			'properties' => [
				'name' => 'properties',
				'type' => Type::nonNull(Type::string()),
			],
		];
	}
	public function resolve($root, $args) {

		$args['user_id'] = Auth::id();

		$account = Account::create($args);

		if (!$account) {
			return null;
		}
		return $account;
	}
}
