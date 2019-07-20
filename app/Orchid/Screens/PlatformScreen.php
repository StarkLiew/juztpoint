<?php

declare (strict_types = 1);

namespace App\Orchid\Screens;

use Orchid\Platform\Dashboard;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;

class PlatformScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Dashboard';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'Welcome';

	/**
	 * Query data.
	 *
	 *
	 * @return array
	 */
	public function query(): array
	{
		return [
			'status' => Dashboard::checkUpdate(),
		];
	}

	/**
	 * Button commands.
	 *
	 * @return Link[]
	 */
	public function commandBar(): array
	{
		return [
			Link::name('Website')
				->link('http://orchid.software')
				->icon('icon-globe-alt'),

			Link::name('GitHub')
				->link('https://github.com/orchidsoftware/platform')
				->icon('icon-social-github'),
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
			Layout::view('platform::partials.update'),
			Layout::view('platform::partials.welcome'),
		];
	}
}
