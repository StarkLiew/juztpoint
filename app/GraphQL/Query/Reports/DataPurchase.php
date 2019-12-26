<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Document;
use Closure;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class DataPurchase {

	public function run($args, Closure $getSelectFields) {
		$documents = TenantTable::parse('documents');
		$settings = TenantTable::parse('settings');
		$items = TenantTable::parse('items');

		$where = function ($query) use ($args, $documents, $items, $settings) {
			if (isset($args['from']) && isset($args['to'])) {
				if ($args['from'] !== "" && $args['to'] !== "") {
					$from = $args['from'] . ' 00:00:00';
					$to = $args['to'] . ' 23:59:59';
					$query->whereBetween($documents . '.date', [$from, $to]);
				}
			}
			if (isset($args['store'])) {
				$query->where($documents . '.store_id', $args['store']);
			}
			if (isset($args['terminal'])) {
				$query->where($documents . '.terminal_id', $args['terminal']);
			}
			if (isset($args['user'])) {
				$query->where($documents . '.transact_by', $args['user']);
			}

		};
		$fields = $getSelectFields();

		$results = Document::with(array_keys($fields->getRelations()))
			->where('type', 'po')
			->where($where)
			->orderBy('id', 'desc')
			->paginate($args['limit'], ['*'], 'page', $args['page']);
		return ['summary' => ['count' => 0, 'sum' => 0], 'data' => $results];
	}
}
