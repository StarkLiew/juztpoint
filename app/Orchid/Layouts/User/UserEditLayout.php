<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows {
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
			Input::make('user.name')
				->type('text')
				->max(255)
				->required()
				->horizontal()
				->title(__('Name'))
				->placeholder(__('Name')),

			Input::make('user.email')
				->type('email')
				->required()
				->horizontal()
				->title(__('Email'))
				->placeholder(__('Email')),

			Select::make('user.properties.role')
				->options([
					'MGR' => 'Manager',
					'CSH' => 'Cashier',
				])
				->required()
				->title(__('Role'))
				->horizontal(),

			Input::make('user.pin')
				->type('text')
				->required()
				->length(4)
				->horizontal()
				->title(__('Pin'))
				->placeholder(__('Pin')),

			CheckBox::make('user.properties.backoffice')
				->sendTrueOrFalse()
				->title(__(''))
				->horizontal()
				->placeholder(__('Allow access to Back Office')),

		];
	}
}
