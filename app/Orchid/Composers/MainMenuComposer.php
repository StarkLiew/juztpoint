<?php

declare (strict_types = 1);

namespace App\Orchid\Composers;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Menu;

class MainMenuComposer {
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
		// Profile
		$this->dashboard->menu
			->add(Menu::PROFILE,
				ItemMenu::label('Empty 1')
					->icon('icon-compass')
			)
			->add(Menu::PROFILE,
				ItemMenu::label('Empty 2')
					->icon('icon-heart')
					->badge(function () {
						return 6;
					})
			);

		// Main

		$this->dashboard->menu
			->add(Menu::MAIN,
				ItemMenu::label('Transaction')
					->slug('transaction')
					->icon('icon-plus')
					->childs()
			)
			->add('transaction',
				ItemMenu::label('Sales')
					->icon('icon-dollar')
			)
			->add('transaction',
				ItemMenu::label('Purchases')
					->icon('icon-bag')
			)
			->add(Menu::MAIN,
				ItemMenu::label('Products')
					->icon('icon-module')
					->route('platform.products')
			)
			->add(Menu::MAIN,
				ItemMenu::label('Services')
					->icon('icon-module')
					->route('platform.services')
			)
			->add(Menu::MAIN,
				ItemMenu::label('Inventory')
					->slug('inventory')
					->icon('icon-plus')
					->childs()
			)
			->add('inventory',
				ItemMenu::label('Categories')
					->icon('icon-organization')
					->route('platform.categories')
			)

			->add(Menu::MAIN,
				ItemMenu::label('Account')
					->slug('account-data')
					->icon('icon-plus')
					->childs()
			)
			->add('account-data',
				ItemMenu::label('Customers')
					->icon('icon-friends')
					->route('platform.customers')
			)
			->add('account-data',
				ItemMenu::label('Vendors')
					->icon('icon-friends')
					->route('platform.vendors')

			)
			->add(Menu::MAIN,
				ItemMenu::label('Settings')
					->icon('icon-settings')
					->route('platform.systems.index')
			);

	}
}
