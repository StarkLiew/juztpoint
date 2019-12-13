<?php
namespace App\GraphQL\Mutation\Document;

use App\Models\Document;
use App\Models\Item;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateDocumentStatusMutation extends Mutation {
	protected $attributes = [
		'name' => 'UpdateDocumentStatus',
	];
	public function type(): Type {
		return GraphQL::type('account');
	}
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
				'rules' => ['required'],
			],
			'qty' => [
				'name' => 'qty',
				'type' => Type::float(),
			],
			'receive' => [
				'name' => 'receive',
				'type' => GraphQL::type('ItemInput'),
			],
		];
	}
	public function validationErrorMessages(array $args = []): array
	{
		return [
			'id.required' => 'Id required',
			'name.required' => 'Line Id is required',
		];
	}
	public function resolve($root, $args) {
		$success = false;
		$error = null;

		DB::beginTransaction();
		try {
			$line = Item::find($args['id']);
			$args['receive']['properties'] = json_decode($args['receive']['properties']);
			$args['receive']['trxn_id'] = $line['id'];

			$received = Item::create($args['receive']);

			$line->refund_qty = $args['qty'];
			$line->save();
			$result = Item::select(DB::raw('SUM(qty) - SUM(refund_qty) as balance'))->where('trxn_id', $line['trxn_id'])->first();
			if ($result['balance'] <= 0) {
				$document = Document::find($line['trxn_id']);
				$document->status = 'completed';
				$document->save();
			}

			DB::commit();

		} catch (\Exception $e) {
			$success = false;
			$error = $e;

			DB::rollback();
		}

		if (!$success) {
			return $error;
		}
		return $received;
	}
}
