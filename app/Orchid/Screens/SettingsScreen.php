<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;

class SettingsScreen extends Screen {

	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'System Settings';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'Global preferences';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [];
	}

	/**
	 * Button commands.
	 *
	 * @return Link[]
	 */
	public function commandBar(): array
	{
		return [

		];
	}

	/**
	 * Views.
	 *
	 * @throws \Throwable
	 *
	 * @return array
	 */
	public function layout(): array
	{
		return [
			Layout::view('orchid.settings'),
		];
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function example() {

		return back();
	}
}
