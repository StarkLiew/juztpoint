<?php
namespace App\GraphQL\Type;
use App\Models\Setting;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SettingsType extends GraphQLType {
	protected $attributes = [
		'name' => 'Settings',
		'description' => 'The collection of all System Settings and Values',
		'model' => Setting::class, // define model for users type
	];
	// define field of type
	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The id of the user',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'description' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'type' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'properties' => [
				'type' => Type::string(),
				'description' => 'A list of the property',
				'is_relation' => false,
			],

		];
	}

}