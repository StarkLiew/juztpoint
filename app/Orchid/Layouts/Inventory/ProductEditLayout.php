<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Inventory;

use App\Models\Setting;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class ProductEditLayout extends Rows {
	/**
	 * Views.
	 *
	 * @throws \Throwable|\Orchid\Screen\Exceptions\TypeException
	 *
	 * @return array
	 */
	public function fields(): array
	{
		return [
			Input::make('product.name')
				->type('text')
				->max(255)
				->required()
				->title(__('Name'))
				->placeholder(__('Name')),
			Select::make('product.type')
				->options([
					'product' => 'Product',
					'service' => 'Service',
				])
				->required()
				->title(__('Type'))
				->placeholder(__('Type')),
			Input::make('product.sku')
				->type('text')
				->max(255)
				->required()
				->title(__('SKU/Barcode'))
				->placeholder(__('SKU/Barcode')),
			Relation::make('product.cat_id')
				->applyScope('category')
				->fromModel(Setting::class, 'name', 'id')
				->required()
				->title('Category'),
			Relation::make('product.tax_id')
				->fromModel(Setting::class, 'name', 'id')
				->applyScope('tax')
				->required()
				->title('Sale Tax'),

			Select::make('product.status')
				->options([
					0 => 'Active',
					2 => 'Discontinue',
				])
				->required()
				->title(__('Status'))
				->placeholder(__('Status')),

		];
	}
}
