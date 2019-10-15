<?php
namespace App\GraphQL\Mutation\Product;
use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class TrashProductMutation extends Mutation {
	protected $attributes = [
		'name' => 'TrashAccount',
	];
	public function type(): Type {
		return GraphQL::type('product');
	}
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::string(),
				'rules' => ['required'],
			],
		];
	}
	public function validationErrorMessages(array $args = []): array
	{
		return [
			'id.required' => 'Id required',
		];
	}
	public function resolve($root, $args) {

		$data = Product::find($args['id']);

		if (!$data->delete()) {
			return null;
		}
		return $data;
	}
}
