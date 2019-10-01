<?php

namespace App\Orchid\Layouts\Reports\Inventory;

use App\Orchid\Filters\DateRangeFilter;
use App\Orchid\Filters\ProductFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class StockCardFiltersLayout extends Selection {

	/**
	 * @return Filter[]
	 */
	public function filters(): array
	{

		return [DateRangeFilter::class, ProductFilter::class];
	}
}
