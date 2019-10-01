<?php

declare (strict_types = 1);

namespace App\Orchid\Filters;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class ProductFilter extends Filter {
	/**
	 * @var array
	 */
	public $parameters = [
		'product',
	];

	/**
	 * @return string
	 */
	public function name(): string {
		return __('Product');
	}

	/**
	 * @param Builder $builder
	 *
	 * @return Builder
	 */
	public function run(Builder $builder): Builder {

		return $builder->whereHas('product', function (Builder $query) {
			$query->where('item_id', $this->request->get('product'));
		});
	}

	/**
	 * @return Field[]
	 */
	public function display(): array
	{
		return [
			Select::make('product')
				->fromModel(Product::where('type', 'product'), 'name', 'id')
				->empty()
				->value($this->request->get('product'))
				->title(__('Product')),
		];
	}
}
