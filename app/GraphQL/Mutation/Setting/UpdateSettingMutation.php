<?php
namespace App\GraphQL\Mutation\Setting;
use App\Models\Setting;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateSettingMutation extends Mutation {
	protected $attributes = [
		'name' => 'UpdateSetting',
	];
	public function type(): Type {
		return GraphQL::type('setting');
	}
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
				'rules' => ['required'],
			],
			'description' => [
				'name' => 'description',
				'type' => Type::string(),
			],
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
				'rules' => ['required'],
			],
			'status' => [
				'name' => 'status',
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
			'name.required' => 'Please enter the name',
		];
	}
	public function resolve($root, $args) {

		$args['properties'] = json_decode($args['properties']);

		$setting = Setting::find($args['id']);
		if (!$setting->update($args)) {
			return null;
		}
		return $setting;
	}
}
