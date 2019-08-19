<?php
namespace App\GraphQL\Query;
use App\Models\Document;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ProductsQuery extends Query {
	protected $attributes = [
		'name' => 'receipts',
		'description' => 'A query of receipts',
	];

	public function type() {
		return Type::listOf(GraphQL::type('receipts'));
	}
	// arguments to filter query
	public function args() {
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
			],
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
			],
			'type' => [
				'name' => 'type',
				'type' => Type::string(),
			],
			'status' => [
				'name' => 'status',
				'type' => Type::string(),
			],
			'sku' => [
				'type' => Type::string(),
				'name' => 'sku',
			],

		];
	}
	public function resolve($root, $args, SelectFields $fields) {
		$where = function ($query) use ($args) {
			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['type'])) {
				$query->where('type', $args['type']);
			}
			if (isset($args['status'])) {
				$query->where('status', $args['status']);
			}
			if (isset($args['sku'])) {
				$query->where('sku', $args['sku']);
			}
		};
		$results = Document::with(array_keys($fields->getRelations()))
			->where('type', 'receipt')
			->where($where)
			->select($fields->getSelect())
			->get();
		return $results;
	}
}