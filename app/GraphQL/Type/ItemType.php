<?php
namespace App\GraphQL\Type;

use App\Models\Item;
use App\Models\Product;
use App\Models\user;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ItemType extends GraphQLType {
	protected $attributes = [
		'name' => 'Item',
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
			'name' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'price' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'trxn_id' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'item_id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Description of the setting',
			],
			'item' => [
				'type' => GraphQL::type('product'),
				'description' => 'A list of the item',
				'is_relation' => true,
			],
			'product' => [
				'type' => GraphQL::type('product'),
				'description' => 'A list of the item',
				'is_relation' => true,
			],
			'saleBy' => [
				'type' => GraphQL::type('user'),
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
				'description' => 'A list of the item',
			],
			'discount_amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'tax' => [
				'type' => GraphQL::type('setting'),
				'description' => 'Description of the setting',
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
			'amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'note' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'composites' => [
				'type' => Type::listOf(GraphQL::type('property')),
				'description' => 'A list of the item',
				'is_relation' => false,
			],
			'shareWith' => [
				'type' => GraphQL::type('user'),
				'description' => 'A list of the item',
				'is_relation' => false,
			],
			'properties' => [
				'type' => GraphQL::type('property'),
				'description' => 'A list of the property',
				'is_relation' => false,
			],

		];
	}
	protected function resolveNameField($root, $args) {
		if ($root['type'] === 'payment') {
			$names = ['CASH', 'CARD', 'BANK', 'BOOST'];
			return $names[(int) $root['item_id'] - 1];
		}
		return $root['product']['name'];
	}
	protected function resolveQtyField($root, $args) {
		return abs($root['qty']);
	}
	protected function resolveAmountField($root, $args) {
		return $root['total_amount'];
	}
	protected function resolveShareWithField($root, $args) {
		if (!isset($root['properties']['shareWith'])) {
			return null;
		}

		$user = User::find($root['properties']['shareWith']);
		if (!$user) {
			return null;
		}
		return $user;
	}

	protected function resolveCompositesField($root, $args) {
		if (!isset($root['properties']['composites'])) {
			return [];
		}
		$composites = [];
		foreach ($root['properties']['composites'] as $composite) {
			$comp = Product::find($composite['item_id']);
			if ($comp) {
				$comp['performBy'] = User::find($composite['perform_by']);
				$composites[] = $comp;
			}
		}
		return $composites;
	}
}