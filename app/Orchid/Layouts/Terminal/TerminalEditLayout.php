<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Terminal;

use App\Models\Setting;
use App\Orchid\Fields\QRCode;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
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
			Select::make('terminal.properties.store_id')
				->fromQuery(Setting::where('type', '=', 'store'), 'name', 'id')
				->required()
				->horizontal()
				->title(__('Store'))
				->empty('No select'),
			QRCode::make('terminal.properties.device_id')
				->title(__('To register terminal on new device, scan this code')),

		];
	}
}
