<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Store;

use App\Helpers\World;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class StoreEditLayout extends Rows {
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
			Input::make('store.name')
				->type('text')
				->max(255)
				->required()
				->horizontal()
				->title(__('Name'))
				->placeholder(__('Name')),
			Select::make('store.properties.timezone')
				->options(World::timezones())
				->empty('No select')
				->horizontal()
				->title(__('Timezone'))
				->placeholder(__('Timezone')),
			Select::make('store.properties.currency')
				->options(World::currencies())
				->empty('No select')
				->horizontal()
				->title(__('Currency'))
				->placeholder(__('Currency')),
		];
	}
}
