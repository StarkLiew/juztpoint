<?php
namespace App\GraphQL\Mutation\Product;
use App\Models\Item;
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
				'type' => GraphQL::type('Upload'),
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
			'variants' => [
				'name' => 'variants',
				'type' => Type::string(),

			],
			'composites' => [
				'name' => 'composites',
				'type' => Type::string(),
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

		if (isset($args['thumbnail'])) {
			$args['thumbnail'] = $args['thumbnail']->get();
		}

		DB::beginTransaction();
		try {
			$args['properties'] = json_decode($args['properties']);
			$args['user_id'] = Auth::id();

			$data = Product::create($args);
			if ($data->type === 'product') {
				$open = Item::where('item_id', $data->id)->where('type', 'open')->first();
				if (!$open) {
					$open = new Item();
				}
				$open->line = 1;
				$open->item_id = $data->id;
				$open->qty = $data->properties['qty'];
				$open->trxn_id = 1;
				$open->tax_id = 1;
				$open->type = 'open';
				$open->discount = '{}';
				$open->refund_qty = 0;
				$open->tax_amount = 0.00;
				$open->discount_amount = 0.00;
				$open->refund_amount = 0.00;
				$open->total_amount = 0.00;
				$open->user_id = $data->user_id;
				$open->save();
			}

			DB::commit();
			return $data;

		} catch (\Illuminate\Database\QueryException $e) {
			DB::rollback();
			return null;
		}

	}
}
