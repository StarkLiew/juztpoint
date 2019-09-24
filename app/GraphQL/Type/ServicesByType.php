<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ServicesByType extends GraphQLType {
	protected $attributes = [
		'name' => 'ServiceByType',
		'description' => 'JSON of object property',

	];
	// define field of type
	public function fields(): array{
		return [
			'item_id' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'user_id' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],

		];
	}

}