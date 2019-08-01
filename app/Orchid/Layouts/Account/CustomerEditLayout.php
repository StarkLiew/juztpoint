<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Payment;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CustomerEditLayout extends Rows {
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
			Input::make('account.name')
				->type('text')
				->max(255)
				->required()
				->horizontal()
				->title(__('Name'))
				->placeholder(__('Name')),
			Input::make('account.description')
				->type('text')
				->max(255)
				->horizontal()
				->title(__('Description'))
				->placeholder(__('Description')),
		];
	}
}
