<?php
namespace App\GraphQL\Mutation;
use App\Models\Item;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class NewAppointmentMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewAppointment',
	];
	public function type(): Type {
		return GraphQL::type('item');
	}
	public function args(): array{
		return [
			'line' => [
				'type' => Type::nonNull(Type::int()),
				'name' => 'line',
			],
			'type' => [
				'type' => Type::string(),
				'name' => 'type',
			],

			'item_id' => [
				'type' => Type::nonNull(Type::int()),
				'name' => 'item_id',
			],

			'discount' => [
				'type' => Type::string(),
				'name' => 'discount',
			],
			'discount_amount' => [
				'type' => Type::float(),
				'name' => 'discount_amount',
			],
			'tax_id' => [
				'type' => Type::nonNull(Type::int()),
				'name' => 'tax_id',
			],
			'tax_amount' => [
				'type' => Type::float(),
				'name' => 'tax_amount',
			],
			'refund_amount' => [
				'type' => Type::float(),
				'name' => 'refund_amount',
			],
			'total_amount' => [
				'type' => Type::float(),
				'name' => 'total_amount',
			],
			'note' => [
				'type' => Type::string(),
				'name' => 'note',
			],
			'properties' => [
				'name' => 'properties',
				'type' => Type::string(),
			],
			'user_id' => [
				'type' => Type::nonNull(Type::int()),
				'name' => 'user_id',
			],

		];
	}
	public function resolve($root, $args) {

		$success = false;
		$error = null;

		DB::beginTransaction();
		try {

			$document = Item::create($args);
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
}
