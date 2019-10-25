<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class VariantStockType extends GraphQLType {
	protected $attributes = [
		'name' => 'VariantStock',
		'description' => 'JSON of object property',

	];
	// define field of type
	public function fields(): array{
		return [
			'name' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'cost' => [
				'type' => Type::float(),
				'description' => 'Description of the setting',
			],
			'price' => [
				'type' => Type::float(),
				'description' => 'Description of the setting',
			],
			'qty' => [
				'type' => Type::float(),
				'description' => 'Description of the setting',
			],
		];
	}

}