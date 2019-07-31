<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Commission;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CommissionEditLayout extends Rows {
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
			Input::make('commission.name')
				->type('text')
				->max(255)
				->required()
				->horizontal()
				->title(__('Name'))
				->placeholder(__('Name')),
			Input::make('commission.description')
				->type('text')
				->max(255)
				->horizontal()
				->title(__('Description'))
				->placeholder(__('Description')),
			Input::make('commission.properties.rate')
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
			Select::make('commission.properties.type')
				->options([
					0 => 'Percentage',
					1 => 'Fixed',
				])
				->required()
				->horizontal()
				->title(__('Rate Type'))
				->placeholder(__('Rate Type')),

		];
	}
}
