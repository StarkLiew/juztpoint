<?php
namespace App\GraphQL\Pagination;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as Pagination;

class UsersPagination extends Pagination {
	protected $attributes = [
		'name' => 'users',
		'description' => 'A type',
		'model' => User::class, // define model for users type
	];
	// define field of type
	public function fields(): array{
		return [
			'id' => [
				'type' => Type::int(),
				'description' => 'The id of the user',
			],
			'avatar' => [
				'type' => Type::string(),
				'description' => 'The avatar of user',
			],
			'email' => [
				'type' => Type::string(),
				'description' => 'The email of user',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The name of the user',
			],
			'pin' => [
				'type' => Type::string(),
				'description' => 'The name of the user',
			],
			'tenant' => [
				'type' => Type::int(),
				'description' => 'The name of the user',
			],
			'verified' => [
				'type' => Type::boolean(),
				'description' => 'The name of the user',
			],
			'properties' => [
				'type' => GraphQL::type('property'),
				'description' => 'A list of the property',
				'is_relation' => false,
			],
		];
	}
	protected function resolveEmailField($root, $args) {
		return strtolower($root->email);
	}

}