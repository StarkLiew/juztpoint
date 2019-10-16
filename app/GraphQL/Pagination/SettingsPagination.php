<?php
namespace App\GraphQL\Pagination;

use App\Models\Setting;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as Pagination;

class SettingsPagination extends Pagination {
	protected $attributes = [
		'name' => 'settings',
		'description' => 'The collection of all System Settings and Values',
		'model' => Setting::class, // define model for users type
	];
	// define field of type
	public function fields(): array{
		return [
			'id' => [
				'type' => Type::int(),
				'description' => 'The id of the user',
			],

			'name' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],

			'description' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],

			'type' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'properties' => [
				'type' => GraphQL::type('property'),
				'description' => 'A list of the property',
				'is_relation' => false,
			],
		];
	}

}