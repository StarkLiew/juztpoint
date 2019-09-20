<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Inventory;

use App\Models\Product;
use App\Models\Setting;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class ServiceRightEditLayout extends Rows {

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

			Input::make('service.properties.price')
				->mask([
					'alias' => 'decimal',
					'rightAlign' => false,
					'radixPoint' => '.',
					'numericInput' => true,
					'digitsOptional' => true,
				])
				->hr()
				->title(__('Selling Price')),

			Input::make('service.properties.duration')
				->mask([
					'rightAlign' => false,
					'radixPoint' => '.',
					'numericInput' => true,
					'digitsOptional' => true,
				])
				->hr()
				->title(__('Duration (minutes)')),

			Relation::make('service.commission_id')
				->fromModel(Setting::class, 'name', 'id')
				->required()
				->applyScope('commission')
				->title('Staff Commission'),

			CheckBox::make('service.allow_assistant')
				->value(0)
				->sendTrueOrFalse()
				->title('Allow Assistant')
				->help('Assistant able to share commission'),

			Select::make('service.properties.contain.')
				->fromModel(Product::where('type', 'service'), 'name')
				->multiple()
				->empty('Nothing')
				->title(__('Service include')),

		];
	}

}
