<?php

declare (strict_types = 1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;

class DateRangeFilter extends Filter {
	/**
	 * @var array
	 */
	public $parameters = [
		'from',
		'to',
	];

	/**
	 * @return string
	 */
	public function name(): string {
		return __('Date Range');
	}

	/**
	 * @param Builder $builder
	 *
	 * @return Builder
	 */
	public function run(Builder $builder): Builder {

		return $builder->whereHas('document', function (Builder $query) {
			$from = $this->request->get('from') . ' 00:00:00';
			$to = $this->request->get('to') . ' 23:59:59';
			$query->whereBetween('date', [$from, $to]);
		});
	}

	/**
	 * @return Field[]
	 */
	public function display(): array
	{
		return [
			DateTimer::make('from')
				->title('From Date')
				->format('Y-m-d'),
			DateTimer::make('to')
				->title('To Date')
				->format('Y-m-d'),
		];
	}
}
