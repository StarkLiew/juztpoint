<?php
namespace App\GraphQL\Mutation\Document;

use App\Models\Document;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateAppointmentStatusMutation extends Mutation {
	protected $attributes = [
		'name' => 'UpdateAppointmentStatus',
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
			'status' => [
				'name' => 'status',
				'type' => Type::string(),
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
		$success = false;
		$error = null;

		DB::beginTransaction();
		try {

			$document = Document::find($args['id']);
			if ($document) {

				$document->status = $args['status'];
				$document->save();
				DB::commit();
				$success = true;
			}

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
