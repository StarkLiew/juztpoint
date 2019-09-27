<?php

namespace App\Orchid\Layouts\Reports;

use App\Orchid\Filters\DateRangeFilter;
use App\Orchid\Filters\UserFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ReportFiltersLayout extends Selection {
	/**
	 * @return Filter[]
	 */
	public function filters(): array
	{
		return [
			DateRangeFilter::class,
			UserFilter::class,
		];
	}
}
