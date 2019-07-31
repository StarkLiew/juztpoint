<?php

declare (strict_types = 1);

namespace App\Orchid\Composers;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Menu;

class SystemMenuComposer {
	/**
	 * @var Dashboard
	 */
	private $dashboard;

	/**
	 * MenuComposer constructor.
	 *
	 * @param Dashboard $dashboard
	 */
	public function __construct(Dashboard $dashboard) {
		$this->dashboard = $dashboard;
	}

	/**
	 * Registering the main menu items.
	 */
	public function compose() {
		$this->dashboard->menu
			->add(Menu::SYSTEMS,
				ItemMenu::label(__('System Settings'))
					->icon('icon-settings')
					->slug('Auth')
					->active('platform.systems.*')
					->permission('platform.systems')
					->sort(1000)
			)
			->add('Auth',
				ItemMenu::label(__('Company'))
					->icon('icon-building')
					->route('platform.systems.company')
					->permission('platform.systems.company')
					->sort(1000)
					->title(__('All registered company'))
			)
			->add('Auth',
				ItemMenu::label(__('Store'))
					->icon('icon-building')
					->permission('platform.systems.stores')
					->route('platform.systems.stores')
					->sort(1000)
					->title(__('All registered stores'))
			)
			->add('Auth',
				ItemMenu::label(__('Tax'))
					->icon('icon-dollar')
					->route('platform.systems.taxes')
					->sort(1000)
					->title(__('All registered taxes'))
			)
			->add('Auth',
				ItemMenu::label(__('Employee'))
					->icon('icon-user')
					->route('platform.systems.users')
					->permission('platform.systems.users')
					->sort(1000)
					->title(__('All registered users'))
			)
			->add('Auth',
				ItemMenu::label(__('Payment'))
					->icon('icon-money')
					->route('platform.systems.payments')
					->sort(1000)
					->title(__('All registered payments'))
			)
			->add('Auth',
				ItemMenu::label(__('Commission'))
					->icon('icon-money')
					->route('platform.systems.commissions')
					->sort(1000)
					->title(__('All registered commissions'))
			)
			->add('Auth',
				ItemMenu::label(__('Roles'))
					->icon('icon-lock')
					->route('platform.systems.roles')
					->permission('platform.systems.roles')
					->sort(1000)
					->title(__('A Role defines a set of tasks a user assigned the role is allowed to perform. '))
			);
	}
}
