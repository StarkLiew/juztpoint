<?php
namespace App\GraphQL\Mutation\Document;
use App\Models\Document;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateDocumentMutation extends Mutation {
	protected $attributes = [
		'name' => 'UpdateDocument',
	];
	public function type(): Type {
		return GraphQL::type('account');
	}
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::nonNull(Type::int()),
			],
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
	public function validationErrorMessages(array $args = []): array
	{
		return [
			'id.required' => 'Id required',
			'name.required' => 'Please enter your full name',
		];
	}
	public function resolve($root, $args) {

		$success = false;
		$error = null;
		DB::beginTransaction();
		try {

			$document = Document::find($args['id']);

			if ($document) {

				$document->items()->where('type', 'pitem')->forceDelete();

				$args['properties'] = json_decode($args['properties']);
				$document->update($args);
				foreach ($args['items'] as $item) {
					$item['discount'] = json_decode($item['discount']);
					$item['properties'] = json_decode($item['properties']);
					$document->items()->create($item);
				}
				DB::commit();

				$success = true;
			}

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
}
