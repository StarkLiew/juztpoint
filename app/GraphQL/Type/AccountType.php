<?php
namespace App\GraphQL\Type;

use App\Models\Account;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AccountType extends GraphQLType {
	protected $attributes = [
		'name' => 'Account',
		'description' => 'The collection of all Accounts',
		'model' => Account::class, // define model for users type
	];
	// define field of type
	public function fields(): array{
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The id of the user',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'description' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'type' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'status' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'properties' => [
				'type' => GraphQL::type('property'),
				'description' => 'A list of the property',
				'is_relation' => false,
			],

		];
	}

}