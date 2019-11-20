<?php
namespace App\GraphQL\Mutation\Product;
use App\Models\Document;
use App\Models\Setting;
use GraphQL\Type\Definition\Type;
use Mail;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class SendReceiptMutation extends Mutation {
	protected $attributes = [
		'name' => 'SendReceipt',
	];
	public function type(): Type {
		return GraphQL::type('document');
	}
	public function args(): array{
		return [
			'to' => [
				'name' => 'to',
				'type' => Type::string(),
				'rules' => ['required'],
			],
			'to_name' => [
				'name' => 'to',
				'type' => Type::string(),
				'rules' => ['required'],
			],
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
				'rules' => ['required'],
			],
		];
	}

	public function validationErrorMessages(array $args = []): array
	{
		return [
			'email.required' => 'Please enter your email',
			'attachment.required' => 'Please attach receipt',
		];
	}
	public function resolve($root, $args) {

		$where = function ($query) use ($args, $documents, $items, $settings) {
			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}

		};
		$fields = $getSelectFields();

		$value = Document::with(array_keys($fields->getRelations()))
			->withTrashed()
			->where('type', 'receipt')
			->where($where)
			->orderBy('date', 'desc')->first();

		$company = Setting::where('type', 'company')->first();

		$data = array('name' => $args['to_name'], 'value' => $value, 'header' => ['company' => $company, 'store' => $value['store']], '');

		Mail::send('mail.receipt', $data, function ($message) {
			$message->to($args['to'], $args['to_name'])->subject
				('Receipt');
			$message->from($args['from'], $args['from_name']);
		});

	}

}
