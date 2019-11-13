<?php
namespace App\GraphQL\Mutation\Setting;
use App\Models\Setting;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class TrashSettingMutation extends Mutation {
	protected $attributes = [
		'name' => 'TrashSetting',
	];
	public function type(): Type {
		return GraphQL::type('setting');
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

		$setting = Setting::find($args['id']);

		if (!$setting->delete()) {
			return null;
		}
		return $setting;
	}
}
