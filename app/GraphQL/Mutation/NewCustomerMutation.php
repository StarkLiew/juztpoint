<?php
namespace App\GraphQL\Mutation;
use App\Models\Account;
use Auth;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class NewCustomerMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewCustomer',
	];
	public function type(): Type {
		return GraphQL::type('account');
	}
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::string(),
			],
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
			],
			'uid' => [
				'name' => 'uid',
				'type' => Type::string(),
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
			'action' => [
				'name' => 'action',
				'type' => Type::string(),
			],
		];
	}
	public function resolve($root, $args) {

		if (isset($args['action']) && $args['action'] === 'delete') {
			$account = Account::where('uid', $args['uid'])->delete();

			return null;
		}

		$args['properties'] = json_decode($args['properties']);
		$args['user_id'] = Auth::id();
		$account = Account::updateOrCreate(['uid' => $args['uid']], $args);

		if (!$account) {
			return null;
		}
		return $account;
	}
}
