<?php
namespace App\GraphQL\Mutation\Product;
use App\Models\Item;
use App\Models\Product;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateProductMutation extends Mutation {
	protected $attributes = [
		'name' => 'UpdateProduct',
	];
	public function type(): Type {
		return GraphQL::type('product');
	}
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
				'rules' => ['required'],
			],
			'thumbnail' => [
				'name' => 'thumbnail',
				'type' => GraphQL::type('Upload'),
			],
			'clear_image' => [
				'name' => 'clear_image',
				'type' => Type::boolean(),
			],
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
				'rules' => ['required'],
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
			'id.required' => 'Id required',
			'name.required' => 'Please enter your full name',
			'sku.required' => 'Please enter SKU',
		];
	}
	public function resolve($root, $args) {

		if (isset($args['thumbnail'])) {
			$args['thumbnail'] = $args['thumbnail']->get();
		}

		if (isset($args['clear_image'])) {
			$args['thumbnail'] = '';
		}
		$args['properties'] = json_decode($args['properties']);

		if (isset($args['variants'])) {
			$args['variants'] = json_decode($args['variants']);
		}

		if (isset($args['composites'])) {
			$args['composites'] = json_decode($args['composites']);
		}

		DB::beginTransaction();
		try {

			$data = Product::find($args['id']);
			if (!$data->update($args)) {
				return null;
			}

			if ($data->type === 'product' && $data->stockable === 1) {
				$this->open($data->id, $data->properties['opening'], $data->user_id);
			}
			if ($data->type === 'product-variant' && $data->stockable === 1) {
				$opening = 0;
				foreach ($data['properties']['stocks'] as $key => $stock) {
					$opening += $stock['qty'];
				}
				$this->open($data->id, $opening, $data->user_id);
			}

			DB::commit();
			return $data;
		} catch (\Illuminate\Database\QueryException $e) {
			DB::rollback();
			return null;
		}

	}
	public function open($id, $qty, $user_id) {
		$open = Item::where('item_id', $id)->where('type', 'open')->first();
		if (!$open) {
			$open = new Item();
		}
		$open->line = 1;
		$open->item_id = $id;
		$open->qty = $qty;
		$open->trxn_id = 1;
		$open->tax_id = 1;
		$open->type = 'open';
		$open->discount = '{}';
		$open->refund_qty = 0;
		$open->tax_amount = 0.00;
		$open->discount_amount = 0.00;
		$open->refund_amount = 0.00;
		$open->total_amount = 0.00;
		$open->terminal_id = 0;
		$open->shift_id = 0;
		$open->store_id = 0;
		$open->user_id = $user_id;
		$open->save();
	}
}
