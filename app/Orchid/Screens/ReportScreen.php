<?php

declare (strict_types = 1);

namespace App\Orchid\Screens;

use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class ReportScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Reports';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'Provides information, and sometimes analysis';

	/**
	 * Query data.
	 *
	 *
	 * @return array
	 */
	public function query(): array
	{
		return [

		];
	}

	/**
	 * Button commands.
	 *
	 * @return Action[]
	 */
	public function commandBar(): array
	{
		return [

			DropDown::make(__('Settings'))
				->icon('icon-open')
				->list([
					Button::make(__('Login as user'))
						->icon('icon-login')
						->method('loginAs'),
					ModalToggle::make(__('Change Password'))
						->icon('icon-lock-open')
						->title(__('Change Password'))
						->method('changePassword')
						->modal('question'),
				]),
		];
	}

	/**
	 * Views.
	 *
	 * @return Layout[]
	 */
	public function layout(): array
	{
		return [

			Layout::view('platform::partials.report'),

			Layout::modals('question', [
				Layout::rows([
					DateTimer::make('open')
						->title('Opening date')
						->format('Y-m-d'),
				]),

			],
			),

		];
	}
}
