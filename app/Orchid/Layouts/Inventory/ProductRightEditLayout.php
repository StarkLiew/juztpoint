<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Inventory;

use App\Models\Setting;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class ProductRightEditLayout extends Rows {

	protected $stockable = false;

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
			CheckBox::make('product.stockable')
				->value(0)
				->sendTrueOrFalse()
				->title('Stockable')
				->help('Allow to kept stock'),

			Input::make('product.properties.qty')
				->mask([
					'alias' => 'decimal',
					'rightAlign' => false,
					'radixPoint' => '.',
					'numericInput' => true,
					'digitsOptional' => true,
				])
				->title(__('Quantity')),

			Input::make('product.properties.cost')
				->mask([
					'alias' => 'decimal',
					'rightAlign' => false,
					'radixPoint' => '.',
					'numericInput' => true,
					'digitsOptional' => true,
				])
				->hr()
				->title(__('Cost')),

			Input::make('product.properties.price')
				->mask([
					'alias' => 'decimal',
					'rightAlign' => false,
					'radixPoint' => '.',
					'numericInput' => true,
					'digitsOptional' => true,
				])
				->hr()
				->title(__('Selling Price')),

			Relation::make('product.commission_id')
				->fromModel(Setting::class, 'name', 'id')
				->required()
				->applyScope('commission')
				->title('Staff Commission'),

			CheckBox::make('allow_assistant')
				->value(0)
				->sendTrueOrFalse()
				->title('Allow Assistant')
				->help('Assistant able to share commission'),

		];
	}

}
