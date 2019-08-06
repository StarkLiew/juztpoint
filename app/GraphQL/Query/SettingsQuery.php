<?php
namespace App\GraphQL\Query;
use App\Models\Setting;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class SettingsQuery extends Query {
	protected $attributes = [
		'name' => 'settings',
		'description' => 'A query of settings',
	];

	public function type() {
		return Type::listOf(GraphQL::type('settings'));
	}
	// arguments to filter query
	public function args() {
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
			],
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
			],
			'description' => [
				'name' => 'description',
				'type' => Type::string(),
			],
			'type' => [
				'name' => 'type',
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args, SelectFields $fields) {
		$where = function ($query) use ($args) {
			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['type'])) {
				$query->where('type', $args['type']);
			}
		};
		$results = Setting::with(array_keys($fields->getRelations()))
			->where($where)
			->select($fields->getSelect())
			->get();
		return $results;
	}
}