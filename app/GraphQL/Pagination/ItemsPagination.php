<?php
namespace App\GraphQL\Pagination;
use App\Models\Item;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as PaginationType;

class ItemsPagination extends PaginationType {
	protected $attributes = [
		'name' => 'items',
		'description' => 'The collection of all Item',
		'model' => Item::class, // define model for users type
	];
	// define field of type
	public function fields(): array{
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The id of the user',
			],
			'line' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The id of the user',
			],
			'type' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'trxn_id' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'item_id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Description of the setting',
			],
			'product' => [
				'type' => GraphQL::type('product'),
				'description' => 'A list of the item',
				'is_relation' => true,
			],
			'commission' => [
				'type' => GraphQL::type('setting'),
				'description' => 'A list of the item',
				'is_relation' => true,
			],
			'payment' => [
				'type' => GraphQL::type('setting'),
				'description' => 'A list of the item',
				'is_relation' => true,
			],
			'discount' => [
				'type' => GraphQL::type('discount'),
				'description' => 'The type of setting',
			],
			'discount_amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'tax_id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Description of the setting',
			],
			'tax_amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'qty' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'refund_qty' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'refund_amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'total_amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'note' => [
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
	protected function resolveNameField($root, $args) {
		return $root['product']['name'];
	}
	protected function resolveQtyField($root, $args) {
		return abs($root['qty']);
	}

}