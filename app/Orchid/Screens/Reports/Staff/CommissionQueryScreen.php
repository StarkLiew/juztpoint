<?php

declare (strict_types = 1);

namespace App\Orchid\Screens;

use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class CommissionQueryScreen extends Screen {
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
	public $description = 'Commission Earning Details';

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

		];
	}
}
