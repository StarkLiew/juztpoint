<?php
namespace App\GraphQL\Mutation\Document;
use App\Models\Document;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class NewDocumentMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewDocument',
	];
	public function type(): Type {
		return GraphQL::type('receipt');
	}
	public function args(): array{
		return [
			'reference' => [
				'name' => 'reference',
				'type' => Type::nonNull(Type::string()),
			],
			'terminal_id' => [
				'name' => 'terminal_id',
				'type' => Type::nonNull(Type::int()),
			],
			'store_id' => [
				'name' => 'store_id',
				'type' => Type::nonNull(Type::int()),
			],
			'shift_id' => [
				'name' => 'shift_id',
				'type' => Type::int(),
			],
			'account_id' => [
				'name' => 'account_id',
				'type' => Type::string(),
			],
			'transact_by' => [
				'name' => 'transact_by',
				'type' => Type::nonNull(Type::int()),
			],
			'type' => [
				'name' => 'type',
				'type' => Type::nonNull(Type::string()),
			],
			'status' => [
				'name' => 'status',
				'type' => Type::nonNull(Type::string()),
			],
			'date' => [
				'name' => 'date',
				'type' => Type::nonNull(Type::string()),
			],
			'discount' => [
				'name' => 'discount',
				'type' => Type::nonNull(Type::string()),
			],
			'discount_amount' => [
				'name' => 'discount_amount',
				'type' => Type::float(),
			],
			'service_charge' => [
				'name' => 'service_charge',
				'type' => Type::float(),
			],
			'tax_amount' => [
				'name' => 'tax_amount',
				'type' => Type::float(),
			],
			'charge' => [
				'name' => 'charge',
				'type' => Type::float(),
			],
			'received' => [
				'name' => 'received',
				'type' => Type::float(),
			],
			'rounding' => [
				'name' => 'rounding',
				'type' => Type::float(),
			],
			'change' => [
				'name' => 'change',
				'type' => Type::float(),
			],
			'refund' => [
				'name' => 'refund',
				'type' => Type::float(),
			],
			'note' => [
				'name' => 'note',
				'type' => Type::string(),
			],
			'items' => [
				'name' => 'items',
				'type' => Type::listOf(GraphQL::type('ItemInput')),
			],
			'payments' => [
				'name' => 'payments',
				'type' => Type::listOf(GraphQL::type('ItemInput')),
			],
			'commissions' => [
				'name' => 'commissions',
				'type' => Type::listOf(GraphQL::type('ItemInput')),
			],
			'properties' => [
				'name' => 'properties',
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args) {

		$success = false;
		$error = null;

		DB::beginTransaction();
		try {

			$args['properties'] = json_decode($args['properties']);

			$document = Document::create($args);
			foreach ($args['items'] as $item) {
				$item['discount'] = json_decode($item['discount']);
				$item['properties'] = json_decode($item['properties']);
				$item['price'] = $item['properties']->price;
				$document->items()->create($item);
			}

			DB::commit();
			$success = true;
		} catch (\Exception $e) {

			$success = false;
			$error = $e;
			DB::rollback();
		}

		if (!$success) {
			return $error;
		}
		return $document;
	}

	protected function row($data, $qty = 1) {

		$row = [
			'line' => $data['line'],
			'type' => $data['type'],
			'item_id' => $data['item_id'],
			'terminal_id' => $data['terminal_id'],
			'store_id' => $data['store_id'],
			'shift_id' => $data['shift_id'],
			'discount' => '{}',
			'discount_amount' => 0.00,
			'tax_id' => 1,
			'qty' => $qty,
			'refund_qty' => 0.00,
			'refund_amount' => 0.00,
			'tax_amount' => 0.00,
			'user_id' => $data['user_id'],
			'total_amount' => $data['amount'],
			'note' => '',
		];
		if (array_key_exists('variant', $data)) {
			$row['properties'] = $data['variant'];
		}
		return $row;
	}

	protected function calcCommission($item, $commission) {

		if ((int) $commission['properties']['type'] === 1) {
			return (float) $commission['properties']['rate'];
		} else {
			return $item['total_amount'] * ((float) $commission['properties']['rate']) / 100;
		}
	}
}
