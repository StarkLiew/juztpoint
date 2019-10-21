<?php
namespace App\GraphQL\Pagination;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as Pagination;

class ReportsPagination extends Pagination {
	protected $attributes = [
		'name' => 'reports',
		'description' => 'The collection of all report fields',
	];
	// define field of type
	public function fields(): array{
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
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
				'type' => Type::nonNull(Type::int()),
				'description' => 'Description of the setting',
			],
			'shift_id' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'account' => [
				'type' => GraphQL::type('account'),
				'description' => 'Description of the setting',
			],
			'transact_by' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'Description of the setting',
			],
			'user' => [
				'type' => GraphQL::type('user'),
				'description' => 'Description of the setting',
			],
			'date' => [
				'type' => Type::nonNull(Type::string()),
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
			'discount' => [
				'type' => Type::string(),
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

		];
	}

}