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
		return GraphQL::type('receipt');
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
			$args['receive']['line'] = $line['id'];
			$args['receive']['trxn_id'] = $line['trxn_id'];
			$args['receive']['price'] = $args['receive']['price'];
			$received = Item::create($args['receive']);

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
