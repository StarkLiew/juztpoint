<?php
namespace App\GraphQL\Pagination;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as PaginationType;

class ReportsPagination extends PaginationType {
	protected $attributes = [
		'name' => 'reports',
		'description' => 'The collection of all report fields',
	];
	// define field of type
	public function fields(): array{
		return [
			'id' => [
				'type' => Type::int(),
				'description' => 'The id of the user',
			],
			'date' => [
				'type' => Type::string(),
				'description' => 'The id of the user',
			],
			'payment_date' => [
				'type' => Type::string(),
				'description' => 'The id of the user',
			],
			'year' => [
				'type' => Type::int(),
				'description' => 'The id of the user',
			],
			'md' => [
				'type' => Type::string(),
				'description' => 'The id of the user',
			],
			'mth' => [
				'type' => Type::string(),
				'description' => 'The id of the user',
			],
			'month' => [
				'type' => Type::int(),
				'description' => 'The id of the user',
			],
			'store' => [
				'type' => GraphQL::type('setting'),
				'description' => 'Description of the setting',
			],
			'discount' => [
				'type' => GraphQL::type('discount'),
				'description' => 'Description of the setting',
			],
			'item_date' => [
				'type' => Type::string(),
				'description' => 'The id of the user',
			],
			'reference' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'account_id' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'terminal_id' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'shift_id' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'store_id' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'customer' => [
				'type' => GraphQL::type('account'),
				'description' => 'Description of the setting',
				'is_relation' => true,
			],
			'terminal' => [
				'type' => GraphQL::type('setting'),
				'description' => 'Description of the setting',
				'is_relation' => true,
			],

			'account' => [
				'type' => GraphQL::type('account'),
				'description' => 'Description of the setting',
				'is_relation' => true,
			],
			'transact_by' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'user' => [
				'type' => GraphQL::type('user'),
				'description' => 'Description of the setting',
				'is_relation' => true,
			],
			'date' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'type' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'method' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'status' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'onhand' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'cost' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'price' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'discount' => [
				'type' => GraphQL::type('discount'),
				'description' => 'The type of setting',
			],
			'discount_rate' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],

			'discount_amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'service_charge' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'tax_amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'payments' => [
				'type' => Type::listOf(GraphQL::type('item')),
				'description' => 'A list of the item',
				'is_relation' => true,
			],
			'rounding' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'charge' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'received' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],

			'change' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'refund' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'note' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'items' => [
				'type' => Type::listOf(GraphQL::type('item')),
				'description' => 'A list of the item',
				'is_relation' => true,
			],
			'trxn_id' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'item_id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Description of the setting',
			],
			'item_name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'customer_name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'terminal_name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'store_name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'payment_name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'user_name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'cat_name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
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
			'teller' => [
				'type' => GraphQL::type('user'),
				'description' => 'Description of the setting',
			],
			'properties' => [
				'type' => GraphQL::type('property'),
				'description' => 'A list of the property',
				'is_relation' => false,
			],
			'count' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'sum' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'net' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],

		];
	}

}