<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Company;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CompanyEditLayout extends Rows {
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
			Input::make('company.properties.name')
				->type('text')
				->max(255)
				->required()
				->horizontal()
				->title(__('Name'))
				->placeholder(__('Name')),
			Input::make('company.properties.address')
				->type('text')
				->max(255)
				->horizontal()
				->title(__('Address'))
				->placeholder(__('Address')),
		];
	}
}
