<?php
namespace App\GraphQL\Type;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductType extends GraphQLType {

	protected $attributes = [
		'name' => 'Product',
		'description' => 'The collection of all Products',
		'model' => Product::class, // define model for users type
	];

	// define field of type
	public function fields(): array{
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The id of the user',
			],
			'thumbnail' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'type' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'status' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'cat_id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The type of setting',
			],
			'category' => [
				'type' => GraphQL::type('setting'),
				'description' => 'The type of setting',
				'is_relation' => true,
			],
			'sku' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'tax_id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The type of setting',

			],
			'tax' => [
				'type' => GraphQL::type('setting'),
				'description' => 'The type of setting',
				'is_relation' => true,
			],
			'items' => [
				'type' => GraphQL::type('item'),
				'description' => 'The type of setting',
				'is_relation' => true,
			],
			'qty' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
				'selectable' => false,
			],
			'sellable' => [
				'type' => Type::boolean(),
				'description' => 'The type of setting',
			],
			'consumable' => [
				'type' => Type::boolean(),
				'description' => 'The type of setting',
			],
			'allow_assistant' => [
				'type' => Type::int(),
				'description' => 'The type of setting',
			],
			'discount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'stockable' => [
				'type' => Type::int(),
				'description' => 'The type of setting',
			],
			'commission_id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The type of setting',
			],
			'commission' => [
				'type' => GraphQL::type('setting'),
				'description' => 'The type of setting',
				'is_relation' => true,
			],
			'properties' => [
				'type' => GraphQL::type('property'),
				'description' => 'A list of the property',
				'is_relation' => false,
			],
			'note' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],

		];
	}

}