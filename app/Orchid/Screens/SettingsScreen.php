<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;

class SettingsScreen extends Screen {
	/**
	 * Fish text for the table.
	 */
	const TEXT_EXAMPLE = 'Lorem ipsum at sed ad fusce faucibus primis, potenti inceptos ad taciti nisi tristique
    urna etiam, primis ut lacus habitasse malesuada ut. Lectus aptent malesuada mattis ut etiam fusce nec sed viverra,
    semper mattis viverra malesuada quam metus vulputate torquent magna, lobortis nec nostra nibh sollicitudin
    erat in luctus.';

	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Settings';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'Customise to your preferences.';

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

		];
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function example() {

		return back();
	}
}
