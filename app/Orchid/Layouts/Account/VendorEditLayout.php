<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Account;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class VendorEditLayout extends Rows {
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
			TextArea::make('account.properties.address')
				->type('text')
				->max(255)
				->horizontal()
				->title(__('Address'))
				->placeholder(__('Address')),
			Input::make('account.properties.email')
				->type('email')
				->horizontal()
				->title(__('Email'))
				->placeholder(__('Email')),
			Input::make('account.properties.phone')
				->type('text')
				->max(50)
				->horizontal()
				->title(__('Phone Number'))
				->placeholder(__('Mobile Number')),
			Input::make('account.properties.mobile')
				->type('text')
				->max(50)
				->horizontal()
				->title(__('Mobile Number'))
				->placeholder(__('Mobile Number')),
			Input::make('account.properties.contact')
				->type('text')
				->horizontal()
				->title(__('Contact Person'))
				->placeholder(__('Contact Person')),
			TextArea::make('account.description')
				->type('text')
				->max(255)
				->horizontal()
				->title(__('Note'))
				->placeholder(__('Note')),
			Select::make('account.status')
				->options([
					'active' => 'Active',
					'suspend' => 'Suspend',
				])
				->required()
				->horizontal()
				->title(__('Status'))
				->placeholder(__('Status')),
		];
	}
}
