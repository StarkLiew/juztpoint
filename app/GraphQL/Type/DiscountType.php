<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class DiscountType extends GraphQLType {
	protected $attributes = [
		'name' => 'Discount',
		'description' => 'JSON of object property',

	];
	// define field of type
	public function fields(): array{
		return [
			'type' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'rate' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'amount' => [
				'type' => Type::float(),
				'description' => 'Description of the setting',
			],

		];
	}

}