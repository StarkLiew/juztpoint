<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Terminal;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class TerminalEditLayout extends Rows {
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
			Input::make('terminal.name')
				->type('text')
				->max(255)
				->required()
				->horizontal()
				->title(__('Name'))
				->placeholder(__('Name')),
			Input::make('terminal.description')
				->type('text')
				->max(255)
				->horizontal()
				->title(__('Description'))
				->placeholder(__('Description')),
			Input::make('terminal.properties.store_id')
				->required()
				->horizontal()
				->title(__('Store'))
				->placeholder(__('Store')),
		];
	}
}
