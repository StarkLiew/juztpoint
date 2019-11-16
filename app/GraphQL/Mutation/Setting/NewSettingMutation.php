<?php
namespace App\GraphQL\Mutation\Setting;
use App\Models\Setting;
use Auth;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class NewSettingMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewSetting',
	];
	public function type(): Type {
		return GraphQL::type('setting');
	}
	public function args(): array{
		return [
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
				'rules' => ['required'],
			],
			'description' => [
				'name' => 'description',
				'type' => Type::string(),
			],
			'type' => [
				'name' => 'type',
				'type' => Type::string(),
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
			'name.required' => 'Please enter the name',
		];
	}
	public function resolve($root, $args) {

		$args['properties'] = json_decode($args['properties']);
		$args['uid'] = uniqid();
		$args['user_id'] = Auth::id();
		if ($args['type'] === 'terminal') {
			$args['properties']->device_id = uniqid();
		}

		$setting = Setting::create($args);

		if (!$setting) {
			return null;
		}
		return $setting;
	}
}
