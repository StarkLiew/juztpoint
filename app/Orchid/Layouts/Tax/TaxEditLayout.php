<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Tax;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class TaxEditLayout extends Rows {
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
			Input::make('tax.name')
				->type('text')
				->max(255)
				->required()
				->horizontal()
				->title(__('Name'))
				->placeholder(__('Name')),
			Input::make('tax.description')
				->type('text')
				->max(255)
				->horizontal()
				->title(__('Description'))
				->placeholder(__('Description')),
			Input::make('tax.properties.code')
				->type('text')
				->max(10)
				->required()
				->horizontal()
				->title(__('Code'))
				->placeholder(__('Code')),
			Input::make('tax.properties.rate')
				->mask([
					'alias' => 'decimal',
					'rightAlign' => false,
					'radixPoint' => '.',
					'numericInput' => true,
					'digitsOptional' => true,
				])
				->required()
				->horizontal()
				->title(__('Rate'))
				->placeholder(__('Rate')),

		];
	}
}
