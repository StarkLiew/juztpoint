<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SummaryType extends GraphQLType {
	protected $attributes = [
		'name' => 'Summary',
		'description' => 'Summary of report',
	];
	// define field of type
	public function fields(): array{
		return [
			'count' => [
				'type' => Type::float(),
				'description' => 'The id of the user',
				'selectable' => false,
			],
			'sum' => [
				'type' => Type::float(),
				'description' => 'The name or value of the setting',
				'selectable' => false,
			],

		];
	}

}