<?php
namespace App\GraphQL\Mutation\Document;

use App\Models\Document;
use App\Models\Item;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class RemoveDocumentStatusMutation extends Mutation {
	protected $attributes = [
		'name' => 'RemoveDocumentStatus',
	];
	public function type(): Type {
		return GraphQL::type('receipt');
	}
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
				'rules' => ['required'],
			],
			'receive_id' => [
				'name' => 'receive_id',
				'type' => Type::int(),
			],
			'qty' => [
				'name' => 'qty',
				'type' => Type::float(),
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
		$received = null;
		DB::beginTransaction();
		try {
			$received = Item::find($args['receive_id']);
			$received->forceDelete();

			$line = Item::find($received['trxn_id']);
			$line->refund_qty = $args['qty'];
			$line->save();

			$result = Item::select(DB::raw('SUM(qty) - SUM(refund_qty) as balance'))->where('trxn_id', $line['trxn_id'])->where('type', 'pitem')->first();
			$document = Document::find($line['trxn_id']);

			if ($result['balance'] <= 0) {
				$document->status = 'completed';
				$document->save();
			} else {
				$document->status = 'shipping';
				$document->save();
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
}
