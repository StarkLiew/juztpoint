<?php
namespace App\GraphQL\Mutation\Product;
use App\Models\Product;
use Auth;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class NewProductMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewProduct',
	];
	public function type(): Type {
		return GraphQL::type('product');
	}
	public function args(): array{
		return [
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
				'rules' => ['required'],
			],
			'thumbnail' => [
				'name' => 'thumbnail',
				'type' => GraphQL::type('upload'),
			],
			'type' => [
				'name' => 'type',
				'type' => Type::string(),
			],
			'status' => [
				'name' => 'status',
				'type' => Type::string(),
			],
			'cat_id' => [
				'name' => 'cat_id',
				'type' => Type::int(),
			],
			'sku' => [
				'name' => 'sku',
				'type' => Type::string(),
				'rules' => ['required'],
			],
			'tax_id' => [
				'name' => 'tax_id',
				'type' => Type::int(),
			],
			'allow_assistant' => [
				'name' => 'allow_assistant',
				'type' => Type::int(),
			],
			'commission_id' => [
				'name' => 'commission_id',
				'type' => Type::int(),
			],
			'discount' => [
				'name' => 'discount',
				'type' => Type::float(),
			],
			'stockable' => [
				'name' => 'stockable',
				'type' => Type::int(),
			],
			'note' => [
				'name' => 'note',
				'type' => Type::string(),
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
			'name.required' => 'Please enter your full name',
			'sku.required' => 'Please enter SKU',
		];
	}
	public function resolve($root, $args) {

		$args['properties'] = json_decode($args['properties']);
		$args['user_id'] = Auth::id();

		$data = Product::create($args);

		if (!$data) {
			return null;
		}
		return $data;
	}
}
