<?php
namespace App\GraphQL\Mutation;
use App\Models\Document;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class ShiftMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewReceipt',
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
			'shift_id' => [
				'name' => 'shift_id',
				'type' => Type::int(),
			],
			'transact_by' => [
				'name' => 'transact_by',
				'type' => Type::nonNull(Type::int()),
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
			'properties' => [
				'name' => 'properties',
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args) {
		$args['discount_amount'] = 0.00;
		$args['service_charge'] = 0.00;
		$args['tax_amount'] = 0.00;
		$args['rounding'] = 0.00;
		$args['charge'] = 0.00;
		$args['received'] = 0.00;
		$args['change'] = 0.00;
		$args['refund'] = 0.00;

		if ($args['status'] === 'close') {
			$document = Document::where('type', 'shift')->where('shift_id', $args['shift_id'])->first();
			$document->update($args);
		} else {
			$args['type'] = 'shift';
			$document = Document::create($args);
		}

		return $document;
	}

}
