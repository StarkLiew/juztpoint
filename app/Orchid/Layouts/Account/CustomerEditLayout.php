<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Company;
use App\Helpers\World;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class CustomerEditLayout extends Rows {
	/**
	 * Views.
	 *
	 * @throws \Throwable|\Orchid\Screen\Exceptions\TypeException
	 *
	 * @return arrays
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
			TextArea::make('company.properties.address')
				->type('text')
				->max(255)
				->horizontal()
				->title(__('Address'))
				->placeholder(__('Address')),
			Select::make('company.properties.timezone')
				->options(World::timezones())
				->empty('No select')
				->horizontal()
				->title(__('Timezone'))
				->placeholder(__('Timezone')),
			Select::make('company.properties.currency')
				->options(World::currencies())
				->empty('No select')
				->horizontal()
				->title(__('Currency'))
				->placeholder(__('Currency')),
		];
	}

}
