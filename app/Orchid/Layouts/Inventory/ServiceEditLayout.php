<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Inventory;

use App\Models\Setting;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ServiceEditLayout extends Rows {
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

			Input::make('service.name')
				->type('text')
				->max(255)
				->required()
				->title(__('Name'))
				->placeholder(__('Name')),

			TextArea::make('service.note')
				->type('text')
				->max(255)
				->rows(5)
				->title(__('Description'))
				->placeholder(__('Description')),

			Input::make('service.sku')
				->type('text')
				->max(255)
				->required()
				->title(__('Code/ID'))
				->placeholder(__('SKU/Barcode')),

			Relation::make('service.cat_id')
				->fromModel(Setting::class, 'name', 'id')
				->applyScope('category')
				->required()
				->title('Category'),

			Relation::make('service.tax_id')
				->fromModel(Setting::class, 'name', 'id')
				->applyScope('tax')
				->required()
				->title('Sale Tax'),

			Select::make('service.status')
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
