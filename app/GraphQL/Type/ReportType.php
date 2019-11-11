<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ReportType extends GraphQLType {
	protected $attributes = [
		'name' => 'Report',
		'description' => 'The collection of all Accounts',
	];
	// define field of type
	public function fields(): array{
		return [
			'data' => [
				'type' => GraphQL::paginate('reports'),
				'description' => 'List of items on the current page',
			],
			'summary' => [
				'type' => GraphQL::type('summary'),
			],
		];
	}

}