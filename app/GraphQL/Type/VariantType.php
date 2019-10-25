<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class VariantType extends GraphQLType {
	protected $attributes = [
		'name' => 'Variant',
		'description' => 'JSON of object property',

	];
	// define field of type
	public function fields(): array{
		return [
			'name' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'value' => [
				'type' => Type::listOf(Type::string()),
				'description' => 'Description of the setting',
			],

		];
	}

}