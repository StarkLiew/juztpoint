<?php
namespace App\GraphQL\Query;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportsQuery extends Query {
	protected $attributes = [
		'name' => 'SalesReport',
		'description' => 'A query of sales',
	];

	public function type(): Type {

		return GraphQL::type('report');
	}
	// arguments to filter query
	public function args(): array{
		return [
			'name' => [
				'type' => Type::string(),
			],
			'from' => [
				'type' => Type::string(),
			],
			'to' => [
				'type' => Type::string(),
			],
			'store' => [
				'type' => Type::int(),
			],
			'terminal' => [
				'type' => Type::int(),
			],
			'user' => [
				'type' => Type::int(),
			],
			'limit' => [
				'type' => Type::int(),
			],
			'page' => [
				'type' => Type::int(),
			],
			'sort' => [
				'type' => Type::string(),
			],
			'desc' => [
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields) {

		$fields = $getSelectFields();
		$fieldList = $fields->getSelect();
		// unset($fieldList[array_search('sum', $fieldList)]);
		// unset($fieldList[array_search('count', $fieldList)]);

		if (isset($args['name'])) {
			$class = 'App\\GraphQL\\Query\\Reports\\' . $args['name'];
			$report = new $class();

			// $func = $args['name'];

			return $report->run($args, $getSelectFields);
		}

	}

}
