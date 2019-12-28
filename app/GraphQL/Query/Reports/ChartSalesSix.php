<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ChartSalesSix {

	public function run($args, Closure $getSelectFields) {

		$documents = TenantTable::parse('documents');

		$sql = <<<EOT
           SELECT t1.mth, t1.md, coalesce(SUM(t1.total_amount+t2.total_amount), 0) AS total_amount from (SELECT DATE_FORMAT(a.Date,"%b") as mth, DATE_FORMAT(a.Date, "%Y-%m") as md, 0 as total_amount from (select curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c) a where a.Date <= NOW() and a.Date >= Date_add(Now(),interval - 11 month)group by md)t1 left join (SELECT DATE_FORMAT(date, "%b") AS mth, SUM(charge)as total_amount ,DATE_FORMAT(date, "%Y-%m") as md FROM $documents where date <= NOW() and date >= Date_add(Now(),interval - 11 month) GROUP BY date, md)t2 on t2.md = t1.md group by t1.md, t1.mth order by t1.md
           EOT;

		$results = DB::select($sql);

		$totalCount = count($results);

		$paginator = new \Illuminate\Pagination\LengthAwarePaginator($results, $totalCount, 12, 1);

		return ['summary' => ['count' => 0, 'sum' => 0], 'data' => $paginator];

	}
}
