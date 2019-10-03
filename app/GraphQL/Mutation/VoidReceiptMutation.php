<?php
namespace App\GraphQL\Mutation;
use App\Models\Document;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class VoidReceiptMutation extends Mutation {
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
		];
	}
	public function resolve($root, $args) {

		$success = false;
		$error = null;

		DB::beginTransaction();
		try {

			$document = Document::where('reference', $args['reference'])->first();
			$document->update(['status' => 'void']);

			$document->items()->delete();
			$document->payments()->delete();
			$document->commissions()->where('type', 'commission')->forceDelete();
			$document->delete();

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
